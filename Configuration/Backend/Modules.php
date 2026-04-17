<?php

use T3element\Insights\Controller\BeinsightController;

return [
    'insights_insights' => [
        'position' => ['after' => 'web'],
        'labels' => [
            'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insights'
        ],
        'iconIdentifier' => 'insights-be',
    ],
    'tx_insights_newslist' => [
        'parent' => 'insights_insights',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/insights-news',
        'extensionName' => 'Insights',
        'labels' => [
            'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insights_news'
        ],
        'iconIdentifier' => 'insights-list',
        'controllerActions' => [
            BeinsightController::class => [
                'benewslist',
                'updateStatus'
            ],
        ],
    ],
    'tx_insights_newsadd' => [
        'parent' => 'insights_insights',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/insights-newnews',
        'extensionName' => 'Insights',
        'labels' => [
            'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insights_newsadd'
        ],
        'iconIdentifier' => 'insights-add',
        'controllerActions' => [
            BeinsightController::class => [
                'benewsadd',
            ],
        ],
    ],
    'tx_insights_comments' => [
        'parent' => 'insights_insights',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/insights-comments',
        'extensionName' => 'Insights',
        'labels' => [
            'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insights_comments'
        ],
        'iconIdentifier' => 'insights-comments',
        'controllerActions' => [
            BeinsightController::class => [
                'becomments',
            ],
        ],
    ],
    'tx_insights_tags' => [
        'parent' => 'insights_insights',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/insights-tags',
        'extensionName' => 'Insights',
        'labels' => [
            'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insights_tags'
        ],
        'iconIdentifier' => 'insights-tag',
        'controllerActions' => [
            BeinsightController::class => [
                'betags',
            ],
        ],
    ],
    'tx_insights_authors' => [
        'parent' => 'insights_insights',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/insights-authors',
        'extensionName' => 'Insights',
        'labels' => [
            'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insights_authors'
        ],
        'iconIdentifier' => 'insights-author',
        'controllerActions' => [
            BeinsightController::class => [
                'beauthors',
            ],
        ],
    ],
    'tx_insights_config' => [
        'parent' => 'insights_insights',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/insights-config',
        'labels' => [
            'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:insights-config'
        ],
        'extensionName' => 'Insights',
        'iconIdentifier' => 'insights-config',
        'controllerActions' => [
            BeinsightController::class => [
                'beconfig',
                'saveconfig',
                'savetemplate'
            ],
        ],
    ]
];