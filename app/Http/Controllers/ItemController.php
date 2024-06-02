<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        return view('Admin.Item.index', [
            'items' => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        return view('Admin.Item.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|numeric|exists:categories,id',
            'type' => 'required|in:Image,Pdf,Excel',
            'message' => 'nullable|string',
            'pdf' => 'nullable|file|mimes:pdf',
            'excel' => 'nullable|file|mimes:xls,xlsx',
            'image' => 'nullable|file|mimes:jpeg,png,jpg',
            'status' => 'required|in:Active,Inactive'
        ]);

        $pdfDirectory = 'pdf';
        $excelDirectory = 'excel';
        $imageDirectory = 'image';

        $imageFilename = '';
        $pdfFilename = '';
        $excelFilename = '';

        if ($request->type == 'Image') {
            if (empty($request->image)) {
                return back()->with('Error', 'Image is required');
            } else {
                $imageFilename = $request->file('image')->getClientOriginalName();
                $request->file('image')->move(public_path($imageDirectory), $imageFilename);

                Item::create([
                    'category_id' => $request->category_id,
                    'type' => $request->type,
                    'message' => $request->message,
                    'image' => $imageFilename,
                    'status' => $request->status
                ]);
            }
        }
        if ($request->type == 'Pdf') {
            if (empty($request->pdf)) {
                return back()->with('Error', 'Pdf is required');
            } else {
                $pdfFilename = $request->file('pdf')->getClientOriginalName();
                $request->file('pdf')->move(public_path($pdfDirectory), $pdfFilename);

                Item::create([
                    'category_id' => $request->category_id,
                    'type' => $request->type,
                    'message' => $request->message,
                    'pdf' => $pdfFilename,
                    'status' => $request->status
                ]);
            }
        }
        if ($request->type == 'Excel') {
            if (empty($request->excel)) {
                return back()->with('Error', 'Excel is required');
            } else {
                $excelFilename = $request->file('excel')->getClientOriginalName();
                $request->file('excel')->move(public_path($excelDirectory), $excelFilename);

                Item::create([
                    'category_id' => $request->category_id,
                    'type' => $request->type,
                    'message' => $request->message,
                    'excel' => $excelFilename,
                    'status' => $request->status
                ]);
            }
        }

        return redirect('/item')->with('Success', 'Item created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Item $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Item $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $item = Item::find($request->id);

        // Check and delete the image file if it exists
        if (!empty($item->image)) {
            $imagePath = public_path('image/' . $item->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Check and delete the pdf file if it exists
        if (!empty($item->pdf)) {
            $pdfPath = public_path('pdf/' . $item->pdf);
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        }

        // Check and delete the excel file if it exists
        if (!empty($item->excel)) {
            $excelPath = public_path('excel/' . $item->excel);
            if (file_exists($excelPath)) {
                unlink($excelPath);
            }
        }

        $item->delete();

        return redirect('/item')->with('success', 'Item deleted successfully.');
    }

    public function deleteItems()
    {
        $dateThreshold = Carbon::now()->subDays(30);

        $items = Item::where('created_at', '<', $dateThreshold)->get();

        if (!empty($items) && count($items) > 0) {
            foreach ($items as $item) {
                // Check and delete the image file if it exists
                if (!empty($item->image)) {
                    $imagePath = public_path('image/' . $item->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // Check and delete the pdf file if it exists
                if (!empty($item->pdf)) {
                    $pdfPath = public_path('pdf/' . $item->pdf);
                    if (file_exists($pdfPath)) {
                        unlink($pdfPath);
                    }
                }

                // Check and delete the excel file if it exists
                if (!empty($item->excel)) {
                    $excelPath = public_path('excel/' . $item->excel);
                    if (file_exists($excelPath)) {
                        unlink($excelPath);
                    }
                }

                // Delete the item from the database
                $item->delete();
            }
        }

        return redirect('/home')->with('Success', 'Items older than 30 days have been deleted successfully.');
    }
}
