<?php

return [

    'ownerMail' => env('CTVERO_OWNER_MAIL'),

    'diaryUrlToProcessor' => [
        'http://drive.cbdx.cz/xdenik' => 'CbdxCz',
        'https://drive.cbdx.cz/xdenik' => 'CbdxCz',
        'http://www.cbpmr.cz/deniky/' => 'CbpmrCz',
        'https://www.cbpmr.cz/deniky/' => 'CbpmrCz',
        'http://www.cbpmr.info/share/' => 'CbpmrInfo',
        'https://www.cbpmr.info/share/' => 'CbpmrInfo',
    ],

    'cbpmrInfoApiUrl' => env('CTVERO_CBPMR_INFO_API_URL'),
    'cbpmrInfoApiAuthUsername' => env('CTVERO_CBPMR_INFO_API_AUTH_USERNAME'),
    'cbpmrInfoApiAuthPassword' => env('CTVERO_CBPMR_INFO_API_AUTH_PASSWORD'),

    'recaptchaSiteKey' => env('CTVERO_RECAPTCHA_SITE_KEY'),
    'recaptchaSecret' => env('CTVERO_RECAPTCHA_SECRET'),
    'recaptchaScoreThreshold' => env('CTVERO_RECAPTCHA_SCORE_THRESHOLD', 0.5),

    'repositoryUrl' => env('CTVERO_REPOSITORY_URL'),
    'issuesReportUrl' => env('CTVERO_ISSUES_REPORT_URL'),
];
