<?php
/*
 * This file is part of the Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Doctrine\Common\Annotations\AnnotationRegistry;

$file = __DIR__ . '/../../../../../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

$autoload = require_once $file;

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(function() use ($autoload) { return call_userfunc([$autoload, 'loadClassLoader']); });
AnnotationRegistry::registerFile(__DIR__ . '/../../../../../vendor/doctrine/phpcr-odm/lib/Doctrine/ODM/PHPCR/Mapping/Annotations/DoctrineAnnotations.php');
