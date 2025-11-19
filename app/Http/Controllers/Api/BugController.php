<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBugRequest;
use App\Http\Requests\UpdateBugRequest;
use App\Models\Bug;
use Illuminate\Http\Request;

class BugController extends Controller
{
    /**
     * Display a listing of bugs.
     */
    public function index(Request $request)
    {
        $query = Bug::with(['user', 'app', 'category'])
            ->withCount(['comments', 'votes']);

        // Filter by app
        if ($request->has('app_id')) {
            $query->where('app_id', $request->app_id);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by severity
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'upvotes') {
            $query->orderBy('upvotes', $sortOrder);
        } elseif ($sortBy === 'views') {
            $query->orderBy('views', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        $bugs = $query->paginate(20);

        return response()->json($bugs);
    }

    /**
     * Store a newly created bug.
     */
    public function store(StoreBugRequest $request)
    {
        $bug = Bug::create([
            'user_id' => $request->user()->id,
            'app_id' => $request->app_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'steps_to_reproduce' => $request->steps_to_reproduce,
            'expected_behavior' => $request->expected_behavior,
            'actual_behavior' => $request->actual_behavior,
            'device_info' => $request->device_info,
            'os_version' => $request->os_version,
            'app_version' => $request->app_version,
            'attachments' => $request->attachments,
            'severity' => $request->severity,
        ]);

        $bug->load(['user', 'app', 'category']);

        return response()->json([
            'message' => 'Bug reported successfully',
            'bug' => $bug,
        ], 201);
    }

    /**
     * Display the specified bug.
     */
    public function show(Bug $bug)
    {
        $bug->incrementViews();

        $bug->load([
            'user',
            'app',
            'category',
            'comments.user',
            'comments.replies.user',
        ]);

        $bug->loadCount(['comments', 'votes']);

        return response()->json($bug);
    }

    /**
     * Update the specified bug.
     */
    public function update(UpdateBugRequest $request, Bug $bug)
    {
        $bug->update($request->validated());

        $bug->load(['user', 'app', 'category']);

        return response()->json([
            'message' => 'Bug updated successfully',
            'bug' => $bug,
        ]);
    }

    /**
     * Remove the specified bug.
     */
    public function destroy(Request $request, Bug $bug)
    {
        // Only owner or moderator can delete
        if ($request->user()->id !== $bug->user_id && !$request->user()->isModerator()) {
            return response()->json([
                'message' => 'Unauthorized to delete this bug',
            ], 403);
        }

        $bug->delete();

        return response()->json([
            'message' => 'Bug deleted successfully',
        ]);
    }
}
