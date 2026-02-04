<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Approval Token Configuration
    |--------------------------------------------------------------------------
    */

    'token' => [
        'expiry_days' => env('APPROVAL_TOKEN_EXPIRY_DAYS', 7),
        'length' => 64,
    ],

    /*
    |--------------------------------------------------------------------------
    | Attachment Configuration
    |--------------------------------------------------------------------------
    */

    'attachments' => [
        'max_count' => env('APPROVAL_MAX_ATTACHMENTS', 10),
        'max_file_size' => env('APPROVAL_MAX_FILE_SIZE', 20480), // KB
        'allowed_image_types' => explode(',', env('APPROVAL_ALLOWED_IMAGE_TYPES', 'jpeg,jpg,png,gif,webp')),
        'allowed_document_types' => explode(',', env('APPROVAL_ALLOWED_DOC_TYPES', 'pdf,doc,docx,xls,xlsx,ppt,pptx,txt')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Status Configuration
    |--------------------------------------------------------------------------
    */

    'statuses' => [
        'pending' => 'pending',
        'approved' => 'approved',
        'rejected' => 'rejected',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Configuration
    |--------------------------------------------------------------------------
    */

    'roles' => [
        'admin' => 'admin',
        'team_member' => 'team_member',
    ],

    /*
    |--------------------------------------------------------------------------
    | PDF Configuration
    |--------------------------------------------------------------------------
    */

    'pdf' => [
        'orientation' => 'portrait',
        'paper_size' => 'a4',
        'company_name' => env('APP_NAME', 'Client Approval System'),
    ],

];
