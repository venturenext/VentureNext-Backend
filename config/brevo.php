<?php

return [
    'api-key' => env('BREVO_API_KEY', env('EMAIL_API_KEY', '')),
];
