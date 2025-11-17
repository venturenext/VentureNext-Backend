<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group_name')
            ->orderBy('key')
            ->get();

        // Group by group_name
        $grouped_settings = $settings->groupBy('group_name');

        return response()->json([
            'success' => true,
            'data' => SettingResource::collection($settings),
            'grouped' => $grouped_settings
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|exists:settings,key',
            'settings.*.value' => 'required',
        ]);

        foreach ($request->settings as $settingData) {
            Setting::where('key', $settingData['key'])
                ->update(['value' => $settingData['value']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ]);
    }
}
