<?php
return [
    'ctrl' => [
        'label' => 'title',
        'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:table_post',
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
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, title,poststatus,recommended,slug,alternativetitle,metakeyword,metadescription,postschema,teaser,description,publishdate,archivedate,thumbnail,category,viewers,author,tags,relatedfiles,previouspost,nextpost,relatedposts,--div--;Content,celement,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
            'columnsOverrides' => [
                'description' => [
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
        'title' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_title',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'required ' => true,
                'fieldControl' => [
                    'aiAssistant' => [
                        'renderType' => 'aiAssistant',
                    ],
                ],
            ],
        ],
        'slug' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_slug',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => [
                        'title',
                    ],
                ],
                'required' => true,
            ],
        ],
        'alternativetitle' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_alternativetitle',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'fieldControl' => [
                    'aiAssistant' => [
                        'renderType' => 'aiAssistant',
                    ],
                ],
            ],
        ],
        'poststatus' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:poststatus',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:poststatus',
                        'value' => 1
                    ],
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],

        ],
        'recommended' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:recommended',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:recommended',
                        'value' => 1
                    ],
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],

        ],
        'metakeyword' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_metakeyword',
            'config' => [
                'type' => 'text',
                'fieldControl' => [
                    'aiAssistant' => [
                        'renderType' => 'aiAssistant',
                    ],
                ],
            ],
        ],
        'metadescription' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_metadescription',
            'config' => [
                'type' => 'text',
                'fieldControl' => [
                    'aiAssistant' => [
                        'renderType' => 'aiAssistant',
                    ],
                ],
            ],
        ],
        'postschema' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:postschema',
            'config' => [
                'type' => 'text',
                'fieldControl' => [
                    'aiAssistant' => [
                        'renderType' => 'aiAssistant',
                    ],
                ],
            ],
        ],
        'teaser' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_teaser',
            'config' => [
                'type' => 'text',
                'required ' => true,
                'fieldControl' => [
                    'aiAssistant' => [
                        'renderType' => 'aiAssistant',
                    ],
                ],
            ],
        ],
        'description' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'fieldControl' => [
                    'aiAssistant' => [
                        'renderType' => 'aiAssistant',
                    ],
                ],
            ],
        ],
        'publishdate' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_publishdate',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
            ],
        ],
        'archivedate' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_archivedate',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
            ],
        ],
        'thumbnail' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_thumbnail',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
            ],
        ],
        'category' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_category',
            'config' => [
                'type' => 'category',
            ],
        ],
        'viewers' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_viewers',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
            ],
        ],
        'author' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_author',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_insights_domain_model_author',
                'foreign_table_where' => 'AND {#tx_insights_domain_model_author}.{#sys_language_uid} = ###REC_FIELD_sys_language_uid###',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'tags' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_tags',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_insights_domain_model_tags',
                'foreign_table_where' =>
                    'AND {#tx_insights_domain_model_tags}.{#sys_language_uid} = 0',
                'MM' => 'tx_insights_post_tag_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
            ],
        ],
        'relatedfiles' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_relatedfiles',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-media-types',
            ],
        ],
        'previouspost' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_previouspost',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_insights_domain_model_post',
                'foreign_table_where' => 'AND {#tx_insights_domain_model_post}.{#sys_language_uid} = ###REC_FIELD_sys_language_uid###',
                'items' => [
                    ["label" => '-- None --', "value" => 0],
                ],
                'default' => 0,
            ],
        ],
        'nextpost' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_nextpost',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_insights_domain_model_post',
                'foreign_table_where' => 'AND {#tx_insights_domain_model_post}.{#sys_language_uid} = ###REC_FIELD_sys_language_uid###',
                'items' => [
                    ["label" => '-- None --', "value" => 0],
                ],
                'default' => 0,
            ],
        ],
        'relatedposts' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_relatedposts',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_insights_domain_model_post',
                'foreign_table_where' => 'AND {#tx_insights_domain_model_post}.{#sys_language_uid} = ###REC_FIELD_sys_language_uid###',
                'items' => [
                    ["label" => '-- None --', "value" => 0],
                ],
                'default' => 0,
            ],
        ],
        'celement' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:post_content',
            'config' => [
                'type' => 'inline',
                'allowed' => 'tt_content',
                'foreign_table' => 'tt_content',
                'foreign_sortby' => 'sorting',
                'foreign_field' => 'tx_insights_content',
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'levelLinksPosition' => 'bottom',
                    'useSortable' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showAllLocalizationLink' => true,
                    'showSynchronizationLink' => true,
                    'enabledControls' => [
                        'info' => false,
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],

            ],
        ],
    ],
];
