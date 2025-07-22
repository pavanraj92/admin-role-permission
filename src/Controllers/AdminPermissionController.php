<?php

namespace admin\admin_role_permissions\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use admin\admin_role_permissions\Requests\Permission\StorePermissionRequest;
use admin\admin_role_permissions\Requests\Permission\UpdatePermissionRequest;
use admin\admin_role_permissions\Models\Permission;
use admin\admin_role_permissions\Models\Role;
use admin\admin_auth\Models\Admin;

class AdminPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:permission_manager_list')->only(['index']);
        $this->middleware('admincan_permission:permission_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:permission_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:permission_manager_view')->only(['show']);
        $this->middleware('admincan_permission:permission_manager_delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search = $request->query('keyword');
            $permissions = Permission::filter($search)
                ->sortable()
                ->latest()
                ->paginate(Admin::getPerPageLimit())
                ->withQueryString();
            return view('admin_role_permissions::admin.permission.index', compact('permissions'));
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Failed to load permissions: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin_role_permissions::admin.permission.createOrEdit');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Failed to open create form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $validateData = $request->validated();
        DB::beginTransaction();
        try {
            $permission =  Permission::create($validateData);
            // Get Super Admin role
            $superAdminRoles = DB::table('roles')->where('name', 'Super Admin')->get();

            if ($superAdminRoles) {
                foreach ($superAdminRoles as $role) {
                    DB::table('permission_role')->updateOrInsert(
                        [
                            'role_id' => $role->id,
                            'permission_id' => $permission->id,
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            DB::commit();
            return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Failed to create permission: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(Permission $permission)
    {
        try {
            return view('admin_role_permissions::admin.permission.show', compact('permission'));
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Failed to open show page: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        try {
            return view('admin_role_permissions::admin.permission.createOrEdit', compact('permission'));
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Failed to open edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $validateData = $request->validated();
        DB::beginTransaction();
        try {
            $permission->fill($validateData);
            $permission->save();
            DB::commit();
            return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Failed to update permission: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        DB::beginTransaction();
        try {
            $permission->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $permission = Permission::findOrFail($request->id);
            $permission->status = $request->status;
            $permission->save();

            // create status html dynamically        
            $dataStatus = $permission->status == '1' ? '0' : '1';
            $label = $permission->status == '1' ? 'Active' : 'InActive';
            $btnClass = $permission->status == '1' ? 'btn-success' : 'btn-warning';
            $tooltip = $permission->status == '1' ? 'Click to change status to inactive' : 'Click to change status to active';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.updateStatus') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $permission->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to ' . $label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }
}
