<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\App;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * Display a listing of apps.
     */
    public function index()
    {
        $apps = App::where('is_active', true)
            ->withCount('bugs')
            ->orderBy('name')
            ->get();

        return response()->json($apps);
    }

    /**
     * Store a newly created app (Admin only).
     */
    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'company' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'logo_url' => ['nullable', 'string', 'url', 'max:255'],
        ]);

        $app = App::create($request->validated());

        return response()->json([
            'message' => 'App created successfully',
            'app' => $app,
        ], 201);
    }

    /**
     * Display the specified app.
     */
    public function show(App $app)
    {
        $app->loadCount('bugs');

        return response()->json($app);
    }

    /**
     * Update the specified app (Admin only).
     */
    public function update(Request $request, App $app)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'company' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'logo_url' => ['nullable', 'string', 'url', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $app->update($request->validated());

        return response()->json([
            'message' => 'App updated successfully',
            'app' => $app,
        ]);
    }

    /**
     * Remove the specified app (Admin only).
     */
    public function destroy(Request $request, App $app)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $app->delete();

        return response()->json([
            'message' => 'App deleted successfully',
        ]);
    }
}
