<?php
use Illuminate\Support\Facades\Route;
use Kanexy\Banking\Controllers\BeneficiaryController;
use Kanexy\Banking\Controllers\CardController;
use Kanexy\Banking\Controllers\CloseLedgerController;
use Kanexy\Banking\Controllers\MembershipComponentController;
use Kanexy\Banking\Controllers\PayoutController;
use Kanexy\Banking\Controllers\RegistrationLedgerController;
use Kanexy\Banking\Controllers\StatementController;
use Kanexy\Banking\Controllers\TransactionController;
use Kanexy\Cms\Middleware\ColorModeMiddleware;
use Kanexy\Cms\Middleware\ValidateRegistrationCompletedMiddleware;

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

Route::group(['middleware' => ['web','auth']], function () {

Route::group(['middleware' => [ValidateRegistrationCompletedMiddleware::class], 'prefix' => 'customer/signup', 'as' => 'customer.signup.'], function () {
    Route::resource('ledger', RegistrationLedgerController::class)->only(['index', 'create', 'store']);
    Route::get('finalize', [RegistrationLedgerController::class, 'show'])->name('ledger-show');
});

Route::group(['middleware' => [ColorModeMiddleware::class]], function () {
    
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::name('cards.activate')->post('cards/{card}/activate', [CardController::class, 'activate']);
        Route::name('cards.approve')->post('cards/{card}/approve', [CardController::class, 'approve']);
        Route::name('cards.close')->post('cards/{card}/close', [CardController::class, 'close']);
        Route::name('cards.finalize-card')->post('cards/wizard/finalize', [CardController::class, 'finalizeCard']);
        Route::name('cards.show-finalize-card')->get('cards/wizard/finalize', [CardController::class, 'showFinalizeCard']);
        Route::name('cards.store-card-detail')->post('cards/wizard/detail', [CardController::class, 'storeCardDetail']);
        Route::name('cards.show-card-detail')->get('cards/wizard/detail', [CardController::class, 'showCardDetail']);
        Route::name('cards.store-card-address')->post('cards/wizard/address', [CardController::class, 'storeCardAddress']);
        Route::name('cards.show-card-address')->get('cards/wizard/address', [CardController::class, 'showCardAddress']);
        Route::name('cards.store-card-mode')->post('cards/wizard/mode', [CardController::class, 'storeCardMode']);
        Route::name('cards.show-card-mode')->get('cards/wizard/mode', [CardController::class, 'showCardMode']);
        Route::resource('cards', CardController::class)->only(['index', 'create', 'store', 'show']);

        Route::group(['prefix' => 'banking', 'as' => 'banking.'], function () {
            Route::resource('beneficiaries', 'Kanexy\Banking\Controllers\BeneficiaryController')->except(['show']);
            Route::post('beneficiaries/getPartnerAccount', [BeneficiaryController::class, 'getPartnerAccount'])->name("beneficiaries.getPartnerAccount");
            Route::name('transactions.index')->get('transactions', TransactionController::class);
            Route::put('transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
            Route::get('payouts/verify', [PayoutController::class, 'verify'])->name("payouts.verify");
            Route::resource('payouts', PayoutController::class);
            Route::resource('statement', StatementController::class);
            Route::resource('close-ledger-requests', CloseLedgerController::class)->names("closeledger");
            Route::get('close-ledger-request/approve/{id}', [CloseLedgerController::class, 'approveRequest'])->name("closeledger.approveRequest");
            Route::get('close-ledger-request/decline/{id}', [CloseLedgerController::class, 'declineRequest'])->name("closeledger.declineRequest");
        });

        Route::get('workspaces/{workspace}/bank-information', [MembershipComponentController::class, 'showBankInformation'])->name('membership-bank-information');
        Route::post('memberships/bank-verification', [MembershipComponentController::class, 'storeVerification'])->name('memberships.ledger-bank-verification');
        Route::get('workspaces/{workspace}/configuration', [MembershipComponentController::class, 'showConfigurationInformation'])->name('membership-configuration-information');
        Route::post('memberships/{id}/configuration', [MembershipComponentController::class, 'storeConfiguration'])->name('membership.store.configuration');
    });

    Route::group(['prefix' => 'dashboard/workspaces/{workspace?}', 'as' => 'dashboard.workspaces.'], function () {
        Route::get('bank-information', [MembershipComponentController::class, 'showBankInformation'])->name('membership-bank-information');
    });
});
});
