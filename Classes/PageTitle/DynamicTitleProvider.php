<?php

declare(strict_types=1);
 
namespace T3element\Insights\PageTitle;

use T3element\Insights\Domain\Model\Post;
use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
 
class DynamicTitleProvider extends AbstractPageTitleProvider
{
    private const DEFAULT_PROPERTIES = 'alternativeTitle,title';

    public function setTitleByPost(Post $post): void
    {
        $title = '';
        $fields = GeneralUtility::trimExplode(',', self::DEFAULT_PROPERTIES, true);

        foreach ($fields as $field) {
            $getter = 'get' . ucfirst($field);
            $value = $post->$getter();
            if ($value) {
                $title = $value;
                break;
            }
        }
        if ($title) {
            $this->title = $title;
        }
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}