<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Review;
use App\Http\Controllers\Api\Role;
use App\Http\Controllers\Api\EmployeeUser;
use App\Http\Controllers\Api\ClientUser;
use App\Http\Controllers\Api\AppUser;
use App\Http\Controllers\Api\Address;
use App\Http\Controllers\Api\Permission;
use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\Category;
use App\Http\Controllers\Api\Supplier;
use App\Http\Controllers\Api\Product;
use App\Http\Controllers\Api\Order;

Route::get('role', [Role::class, 'index']);
Route::post('role', [Role::class, 'store']);
Route::get('role/{id}', [Role::class, 'show']);
Route::patch('role/{id}', [Role::class, 'update']);
Route::delete('role/{id}/{newId}', [Role::class, 'destroy']);

Route::get('employee', [EmployeeUser::class, 'index']);
Route::post('employee', [EmployeeUser::class, 'store']);
Route::get('employee/{id}', [EmployeeUser::class, 'show']);
Route::patch('employee/{id}', [EmployeeUser::class, 'update']);
Route::delete('employee/{id}', [EmployeeUser::class, 'destroy']);

Route::get('client', [ClientUser::class, 'index']);
Route::post('client', [ClientUser::class, 'store']);
Route::get('client/{id}', [ClientUser::class, 'show']);
Route::patch('client/{id}', [ClientUser::class, 'update']);
Route::delete('client/{id}', [ClientUser::class, 'destroy']);

Route::get('guest', [AppUser::class, 'index']);
Route::post('guest', [AppUser::class, 'store']);
Route::get('guest/{id}', [AppUser::class, 'show']);
Route::patch('guest/{id}', [AppUser::class, 'update']);
Route::delete('guest/{id}', [AppUser::class, 'destroy']);

Route::get('address', [Address::class, 'index']);
Route::post('address', [Address::class, 'store']);
Route::get('address/{id}', [Address::class, 'show']);
Route::patch('address/{id}', [Address::class, 'update']);
Route::delete('address/{id}', [Address::class, 'destroy']);

Route::get('permission', [Permission::class, 'index']);
Route::get('permission/{id}', [Permission::class, 'show']);

Route::post('login', [Auth::class, 'login']);
Route::post('forgottenPassword', [Auth::class, 'forgottenPassword']);
Route::get('resetPassword/{token}', [Auth::class, 'resetPassword']);

Route::get('category', [Category::class, 'index']);
Route::get('category/options', [Category::class, 'options']);
Route::post('category', [Category::class, 'store']);
Route::get('category/{id}', [Category::class, 'show']);
Route::patch('category/{id}', [Category::class, 'update']);
Route::put('category/{id}', [Category::class, 'update']);
Route::delete('category/{id}', [Category::class, 'destroy']);

Route::get('supplier', [Supplier::class, 'index']);
Route::get('supplier/options', [Supplier::class, 'options']);
Route::post('supplier', [Supplier::class, 'store']);
Route::get('supplier/{id}', [Supplier::class, 'show']);
Route::patch('supplier/{id}', [Supplier::class, 'update']);
Route::put('supplier/{id}', [Supplier::class, 'update']);
Route::delete('supplier/{id}', [Supplier::class, 'destroy']);

Route::get('product', [Product::class, 'index']);
Route::get('product/price-range', [Product::class, 'priceRange']);
Route::post('product/filter', [Product::class, 'filter']);
Route::post('product', [Product::class, 'store']);
Route::get('product/{id}', [Product::class, 'show']);
Route::patch('product/{id}', [Product::class, 'update']);
Route::put('product/{id}', [Product::class, 'update']);
Route::delete('product/{id}', [Product::class, 'destroy']);

Route::get('order', [Order::class, 'index']);
Route::get('order/options', [Order::class, 'options']);
Route::post('order/filter', [Order::class, 'filter']);
Route::post('order', [Order::class, 'store']);
Route::get('order/{id}', [Order::class, 'show']);
Route::put('order/{id}', [Order::class, 'update']);
Route::delete('order/{id}', [Order::class, 'destroy']);

Route::get('review', [Review::class, 'index']);
Route::get('review/{productId}/average-rating', [Review::class, 'averageRating']);
Route::get('review/product/{productId}', [Review::class, 'indexByProduct']);
Route::get('review/{productId}/{email}', [Review::class, 'show']);
Route::post('review', [Review::class, 'store']);
Route::put('review/{productId}/{email}', [Review::class, 'update']);
Route::delete('review/{productId}/{email}', [Review::class, 'destroy']);