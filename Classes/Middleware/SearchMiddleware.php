<?php

namespace T3element\Insights\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use T3element\Insights\Domain\Repository\PostRepository;
use T3element\Insights\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Routing\SiteMatcher;
use TYPO3\CMS\Core\Site\SiteFinder;



class SearchMiddleware implements \Psr\Http\Server\MiddlewareInterface
{
    protected PostRepository $postRepository;
    protected CategoryRepository $categoryRepository;

    public function __construct(PostRepository $postRepository, CategoryRepository $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }



    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $site = $request->getAttribute('site');
        $settings = $site->getSettings();
        $detailPid = (int) $settings->get('insightsDetailPid');
        $path = $request->getUri()->getPath();

        if ($path == '/api/search') {
            $queryParams = $request->getQueryParams();
            $query = $queryParams['q'] ?? '';
            $items = $this->postRepository->findByCategoryAndTags(
                0,
                [],
                $query
            );

            $results = [];

            foreach ($items as $item) {
                $deepestCategory = null;
                $maxLevel = 0;

                foreach ($item->getCategory() as $cat) {

                    $level = 0;
                    $temp = $cat;

                    while ($temp) {
                        $level++;
                        $temp = $temp->getParentcategory();
                    }

                    if ($level > $maxLevel) {
                        $maxLevel = $level;
                        $deepestCategory = $cat;
                    }
                }

                $categoryUid = $deepestCategory ? $deepestCategory->getUid() : ($item->getPrimaryCategory() ? $item->getPrimaryCategory()->getUid() : 0);
                
                $url = (string) $site->getRouter()->generateUri($detailPid, [
                    'tx_insights_postdetail' => [
                        'action' => 'postdetail',
                        'controller' => 'Insights',
                        'item' => $item->getUid(),
                        'category' => $categoryUid
                    ]
                ]);

                $categoryTree = [];
                if ($deepestCategory) {
                    $categoryTree = $this->getRootParentTree($deepestCategory, $detailPid, $site);
                }
                $results[] = [
                    'uid' => $item->getUid(),
                    'title' => $item->getTitle(),
                    'slug' => $item->getSlug(),
                    'url' => (string) $url,
                    'category' => $categoryTree
                ];
            }

            return new JsonResponse([
                'results' => $results
            ]);
        } elseif ($path == '/api/popularinsight') {
            $finalData = [];
            $this->postRepository->setStorage();
            $items = $this->postRepository->fetchpopular();



            foreach ($items as $item) {
                $url = (string) $site->getRouter()->generateUri($detailPid, [
                    'tx_insights_postdetail' => [
                        'action' => 'postdetail',
                        'controller' => 'Insights',
                        'item' => $item->getUid(),
                        'category' => $item->getPrimaryCategory() ? $item->getPrimaryCategory()->getUid() : 0
                    ]
                ]);
                $finalData[] = [
                    "uid" => $item->getUid(),
                    "title" => $item->getTitle(),
                    "url" => $url
                ];
            }
            return new JsonResponse([
                'results' => $finalData
            ]);
        } else {
            return $handler->handle($request);
        }

    }
    private function getRootParentTree($category, $detailPid, $site): array
    {
        $tree = [];
        while ($category) {
            $pageUid = $detailPid;
            $_catdetails = $this->categoryRepository->findByUid($category->getUid());
            $this->postRepository->setStorage();
            $_items = $this->postRepository->findByCategory($_catdetails);
            $url = "/";
            if (isset($_items) && sizeof($_items) > 0) {
                $categorySlug = $category->getSlug();
                $url = (string) $site->getRouter()->generateUri($pageUid, [
                    'tx_insights_postdetail' => [
                        'action' => 'postdetail',
                        'controller' => 'Insights',
                        'item' => $_items[0]->getUid(),
                        'category' => $category->getUid()
                    ]
                ]);
            }
            array_unshift($tree, [
                'uid' => $category->getUid(),
                'title' => $category->getTitle(),
                'url' => $url
            ]);
            $category = $category->getParentcategory();
        }
        return $tree;
    }
}