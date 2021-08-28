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
];
