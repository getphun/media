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
    '_server' => [
        'PHP Lib GD' => 'Media\\Library\\Server::gdlib'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'Media\\Controller\\ResizerController'  => 'modules/media/controller/ResizerController.php',
            'Media\\Library\\Server'                => 'modules/media/library/Server.php',
            'Gumlet\\ImageResize'                   => 'modules/media/third-party/ImageResize.php',
            'Gumlet\\ImageResizeException'          => 'modules/media/third-party/ImageResizeException.php',
            'Media\\Controller\\MediaController'    => 'modules/media/controller/MediaController.php'
        ],
        'files' => []
    ],
    
    '_gates' => [
        'media' => [
            'path' => '/media'
        ]
    ],
    
    '_routes' => [
        'media' => [
            '404' => [
                'handler' => 'Media\\Controller\\Media::notFound'
            ],
            'mediaReceiver' => [
                'rule' => '/:dir1/:dir2/:dir3/:file',
                'handler' => 'Media\\Controller\\Resizer::init'
            ],
            'mediaGenerator' => [
                'rule' => '/:path',
                'handler' => 'Media\\Controller\\Resizer::init'
            ]
        ]
    ],
    
    'media' => [
        'webp' => false
    ]
];