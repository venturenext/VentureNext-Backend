<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJournalPostRequest;
use App\Http\Requests\UpdateJournalPostRequest;
use App\Http\Resources\JournalPostResource;
use App\Models\JournalPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = JournalPost::query()
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $query->where('title', 'ILIKE', "%{$request->search}%")
                ->orWhere('excerpt', 'ILIKE', "%{$request->search}%");
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $perPage = $request->input('per_page', 20);
        $posts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => JournalPostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ]
        ]);
    }

    public function store(StoreJournalPostRequest $request)
    {
        $data = $request->validated();
        $data['tags'] = $this->normalizeTags($request->input('tags'));
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('journal/covers', 'public');
        }
        if ($request->hasFile('author_avatar')) {
            $data['author_avatar'] = $request->file('author_avatar')->store('journal/avatars', 'public');
        }
        $post = JournalPost::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Journal post created successfully',
            'data' => new JournalPostResource($post)
        ], 201);
    }

    public function show($id)
    {
        $post = JournalPost::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => new JournalPostResource($post)
        ]);
    }

    public function update(UpdateJournalPostRequest $request, $id)
    {
        $post = JournalPost::findOrFail($id);
        $data = $request->validated();
        $data['tags'] = $this->normalizeTags($request->input('tags'));
        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('journal/covers', 'public');
        }
        if ($request->hasFile('author_avatar')) {
            if ($post->author_avatar) {
                Storage::disk('public')->delete($post->author_avatar);
            }
            $data['author_avatar'] = $request->file('author_avatar')->store('journal/avatars', 'public');
        }
        $post->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Journal post updated successfully',
            'data' => new JournalPostResource($post)
        ]);
    }

    public function destroy($id)
    {
        $post = JournalPost::findOrFail($id);
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
        if ($post->author_avatar) {
            Storage::disk('public')->delete($post->author_avatar);
        }
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Journal post deleted successfully'
        ]);
    }

    private function normalizeTags(?string $tags): ?array
    {
        if (!$tags) {
            return null;
        }
        $list = array_filter(array_map('trim', explode(',', $tags)));
        return $list ?: null;
    }
}
