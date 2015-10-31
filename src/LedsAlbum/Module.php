<?php

namespace LedsAlbum;

use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

//use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface {

//    public function onBootstrap($e) {
//        $em = $e->getApplication()->getEventManager();
//        $em->attach(MvcEvent::EVENT_RENDER, function ($e) {
//            $flashMessenger = new \Zend\Mvc\Controller\Plugin\FlashMessenger();
//            if ($flashMessenger->hasSuccessMessage()) {
//                $e->getViewModel()->setVariable('successMessage', $flashMessenger->getSuccessMessage());
//            }
//        });
//    }

    public function getConfig() {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
            ),
        );
    }

    public function getControllerConfig() {
        return array(
            'invokables' => array(
                'LedsAlbum\Controller\LedsAlbum' => 'LedsAlbum\Controller\LedsAlbumController',
                'LedsAlbum\Controller\Artist' => 'LedsAlbum\Controller\ArtistController',
            ),
        );
    }

}
