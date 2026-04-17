<?php

return [
    'frontend' => [
        't3element/insights/search-api' => [
            'target' => \T3element\Insights\Middleware\SearchMiddleware::class,
            'before' => [
                'typo3/cms-frontend/page-resolver',
            ],
        ],
    ],
];