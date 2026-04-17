<?php

return [
    'ctrl' => [
        'label' => '',
        'title' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:table_author_socialmedia',
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
        'hideTable' => true,
        'typeicon_classes' => [
            'default' => 'insights',
        ], 
        'security' => [
            'ignorePageTypeRestriction' => true,
        ], 
    ],
    'types' =>  [
        '1' => [
            'showitem' => '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, f17654463090,f17654463231,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime',
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
        'f17654463090' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:author_socialmedia_f17654463090',
            'config' => [
                'type' => 'link',
            ], 
        ],
        'f17654463231' => [
            'label' => 'LLL:EXT:insights/Resources/Private/Language/locallang.xlf:author_socialmedia_f17654463231',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
            ], 
        ],
        "parent" => [
            'label' => 'tx_insights_domain_model_author',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_insights_domain_model_author',
                'foreign_table_where' => 'AND {#tx_insights_domain_model_author}.{#pid}=###CURRENT_PID### AND {#tx_insights_domain_model_author}.{#sys_language_uid}=\'###REC_FIELD_sys_language_uid###\'', 
            ],
        ]
        
    ],
];
