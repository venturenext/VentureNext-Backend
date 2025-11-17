<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\InboxResource;
use App\Models\Inbox;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    /**
     * List inbox messages
     */
    public function index(Request $request)
    {
        $query = Inbox::query();

        // Filter by read status
        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        // Search
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Date range filter
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $messages = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => InboxResource::collection($messages),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
                'unread_count' => Inbox::unread()->count(),
            ]
        ]);
    }

    /**
     * Show single inbox message
     */
    public function show($id)
    {
        $message = Inbox::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new InboxResource($message)
        ]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead($id)
    {
        $message = Inbox::findOrFail($id);
        $message->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read',
            'data' => new InboxResource($message)
        ]);
    }

    /**
     * Mark message as unread
     */
    public function markAsUnread($id)
    {
        $message = Inbox::findOrFail($id);
        $message->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'Message marked as unread',
            'data' => new InboxResource($message)
        ]);
    }

    /**
     * Delete inbox message
     */
    public function destroy($id)
    {
        $message = Inbox::findOrFail($id);
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }

    /**
     * Bulk mark as read
     */
    public function bulkMarkAsRead(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:inbox,id'
        ]);

        Inbox::whereIn('id', $request->ids)->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read'
        ]);
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:inbox,id'
        ]);

        Inbox::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Messages deleted successfully'
        ]);
    }
}
