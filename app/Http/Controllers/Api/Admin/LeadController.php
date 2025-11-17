<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with('perk');

        // Filter by lead type
        if ($request->has('lead_type')) {
            $query->where('lead_type', $request->lead_type);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%")
                  ->orWhere('company', 'ILIKE', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => LeadResource::collection($leads),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $lead = Lead::with('perk')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new LeadResource($lead)
        ]);
    }

    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully'
        ]);
    }

    public function export(Request $request)
    {
        $query = Lead::with('perk');

        // Apply same filters as index
        if ($request->has('lead_type')) {
            $query->where('lead_type', $request->lead_type);
        }
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->orderBy('created_at', 'desc')->get();

        // Convert to CSV format
        $csv_data = [];
        $csv_data[] = ['ID', 'Type', 'Name', 'Email', 'Phone', 'Company', 'Perk', 'Created At'];

        foreach ($leads as $lead) {
            $csv_data[] = [
                $lead->id,
                $lead->lead_type,
                $lead->name,
                $lead->email,
                $lead->phone,
                $lead->company,
                $lead->perk?->title,
                $lead->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $csv_data,
            'message' => 'Leads exported successfully'
        ]);
    }
}
