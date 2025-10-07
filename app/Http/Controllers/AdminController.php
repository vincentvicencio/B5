<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $admins = Admin::query()
    ->when($search, function ($query, $search) {
        return $query->where('username', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
    })
    ->orderBy('id', 'asc') 
    ->paginate(5)
    ->appends(['search' => $search]);


        $mockUpdatedBy = 'Admin';

        return view('admin.index', compact('admins', 'mockUpdatedBy'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|string|max:255|unique:admins,username',
                'employee_code' => 'required|string|max:255|unique:admins,employee_code',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins,email',
                'password' => 'required|string|min:8',
            ]);

            Admin::create([
                'username' => $validatedData['username'],
                'employee_code' => $validatedData['employee_code'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'updated_by' => auth()->user()->name ?? 'System',
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Admin user created successfully.'
                ]);
            }

            return redirect()->route('admin.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    public function edit(Admin $admin)
    {
        return response()->json($admin);
    }

    public function update(Request $request, Admin $admin)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
                'employee_code' => 'required|string|max:255|unique:admins,employee_code,' . $admin->id,
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
                'password' => 'nullable|string|min:8',
            ]);

            $admin->username = $validatedData['username'];
            $admin->employee_code = $validatedData['employee_code'];
            $admin->first_name = $validatedData['first_name'];
            $admin->last_name = $validatedData['last_name'];
            $admin->email = $validatedData['email'];

            if (!empty($validatedData['password'])) {
                $admin->password = Hash::make($validatedData['password']);
            }

            $admin->updated_by = auth()->user()->name ?? 'System';
            $admin->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Admin user updated successfully.',
                    'admin' => $admin->fresh() // Return fresh data
                ]);
            }

            return redirect()->route('admin.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Admin user deleted successfully.'
            ]);
        }

        return redirect()->route('admin.index');
    }
}