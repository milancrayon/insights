<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die;

$newSysFileReferenceColumns = [
    'insightview' => [
        'exclude' => true,
        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insightviews',
        'config' => [
             'type' => 'check', 
            'items' => [
                ['label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insightviews.0', 'value' => 0], 
                ['label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insightviews.2', 'value' => 2],
            ],
            'default' => 0,
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newSysFileReferenceColumns);
ExtensionManagementUtility::addFieldsToPalette('sys_file_reference', 'insightPalette', 'insightview');
