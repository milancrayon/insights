<?php

/**
 * Backend Routes configuration for TYPO3 v12/13
 */
return [
    // Register the AI generation route
    'insights_ai_generate' => [
        'path' => '/insights/ai/generate',
        'target' => \T3element\Insights\Controller\AiController::class . '::generateAction',
    ],
];
