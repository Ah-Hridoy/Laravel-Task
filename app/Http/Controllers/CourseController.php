<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class CourseController extends Controller
{

    public function showDefault()
    {
        $course = Course::with('modules.contents')->latest()->firstOrFail();

        return view('show', compact('course'));
    }



    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'feature_video' => 'nullable|file|mimes:mp4,mov,webm,avi',

            'modules' => 'nullable|array',
            'modules.*.title' => 'required_with:modules|string|max:255',
            'modules.*.description' => 'nullable|string',
            'modules.*.contents' => 'nullable|array',
            'modules.*.contents.*.title' => 'required_with:modules.*.contents|string|max:255',
            'modules.*.contents.*.type' => 'required_with:modules.*.contents|string',
            'modules.*.contents.*.body' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $courseData = [
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'] ?? null,
            ];

            // handle feature video
            if ($request->hasFile('feature_video')) {
                $path = $request->file('feature_video')->store('videos', 'public');
                $courseData['feature_video_path'] = $path;
            }

            $course = Course::create($courseData);

            // modules & contents
            $modules = $request->input('modules', []);
            foreach ($modules as $mIndex => $m) {
                $module = $course->modules()->create([
                    'title' => $m['title'] ?? 'Untitled Module',
                    'description' => $m['description'] ?? null,
                    'position' => $mIndex,
                ]);

                $contents = $m['contents'] ?? [];
                foreach ($contents as $cIndex => $c) {
                    $contentData = [
                        'title' => $c['title'] ?? 'Untitled',
                        'type' => $c['type'] ?? null,
                        'body' => $c['body'] ?? null,
                        'position' => $cIndex,
                    ];

                    // If the content is a file, look for uploaded file under either 'file' or 'body' key for that indexed input
                    if (($contentData['type'] ?? null) === 'file') {
                        $fileInput1 = "modules.$mIndex.contents.$cIndex.file";
                        $fileInput2 = "modules.$mIndex.contents.$cIndex.body";

                        if ($request->hasFile($fileInput1)) {
                            $contentPath = $request->file($fileInput1)->store('contents', 'public');
                            $contentData['body'] = $contentPath;
                        } elseif ($request->hasFile($fileInput2)) {
                            $contentPath = $request->file($fileInput2)->store('contents', 'public');
                            $contentData['body'] = $contentPath;
                        } else {
                            // no uploaded file found; keep provided body if any (could be null)
                            $contentData['body'] = $c['body'] ?? null;
                        }
                    }

                    $module->contents()->create($contentData);
                }
            }

            DB::commit();

            flash()->success('Course Create Successfully');
            return redirect()->route('index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
