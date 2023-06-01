<?php

use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

$kernel = new AppKernel('prod', false);
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

/* Migration 3.4 */

// tell Symfony about your reverse proxy
Request::setTrustedProxies(
    // trust *all* requests
    ['127.0.0.1', $request->server->get('REMOTE_ADDR')],

    // if you're using ELB, otherwise use a constant from above
    Request::HEADER_X_FORWARDED_AWS_ELB
);