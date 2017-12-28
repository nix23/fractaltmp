<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__.'/../wasm/Wasm/_app/autoload.php';
Debug::enable();

require_once __DIR__.'/../wasm/Wasm/_app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

// By default load WASM app??? add app_wasm.php file here???
// Select correct App based on params(Domain)

// Map request to N-th app by City/Country/Device, etc... (Experimental features)
// This is appDebug.php -> load sym debugger