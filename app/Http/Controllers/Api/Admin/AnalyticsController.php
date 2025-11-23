<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\Perk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function getDashboardStats(Request $request)
    {
        $dateRange = $request->get('range', '30');
        $startDate = now()->subDays((int)$dateRange);

        // Overall stats
        $totalImpressions = Analytic::where('event_type', 'impression')
            ->where('created_at', '>=', $startDate)
            ->count();

        $totalClicks = Analytic::where('event_type', 'click')
            ->where('created_at', '>=', $startDate)
            ->count();

        $totalFormSubmissions = Analytic::where('event_type', 'form_submission')
            ->where('created_at', '>=', $startDate)
            ->count();

        $totalAffiliateClicks = Analytic::where('event_type', 'affiliate_click')
            ->where('created_at', '>=', $startDate)
            ->count();

        $ctr = $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0;
        $formConversionRate = $totalClicks > 0 ? round(($totalFormSubmissions / $totalClicks) * 100, 2) : 0;
        $affiliateConversionRate = $totalClicks > 0 ? round(($totalAffiliateClicks / $totalClicks) * 100, 2) : 0;

        // Top performing perks
        $topPerks = DB::table('analytics')
            ->select(
                'perk_id',
                'perks.title',
                DB::raw("SUM(CASE WHEN event_type = 'impression' THEN 1 ELSE 0 END) as impressions"),
                DB::raw("SUM(CASE WHEN event_type = 'click' THEN 1 ELSE 0 END) as clicks"),
                DB::raw("SUM(CASE WHEN event_type = 'form_submission' THEN 1 ELSE 0 END) as form_submissions"),
                DB::raw("SUM(CASE WHEN event_type = 'affiliate_click' THEN 1 ELSE 0 END) as affiliate_clicks")
            )
            ->join('perks', 'analytics.perk_id', '=', 'perks.id')
            ->where('analytics.created_at', '>=', $startDate)
            ->groupBy('perk_id', 'perks.title')
            ->orderByDesc('clicks')
            ->limit(10)
            ->get()
            ->map(function ($perk) {
                $perk->ctr = $perk->impressions > 0 ? round(($perk->clicks / $perk->impressions) * 100, 2) : 0;
                $perk->form_conversion_rate = $perk->clicks > 0 ? round(($perk->form_submissions / $perk->clicks) * 100, 2) : 0;
                $perk->affiliate_conversion_rate = $perk->clicks > 0 ? round(($perk->affiliate_clicks / $perk->clicks) * 100, 2) : 0;
                return $perk;
            });

        // Daily trend data for chart
        $dailyTrends = DB::table('analytics')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw("SUM(CASE WHEN event_type = 'impression' THEN 1 ELSE 0 END) as impressions"),
                DB::raw("SUM(CASE WHEN event_type = 'click' THEN 1 ELSE 0 END) as clicks"),
                DB::raw("SUM(CASE WHEN event_type = 'form_submission' THEN 1 ELSE 0 END) as form_submissions"),
                DB::raw("SUM(CASE WHEN event_type = 'affiliate_click' THEN 1 ELSE 0 END) as affiliate_clicks")
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'overall' => [
                'impressions' => $totalImpressions,
                'clicks' => $totalClicks,
                'form_submissions' => $totalFormSubmissions,
                'affiliate_clicks' => $totalAffiliateClicks,
                'ctr' => $ctr,
                'form_conversion_rate' => $formConversionRate,
                'affiliate_conversion_rate' => $affiliateConversionRate,
            ],
            'top_perks' => $topPerks,
            'daily_trends' => $dailyTrends,
        ]);
    }

    public function getPerformanceByPerk(Request $request, $perkId)
    {
        $dateRange = $request->get('range', '30');
        $startDate = now()->subDays((int)$dateRange);

        $perk = Perk::findOrFail($perkId);

        $impressions = Analytic::where('perk_id', $perkId)
            ->where('event_type', 'impression')
            ->where('created_at', '>=', $startDate)
            ->count();

        $clicks = Analytic::where('perk_id', $perkId)
            ->where('event_type', 'click')
            ->where('created_at', '>=', $startDate)
            ->count();

        $formSubmissions = Analytic::where('perk_id', $perkId)
            ->where('event_type', 'form_submission')
            ->where('created_at', '>=', $startDate)
            ->count();

        $affiliateClicks = Analytic::where('perk_id', $perkId)
            ->where('event_type', 'affiliate_click')
            ->where('created_at', '>=', $startDate)
            ->count();

        $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;
        $formConversionRate = $clicks > 0 ? round(($formSubmissions / $clicks) * 100, 2) : 0;
        $affiliateConversionRate = $clicks > 0 ? round(($affiliateClicks / $clicks) * 100, 2) : 0;

        return response()->json([
            'perk' => $perk,
            'stats' => [
                'impressions' => $impressions,
                'clicks' => $clicks,
                'form_submissions' => $formSubmissions,
                'affiliate_clicks' => $affiliateClicks,
                'ctr' => $ctr,
                'form_conversion_rate' => $formConversionRate,
                'affiliate_conversion_rate' => $affiliateConversionRate,
            ],
        ]);
    }
}
