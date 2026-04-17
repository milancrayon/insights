<?php

defined('TYPO3') or die;

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$postlistType = ExtensionUtility::registerPlugin(
    'Insights',
    'Postlist',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:postlist_title',
    'insights',
    'insights',
);
$recommendedType = ExtensionUtility::registerPlugin(
    'Insights',
    'Recommended',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:recommended_title',
    'insights',
    'insights',
);
$SearchpopularType = ExtensionUtility::registerPlugin(
    'Insights',
    'Searchpopular',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:searchpopular_title',
    'insights',
    'insights',
);

$quicksearchType = ExtensionUtility::registerPlugin(
    'Insights',
    'Quicksearch',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:quicksearch_title',
    'insights',
    'insights',
);

$insightelementsType = ExtensionUtility::registerPlugin(
    'Insights',
    'Insightelements',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insightelements_title',
    'insights',
    'insights',
);

$postdetailType = ExtensionUtility::registerPlugin(
    'Insights',
    'Postdetail',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:postdetail_title',
    'insights',
    'insights',
);
$breadcrumbsType = ExtensionUtility::registerPlugin(
    'Insights',
    'Breadcrumbs',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:breadcrumbs_title',
    'insights',
    'insights',
);

$TagsType = ExtensionUtility::registerPlugin(
    'Insights',
    'Tags',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:tags_title',
    'insights',
    'insights',
);

$PostfilterType = ExtensionUtility::registerPlugin(
    'Insights',
    'Postfilter',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:postfilter_title',
    'insights',
    'insights',
);

$Filterresultlist = ExtensionUtility::registerPlugin(
    'Insights',
    'Filterresultlist',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:filterresultlist_title',
    'insights',
    'insights',
);
$Categoryinsights = ExtensionUtility::registerPlugin(
    'Insights',
    'Categoryinsights',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:categoryinsights_title',
    'insights',
    'insights',
);
$Categorylist = ExtensionUtility::registerPlugin(
    'Insights',
    'Categorylist',
    'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:categorylist_title',
    'insights',
    'insights',
);

/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Postlist.xml',
    $postlistType
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Categoryinsights.xml',
    $Categoryinsights
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Recommended.xml',
    $recommendedType
);

/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Quicksearch.xml',
    $quicksearchType
);

/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Postdetail.xml',
    $postdetailType
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Breadcrumbs.xml',
    $breadcrumbsType
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Tags.xml',
    $TagsType
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Postfilter.xml',
    $PostfilterType
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Filterresultlist.xml',
    $Filterresultlist
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insights/Configuration/Flexforms/Categorylist.xml',
    $Categorylist
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $postlistType,
    'after:palette:headers'
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $recommendedType,
    'after:palette:headers'
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $quicksearchType,
    'after:palette:headers'
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $insightelementsType,
    'after:palette:headers'
);

/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $postdetailType,
    'after:palette:headers'
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $breadcrumbsType,
    'after:palette:headers'
);

/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $TagsType,
    'after:palette:headers'
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $PostfilterType,
    'after:palette:headers'
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $Filterresultlist,
    'after:palette:headers'
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $Categoryinsights,
    'after:palette:headers'
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $Categorylist,
    'after:palette:headers'
);
/** @extensionScannerIgnoreLine */
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
    $SearchpopularType,
    'after:palette:headers'
);
