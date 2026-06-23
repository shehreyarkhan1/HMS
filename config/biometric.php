<?php

return [
    // IPs of your ZKTeco machines — empty array = allow all
    'allowed_ips' => array_filter(
        explode(',', env('BIOMETRIC_ALLOWED_IPS', ''))
    )
];
