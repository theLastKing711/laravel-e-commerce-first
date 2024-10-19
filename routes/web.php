<?php

use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Store\AuthController as StoreAuthController;
use App\Http\Controllers\Store\OrderController as StoreOrderController;
use App\Http\Controllers\User\Auth\LoginController as UserLoginController;
use App\Http\Controllers\User\Auth\LogoutController as UserLogoutController;
use App\Http\Controllers\User\Categories\ParentListController;
use App\Http\Controllers\User\Home\HomeController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\Product\FavouriteProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('files')
    ->middleware(['api'])
    ->group(function () {
        Route::get('', [FileController::class, 'index']);
        Route::post('', [FileController::class, 'store']);
    });

Route::prefix('admin')
    ->middleware(['api'])
    ->group(function () {
        $adminRole = RolesEnum::ADMIN->value;

        Route::middleware(['auth:sanctum', "role:{$adminRole}"])
            //auth:sanctum check if user is logged in (middleware('auth')),
            ->group(function () {

                Route::prefix('admin')
                    ->group(function () {
                        Route::get('', [AdminController::class, 'index']);
                        Route::get('{id}', [AdminController::class, 'show']);

                        Route::post('', [AdminController::class, 'store']);

                        Route::delete('{id}', [AdminController::class, 'destroy']);
                        Route::patch('{id}', [AdminController::class, 'update']);

                    });

                Route::prefix('users')
                    ->group(function () {
                        Route::get('', [UserController::class, 'index']);
                        Route::get('{id}', [UserController::class, 'show']);

                        Route::post('', [UserController::class, 'store']);
                        Route::patch('{id}', [UserController::class, 'update']);
                        Route::delete('{id}', [UserController::class, 'destroy']);

                    });

                Route::prefix('drivers')
                    ->group(function () {
                        Route::get('', [DriverController::class, 'index']);
                        Route::get('{id}', [DriverController::class, 'show']);

                        Route::post('', [DriverController::class, 'store']);
                        Route::patch('{id}', [DriverController::class, 'update']);
                        Route::delete('{id}', [DriverController::class, 'destroy']);

                    });

                Route::prefix('products')
                    ->group(function () {

                        Route::get('', [ProductController::class, 'index']);
                        Route::get('getProductsByName', [ProductController::class, 'getProductsByName']);
                        Route::get('{id}', [ProductController::class, 'show']);

                        Route::post('', [ProductController::class, 'store']);

                        Route::patch('{id}', [ProductController::class, 'update']);

                        Route::patch('{id}/activate', [ProductController::class, 'activate']);

                        Route::patch('{id}/deActivate', [ProductController::class, 'deActivate']);

                    });

                Route::prefix('orders')->group(function () {
                    Route::get('', [OrderController::class, 'index']);
                    Route::get('{id}', [OrderController::class, 'show']);
                    //

                    Route::patch('{id}/changeStatus', [OrderController::class, 'changeStatus']);

                });

                Route::prefix('categories')->group(function () {
                    Route::get('parentList', [CategoryController::class, 'getParentCategoriesList']);
                    Route::get('list', [CategoryController::class, 'list']);
                    Route::get('', [CategoryController::class, 'index']);
                    Route::get('getSubCategories/{id}', [CategoryController::class, 'getSubCategories']);
                    Route::get('GetSubCategoriesByParents', [CategoryController::class, 'GetSubCategoriesByParents']);
                    Route::get('{id}', [CategoryController::class, 'show']);

                    Route::post('', [CategoryController::class, 'store']);

                    Route::patch('{id}', [CategoryController::class, 'update']);

                    Route::delete('{id}', [CategoryController::class, 'destroy']);

                });

                Route::prefix('coupons')->group(function () {
                    Route::get('', [CouponController::class, 'index']);
                    Route::get('{id}', [CouponController::class, 'show']);

                    Route::post('', [CouponController::class, 'store']);
                    Route::patch('{id}', [CouponController::class, 'update']);
                    Route::delete('{id}', [CouponController::class, 'destroy']);

                });

                Route::prefix('groups')->group(function () {
                    Route::get('', [GroupController::class, 'index']);
                    Route::get('{id}', [GroupController::class, 'show']);

                    Route::post('', [GroupController::class, 'store']);
                    Route::patch('{id}', [GroupController::class, 'update']);
                    Route::delete('{id}', [GroupController::class, 'destroy']);

                });

                Route::prefix('notifications')->group(function () {
                    Route::get('', [NotificationController::class, 'index']);
                    Route::get('{id}', [NotificationController::class, 'show']);

                    Route::post('', [NotificationController::class, 'store']);
                    Route::patch('{id}', [NotificationController::class, 'update']);
                    Route::delete('{id}', [NotificationController::class, 'destroy']);

                });

                Route::prefix('stats')
                    ->group(function () {
                        Route::get('', [StatsController::class, 'getBestSellingProducts']);

                    });

            });

        Route::prefix('auth')->group(function () {
            Route::post('login', [AuthController::class, 'login']);
            Route::post('logout', [AuthController::class, 'logout']);
        });

    });

Route::prefix('store')
    ->middleware(['api'])
    ->group(function () {

        Route::prefix('auth')->group(function () {
            Route::post('login', [StoreAuthController::class, 'login']);
            Route::post('logout', [StoreAuthController::class, 'logout']);
        });

        $storeRole = RolesEnum::STORE->value;
        Route::middleware(['auth:sanctum', "role:{$storeRole}"])
            ->group(function () {

                Route::prefix('orders')->group(function () {
                    Route::patch('{id}/accept', [StoreOrderController::class, 'accept']);
                    Route::patch('{id}/reject', [StoreOrderController::class, 'reject']);
                });
            });
    });

Route::prefix('user')
    ->middleware(['api'])
    ->group(function () {

        Route::prefix('auth')->group(function () {
            Route::post('login', UserLoginController::class);
            Route::post('logout', UserLogoutController::class);
        });

        $userRole = RolesEnum::USER->value;

        //sanctum middleware
        //allows Auth::user->id to return value for user and id in controller
        Route::middleware(['auth:sanctum', "role:{$userRole}"])
        // Route::middleware([])
            ->group(function () {

                Route::prefix('home')->group(function () {
                    Route::get('', HomeController::class);
                });

                Route::prefix('categories')->group(function () {
                    Route::get('parent-list', ParentListController::class);
                });

                Route::prefix('products')->group(function () {
                    Route::post('favourite/{id}', FavouriteProductController::class);
                });

                Route::prefix('orders')->group(function () {
                    Route::get('', [UserOrderController::class, 'index']);
                    Route::get('{order}', [UserOrderController::class, 'show'])
                        ->can('show', 'order');

                    Route::post('', [UserOrderController::class, 'store']);
                });
            });
    });
