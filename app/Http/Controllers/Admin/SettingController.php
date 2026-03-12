<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman setting
     */
    public function index()
    {
        $setting = Setting::first(); // cuma 1 row
        return view('admin.setting.index', compact('setting'));
    }

    /**
     * Simpan setting (HANYA SEKALI)
     */
    public function store(Request $request)
    {
        if (Setting::exists()) {
            return redirect()
                ->route('admin.setting.index')
                ->with('error', 'Setting sudah ada.');
        }

        $data = $this->validateData($request);

        // upload logo (jika ada)
        if ($request->hasFile('app_logo')) {
            $data['app_logo'] = $this->uploadLogo($request);
        }

        // checkbox midtrans
        $data['midtrans_is_production'] = $request->boolean('midtrans_is_production');

        Setting::create($data);

        return redirect()
            ->route('admin.setting.index')
            ->with('success', 'Setting berhasil disimpan.');
    }

    /**
     * Update setting
     */
    public function update(Request $request)
    {
        $setting = Setting::firstOrFail();

        $data = $this->validateData($request);

        // upload logo baru (replace lama)
        if ($request->hasFile('app_logo')) {
            $this->deleteOldLogo($setting);
            $data['app_logo'] = $this->uploadLogo($request);
        }

        // checkbox midtrans
        $data['midtrans_is_production'] = $request->boolean('midtrans_is_production');

        $setting->update($data);

        return redirect()
            ->route('admin.setting.index')
            ->with('success', 'Setting berhasil diperbarui.');
    }

    /**
     * Validasi form (TANPA logo)
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'app_name' => 'required|string|max:255',

            // JAPATI
            'japati_api_token'      => 'nullable|string',
            'japati_gateway_number' => 'nullable|string',

            // MIDTRANS KEY
            'midtrans_server_key'   => 'nullable|string',
            'midtrans_client_key'   => 'nullable|string',
        ]);
    }

    /**
     * Upload logo
     */
    private function uploadLogo(Request $request)
    {
        $request->validate([
            'app_logo' => 'image|max:2048',
        ]);

        $file = $request->file('app_logo');
        $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();

        $file->storeAs('logo', $filename, 'public');

        return $filename;
    }

    /**
     * Hapus logo lama
     */
    private function deleteOldLogo(Setting $setting)
    {
        if ($setting->app_logo) {
            Storage::disk('public')->delete('logo/' . $setting->app_logo);
        }
    }
}
