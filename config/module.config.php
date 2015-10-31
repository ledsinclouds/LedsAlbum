<?php

return array(
    'controllers' => array(
//        'invokables' => array(
//            'LedsAlbum\Controller\LedsAlbum' => 'LedsAlbum\Controller\LedsAlbumController',
//            'LedsAlbum\Controller\Artist' => 'LedsAlbum\Controller\ArtistController',
//        ),
    ),
    'router' => array(
        'routes' => array(
            'leds-album' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/leds-album[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'LedsAlbum\Controller\LedsAlbum',
                        'action' => 'index',
                    ),
                ),
            ),
            'artist' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/leds-album/artist[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'LedsAlbum\Controller\Artist',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'leds-album' => __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'LedsAlbum_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/LedsAlbum/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'LedsAlbum\Entity' => 'LedsAlbum_driver'
                )
            )
        )
    ),
);
