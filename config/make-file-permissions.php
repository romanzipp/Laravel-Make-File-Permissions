<?php

return [

    /**
     * Enable the permission service
     */
    'enabled' => env('MAKE_PERMISSIONS_ENABLED', true),

    /**
     * Apply the following permission
     */
    'permission' => env('MAKE_PERMISSIONS', '600'),

    /**
     * Ignore commands
     */
    'ignore' => [],
];
