<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\ChekerController;
use App\Http\Controllers\admin\CKEditorController;
use App\Http\Controllers\admin\CountryController;
use App\Http\Controllers\admin\DeletedPostController;
use App\Http\Controllers\admin\LanguageScriptController;
use App\Http\Controllers\admin\LiveCityController;
use App\Http\Controllers\admin\MakerController;
use App\Http\Controllers\admin\MisReportController;
use App\Http\Controllers\Admin\OurTeamController;
use App\Http\Controllers\admin\PortalController;
use App\Http\Controllers\admin\PostAnalyticsCheckerController;
use App\Http\Controllers\admin\PostAnalyticsController;
use App\Http\Controllers\admin\PostAnalyticsMakerController;
use App\Http\Controllers\admin\PostController;
use App\Http\Controllers\admin\RegionController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\SCityController;
use App\Http\Controllers\admin\TagCategoryController;
use App\Http\Controllers\admin\TagController;
use App\Http\Controllers\admin\UploaderController;
use App\Http\Controllers\admin\UserCityController;
use App\Http\Controllers\admin\UserCountryController;
use App\Livewire\Localization\Portal;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

        Route::get('user-profile', [AdminController::class, 'userProfile'])->name('admin.user-profile');
        Route::get('user-listing', [AdminController::class, 'userListing'])->name('admin.user-listing');
        Route::get('user-register', [AdminController::class, 'useruRegister'])->name('admin.user-register');
        Route::post('users-store', [AdminController::class, 'userStore'])->name('admin.users-store');
        Route::post('users-delete/{id}', [AdminController::class, 'userDelete'])->name('admin.users-delete');
        Route::get('user-edit/{id}', [AdminController::class, 'userEdit'])->name('admin.user-edit');
        Route::put('user-update/{id}', [AdminController::class, 'userUpdate'])->name('admin.user-update');

        Route::put('update-user-profile/{id}', [AdminController::class, 'userProfileUpdate'])->name('admin.user-profile-update');

        Route::get('update-user-profile/{id}', [AdminController::class, 'userProfileUpdate'])->name('admin.user-profile-update');

        Route::get('/role/role-listing', [RoleController::class, 'index'])->name('admin.role-listing');
        Route::get('/role/role-register', [RoleController::class, 'roleRegister'])->name('admin.role-register');
        Route::post('/role/role-store', [RoleController::class, 'roleStore'])->name('admin.role-store');
        Route::post('/role/role-delete/{id}', [RoleController::class, 'roleDelete'])->name('admin.role-delete');
        Route::get('/role/role-edit/{id}', [RoleController::class, 'roleEdit'])->name('admin.role-edit');
        Route::put('/role-update/{id}', [RoleController::class, 'roleUpdate'])->name('admin.role-update');

        Route::get('/languagescript/languagescript-listing', [LanguageScriptController::class, 'index'])->name('admin.languagescript-listing');
        Route::get('/languagescript/languagescript-register', [LanguageScriptController::class, 'languagescriptRegister'])->name('admin.languagescript-register');
        Route::post('/languagescript/languagescript-store', [LanguageScriptController::class, 'languagescriptStore'])->name('admin.languagescript-store');
        Route::post('/languagescript/languagescript-delete/{id}', [LanguageScriptController::class, 'languagescriptDelete'])->name('admin.languagescript-delete');
        Route::get('/languagescript/languagescript-edit/{id}', [languagescriptController::class, 'languagescriptEdit'])->name('admin.languagescript-edit');
        Route::put('/languagescript-update/{id}', [languagescriptController::class, 'languagescriptUpdate'])->name('admin.languagescript-update');

        Route::get('/country/country-listing', [CountryController::class, 'index'])->name('admin.country-listing');
        Route::get('/country/country-register', [CountryController::class, 'countryRegister'])->name('admin.country-register');
        Route::post('/country/country-store', [CountryController::class, 'countryStore'])->name('admin.country-store');
        Route::post('/country/country-delete/{id}', [CountryController::class, 'countrytDelete'])->name('admin.country-delete');
        Route::get('/country/country-edit/{id}', [CountryController::class, 'countryEdit'])->name('admin.country-edit');
        Route::put('/country-update/{id}', [CountryController::class, 'countryUpdate'])->name('admin.country-update');

        Route::get('/livecity/live-city-listing', [LiveCityController::class, 'index'])->name('admin.live-city-listing');
        Route::get('/livecity/live-city-register', [LiveCityController::class, 'liveCityRegister'])->name('admin.live-city-register');
        Route::post('/livecity/live-city-store', [LiveCityController::class, 'liveCityStore'])->name('admin.live-city-store');
        Route::post('/livecity/live-city-delete/{id}', [LiveCityController::class, 'liveCitytDelete'])->name('admin.live-city-delete');
        Route::get('/livecity/live-city-edit/{id}', [LiveCityController::class, 'liveCityEdit'])->name('admin.live-city-edit');
        Route::put('/livecity/live-city-update/{id}', [LiveCityController::class, 'liveCityUpdate'])->name('admin.live-city-update');

        Route::get('/scities/scities-listing', [SCityController::class, 'index'])->name('admin.scities-listing');
        Route::get('/scities/scities-register', [SCityController::class, 'SCityRegister'])->name('admin.scities-register');
        Route::post('/scities/scities-store', [SCityController::class, 'SCityStore'])->name('admin.scities-store');
        Route::post('/scities/scities-delete/{id}', [SCityController::class, 'SCityDelete'])->name('admin.scities-delete');
        Route::get('/scities/scities-edit/{id}', [SCityController::class, 'SCityEdit'])->name('admin.scities-edit');
        Route::put('/scities/scities-update/{id}', [SCityController::class, 'SCityUpdate'])->name('admin.scities-update');

        Route::get('/region/region-listing', [RegionController::class, 'index'])->name('admin.region-listing');
        Route::get('/region/region-register', [RegionController::class, 'regionRegister'])->name('admin.region-register');
        Route::post('/region/region-store', [RegionController::class, 'regionStore'])->name('admin.region-store');
        Route::post('/region/region-delete/{id}', [RegionController::class, 'regionDelete'])->name('admin.region-delete');
        Route::get('/region/region-edit/{id}', [RegionController::class, 'regionEdit'])->name('admin.region-edit');
        Route::put('/region/region-update/{id}', [RegionController::class, 'regionUpdate'])->name('admin.region-update');

        Route::get('/tagcategory/tag-category-listing', [TagCategoryController::class, 'index'])->name('admin.tag-category-listing');
        Route::get('/tagcategory/tag-category-register', [TagCategoryController::class, 'tagCategoryRegister'])->name('admin.tag-category-register');
        Route::post('/tagcategory/tag-category-store', [TagCategoryController::class, 'tagCategoryStore'])->name('admin.tag-category-store');
        Route::post('/tagcategory/tag-category-delete/{id}', [TagCategoryController::class, 'tagCategoryDelete'])->name('admin.tag-category-delete');
        Route::get('/tagcategory/tag-category-edit/{id}', [TagCategoryController::class, 'tagCategoryEdit'])->name('admin.tag-category-edit');
        Route::put('/tagcategory/tag-category-update/{id}', [TagCategoryController::class, 'tagCategoryUpdate'])->name('admin.tag-category-update');

        Route::get('/tag/tag-listing', [TagController::class, 'index'])->name('admin.tag-listing');
        Route::get('/tag/tag-register', [TagController::class, 'tagRegister'])->name('admin.tag-register');
        Route::post('/tag/tag-store', [TagController::class, 'tagStore'])->name('admin.tag-store');
        Route::post('/tag/tag-delete/{id}', [TagController::class, 'tagDelete'])->name('admin.tag-delete');
        Route::get('/tag/tag-edit/{id}', [TagController::class, 'tagEdit'])->name('admin.tag-edit');
        Route::put('/tag/tag-update/{id}', [TagController::class, 'tagUpdate'])->name('admin.tag-update');

        Route::get('/usercountry/user-country-listing', [UserCountryController::class, 'index'])->name('admin.user-country-listing');
        Route::get('/usercountry/user-country-register', [UserCountryController::class, 'userCountryRegister'])->name('admin.user-country-register');
        Route::post('/usercountry/user-country-store', [UserCountryController::class, 'userCountryStore'])->name('admin.user-country-store');
        Route::post('/usercountry/user-country-delete/{id}', [UserCountryController::class, 'userCountryDelete'])->name('admin.user-country-delete');
        Route::get('/usercountry/user-country-edit/{id}', [UserCountryController::class, 'userCountryEdit'])->name('admin.user-country-edit');
        Route::put('/usercountry/user-country-update/{id}', [UserCountryController::class, 'userCountryUpdate'])->name('admin.user-country-update');

        Route::get('/usercity/user-city-listing', [UserCityController::class, 'index'])->name('admin.user-city-listing');
        Route::get('/usercity/user-city-register', [UserCityController::class, 'userCityRegister'])->name('admin.user-city-register');
        Route::post('/usercity/user-city-store', [UserCityController::class, 'userCityStore'])->name('admin.user-city-store');
        Route::post('/usercity/user-city-delete/{id}', [UserCityController::class, 'userCityDelete'])->name('admin.user-city-delete');
        Route::get('/usercity/user-city-edit/{id}', [UserCityController::class, 'userCityEdit'])->name('admin.user-city-edit');
        Route::put('/usercity/user-city-update/{id}', [UserCityController::class, 'userCityUpdate'])->name('admin.user-city-update');

        Route::get('/maker/maker-listing', [MakerController::class, 'index'])->name('admin.maker-listing');
        Route::get('/maker/maker-register', [MakerController::class, 'makerRegister'])->name('admin.maker-register');
        Route::post('/maker/maker-store', [MakerController::class, 'makerStore'])->name('admin.maker-store');
        Route::get('/maker/maker-edit/{id}', [MakerController::class, 'makerEdit'])->name('admin.maker-edit');
        Route::put('/maker/maker-update/{id}', [MakerController::class, 'makerUpdate'])->name('admin.maker-update');
        Route::get('/maker/maker-delete/{id}', [MakerController::class, 'makerdelete'])->name('admin.maker-delete');

        Route::get('/checker/checker-listing', [ChekerController::class, 'indexMain'])->name('admin.checker-listing');

        Route::get('/checker/checker-edit/{id}', [ChekerController::class, 'checkerEdit'])->name('admin.checker-edit');
        Route::get('/checker/checker-update/{id}', [ChekerController::class, 'checkerUpdate'])->name('admin.checker-update');

        Route::get('/uploader/uploader-listing', [UploaderController::class, 'indexMain'])->name('admin.uploader-listing');
        // Route::get('/uploader/uploader-listing/{id}', [UploaderController::class, 'index'])->name('admin.uploader-listing');
        Route::get('/uploader/uploader-edit/{id}', [UploaderController::class, 'uploaderEdit'])->name('admin.uploader-edit');
        Route::put('/uploader/uploader-update/{id}', [UploaderController::class, 'uploaderUpdate'])->name('admin.uploader-update');

        Route::get('/uploader/uploader-chitti-return-to-checker-region/{id}', [UploaderController::class, 'uploaderChittiReturnCheckerRegion'])->name('admin.uploader-chitti-return-to-checker-region');

        Route::put('/uploader/chitti-uploader-sendtochecker/sendtochecker/{id}', [UploaderController::class, 'uploaderChittiSendToChecker'])->name('admin.chitti-uploader-sendtouploader');

        Route::get('/post/post-listing', [PostController::class, 'index'])->name('admin.post-listing');
        Route::get('/post/post-edit/{id}', [PostController::class, 'postEdit'])->name('admin.post-edit');
        Route::put('/post/post-update/{id}', [PostController::class, 'postUpdate'])->name('admin.post-update');
        Route::post('/post/post-delete/{id}', [PostController::class, 'postDelete'])->name('admin.post-delete')->middleware('admin.auth');
        Route::post('/admin/send-to-checker/{id}', [PostController::class, 'sendToChecker'])->name('admin.send-to-checker');

        Route::get('/deleted-post/deleted-post-listing', [DeletedPostController::class, 'index'])->name('admin.deleted-post-listing');
        Route::get('/deleted-post-to-checker/{chittiId}', [DeletedPostController::class, 'deletedPostToChecker'])->name('admin.deletepost-to-checker');
        Route::get('/post/post-preview/{id}', [PostController::class, 'postPreview'])->name('post-preview');

        Route::get('/postanalyticsmaker/post-analytics-maker-city-listing', [PostAnalyticsMakerController::class, 'index'])->name('admin.post-analytics-maker-city-listing');
        Route::get('/postanalyticsmaker/post-analytics-maker-listing', [PostAnalyticsMakerController::class, 'postAnalyticsMakerListing'])->name('admin.post-analytics-maker-listing');
        Route::get('/postanalyticsmaker/post-analytics-maker-create', [PostAnalyticsMakerController::class, 'postAnalyticsMakerEdit'])->name('admin.post-analytics-maker-create');
        Route::put('/postanalyticsmaker/post-analytics-maker/update/{id}', [PostAnalyticsMakerController::class, 'postAnalyticsMakerUpdate'])->name('admin.post-analytics-maker-update');

        Route::get('/postanalyticschecker/post-analytics-checker-city-listing', [PostAnalyticsCheckerController::class, 'index'])->name('admin.post-analytics-checker-city-listing');

        Route::get('/postanalyticschecker/post-analytics-checker-listing', [PostAnalyticsCheckerController::class, 'postAnalyticsCheckerListing'])->name('admin.post-analytics-checker-listing');

        Route::get('/postanalyticschecker/post-analytics-checker-edit', [PostAnalyticsCheckerController::class, 'postAnalyticsChckerEdit'])->name('admin.post-analytics-checker-edit');

        Route::put('/postanalyticschecker/post-analytics-checker/update/{id}', [PostAnalyticsCheckerController::class, 'postAnalyticsCheckerUpdate'])->name('admin.post-analytics-checker-update');

        Route::get('/postanalyticschecker/post-analytics-checker/approve/{id}', [PostAnalyticsCheckerController::class, 'postAnalyticsCheckerApprove'])->name('admin.post-analytics-checker-approve');

        Route::get('/postanalyticschecker/post-analytics-checker-return-region/{id}', [PostAnalyticsCheckerController::class, 'postAnalyticsCheckerReturnRegion'])->name('admin.post-analytics-checker-return-region');

        Route::put('/postanalyticschecker/post-analytics-checker-sendtomaker/sendtomaker/{id}', [PostAnalyticsCheckerController::class, 'postAnalyticsCheckerSendToMaker'])->name('admin.post-analytics-checker-sendtomaker');

        Route::get('/postanalyticsmaker/post-analytics-from-checker-listing', [PostAnalyticsMakerController::class, 'postAnalyticsListReturnFromCheckerL'])->name('admin.post-analytics-from-checker-listing');

        Route::get('/postanalytics/post-analytics-listing', [PostAnalyticsController::class, 'index'])->name('admin.post-analytics-listing');

        Route::get('/post-analytics/export', [PostAnalyticsController::class, 'export'])->name('postanalytics.export');

        Route::get('/misreport/mis-report', [MisReportController::class, 'index'])->name('admin.mis-report');
        Route::post('/misreport/mis-report-generate', [MisReportController::class, 'generateMisReport'])->name('admin.mis-report-generate');
        Route::post('/misreport/export', [MisReportController::class, 'export'])->name('admin.generate-mis-report-export');

        Route::post('/admin/ckeditor-upload', [CKEditorController::class, 'upload'])->name('admin.ckeditor-upload');

        Route::get('/checker/checker-chitti-return-to-maker-region/{id}', [ChekerController::class, 'checkerChittiReturnMakerRegion'])->name('admin.checker-chitti-return-to-maker-region');
        Route::put('/checker/chitti-checker-sendtomaker/sendtomaker/{id}', [ChekerController::class, 'checkerChittiSendToMaker'])->name('admin.chitti-checker-sendtomaker');

        Route::get('/maker/chitti-rejected-from-checker-listing', [MakerController::class, 'chittiListReturnFromCheckerL'])->name('admin.post-return-from-checker-listing');

        Route::get('/checker/chitti-rejected-from-uploader-listing', [ChekerController::class, 'chittiListReturnFromUploaderL'])->name('admin.post-return-from-uploader-listing');

        //show the listing of rejected maker end
        Route::post('/maker/maker-update-title', [MakerController::class, 'updateTitle'])->name('update.title');

        Route::resource('portal', PortalController::class);
        Route::get('portal-localization', Portal::class)->name('portal.localization');
        Route::resource('our-team', OurTeamController::class);
    });
});
