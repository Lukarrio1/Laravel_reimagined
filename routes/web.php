<?php

use App\Models\Node\Node;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckSuperAdmin;
use App\Http\Controllers\Node\NodeController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Cache\CacheController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Node\NodeTypeController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Permission\PermissionController;

Auth::routes();

Route::middleware(['auth',CheckSuperAdmin::class])->group(function(){

Route::get('/nodes', [NodeController::class,'index'])->name('viewNodes');
Route::post('/node', [NodeController::class,'save'])->name('saveNode');
Route::get('/node/{node}', [NodeController::class,'node'])->name('viewNode');
Route::delete('/node/delete/{node}', [NodeController::class,'delete'])->name('deleteNode');

Route::get('/roles', [RoleController::class,'index'])->name('viewRoles');
Route::get('/role/{role}', [RoleController::class,'edit'])->name('editRole');
Route::post('/role', [RoleController::class,'save'])->name('saveRole');
Route::delete('/role/{role}', [RoleController::class,'delete'])->name('deleteRole');
// Route::get('/node/types', [NodeTypeController::class,'index']);



Route::get('/permissions', [PermissionController::class, 'index'])->name('viewPermissions');
Route::get('/permission/{permission}', [PermissionController::class, 'edit'])->name('editPermission');
Route::post('/permission', [PermissionController::class, 'save'])->name('savePermission');
Route::delete('/permission/{permission}', [PermissionController::class, 'delete'])->name('deletePermission');


Route::get('/caches', [CacheController::class,'index'])->name('viewCache');
Route::get('/clear/caches', [CacheController::class, 'clearCache'])->name('clearCache');

Route::post('/update/user/{user}', [ProfileController::class, 'update'])->name('updateUser');
Route::get('/users',[UserController::class,'index'])->name('viewUsers');
Route::post('/assign/role/{user}', [UserController::class, 'assignRole'])->name('assignRole');


Route::get('/settings',[SettingController::class,'index'])->name('viewSettings');
Route::post('/save/setting',[SettingController::class,'save'])->name('saveSetting');




Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});