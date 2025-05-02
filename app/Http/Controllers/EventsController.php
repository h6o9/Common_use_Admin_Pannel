<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventsController extends Controller
{
    public function eventsView() {
        // Sabhi events ko unki images ke sath load karein
        $events = Event::with('eventImages')->get();
        return view('events.events', compact('events'));
    }

    
    public function createEvent() {
        // $events = Event::all();
        return view('events.create');
    }

    public function UpdateView($id)
    {
        $find = Event::with(['eventImages' => function($query) {
            $query->where('is_cover', 1);
        }])->find($id);
         
        // return $find;
    
        return view('events.update', compact('find'));
    }
    

    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i', // validate start time (hours:minutes format)
            'location' => 'required|string|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        // Create event
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time, // store start_time
            'location' => $request->location,
        ]);
    
        // Handle image uploads
        if ($request->hasFile('images')) {
            $coverImageSet = false;
            
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('admin/assets/images/events'), $filename);
        
                // Set first image as cover and store full image path
                EventImage::create([
                    'event_id'   => $event->id,
                    'image_path' => 'admin/assets/images/events/' . $filename,
                    'is_cover'   => !$coverImageSet,
                ]);
            
                if (!$coverImageSet) {
                    $coverImageSet = true;
                }
            }
        
        
        }
    
        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event
        ]);
    }
    

// add more images 

public function addmoreivew($id) {
    $find = Event::find($id);
    return view('common.addmoreimages', compact('find'));
}


// slider images details

public function SliderimgView($id) {
   
    $event = Event::with('eventImages')->find($id);

    // return $event;
    
    
    return view('events.sliderimages', compact('event'));
}


// SLIDER image date

public function SlidereditImage(Request $request, $id)
{
    $request->validate([
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    $event = Event::findOrFail($id);
    $existingImagesCount = EventImage::where('event_id', $id)->count();

    //  dd($existingImagesCount);
    $newImages = $request->file('images');
    $newImagesCount = is_array($newImages) ? count($newImages) : ($newImages ? 1 : 0);

    if (($existingImagesCount + $newImagesCount) > 5) {
        return response()->json([
            'error' => 'You can only have up to 5 images. Please delete or update existing images.'
        ], 400);
    }

    if ($newImages) {
        foreach ($newImages as $image) {
            $filename = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path('admin/assets/images/events'), $filename);

            EventImage::create([
                'event_id'   => $event->id,
                'image_path' => 'admin/assets/images/events/' . $filename,
            ]);
        }

        return response()->json(['message' => 'Slider Images added successfully!']);
    }

    return response()->json(['error' => 'No images found!'], 400);
}


//add more saving

public function addMoreImages(Request $request, $id)
{
    // Validate that each file is an image and meets the requirements.
    $request->validate([
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    $event = Event::findOrFail($id);
    
    // Count the current number of images for the event.
    $existingImagesCount = $event->eventImages()->count();
    
    // Get the number of new images being uploaded.
    $newImages = $request->file('images');
    $newImagesCount = is_array($newImages) ? count($newImages) : 0;

    // Check if adding the new images would exceed the limit of 4 images.
    if (($existingImagesCount + $newImagesCount) > 5) {
        return response()->json([
            'error' => 'You can only add more 4 images. If you want to add more images, please update the existing images using the update button.'
        ], 400);
    }

    if ($newImages) {
        foreach ($newImages as $image) {
            $filename = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path('admin/assets/images/events'), $filename);

            EventImage::create([
                'event_id'   => $event->id,
                'image_path' => 'admin/assets/images/events/' . $filename,
            ]);
        }

        return response()->json(['message' => 'Images added successfully!']);
    }

    return response()->json(['error' => 'No images found!'], 400);
}





public function Update(Request $request, $id)  
{
    $find = Event::find($id);
    
    if (!$find) {
        return redirect()->back()->with('error', 'Event not found!');
    }

    // Validation
    $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'date'        => 'required|date',
        'location'    => 'required|string|max:255',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'start_time' => 'required|date_format:H:i', 
    ]);

    // Agar naye image upload ki gayi hai
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/assets/images/events'), $filename);

        // Purani image delete karein (optional)
        if ($find->images && file_exists(public_path('admin/assets/images/events/' . $find->images))) {
            unlink(public_path('admin/assets/images/events/' . $find->images));
        }
        
        $find->images = $filename;
    }

    // Database update karein
    $find->update([
        'title'       => $request->title,
        'description' => $request->description ?? '',
        'date'        => $request->date,
        'location'    => $request->location,
        'start_time' => $request->start_time, 
    ]);

    return redirect()->route('event.view')->with('success', 'Record updated successfully!');
}

public function Delete($id) {
    $find = Event::find($id);
    $find->delete();

    if($find) {
        return redirect()->route('event.view')->with('success', 'Record Deleted  successfully!'); 
    }

}

// search 

public function index(Request $request)
{
    $query = Event::query();

    if ($request->has('search') && !empty($request->search)) {
        $query->where('title', 'LIKE', '%' . $request->search . '%');
    }

    $events = $query->get();

    return view('common.index', compact('events'));
}


}
 