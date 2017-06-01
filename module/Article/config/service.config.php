<?php

namespace Article;

return array(
    'invokables' => array(
        'Article\Repository\PostRepository' => 'Article\Repository\PostRepositoryImpl',
    ),

    'factories' => array(
        'Article\Service\ArticleService' => function(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
            $articleService = new \Article\Service\ArticleServiceImpl();
            $articleService->setArticleRepository($serviceLocator->get('Article\Repository\PostRepository'));

            return $articleService;
        },
    ),

    'initializers' => array(
        function($instance, \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
            if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                $instance->setDbAdapter($serviceLocator->get('Zend\Db\Adapter\Adapter'));
            }
        },
    ),
);