<?php
namespace T3element\Insights\Sitemap;

use TYPO3\CMS\Seo\XmlSitemap\AbstractXmlSitemapDataProvider;
use T3element\Insights\Domain\Repository\PostRepository;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Routing\SiteMatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class InsightsSitemapProvider extends AbstractXmlSitemapDataProvider
{
    protected PostRepository $postRepository;

    public function __construct(
        ServerRequestInterface $request,
        string $name,
        array $config = [],
        ?ContentObjectRenderer $cObj = null
    ) {
        parent::__construct($request, $name, $config, $cObj);
        $this->postRepository = GeneralUtility::makeInstance(PostRepository::class);
    }

    public function getItems(): array
    {
        $site = $this->request->getAttribute('site');
        $settings = $site->getSettings();
        // $detailPid = (int) $settings->get('insightsDetailPid');

        $entries = [];
        /** @extensionScannerIgnoreLine */
        $pageUid = (int) ($this->config['pid'] ?? 0);

        $_filters = array();
        $_filters['poststatus'] = 1;
        $this->postRepository->setStorage((int) $pageUid);
        $posts = $this->postRepository->findBy($_filters);

        foreach ($posts as $post) {
            $url = (string) $site->getRouter()->generateUri($pageUid, [
                'tx_insights_postdetail' => ['item' => $post->getUid()]
            ]);

            $entries[] = [
                'loc' => $url,
                'lastMod' => $post->getTstamp()?->format('Y-m-d') ?? date('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => 0.7,
            ];
        }

        return $entries;
    }
}