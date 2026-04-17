<?php

return [
    'ctrl' => [
        'label' => 'name',
        'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:table_comments',
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
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, name,email,comment,post,status,clang,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
            'columnsOverrides' => [
                'comment' => [
                    'config' => [
                        'enableRichtext' => true,
                        'richtextConfiguration' => 'default',
                    ],
                ],
            ],
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
        'name' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:comments_name',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'required ' => true,
            ],
        ],
        'email' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:comments_email',
            'config' => [
                'type' => 'email',
                'required ' => true,
            ],
        ],
        'comment' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:comments_comment',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'required ' => true,
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
        'status' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:comments_status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'pending',
                'items' => [
                    [
                        'label' => '',
                        'value' => null,
                    ],
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:comments_status_pending',
                        'value' => 'pending',
                    ],
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:comments_status_approved',
                        'value' => 'approved',
                    ]
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],
        ],
        'clang' => [
            'exclude' => true,
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:clang',
            'config' => [
                'type' => 'language',
            ],
        ],
    ],
];
