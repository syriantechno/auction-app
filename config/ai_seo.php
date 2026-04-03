<?php

return [
    // AgentRouter API Configuration
    'agent_router' => [
        'base_url' => env('AI_SEO_BASE_URL', 'https://agentrouter.org/v1'),
        'api_key' => env('AI_SEO_API_KEY'),
        'model' => env('AI_SEO_MODEL', 'deepseek-v3.2'),
        'timeout' => env('AI_SEO_TIMEOUT', 30),
        'supported_models' => [
            'deepseek-r1-0528' => 'DeepSeek R1 0528',
            'deepseek-v3.1' => 'DeepSeek V3.1',
            'deepseek-v3.2' => 'DeepSeek V3.2',
            'glm-4.5' => 'GLM-4.5',
            'glm-4.6' => 'GLM-4.6',
        ],
    ],

    // SEO Generation Settings
    'generation' => [
        'meta_title_length' => env('SEO_TITLE_LENGTH', 60),
        'meta_description_length' => env('SEO_DESC_LENGTH', 160),
        'max_keywords' => env('SEO_MAX_KEYWORDS', 10),
        'content_analysis_depth' => env('SEO_ANALYSIS_DEPTH', 'deep'),
    ],

    // Indexing & Submission
    'indexing' => [
        'auto_submit_google' => env('SEO_AUTO_SUBMIT_GOOGLE', true),
        'auto_submit_bing' => env('SEO_AUTO_SUBMIT_BING', true),
        'submission_delay' => env('SEO_SUBMISSION_DELAY', 2), // seconds between submissions
        'max_daily_submissions' => env('SEO_MAX_DAILY_SUBMISSIONS', 100),
    ],

    // Content Types
    'content_types' => [
        'auction' => [
            'schema' => 'Product',
            'priority' => 'high',
            'update_frequency' => 'daily',
        ],
        'page' => [
            'schema' => 'WebPage',
            'priority' => 'medium',
            'update_frequency' => 'weekly',
        ],
        'blog' => [
            'schema' => 'Article',
            'priority' => 'medium',
            'update_frequency' => 'monthly',
        ],
    ],

    // Performance Monitoring
    'monitoring' => [
        'track_rankings' => env('SEO_TRACK_RANKINGS', true),
        'track_indexing_status' => env('SEO_TRACK_INDEXING', true),
        'alert_on_errors' => env('SEO_ALERT_ERRORS', true),
        'daily_report' => env('SEO_DAILY_REPORT', true),
    ],
];
