<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use Illuminate\Http\Request;

class HighlightController extends Controller
{
    //

    public function highlightview(Request $request) {
        $latest = $request->has('latest');
        
        if ($latest) {
            $videos = Highlight::orderBy('created_at', 'desc')->get();
        } else {
            $videos = Highlight::all();
        }
    
        return view('highlight.highlight', compact('videos'));
    }

    
    
    public function highlightcrview() {
        return view('highlight.create');
    }


    public function store(Request $request)
    {
        // ویڈیو اور عنوان کے لیے ویلیڈیشن قواعد
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi,flv|max:20480',
            'title' => 'required|string|max:255',
        ]);
    
        // چیک کریں کہ آیا ویڈیو فائل موجود ہے
        if ($request->hasFile('video')) {
            $video = $request->file('video');
    
            // ویڈیو کا منفرد نام بنائیں
            $videoName = time() . '.' . $video->getClientOriginalExtension();
    
            // ویڈیو کو 'public/admin/assets/videos/highlights' ڈائریکٹری میں منتقل کریں
            $destinationPath = public_path('admin/assets/videos/highlights');
            $video->move($destinationPath, $videoName);
    
            // ڈیٹا بیس میں ویڈیو کا راستہ اور عنوان محفوظ کریں
            $highlight = Highlight::create([
                'video' => 'admin/assets/videos/highlights/' . $videoName,
                'title' => $request->title,
            ]);
    
            // کامیابی کا پیغام واپس کریں
            return response()->json([
                'success' => 'ویڈیو کامیابی سے اپلوڈ ہو گئی۔',
                'video' => $highlight
            ]);
        }
    
        // اگر ویڈیو اپلوڈ ناکام ہو جائے تو ایرر پیغام واپس کریں
        return response()->json(['error' => 'ویڈیو اپلوڈ کرنے میں ناکامی ہوئی۔'], 400);
    }
    

    public function index(Request $request)
    {
        $latest = $request->has('latest');

        if ($latest) {
            $videos = Highlight::orderBy('created_at', 'desc')->get();
        } else {
            $videos = Highlight::all();
        }

        return view('common.hightlight', compact('videos'));

    }

    public function delete($id)
    {
        // دی گئی ID کے مطابق Highlight ماڈل کی تلاش
        $highlight = Highlight::find($id);
    
        // چیک کریں کہ آیا ماڈل موجود ہے
        if (!$highlight) {
            return response()->json(['error' => 'Something Went Wrong.Please try again'], 404);
        }
    
        // ویڈیو کو حذف کریں
        $highlight->delete();
    
        // کامیابی کا پیغام واپس کریں
        return response()->json(['success' => 'Video is deleted Successfully Uploaded.']);
    }


    public function Updateview($id) {
        $highlight = Highlight::find($id);
        return view('highlight.update', compact('highlight'));
    }
    


    public function Update(Request $request, $id)
{
    // Record find karo
    $highlight = Highlight::find($id);

    if (!$highlight) {
        return response()->json([
            'error' => 'Highlight not found.'
        ], 404);
    }

    // Embed URL aur title ke liye validation rules
    $request->validate([
        'video' => 'required|url', // URL validate karega
        'title' => 'required|string|max:255',
    ]);

    // Directly embed URL aur title database mein store karein via instance update
    $highlight->update([
        'video' => $request->video, // Yahan embed URL aayega
        'title' => $request->title,
    ]);

    // Success ka message return karein
    return response()->json([
        'success' => 'Video embed URL successfully saved.',
        'video'   => $highlight
    ]);
}


    //search 
    public function searchIndex(Request $request)
{
    $query = Highlight::query();

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('title', 'LIKE', "%{$search}%");
    }

    $videos = $query->paginate(10);

    return view('common.highlightindex', compact('videos'));
}

    

}
