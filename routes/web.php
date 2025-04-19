<?php

use App\Http\Controllers\accounts\{
    AccChekerController,
    AccCKEditorController,
    AccMakerController,
    AccPostAnalyticsCheckerController,
    AccPostAnalyticsMakerController,
    AccUploaderController
};
use App\Http\Controllers\{
    AccountsController,
    LoginController,
    VisitorController,
    VisitorLocationController
};
use App\Http\Controllers\Admin\OurTeamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\MakerController;
use App\Http\Controllers\WhatsappApi\WebHook;
use App\Livewire\AutoContent\PostListing;
use App\Livewire\Marketing\HitBox;
use App\Livewire\Marketing\SubscriberList;

Route::get('/', [LoginController::class, 'loginOption'])->name('loginOption');
Route::group(['prefix' => 'accounts'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', [LoginController::class, 'index'])->name('accounts.login');
        Route::post('authenticate', [LoginController::class, 'authenticate'])->name('accounts.authenticate');
    });
    Route::group(['middleware' => 'auth'], function () {
        Route::get('logout', [LoginController::class, 'logout'])->name('accounts.logout');
        Route::get('dashboard', [AccountsController::class, 'index'])->name('accounts.dashboard');

        Route::group(['middleware' => ['role:2|13|14']], function () {
            Route::get('/maker/dashboard', [AccMakerController::class, 'index'])->name('accounts.maker-dashboard');

            Route::get('/maker/acc-maker-register', [AccMakerController::class, 'accMakerRegister'])->name('accounts.acc-maker-register');

            Route::post('/maker/acc-maker-store', [AccMakerController::class, 'accMakerStore'])->name('accounts.acc-maker-store');

            Route::get('/maker/acc-maker-edit/{id}', [AccMakerController::class, 'accMakerEdit'])->name('accounts.acc-maker-edit');

            Route::get('/maker/acc-maker-delete/{id}', [AccMakerController::class, 'accMakerDelete'])->name('accounts.acc-maker-delete');

            Route::put('/maker/acc-maker-update/{id}', [AccMakerController::class, 'accMakerUpdate'])->name('accounts.acc-maker-update');

            Route::post('/maker/acc-maker-update-title', [AccMakerController::class, 'updateTitle'])->name('accupdate.title');

            Route::get('/maker/acc-chitti-rejected-from-checker-listing', [AccMakerController::class, 'accChittiListReturnFromCheckerL'])->name('accounts.acc-post-return-from-checker-listing');
            //this method is use for account maker listing end

            //this method is use for account uploader listing start
            Route::post('/maker/maker-update-title', [MakerController::class, 'updateTitle'])->name('update.title');
        });
        //upload image using accounts ck-editor start
        Route::post('/accounts/acc-ckeditor-upload', [AccCKEditorController::class, 'accUpload'])->name('accounts.acc-ckeditor-upload');

        Route::group(['middleware' => ['role:3|14']], function () {
            Route::get('/checker/dashboard', [AccChekerController::class, 'accIndexMain'])->name('accounts.checker-dashboard');

            Route::get('/checker/acc-checker-edit/{id}', [AccChekerController::class, 'accCheckerEdit'])->name('accounts.acc-checker-edit');

            Route::put('/checker/acc-checker-update/{id}', [AccChekerController::class, 'accCheckerUpdate'])->name('accounts.acc-checker-update');

            Route::get('/checker/acc-checker-chitti-return-to-maker-region/{id}', [AccChekerController::class, 'accCheckerChittiReturnMakerRegion'])->name('accounts.acc-checker-chitti-return-to-maker-region');

            Route::put('/checker/acc-chitti-checker-sendtomaker/sendtomaker/{id}', [AccChekerController::class, 'accCheckerChittiSendToMaker'])->name('accounts.acc-chitti-checker-sendtomaker');

            Route::get('/checker/chitti-rejected-from-uploader-listing', [AccChekerController::class, 'accChittiListReturnFromUploaderL'])->name('accounts.acc-post-return-from-uploader-listing');
        });
        Route::group(['middleware' => ['role:4!13|14']], function () {
            Route::get('/uploader/dashboard', [AccUploaderController::class, 'accIndexMain'])->name('accounts.uploader-dashboard');

            Route::get('/uploader/uploader-listing/{id}', [AccUploaderController::class, 'accIndex'])->name('accounts.acc-uploader-listing');

            Route::get('/uploader/acc-uploader-edit/{id}', [AccUploaderController::class, 'accUploaderEdit'])->name('accounts.acc-uploader-edit');

            Route::put('/uploader/aac-uploader-update/{id}', [AccUploaderController::class, 'accUploaderUpdate'])->name('accounts.acc-uploader-update');

            Route::get('/uploader/acc-uploader-chitti-return-to-checker-region/{id}', [AccUploaderController::class, 'accUploaderChittiReturnCheckerRegion'])->name('accounts.acc-uploader-chitti-return-to-checker-region');

            Route::put('/uploader/acc-chitti-uploader-sendtochecker/sendtochecker/{id}', [AccUploaderController::class, 'accUploaderChittiSendToChecker'])->name('accounts.acc-chitti-uploader-sendtouploader');
        });

        Route::group(['middleware' => ['role:6']], function () {
            //this method is use for account post maker analytics listing start
            Route::get('/postanalyticsmaker/acc-post-analytics-maker-city-listing', [AccPostAnalyticsMakerController::class, 'index'])->name('accounts.analyticsmaker-dashboard');

            Route::get('/postanalyticsmaker/acc-post-analytics-maker-listing', [AccPostAnalyticsMakerController::class, 'accPostAnalyticsMakerListing'])->name('accounts.acc-post-analytics-maker-listing');

            Route::get('/postanalyticsmaker/acc-post-analytics-maker-create', [AccPostAnalyticsMakerController::class, 'accPostAnalyticsMakerEdit'])->name('accounts.acc-post-analytics-maker-create');

            Route::put('/postanalyticsmaker/acc-post-analytics-maker/update/{id}', [AccPostAnalyticsMakerController::class, 'accPostAnalyticsMakerUpdate'])->name('accounts.acc-post-analytics-maker-update');

            Route::get('/postanalyticsmaker/acc-post-analytics-from-checker-listing', [AccPostAnalyticsMakerController::class, 'accPostAnalyticsListReturnFromCheckerL'])->name('accounts.acc-post-analytics-from-checker-listing');
        });

        Route::group(['middleware' => ['role:7']], function () {
            //this method is use for account post checker analytics listing start
            Route::get('/postanalyticschecker/acc-post-analytics-checker-city-listing', [AccPostAnalyticsCheckerController::class, 'index'])->name('accounts.analyticschecker-dashboard');

            Route::get('/postanalyticschecker/acc-post-analytics-checker-listing', [AccPostAnalyticsCheckerController::class, 'accPostAnalyticsCheckerListing'])->name('accounts.acc-post-analytics-checker-listing');

            Route::get('/postanalyticschecker/acc-post-analytics-checker-edit', [AccPostAnalyticsCheckerController::class, 'accPostAnalyticsChckerEdit'])->name('accounts.acc-post-analytics-checker-edit');

            Route::put('/postanalyticschecker/acc-post-analytics-checker/update/{id}', [AccPostAnalyticsCheckerController::class, 'accPostAnalyticsCheckerUpdate'])->name('accounts.acc-post-analytics-checker-update');

            Route::get('/postanalyticschecker/acc-post-analytics-checker/approve/{id}', [AccPostAnalyticsCheckerController::class, 'accPostAnalyticsCheckerApprove'])->name('accounts.acc-post-analytics-checker-approve');

            Route::get('/postanalyticschecker/acc-post-analytics-checker-return-region/{id}', [AccPostAnalyticsCheckerController::class, 'accPostAnalyticsCheckerReturnRegion'])->name('accounts.acc-post-analytics-checker-return-region');

            Route::put('/postanalyticschecker/acc-post-analytics-checker-sendtomaker/sendtomaker/{id}', [AccPostAnalyticsCheckerController::class, 'accPostAnalyticsCheckerSendToMaker'])->name('accounts.acc-post-analytics-checker-sendtomaker');
        });

    });

});

require __DIR__.'/admin.php';

Route::get('visitor',[VisitorController::class,'index'])->name('visitor');
Route::get('show-visitor',[VisitorController::class,'showVisitor'])->name('visitor.show');
Route::get('get-our-teams', [OurTeamController::class, 'getAllTeamsJson']);
Route::any('visitor-location',[VisitorLocationController::class,'storeVisitorLocation']);
Route::get('marketing-hit-box',HitBox::class)->name('marketing.hit-box');
Route::get('subscribers',SubscriberList::class)->name('marketing.hit-box')->name('subscribers');
Route::get('content/post-listing',PostListing::class);
Route::get('whatsapp-webhook', [WebHook::class, 'index'])->name('whatsapp-webhook');
// Route::get('sendWhatsAppMessage',[VisitorController::class,'sendWhatsAppMessage'])->name('sendWhatsAppMessage');



