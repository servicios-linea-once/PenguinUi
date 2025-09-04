<?php

return [
    /**
     * Default component prefix.
     *
     * Make sure to clear view cache after renaming with `php artisan view:clear`
     *
     *    prefix => ''
     *              <x-button />
     *              <x-card />
     *
     *    prefix => 'penguin-'
     *               <x-penguin-button />
     *               <x-penguin-card />
     *
     */
    'prefix' => '',

    /**
     * Default route prefix.
     *
     * Some maryUI components make network request to its internal routes.
     *
     *      route_prefix => ''
     *          - Spotlight: '/penguin/spotlight'
     *          - Editor: '/penguin/upload'
     *          - ...
     *
     *      route_prefix => 'my-components'
     *          - Spotlight: '/my-components/penguin/spotlight'
     *          - Editor: '/my-components/penguin/upload'
     *          - ...
     */
    'route_prefix' => '',

    /**
     * Components settings
     */
    'components' => [
        'spotlight' => [
            'class' => 'App\Support\Spotlight',
        ]
    ]
];