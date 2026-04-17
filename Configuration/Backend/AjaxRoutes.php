<?php
use T3element\Insights\Controller\BeinsightController;

return [
    'insight_be_table_posts' => [
        'path' => '/insight/postsbyAjax',
        'target' => BeinsightController::class . '::postsbyAjax',
    ],
    'insight_be_table_comments' => [
        'path' => '/insight/comments',
        'target' => BeinsightController::class . '::commentsbyAjax',
    ],
    'insight_be_table_authors' => [
        'path' => '/insight/authors',
        'target' => BeinsightController::class . '::authorsbyAjax',
    ],
    'insight_posts_status' => [
        'path' => '/insight/updatestatus',
        'target' => BeinsightController::class . '::updateStatusAction',
    ],
    'insight_posts_details' => [
        'path' => '/insight/postdata',
        'target' => BeinsightController::class . '::bePostData',
    ],
    'insight_comment_status' => [
        'path' => '/insight/commentstatus',
        'target' => BeinsightController::class . '::commentStatus',
    ],
];
