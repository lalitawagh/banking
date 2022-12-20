<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => [ColorModeMiddleware::class]], function () {

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard'], function () {
        Route::name('cards.activate')->post('cards/{card}/activate', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'activate']);
        Route::name('cards.approve')->post('cards/{card}/approve', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'approve']);
        Route::name('cards.close')->post('cards/{card}/close', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'close']);
        Route::name('cards.finalize-card')->post('cards/wizard/finalize', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'finalizeCard']);
        Route::name('cards.show-finalize-card')->get('cards/wizard/finalize', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'showFinalizeCard']);
        Route::name('cards.store-card-detail')->post('cards/wizard/detail', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'storeCardDetail']);
        Route::name('cards.show-card-detail')->get('cards/wizard/detail', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'showCardDetail']);
        Route::name('cards.store-card-address')->post('cards/wizard/address', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'storeCardAddress']);
        Route::name('cards.show-card-address')->get('cards/wizard/address', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'showCardAddress']);
        Route::name('cards.store-card-mode')->post('cards/wizard/mode', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'storeCardMode']);
        Route::name('cards.show-card-mode')->get('cards/wizard/mode', [Kanexy\PartnerFoundation\Banking\Controllers\CardController::class, 'showCardMode']);
        Route::resource('cards', Kanexy\PartnerFoundation\Banking\Controllers\CardController::class)->only(['index', 'create', 'store', 'show']);

        Route::group(['prefix' => 'banking', 'as' => 'banking.'], function () {
            Route::resource('beneficiaries', 'Kanexy\PartnerFoundation\Banking\Controllers\BeneficiaryController')->except(['show']);
            Route::post('beneficiaries/getPartnerAccount', [Kanexy\PartnerFoundation\Banking\Controllers\BeneficiaryController::class, 'getPartnerAccount'])->name("beneficiaries.getPartnerAccount");
            Route::name('transactions.index')->get('transactions', Kanexy\PartnerFoundation\Banking\Controllers\TransactionController::class);
            Route::put('transactions/{transaction}', [Kanexy\PartnerFoundation\Banking\Controllers\TransactionController::class, 'update'])->name('transactions.update');
            Route::get('payouts/verify', [Kanexy\PartnerFoundation\Banking\Controllers\PayoutController::class, 'verify'])->name("payouts.verify");
            Route::resource('payouts', Kanexy\PartnerFoundation\Banking\Controllers\PayoutController::class);
            Route::resource('statement', Kanexy\PartnerFoundation\Banking\Controllers\StatementController::class);
            Route::resource('close-ledger-requests', Kanexy\PartnerFoundation\Banking\Controllers\CloseLedgerController::class)->names("closeledger");
            Route::get('close-ledger-request/approve/{id}', [Kanexy\PartnerFoundation\Banking\Controllers\CloseLedgerController::class, 'approveRequest'])->name("closeledger.approveRequest");
            Route::get('close-ledger-request/decline/{id}', [Kanexy\PartnerFoundation\Banking\Controllers\CloseLedgerController::class, 'declineRequest'])->name("closeledger.declineRequest");
        });
    });
});
