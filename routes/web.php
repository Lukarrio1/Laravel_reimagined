<?php

use App\Models\Node\Node;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Node\NodeController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Node\NodeTypeController;
use App\Http\Controllers\Permission\PermissionController;

Route::get('/nodes', [NodeController::class,'index'])->name('viewNodes');
Route::post('/node',[NodeController::class,'save'])->name('saveNode');
Route::get('/node/{node}',[NodeController::class,'node'])->name('viewNode');
Route::delete('/node/delete/{node}',[NodeController::class,'delete'])->name('deleteNode');

Route::get('/roles',[RoleController::class,'index'])->name('viewRoles');
Route::get('/role/{role}',[RoleController::class,'edit'])->name('editRole');
Route::post('/role',[RoleController::class,'save'])->name('saveRole');
Route::delete('/role/{role}',[RoleController::class,'delete'])->name('deleteRole');
// Route::get('/node/types', [NodeTypeController::class,'index']);



Route::get('/permissions', [PermissionController::class, 'index'])->name('viewPermissions');
Route::get('/permission/{permission}', [PermissionController::class, 'edit'])->name('editPermission');
Route::post('/permission', [PermissionController::class, 'save'])->name('savePermission');
Route::delete('/permission/{permission}', [PermissionController::class, 'delete'])->name('deletePermission');
