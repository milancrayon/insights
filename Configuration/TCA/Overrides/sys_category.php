<?php
defined('TYPO3') or die();

$customSlugField = [
    'slug' => [
        'label' => 'URL Segment',
        'exclude' => true,
        'config' => [
            'type' => 'slug',
            'size' => 50,
            'generatorOptions' => [
        'fields' => ['title'],
        'replacements' => [
            '/' => '-'
        ],
        // This is the magic part for nested categories:
        'parentFieldName' => 'parent',
        'prependSlash' => true, 
    ],
            'fallbackCharacter' => '-',
            'eval' => 'uniqueInSite',
            'default' => ''
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category', $customSlugField);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'slug',
    '',
    'after:title'
);