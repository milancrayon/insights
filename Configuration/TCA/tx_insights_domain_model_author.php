<?php

return [
    'ctrl' => [
        'label' => 'name',
        'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:table_author',
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
    'types' =>  [
        '1' => [
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, name,email,avtar,slug,designation,intro,socialmedia,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
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
        'name' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:author_name',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'required ' => true,
            ], 
        ],
        'email' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:author_email',
            'config' => [
                'type' => 'email',
                'required ' => true,
            ], 
        ],
        'avtar' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:author_avtar',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
            ], 
        ],
        'slug' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:author_slug',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => [
                        'name',
                    ],
                    'fieldSeparator' => '/',
                    'prefixParentPageSlug' => true,
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'required ' => true,
            ], 
        ],
        'designation' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:author_designation',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ], 
        ],
        'intro' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:author_intro',
            'config' => [
                'type' => 'text',
            ], 
        ],
        'socialmedia' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:author_socialmedia',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_insights_domain_model_socialmedia',
                'foreign_field' => 'parent',
                'appearance' => [
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'newRecordLinkAddTitle' => false,
                    'newRecordLinkTitle'=> "Add New Social Media",
                ],
            ], 
        ],
    ],
];
