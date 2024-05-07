<?php

namespace App\Http\Controllers\Cache;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function index()
    {
        return view('Cache.View');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize');
        return \redirect()->back();
    }
}
