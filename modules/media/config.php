<?php
/**
 * media config file
 * @package media
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'media',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/media',
    '__files' => [
        'modules/media' => [
            'install',
            'remove',
            'update'
        ]
    ],
    '__dependencies' => [
        'core'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'Media\\Controller\\ResizerController'  => 'modules/media/controller/ResizerController.php',
            'Eventviva\\ImageResize'                => 'modules/media/third-party/ImageResize.php'
        ],
        'files' => []
    ],
    
    '_routes' => [
        'media' => [
            'mediaReceiver' => [
                'rule' => '/:dir1/:dir2/:dir3/:file',
                'handler' => 'Media\\Controller\\Resizer::init'
            ],
            'mediaGenerator' => [
                'rule' => '/:path',
                'handler' => 'Media\\Controller\\Resizer::init'
            ]
        ]
    ]
];