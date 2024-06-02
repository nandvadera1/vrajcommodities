<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class UserController extends Controller
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
            'Mobile',
            'Device Id',
            'Status',
            'Subcription Start',
            'Subcription End',
            ['label' => 'Edit', 'no-export' => true, 'width' => 5],
            ['label' => 'Delete', 'no-export' => true, 'width' => 5],
        ];

        $config = [
            'responsive' => true,
            'processing' => true,
            'serverSide' => true,
            'ajax' => [
                'url' => url('users/dataTable'),
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
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'device_id', 'name' => 'device_id'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'subcription_start', 'name' => 'subcription_start'],
                ['data' => 'subcription_end', 'name' => 'subcription_end'],
                ['data' => 'edit', 'name' => 'edit', 'orderable' => false, 'searchable' => false],
                ['data' => 'delete', 'name' => 'delete', 'orderable' => false, 'searchable' => false],
            ]
        ];
        return view('Admin.User.index', compact('heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.User.create');
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
            'name' => 'required|string|max:255',
            'mobile' => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'device_id' => [
                'nullable',
                'string',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'status' => 'required|in:Active,Inactive',
            'subcription_start' => 'nullable|date',
            'subcription_end' => 'required|date|after:subcription_start'
        ]);

        User::create([
            'role_id' => 2,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'device_id' => $request->device_id,
            'status' => $request->status,
            'subcription_start' => $request->subcription_start,
            'subcription_end' => $request->subcription_end
        ]);

        return redirect('/users')->with('Success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        return view('Admin.User.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|max:255',
            'mobile' => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at')
            ],
            'device_id' => [
                'nullable',
                'string',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at')
            ],
            'status' => 'required|in:Active,Inactive',
            'subcription_start' => 'nullable|date',
            'subcription_end' => 'required|date|after:subcription_start'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'device_id' => $request->device_id,
            'status' => $request->status,
            'subcription_start' => $request->subcription_start,
            'subcription_end' => $request->subcription_end
        ]);

        return redirect('/users')->with('Success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        User::destroy($request->id);
        return redirect('/users')->with('success', 'User deleted successfully.');
    }

    public function getDataTable()
    {
        $users = User::where('role_id', 2)->get();

        return DataTables::of($users)
            ->addColumn('edit', function ($user) {
                return '<a href="/users/' . $user->id . '/edit" class="btn btn-secondary btn-sm">Edit</a>';
                return '';
            })
            ->addColumn('delete', function ($user) {
                return '<button type="button" class="delete btn btn-sm btn-danger" data-delete-id="' . $user->id . '" data-token="' . csrf_token() . '" >Delete</button>';
            })
            ->rawColumns(['edit', 'delete'])
            ->make(true);
    }
}
