<?php

use App\Mail\UserCredentialsMail;
use Illuminate\Support\Facades\Mail;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Mail::to('darlyn@cfrd.cl')->send(new UserCredentialsMail('Darlyn', 'darlyn@cfrd.cl', 'test-password-123'));
    echo "Email sent successfully to darlyn@cfrd.cl\n";
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
