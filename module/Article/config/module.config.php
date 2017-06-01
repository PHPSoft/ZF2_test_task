<?php

namespace Article;

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /article/:controller/:action
            'article' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/article',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Article\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'page'          => 1,
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),

                    'paged' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/page/:page',
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Article\Controller\Index',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),

            'edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/article/edit/:postId',
                    'constraints' => array(
                        'postId' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Article\Controller\Index',
                        'action' => 'edit',
                    ),
                ),
            ),

            'delete' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/article/delete/:postId',
                    'constraints' => array(
                        'postId' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Article\Controller\Index',
                        'action' => 'delete',
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Article\Controller\Index' => Controller\IndexController::class
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);