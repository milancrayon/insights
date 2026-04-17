<?php
namespace T3element\Insights\Utility;

use TYPO3\CMS\Core\Attribute\AsAllowedCallable;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use T3element\Insights\Domain\Repository\PostRepository;

class SchemaUtility
{
    #[AsAllowedCallable]
    public function renderSchema(
        string $content,
        array $configuration,
        ServerRequestInterface $request
    ): string {

        $queryParams = $request->getQueryParams();
        $pluginArguments = $queryParams['tx_insights_postdetail'] ?? [];
        $item = $pluginArguments['item'] ?? null;

        if (!$item) {
            return '';
        }

        /** @var PostRepository $postRepository */
        $postRepository = GeneralUtility::makeInstance(PostRepository::class);

        $post = is_numeric($item)
            ? $postRepository->findByUid((int)$item)
            : $postRepository->findOneBySlug($item);

        if (!$post) {
            return '';
        }
        $json = $post->getPostschema();

        
        return '<script type="application/ld+json">'
            .$json
            . '</script>';
    }
}