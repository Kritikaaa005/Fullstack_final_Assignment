<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\View\FileViewFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\View\Factory;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Compilers\BladeCompiler;

// Setup the Blade templating engine
$views = __DIR__ . '/../views'; // Path to the views directory
$cache = __DIR__ . '/../cache'; // Path to the cache directory

$filesystem = new Filesystem;
$eventDispatcher = new Dispatcher;

$bladeCompiler = new BladeCompiler($filesystem, $cache);

$engineResolver = new EngineResolver;
$engineResolver->register('blade', function () use ($bladeCompiler) {
    return new CompilerEngine($bladeCompiler);
});
$engineResolver->register('php', function () use ($filesystem) {
    return new PhpEngine($filesystem);
});

$viewFinder = new FileViewFinder($filesystem, [$views]);

$blade = new Factory($engineResolver, $viewFinder, $eventDispatcher);

return $blade;