<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MediaController extends Controller
{
    public function proxy(Request $request)
    {
        $url = $request->query('url');
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['success' => false, 'message' => 'Invalid url'], 422);
        }

        try {
            $resp = Http::timeout(10)->withHeaders([
                'User-Agent' => 'PerkPal-MediaProxy/1.0'
            ])->get($url);

            if (!$resp->ok()) {
                return response()->json(['success' => false, 'message' => 'Failed to fetch'], $resp->status());
            }

            $contentType = $resp->header('Content-Type', 'application/octet-stream');
            return response($resp->body(), 200)
                ->header('Content-Type', $contentType)
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Proxy error'], 500);
        }
    }
}

