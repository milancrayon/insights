<?php
declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use T3element\Insights\Controller\InsightsController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionUtility::configurePlugin(
    'Insights',
    'Postlist',
    [InsightsController::class => 'postlist,updateStatus,like,dislike,commentform,commentformSubmit'],
    [InsightsController::class => 'postlist,updateStatus,like,dislike,commentform,commentformSubmit'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT

);
ExtensionUtility::configurePlugin(
    'Insights',
    'Recommended',
    [InsightsController::class => 'recommended,updateStatus,like,dislike,commentform,commentformSubmit'],
    [InsightsController::class => 'recommended,updateStatus,like,dislike,commentform,commentformSubmit'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT

);
ExtensionUtility::configurePlugin(
    'Insights',
    'Tags',
    [InsightsController::class => 'tags'],
    [InsightsController::class => 'tags'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
ExtensionUtility::configurePlugin(
    'Insights',
    'Postdetail',
    [InsightsController::class => 'postdetail,like,dislike,commentform,commentformSubmit'],
    [InsightsController::class => 'postdetail,like,dislike,commentform,commentformSubmit'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
ExtensionUtility::configurePlugin(
    'Insights',
    'Postfilter',
    [InsightsController::class => 'postfilter,postfilterSubmit'],
    [InsightsController::class => 'postfilter,postfilterSubmit'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
ExtensionUtility::configurePlugin(
    'Insights',
    'Filterresultlist',
    [InsightsController::class => 'filterresultlist,postdetail'],
    [InsightsController::class => 'filterresultlist,postdetail'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Insights',
    'Breadcrumbs',
    [InsightsController::class => 'breadcrumbs,like,dislike'],
    [InsightsController::class => 'breadcrumbs,like,dislike'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Insights',
    'Quicksearch',
    [InsightsController::class => 'quicksearch'],
    [InsightsController::class => 'quicksearch'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Insights',
    'Insightelements',
    [InsightsController::class => 'insightelements'],
    [InsightsController::class => 'insightelements'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Insights',
    'Categoryinsights',
    [InsightsController::class => 'categoryinsights'],
    [InsightsController::class => 'categoryinsights'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Insights',
    'Categorylist',
    [InsightsController::class => 'categorylist'],
    [InsightsController::class => 'categorylist'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Insights',
    'Searchpopular',
    [InsightsController::class => 'searchpopular'],
    [InsightsController::class => 'searchpopular'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT

);

ExtensionManagementUtility::addTypoScriptSetup(trim('
    
    config.pageTitleProviders {
        insights {
            provider = T3element\Insights\PageTitle\DynamicTitleProvider
            before = record,seo
        }
    }
'));

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']
['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
    \T3element\Insights\Hooks\ArchiveDateValidationHook::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\BackendController']['extendBackendCsp'] =
    \T3element\Insights\Hooks\BackendCspHook::class;

$GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['insights_ai'] = 'EXT:insights/Resources/Public/css/ai-assistant.css';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1712754123] = [
    'nodeName' => 'aiAssistant',
    'priority' => 40,
    'class' => \T3element\Insights\FormEngine\FieldControl\AiAssistantControl::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['PersistedHierarchicalAliasMapper'] =
    \T3element\Insights\Routing\Aspect\PersistedHierarchicalAliasMapper::class;