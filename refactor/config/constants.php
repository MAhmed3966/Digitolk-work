<?php

return
    [
        'constants' => [
            "ADMIN_ROLE_ID" => env('ADMIN_ROLE_ID', 1),
            "SUPERADMIN_ROLE_ID" => env('SUPERADMIN_ROLE_ID',1),
            "PER_PAGE_PAGINATION" => 15, 
            "IMMEDIATE_TIME" => 5, 
            "CUSTOMER_ROLE_ID" => env('CUSTOMER_ROLE_ID', 1)
        ]
    ];
