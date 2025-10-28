<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Theme;
use App\Services\PageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function create()
    {
        $themes = Theme::all();
        return view('pages.create', compact('themes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'theme_id' => 'required|exists:themes,id',
        ]);

        $page = $this->pageService->createPage($request->user(), $validated);

        return redirect()->route('pages.edit', $page)
            ->with('success', 'Page created successfully!');
    }

    public function edit(Page $page)
    {
        Gate::authorize('update', $page);

        $themes = Theme::all();
        return view('pages.edit', compact('page', 'themes'));
    }

    public function update(Request $request, Page $page)
    {
        Gate::authorize('update', $page);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|array',
            'theme_id' => 'sometimes|exists:themes,id',
            'change_description' => 'nullable|string',
        ]);

        $this->pageService->updatePage($page, $validated);

        return back()->with('success', 'Page published successfully!');
    }

    public function unpublish(Page $page)
    {
        Gate::authorize('update', $page);

        $this->pageService->unpublishPage($page);

        return back()->with('success', 'Page unpublished successfully!');
    }

    public function destroy(Page $page)
    {
        Gate::authorize('delete', $page);

        $page->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Page deleted successfully!');
    }

    public function versions(Page $page)
    {
        Gate::authorize('view', $page);

        $versions = $page->versions()->with('user', 'theme')->latest()->paginate(20);

        return view('pages.versions', compact('page', 'versions'));
    }


    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->with(['theme', 'user'])
            ->firstOrFail();

        $themes = Theme::all();

        return view('public.page', compact('page', 'themes'));
    }

    public function publish(Page $page)
    {
        Gate::authorize('update', $page);

        $this->pageService->publishPage($page);

        return back()->with('success', 'Page published successfully!');
    }


    public function uploadImage(Request $request, Page $page)
    {
        // Log for debugging
        Log::info('Upload request', [
            'user_id' => auth()->id(),
            'page_user_id' => $page->user_id,
            'expects_json' => $request->expectsJson(),
            'ajax' => $request->ajax()
        ]);

        // Authorization
        try {
            Gate::authorize('update', $page);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return back()->with('error', 'Unauthorized');
        }

        // Validation
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048',
                'type' => 'required|in:logo,hero',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors());
        }

        // Upload
        try {
            $path = $this->pageService->uploadImage($request->file('image'), $request->type);

            // Update page
            if ($request->type === 'logo') {
                $page->update(['logo' => $path]);
            } else {
                $page->update(['hero_image' => $path]);
            }

            Log::info('Upload successful', ['path' => $path]);

            // ALWAYS return JSON for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'url' => $path,
                    'message' => 'Image uploaded successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Image uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('Upload failed', ['error' => $e->getMessage()]);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }
}
