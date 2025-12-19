<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private $jsonPath = 'settings.json';

    private function getSettings()
    {
        if (!\Illuminate\Support\Facades\Storage::exists($this->jsonPath)) {
            // Default Settings
            return [
                'platform_name' => 'Sistema de Capacitación',
                'institution_email' => 'contacto@institucion.cl',
                'contact_phone' => '+56 9 1234 5678',
                'timezone' => 'America/Santiago',
                'date_format' => 'd/m/Y',
                'system_status' => 'active',
                'maintenance_message' => 'Estamos realizando mejoras. Volveremos pronto.',
                'logo_url' => null
            ];
        }
        return json_decode(\Illuminate\Support\Facades\Storage::get($this->jsonPath), true) ?? [];
    }

    private function saveSettings($data)
    {
        \Illuminate\Support\Facades\Storage::put($this->jsonPath, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function index()
    {
        $settings = $this->getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'platform_name' => 'required|string|max:255',
            'institution_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'system_status' => 'required|in:active,maintenance',
            'maintenance_message' => 'nullable|string',
            'logo' => 'nullable|image|max:2048'
        ]);

        $settings = $this->getSettings();

        // Update basic fields
        $fields = ['platform_name', 'institution_email', 'contact_phone', 'timezone', 'date_format', 'system_status', 'maintenance_message'];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $settings[$field] = $request->input($field);
            }
        }

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/settings');
            $settings['logo_url'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}
