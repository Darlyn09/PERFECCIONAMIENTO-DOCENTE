<?php
use App\Models\Evento;
use Carbon\Carbon;

echo "Current Time (App): " . now()->format('Y-m-d H:i:s') . "\n";
echo "Current Time (PHP): " . date('Y-m-d H:i:s') . "\n";
echo "Timezone: " . config('app.timezone') . "\n\n";

$events = Evento::all();
echo "Total Events: " . $events->count() . "\n";

foreach ($events as $event) {
    echo "ID: " . $event->eve_id . "\n";
    echo "Name: " . $event->eve_nombre . "\n";
    echo "Start: " . $event->eve_inicia . "\n";
    echo "End: " . $event->eve_finaliza . "\n";
    echo "Status: " . $event->eve_estado . "\n";

    $start = Carbon::parse($event->eve_inicia);
    $end = Carbon::parse($event->eve_finaliza);
    $now = now();

    $isActive = $now->between($start, $end);
    echo "Is Active? " . ($isActive ? 'YES' : 'NO') . "\n";
    echo "--------------------------\n";
}
