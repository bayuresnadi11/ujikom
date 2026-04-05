<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class CleanJapatiSettings extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = Setting::first();
        
        if ($setting) {
            // Clean API Token - remove all whitespace and newlines
            $setting->japati_api_token = trim(preg_replace('/\s+/', '', $setting->japati_api_token));
            
            // Clean Gateway Number - remove all whitespace and newlines
            $setting->japati_gateway_number = trim(preg_replace('/\s+/', '', $setting->japati_gateway_number));
            
            $setting->save();
            
            $this->command->info('✅ Japati settings cleaned successfully!');
            $this->command->info('API Token: ' . substr($setting->japati_api_token, 0, 20) . '...');
            $this->command->info('Gateway: ' . $setting->japati_gateway_number);
        } else {
            $this->command->error('❌ No settings found in database');
        }
    }
}
