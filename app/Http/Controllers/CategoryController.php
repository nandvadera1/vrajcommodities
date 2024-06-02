<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $heads = [
            'Name',
            'Status',
            ['label' => 'Edit', 'no-export' => true, 'width' => 5],
            ['label' => 'Delete', 'no-export' => true, 'width' => 5],
        ];

        $config = [
            'responsive' => true,
            'processing' => true,
            'serverSide' => true,
            'ajax' => [
                'url' => url('category/dataTable'),
            ],
            'order' => [
                ['0', 'asc']
            ],
            'lengthMenu' => [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ],
            'columns' => [
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'edit', 'name' => 'edit', 'orderable' => false, 'searchable' => false],
                ['data' => 'delete', 'name' => 'delete', 'orderable' => false, 'searchable' => false],
            ]
        ];
        return view('Admin.Category.index', compact('heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.Category.create');
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
            'name' => [
                'required',
                'string',
                'max:200',
                Rule::unique('categories')->whereNull('deleted_at')
            ],
            'status' => 'required|in:Active,Inactive'
        ]);

        Category::create([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect('/category')->with('Success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);

        return view('Admin.Category.edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:200',
                Rule::unique('categories')->ignore($id)->whereNull('deleted_at')
            ],
            'status' => 'required|in:Active,Inactive'
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect('/category')->with('Success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Category::destroy($request->id);
        return redirect('/category')->with('success', 'Category deleted successfully.');
    }

    public function getDataTable()
    {
        $categories = Category::whereNull('deleted_at')->get();

        return DataTables::of($categories)
            ->addColumn('edit', function ($category) {
                return '<a href="/category/' . $category->id . '/edit" class="btn btn-secondary btn-sm">Edit</a>';
                return '';
            })
            ->addColumn('delete', function ($category) {
                return '<button type="button" class="delete btn btn-sm btn-danger" data-delete-id="' . $category->id . '" data-token="' . csrf_token() . '" >Delete</button>';
            })
            ->rawColumns(['edit', 'delete'])
            ->make(true);
    }
}
