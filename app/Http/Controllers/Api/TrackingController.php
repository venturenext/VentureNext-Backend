<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\Perk;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function trackImpression(Request $request)
    {
        $request->validate([
            'perk_id' => 'required|exists:perks,id',
        ]);

        $this->track($request, 'impression');

        return response()->json(['success' => true]);
    }

    public function trackClick(Request $request)
    {
        $request->validate([
            'perk_id' => 'required|exists:perks,id',
        ]);

        $this->track($request, 'click');

        return response()->json(['success' => true]);
    }

    public function trackFormSubmission(Request $request)
    {
        $request->validate([
            'perk_id' => 'required|exists:perks,id',
        ]);

        $this->track($request, 'form_submission');

        return response()->json(['success' => true]);
    }

    public function trackAffiliateClick(Request $request)
    {
        $request->validate([
            'perk_id' => 'required|exists:perks,id',
        ]);

        $this->track($request, 'affiliate_click');

        return response()->json(['success' => true]);
    }

    private function track(Request $request, string $eventType)
    {
        Analytic::create([
            'perk_id' => $request->perk_id,
            'event_type' => $eventType,
            'session_id' => $request->header('X-Session-ID') ?? session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
        ]);
    }
}
