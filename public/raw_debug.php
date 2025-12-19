<?php
define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Manually Query logic here to print debug info
echo "<h1>RAW DEBUG</h1>";
$now = now();
echo "NOW: " . $now . "<br>";
$events = \App\Models\Evento::take(10)->get();
echo "Count: " . $events->count() . "<br><hr>";
foreach ($events as $e) {
    echo $e->eve_nombre . " | " . $e->eve_inicia . " | " . $e->eve_finaliza . "<br>";
}
