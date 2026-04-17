<?php
declare(strict_types=1);
return [
    \T3element\Insights\Domain\Model\FileReference::class => [
        'tableName' => 'sys_file_reference',
    ],
    \T3element\Insights\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
        'properties' => [
            'parentcategory' => [
                'fieldName' => 'parent',
            ],
        ],
    ],
    \T3element\Insights\Domain\Model\TtContent::class => [
        'tableName' => 'tt_content',
    ],
    \T3element\Insights\Domain\Model\Comments::class => [
        'tableName' => 'tx_insights_domain_model_comments',
    ],
    \T3element\Insights\Domain\Model\Post::class => [
        'tableName' => 'tx_insights_domain_model_post',
        'properties' => [
            'category' => [
                'fieldName' => 'category',
            ],
            'poststatus' => [
                'fieldName' => 'poststatus',
            ],
        ],
    ],
];