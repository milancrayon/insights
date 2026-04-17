<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$suffix = GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() >= 14 ? '-14' : '';

return [
    'insights' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:insights/Resources/Public/Icons/Insight.svg',
        'spinning' => false,
    ],
    'insights-be' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:insights/Resources/Public/Icons/Insight-be' . $suffix . '.svg',
        'spinning' => false,
    ],
    'insights-comments' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:insights/Resources/Public/Icons/Comments' . $suffix . '.svg',
        'spinning' => false,
    ],
    'insights-config' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:insights/Resources/Public/Icons/Config' . $suffix . '.svg',
        'spinning' => false,
    ],
    'insights-author' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:insights/Resources/Public/Icons/Author' . $suffix . '.svg',
        'spinning' => false,
    ],
    'insights-tag' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:insights/Resources/Public/Icons/Tags' . $suffix . '.svg',
        'spinning' => false,
    ],
    'insights-add' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:insights/Resources/Public/Icons/Add' . $suffix . '.svg',
        'spinning' => false,
    ],
    'insights-list' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:insights/Resources/Public/Icons/List' . $suffix . '.svg',
        'spinning' => false,
    ],
    'actions-robot' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:insights/Resources/Public/Icons/ai' . $suffix . '.png',
        'spinning' => false,
    ],
];
