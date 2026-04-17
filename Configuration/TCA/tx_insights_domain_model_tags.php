<?php

return [
    'ctrl' => [
        'label' => 'title',
        'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:table_tags',
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
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, title,slug,description,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
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
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:tags_title',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'required ' => true,
            ], 
        ],
        'slug' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:tags_slug',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => [
                        'title',
                    ],
                    'fieldSeparator' => '/',
                    'prefixParentPageSlug' => true,
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'required ' => true,
            ], 
        ],
        'description' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:tags_description',
            'config' => [
                'type' => 'text',
            ], 
        ],
    ],
];
