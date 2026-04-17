<?php

return [
    'ctrl' => [
        'label' => 'status_to_show',
        'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:table_config',
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
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, socialmedia,storageid,status_to_show,nextprvbtn,socialshare,addcomments,displayauthor,aioptions,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
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
        'socialmedia' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:socialmedia',
            'config' => [
                'type' => 'text',
            ],
        ],
        'status_to_show' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:status_to_show',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:comments_status_approved',
                        'value' => 1
                    ],
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],
        ],
        'displaycomment' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:displaycomment',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:displaycomment',
                        'value' => 1
                    ]
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],
        ],
        'storageid' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:storageid',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'pages',
                'foreign_table_where' => ' AND pages.doktype=' . \TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_SYSFOLDER . ' AND pages.deleted=0 AND pages.hidden=0 ORDER BY pages.title',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'nextprvbtn' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:nextprvbtn',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:nextprvbtn',
                        'value' => 1
                    ]
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],
        ],
        'socialshare' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:socialshare',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:socialshare',
                        'value' => 1
                    ]
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],
        ],
        'addcomments' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:addcomments',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:addcomments',
                        'value' => 1
                    ]
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],
        ],
        'displayauthor' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:displayauthor',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:displayauthor',
                        'value' => 1
                    ]
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],
        ],
        'langwisecomment' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:langwisecomment',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:langwisecomment',
                        'value' => 1
                    ]
                ],
                'behaviour' => [
                    'allowNull' => true,
                ],
            ],
        ],
        'aioptions' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:aioptions',
            'config' => [
                'type' => 'json',
                'default' => '[]',
            ],
        ],

    ],
];
