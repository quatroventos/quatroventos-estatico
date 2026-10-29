<?php

return [
    'admin_path' => trim((string) env('ADMIN_PATH', 'gestao'), '/'),
    'lighthouse_url' => trim((string) env('LIGHTHOUSE_URL', env('APP_URL', '')), '/'),
    'pagespeed_key' => (string) env('PAGESPEED_API_KEY', ''),
    'options_ttl' => (int) env('CMS_OPTIONS_TTL', 3600),
    'backup' => [
        'hour' => (int) env('BACKUP_HOUR', 3),
        'retention_days' => (int) env('BACKUP_RETENTION_DAYS', 7),
    ],
];
