<?php

return [
    'ctrl' => [
        'label' => 'ipaddress',
        'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:table_likes',
        'sortby' => 'sorting',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'versioningWS' => true,
        'label_alt_force' => true,
        'origUid' => 't3_origuid',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'typeicon_classes' => [
            'default' => 'insights',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, ipaddress,browser,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
        ],
    ],
    'columns' => [
        'hidden' => [
            'config' => [
                'type' => 'check',
                'items' => [
                    ['label' => 'Disable'],
                ],
            ]
        ],
        'starttime' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
            ],
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
            ],
        ],
        'ipaddress' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:likes_ipaddress',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'browser' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:likes_browser',
            'config' => [
                'type' => 'text',
            ],
        ],
        'post' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:comments_post',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_insights_domain_model_post',
                'foreign_table_where' => 'AND {#tx_insights_domain_model_post}.{#sys_language_uid} = ###REC_FIELD_sys_language_uid###',

            ],
        ],
        'like' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:like',
            'config' => [
                'type' => 'radio',
                'items' => [
                    [
                        'label' => 'no',
                        'value' => 0,
                    ],
                    [
                        'label' => 'yes',
                        'value' => 1,
                    ]
                ],
            ]
        ],
        'dislike' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:dislike',
            'config' => [
                'type' => 'radio',
                'items' => [
                    [
                        'label' => 'no',
                        'value' => 0,
                    ],
                    [
                        'label' => 'yes',
                        'value' => 1,
                    ]
                ],
            ]
        ],
    ],
];
