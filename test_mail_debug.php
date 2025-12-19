<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to send UserCredentialsMail via " . config('mail.default') . "...\n";

    // Simulate data
    $mail = new \App\Mail\UserCredentialsMail('Test User', 'test@example.com', 'secret123');

    Illuminate\Support\Facades\Mail::to('test@example.com')->send($mail);

    echo "Mailable sent successfully\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
