<?php

return [
    'office_wifi_allowed_ips' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('OFFICE_WIFI_ALLOWED_IPS', ''))
    ))),
];
