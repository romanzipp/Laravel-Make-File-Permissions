<?php

return [

    /**
     * Enable the permission service
     */
    'enabled' => env('MAKE_PERMISSIONS_ENABLED', true),

    /**
     * Appyl the following permission
     */
    'permissions' => env('MAKE_PERMISSIONS', '600'),

    /**
     * Ignore commands
     */
    'ignore' => [],
];
