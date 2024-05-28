<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Tenant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    public function index($Tenant = null)
    {
        $tenants = Tenant::query();

        return \view('Multi_Tenancy.View', ['tenant' => Tenant::find($Tenant), 'tenants' => $tenants->get()]);
    }

    public function save(Request $request)
    {

        $rules = [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'required'],
            'description' => ['required', 'min:5'],
            'status' => ['required'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \redirect()->back()->withErrors($validator)->withInput();
        }
        Tenant::updateOrCreate(['id' => $request->id], $request->all());

        return \redirect()->route('viewTenants');
    }

    public function delete(Tenant $tenant)
    {
        $tenant->delete();
        return \redirect()->route('viewTenants');
    }
}
