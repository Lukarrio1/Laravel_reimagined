<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Permission\PermissionSaveRequest;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:can crud permissions');
    }

    public function index($permission = null)
    {
        $permissions_count = Permission::all()->count();
        $max_amount_of_pages = $permissions_count / 6;
        \request()->merge(['page' => \request('page') == null || (int) \request('page') < 1 ? 1 : ((int)\request('page') > $max_amount_of_pages ? \ceil($max_amount_of_pages) : \request('page'))]);
        $permissions = Permission::latest()
            ->skip((int) 6 * (int)  \request('page') - (int) 6)
            ->take((int) 6)->get();
        return view('Permission.View', ['permissions' => $permissions, 'permissions_count' => $permissions_count, 'permission' => $permission]);
    }

    public function save(PermissionSaveRequest $request)
    {
        Permission::updateOrCreate(['id' => $request->id], $request->all() + ['guard' => 'api']);
        Session::flash('message', 'The permission was saved successfully.');
        Session::flash('alert-class', 'alert-success');

        return \redirect()->route('viewPermissions');
    }

    public function edit(Permission $permission)
    {
        return $this->index($permission);
    }

    public function delete(Permission $permission)
    {
        $permission->delete();
        Session::flash('message', 'The permission was deleted successfully.');
        Session::flash('alert-class', 'alert-success');
        return \redirect()->route('viewPermissions');
    }
}
