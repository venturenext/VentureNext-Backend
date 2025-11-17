<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Lead;
use App\Models\Perk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_perks' => Perk::count(),
            'active_perks' => Perk::where('is_active', true)->count(),
            'featured_perks' => Perk::where('is_featured', true)->count(),
            'total_categories' => Category::count(),
            'total_leads' => Lead::count(),
            'recent_leads' => Lead::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        // Recent leads
        $recent_leads = Lead::with('perk')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($lead) {
                return [
                    'id' => $lead->id,
                    'lead_type' => $lead->lead_type,
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'perk_title' => $lead->perk?->title,
                    'created_at' => $lead->created_at,
                ];
            });

        // Top perks by views
        $top_perks_by_views = Perk::with(['statistics', 'category'])
            ->whereHas('statistics')
            ->get()
            ->sortByDesc(function ($perk) {
                return $perk->statistics->view_count;
            })
            ->take(5)
            ->map(function ($perk) {
                return [
                    'id' => $perk->id,
                    'title' => $perk->title,
                    'category' => $perk->category?->name,
                    'views' => $perk->statistics->view_count,
                    'claims' => $perk->statistics->claim_count,
                ];
            })
            ->values();

        // Leads by type
        $leads_by_type = Lead::select('lead_type', DB::raw('count(*) as count'))
            ->groupBy('lead_type')
            ->get()
            ->pluck('count', 'lead_type');

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_leads' => $recent_leads,
                'top_perks' => $top_perks_by_views,
                'leads_by_type' => $leads_by_type,
            ]
        ]);
    }
}
