<?php

declare(strict_types=1);

namespace T3element\Insights\Event\Listener;

use TYPO3\CMS\Backend\Form\Event\ModifyFileReferenceControlsEvent;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration; // Import this

final class ModifyFileReferenceControlsEventListener
{
    public function modifyControls(
        ModifyFileReferenceControlsEvent $event
    ): void {
        $childRecord = $event->getRecord();
        $previewSetting = (int) (is_array($childRecord['showinpreview'] ?? false) ? ($childRecord['showinpreview'][0] ?? 0) : ($childRecord['showinpreview'] ?? 0));

        if ($event->getForeignTable() === 'sys_file_reference' && $previewSetting > 0) {
            $ll = 'LLL:EXT:insights/Resources/Private/Language/locallang_db.xlf:';

            $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

            // Fix: Use the Core ExtensionConfiguration API
            // Replace 'insights' with your actual extension key if different
            $extensionConfig = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('insights');

            if ($previewSetting === 1) {
                $icon = $iconFactory->getIcon('ext-news-doublecheck', IconSize::SMALL);
                $label = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.showinviews.1');
                // Note: render() is usually needed on the icon object
                $event->setControl('ext-news-preview', ' <span class="btn btn-default" title="' . htmlspecialchars($label) . '">' . $icon->render() . '</span>');
            } elseif ($previewSetting === 2) {
                $icon = $iconFactory->getIcon('actions-check', IconSize::SMALL);
                $label = $GLOBALS['LANG']->sL($ll . 'tx_news_domain_model_media.showinviews.2');
                $event->setControl('ext-news-preview', ' <span class="btn btn-default" title="' . htmlspecialchars($label) . '">' . $icon->render() . '</span>');
            }
        }
    }
}