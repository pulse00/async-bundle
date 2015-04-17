<?php

if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;

spl_autoload_register(function($class) {
    if ($class === 'TestService') {
        require_once __DIR__ . '/TestService.php';
    }
});

AnnotationRegistry::registerFile(realpath(__DIR__ . '/../Annotation/Async.php'));

AnnotationRegistry::registerFile(realpath(__DIR__ . '/../vendor/jms/di-extra-bundle/JMS/DiExtraBundle/Annotation/Service.php'));
AnnotationRegistry::registerFile(realpath(__DIR__ . '/../vendor/nelmio/api-doc-bundle/Nelmio/ApiDocBundle/Annotation/ApiDoc.php'));

AnnotationRegistry::registerFile(realpath(__DIR__ . '/../vendor/friendsofsymfony/rest-bundle/FOS/RestBundle/Controller/Annotations/QueryParam.php'));
AnnotationRegistry::registerFile(realpath(__DIR__ . '/../vendor/friendsofsymfony/rest-bundle/FOS/RestBundle/Controller/Annotations/View.php'));
