<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Enviando correo de prueba a: {$email}");

        try {
            $nombre = 'Usuario Pruebas';
            $password = 'password123';

            \Illuminate\Support\Facades\Mail::to($email)
                ->send(new \App\Mail\UserCredentialsMail($nombre, $email, $password));

            $this->info('Correo enviado exitosamente.');
        } catch (\Exception $e) {
            $this->error('Falló el envío del correo: ' . $e->getMessage());
            $this->error('Detalles: ' . $e->getTraceAsString());
        }
    }
}
