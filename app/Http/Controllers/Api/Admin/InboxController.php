<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\InboxResource;
use App\Models\Inbox;
use Illuminate\Http\Request;

class InboxController extends Controller
{

    public function index(Request $request)
    {
        $query = Inbox::query();


        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }


        if ($request->has('search')) {
            $query->search($request->search);
        }


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


    public function show($id)
    {
        $message = Inbox::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new InboxResource($message)
        ]);
    }


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


    public function destroy($id)
    {
        $message = Inbox::findOrFail($id);
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }


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
