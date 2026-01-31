<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Role;
use App\Http\Controllers\Api\EmployeeUser;
use App\Http\Controllers\Api\ClientUser;
use App\Http\Controllers\Api\AppUser;
use App\Http\Controllers\Api\Address;
use App\Http\Controllers\Api\Permission;

Route::get('role', [Role::class, 'index']);
Route::post('role', [Role::class, 'store']);
Route::get('role/{id}', [Role::class, 'show']);
Route::put('role/{id}', [Role::class, 'update']);
Route::delete('role/{id}', [Role::class, 'destroy']);

Route::get('employee', [EmployeeUser::class, 'index']);
Route::post('employee', [EmployeeUser::class, 'store']);
Route::get('employee/{id}', [EmployeeUser::class, 'show']);
Route::put('employee/{id}', [EmployeeUser::class, 'update']);
Route::delete('employee/{id}', [EmployeeUser::class, 'destroy']);

Route::get('client', [ClientUser::class, 'index']);
Route::post('client', [ClientUser::class, 'store']);
Route::get('client/{id}', [ClientUser::class, 'show']);
Route::put('client/{id}', [ClientUser::class, 'update']);
Route::delete('client/{id}', [ClientUser::class, 'destroy']);

Route::get('guest', [AppUser::class, 'index']);
Route::post('guest', [AppUser::class, 'store']);
Route::get('guest/{id}', [AppUser::class, 'show']);
Route::put('guest/{id}', [AppUser::class, 'update']);
Route::delete('guest/{id}', [AppUser::class, 'destroy']);

Route::get('address', [Address::class, 'index']);
Route::post('address', [Address::class, 'store']);
Route::get('address/{id}', [Address::class, 'show']);
Route::put('address/{id}', [Address::class, 'update']);
Route::delete('address/{id}', [Address::class, 'destroy']);

Route::get('permission', [Permission::class, 'index']);
Route::get('permission/{id}', [Permission::class, 'show']);