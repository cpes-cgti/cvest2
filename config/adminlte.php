<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'CVEST - Redações 2019.1',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => 'REDAÇÕES <b>2019.1</b>',

    'logo_mini' => '<b>IFPE</b>',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'green',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => true,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'admin/resumo',

    'logout_url' => 'sair',

    'logout_method' => 'GET',

    'login_url' => 'entrar',

    'register_url' => null,

    'password_reset_url' => '/senha/recuperar',

    'password_email_url' => '/senha/email',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        [
            'text' => 'Resumo',
            'icon' => 'home',
            'route'  => 'admin',
        ],
        [
            'text' => 'Alterar senha',
            'icon' => 'key',
            'route'  => 'password.change',
        ],
        [
            'text' => 'Avaliadores',
            'icon' => 'users',
            'can' => 'level2',
            'route'  => 'corrector.index',
        ],
        [
            'text' => 'Importar Redações',
            'icon' => 'file-import',
            'can' => 'level4',
            'route'  => 'redaction.import',
        ],
        [
            'text' => 'Marcar para Correção',
            'icon' => 'clipboard-check',
            'can' => 'level4',
            'route'  => 'redaction.for_correction',
        ],
        [
            'text' => 'Redações',
            'icon' => 'file-signature',
            'can' => 'level2',
            'route'  => 'redaction.index',
        ],
        [
            'text' => 'Distribuir redações',
            'icon' => 'share-square',
            'can' => 'level4',
            'route'  => 'redaction.allocate',
        ],
        [
            'text' => 'Corrigir redações',
            'icon' => 'user-check',
            'can' => 'corrector',
            'route'  => 'redaction.rate_lots',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => false,
        'select2'    => false,
        'chartjs'    => false,
    ],
];
