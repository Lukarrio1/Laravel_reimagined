<?php

namespace App\Http\Controllers\Cache;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class CacheController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:can clear cache');
    }

    public function index()
    {
        return view('Cache.View');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize');
        Session::flash('message', 'The system cache was refreshed successfully.');
        Session::flash('alert-class', 'alert-success');

        return \redirect()->back();
    }
}
