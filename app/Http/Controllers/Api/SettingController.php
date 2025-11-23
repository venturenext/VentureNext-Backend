<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;

class SettingController extends Controller
{
    
    public function index()
    {
        $settings = Setting::all();


        $settingsData = [];
        foreach ($settings as $setting) {
            $settingsData[$setting->key] = $setting->value;
        }

        return response()->json([
            'success' => true,
            'data' => $settingsData
        ]);
    }
}
