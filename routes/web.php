<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Login;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\LanguageScriptController;
use App\Http\Controllers\admin\CountryController;
use App\Http\Controllers\admin\LiveCityController;
use App\Http\Controllers\admin\SCityController;
use App\Http\Controllers\admin\RegionController;
use App\Http\Controllers\admin\TagCategoryController;
use App\Http\Controllers\admin\TagController;
use App\Http\Controllers\admin\UserCountryController;
use App\Http\Controllers\admin\UserCityController;


Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'accounts'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::get('login', [LoginController::class, 'index'])->name('accounts.login');
        Route::post('authenticate', [LoginController::class, 'authenticate'])->name('accounts.authenticate');
    });

    Route::group(['middleware' => 'auth'], function(){
        Route::get('logout', [LoginController::class, 'logout'])->name('accounts.logout');
        Route::get('dashboard', [AccountsController::class, 'index'])->name('accounts.dashboard');
    });
});

Route::group(['prefix' => 'admin'], function(){
    Route::group(['middleware' => 'admin.guest'], function(){
        Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
        // Route::post('users-store', [AdminController::class, 'userStore'])->name('admin.users-store');
    });

    Route::group(['middleware' => 'admin.auth'], function(){
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

        #this route is use for admin users
            Route::get('user-profile', [AdminController::class, 'userProfile'])->name('admin.user-profile');
            Route::get('user-listing', [AdminController::class, 'userListing'])->name('admin.user-listing');
            Route::get('user-register', [AdminController::class, 'useruRegister'])->name('admin.user-register');
            Route::post('users-store', [AdminController::class, 'userStore'])->name('admin.users-store');
            Route::post('users-delete/{id}', [AdminController::class, 'userDelete'])->name('admin.users-delete');
            Route::get('user-edit/{id}', [AdminController::class, 'userEdit'])->name('admin.user-edit');
            Route::put('user-update/{id}', [AdminController::class, 'userUpdate'])->name('admin.user-update');
        #this route is use for admin users end

        #this route is use for Role
            Route::get('/role/role-listing', [RoleController::class, 'index'])->name('admin.role-listing');
            Route::get('/role/role-register', [RoleController::class, 'roleRegister'])->name('admin.role-register');
            Route::post('/role/role-store', [RoleController::class, 'roleStore'])->name('admin.role-store');
            Route::post('/role/role-delete/{id}', [RoleController::class, 'roleDelete'])->name('admin.role-delete');
            Route::get('/role/role-edit/{id}', [RoleController::class, 'roleEdit'])->name('admin.role-edit');
            Route::put('/role-update/{id}', [RoleController::class, 'roleUpdate'])->name('admin.role-update');
        #this route is use for Role end

        #this route is use for language script
            Route::get('/languagescript/languagescript-listing', [LanguageScriptController::class, 'index'])->name('admin.languagescript-listing');
            Route::get('/languagescript/languagescript-register', [LanguageScriptController::class, 'languagescriptRegister'])->name('admin.languagescript-register');
            Route::post('/languagescript/languagescript-store', [LanguageScriptController::class, 'languagescriptStore'])->name('admin.languagescript-store');
            Route::post('/languagescript/languagescript-delete/{id}', [LanguageScriptController::class, 'languagescriptDelete'])->name('admin.languagescript-delete');
            Route::get('/languagescript/languagescript-edit/{id}', [languagescriptController::class, 'languagescriptEdit'])->name('admin.languagescript-edit');
            Route::put('/languagescript-update/{id}', [languagescriptController::class, 'languagescriptUpdate'])->name('admin.languagescript-update');
        #this route is use for language script end

        #this route is use for country
            Route::get('/country/country-listing', [CountryController::class, 'index'])->name('admin.country-listing');
            Route::get('/country/country-register', [CountryController::class, 'countryRegister'])->name('admin.country-register');
            Route::post('/country/country-store', [countryController::class, 'countryStore'])->name('admin.country-store');
            Route::post('/country/country-delete/{id}', [countryController::class, 'countrytDelete'])->name('admin.country-delete');
            Route::get('/country/country-edit/{id}', [countryController::class, 'countryEdit'])->name('admin.country-edit');
            Route::put('/country-update/{id}', [countryController::class, 'countryUpdate'])->name('admin.country-update');
        #this route is use for country end

        #this route is use for live-city
            Route::get('/livecity/live-city-listing', [LiveCityController::class, 'index'])->name('admin.live-city-listing');
            Route::get('/livecity/live-city-register', [LiveCityController::class, 'liveCityRegister'])->name('admin.live-city-register');
            Route::post('/livecity/live-city-store', [LiveCityController::class, 'liveCityStore'])->name('admin.live-city-store');
            Route::post('/livecity/live-city-delete/{id}', [LiveCityController::class, 'liveCitytDelete'])->name('admin.live-city-delete');
            Route::get('/livecity/live-city-edit/{id}', [LiveCityController::class, 'liveCityEdit'])->name('admin.live-city-edit');
            Route::put('/livecity/live-city-update/{id}', [LiveCityController::class, 'liveCityUpdate'])->name('admin.live-city-update');
        #this route is use for live-city end

        #this route is use for s-city
            Route::get('/scities/scities-listing', [SCityController::class, 'index'])->name('admin.scities-listing');
            Route::get('/scities/scities-register', [SCityController::class, 'SCityRegister'])->name('admin.scities-register');
            Route::post('/scities/scities-store', [SCityController::class, 'SCityStore'])->name('admin.scities-store');
            Route::post('/scities/scities-delete/{id}', [SCityController::class, 'SCityDelete'])->name('admin.scities-delete');
            Route::get('/scities/scities-edit/{id}', [SCityController::class, 'SCityEdit'])->name('admin.scities-edit');
            Route::put('/scities/scities-update/{id}', [SCityController::class, 'SCityUpdate'])->name('admin.scities-update');
        #this route is use for s-city end

        #this route is use for region
            Route::get('/region/region-listing', [RegionController::class, 'index'])->name('admin.region-listing');
            Route::get('/region/region-register', [RegionController::class, 'regionRegister'])->name('admin.region-register');
            Route::post('/region/region-store', [RegionController::class, 'regionStore'])->name('admin.region-store');
            Route::post('/region/region-delete/{id}', [RegionController::class, 'regionDelete'])->name('admin.region-delete');
            Route::get('/region/region-edit/{id}', [RegionController::class, 'regionEdit'])->name('admin.region-edit');
            Route::put('/region/region-update/{id}', [RegionController::class, 'regionUpdate'])->name('admin.region-update');
        #this route is use for region end

        #this route is use for tag category
            Route::get('/tagcategory/tag-category-listing', [TagCategoryController::class, 'index'])->name('admin.tag-category-listing');
            Route::get('/tagcategory/tag-category-register', [TagCategoryController::class, 'tagCategoryRegister'])->name('admin.tag-category-register');
            Route::post('/tagcategory/tag-category-store', [TagCategoryController::class, 'tagCategoryStore'])->name('admin.tag-category-store');
            Route::post('/tagcategory/tag-category-delete/{id}', [TagCategoryController::class, 'tagCategoryDelete'])->name('admin.tag-category-delete');
            Route::get('/tagcategory/tag-category-edit/{id}', [TagCategoryController::class, 'tagCategoryEdit'])->name('admin.tag-category-edit');
            Route::put('/tagcategory/tag-category-update/{id}', [TagCategoryController::class, 'tagCategoryUpdate'])->name('admin.tag-category-update');
        #this route is use for tag category end

        #this route is use for tag
            Route::get('/tag/tag-listing', [TagController::class, 'index'])->name('admin.tag-listing');
            Route::get('/tag/tag-register', [TagController::class, 'tagRegister'])->name('admin.tag-register');
            Route::post('/tag/tag-store', [TagController::class, 'tagStore'])->name('admin.tag-store');
            Route::post('/tag/tag-delete/{id}', [TagController::class, 'tagDelete'])->name('admin.tag-delete');
            Route::get('/tag/tag-edit/{id}', [TagController::class, 'tagEdit'])->name('admin.tag-edit');
            Route::put('/tag/tag-update/{id}', [TagController::class, 'tagUpdate'])->name('admin.tag-update');
        #this route is use for tag end

        #this route is use for User Country
            Route::get('/usercountry/user-country-listing', [UserCountryController::class, 'index'])->name('admin.user-country-listing');
            Route::get('/usercountry/user-country-register', [UserCountryController::class, 'userCountryRegister'])->name('admin.user-country-register');
            Route::post('/usercountry/user-country-store', [UserCountryController::class, 'userCountryStore'])->name('admin.user-country-store');
            Route::post('/usercountry/user-country-delete/{id}', [UserCountryController::class, 'userCountryDelete'])->name('admin.user-country-delete');
            Route::get('/usercountry/user-country-edit/{id}', [UserCountryController::class, 'userCountryEdit'])->name('admin.user-country-edit');
            Route::put('/usercountry/user-country-update/{id}', [UserCountryController::class, 'userCountryUpdate'])->name('admin.user-country-update');
        #this route is use for User Country end

        #this route is use for user city
            Route::get('/usercity/user-city-listing', [UserCityController::class, 'index'])->name('admin.user-city-listing');
            Route::get('/usercity/user-city-register', [UserCityController::class, 'userCityRegister'])->name('admin.user-city-register');
            Route::post('/usercity/user-city-store', [UserCityController::class, 'userCityStore'])->name('admin.user-city-store');
            Route::post('/usercity/user-city-delete/{id}', [UserCityController::class, 'userCityDelete'])->name('admin.user-city-delete');
            Route::get('/usercity/user-city-edit/{id}', [UserCityController::class, 'userCityEdit'])->name('admin.user-city-edit');
            Route::put('/usercity/user-city-update/{id}', [UserCityController::class, 'userCityUpdate'])->name('admin.user-city-update');
        #this route is use for user city end
    });
});






