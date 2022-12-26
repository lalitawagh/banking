<?php

namespace Kanexy\Banking;

use App\Models\User;
use Kanexy\Banking\Controllers\PayoutController;
use Kanexy\Banking\Livewire\CreateBeneficiariesComponent;
use Kanexy\Banking\Livewire\OtpVerificationComponent;
use Kanexy\Banking\Livewire\StatementExportPdf;
use Kanexy\Banking\Livewire\TotalTransactionDashboard;
use Kanexy\Banking\Livewire\TransactionGraphDashboard;
use Kanexy\Banking\Livewire\TransactionListDashboard;
use Kanexy\Banking\Models\Card;
use Kanexy\Banking\Models\Statement;
use Kanexy\Banking\Policies\CardPolicy;
use Kanexy\Banking\Policies\CloseLedgerPolicy;
use Kanexy\Banking\Policies\StatementPolicy;
use Kanexy\Banking\Policies\TransactionPolicy;
use Kanexy\Banking\Services\PayoutService;
use Kanexy\PartnerFoundation\Core\Models\ArchivedMember;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Livewire\Livewire;
use Illuminate\Support\Facades\Gate;
use Kanexy\Banking\Components\TopbarSendMoney;
use Kanexy\Banking\Dashboard\CardWidget;
use Kanexy\Banking\Dashboard\DashboardTileWidget;
use Kanexy\Banking\Dashboard\TransactionWidget;
use Kanexy\Banking\Livewire\TransactionDetail;
use Kanexy\Banking\Membership\MembershipBankingComponent;
use Kanexy\Banking\Membership\MembershipComponent;
use Kanexy\Banking\Menu\BankingMenuItem;
use Kanexy\Banking\Menu\CardsMenuItem;
use Kanexy\Banking\Registration\BankingServiceSelectionContent;
use Kanexy\Banking\Step\BusinessBankingRegistrationStep;
use Kanexy\Banking\Step\PersonalBankingRegistrationStep;
use Kanexy\Cms\Setting\Models\Setting;
use Kanexy\Cms\Traits\InteractsWithMigrations;
use Kanexy\PartnerFoundation\Core\Enums\WorkspaceType;
use Kanexy\PartnerFoundation\Core\Facades\PartnerFoundation;
use Kanexy\Banking\Dashboard\TransactionGraphWidget;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BankingServiceProvider extends PackageServiceProvider
{

    use InteractsWithMigrations;

    private array $policies = [
        Card::class => CardPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Statement::class => StatementPolicy::class,
        ArchivedMember::class => CloseLedgerPolicy::class
    ];

    public function registerDefaultPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * The date and time for these migrations will be preserved when
     * published.
     *
     * @var array|string[]
     */

    protected array $migrationsWithPresetDateTime = [];

    /**
     * A new date and time for these migrations will be appended in the
     * files when published.
     *
     * @var array|string[]
     */
    protected array $migrationsWithoutPresetDateTime = [];

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
         $package
            ->name('banking')
            ->hasViews()
            ->hasRoute('web')
            ->hasRoute('api')
            ->hasMigrations($this->migrationsWithoutPresetDateTime);

        $this->publishMigrationsWithPresetDateTime($this->migrationsWithPresetDateTime);
    }

    public function packageRegistered()
    {
    }

    public function packageBooted()
    {
        parent::packageBooted();

        $this->registerDefaultPolicies();

        $this->app->singleton(\Kanexy\PartnerFoundation\Core\Services\WrappexService::class, function () {
            /**
             * We will give preference to the prefixes set in the configuration
             * in case the management wants to overwrite the prefixes for the
             * temporary usage. So, all we will have to do is to set it in the
             * .env file and it will automatically reflect in the system wide.
             */
            $environment = config('partner-foundation.services.wrappex.environment');

            if (empty($environment)) {
                $environment = Setting::getValue('wrappex_environment');
            }

            return new \Kanexy\PartnerFoundation\Core\Services\WrappexService();
        });

        \Kanexy\Cms\Facades\SidebarMenu::addItem(new BankingMenuItem());
        \Kanexy\Cms\Facades\SidebarMenu::addItem(new CardsMenuItem());

        PartnerFoundation::setRouteForMembershipVerification(function () {
            return route('dashboard.memberships.ledger-bank-verification');
        });

        PartnerFoundation::setBankingPayment(function (User $user){
            return true;
        });

        \Kanexy\PartnerFoundation\Membership\Facades\MembershipComponent::addItem(MembershipComponent::class);
        \Kanexy\PartnerFoundation\Membership\Facades\MembershipBankingComponent::addItem(MembershipBankingComponent::class);
        \Kanexy\Cms\Facades\MembershipServiceSelection::addItem(new BankingServiceSelectionContent());

        \Kanexy\Cms\Facades\RegistrationStep::addItem(new BusinessBankingRegistrationStep());
        \Kanexy\Cms\Facades\RegistrationStep::addItem(new PersonalBankingRegistrationStep());


        \Kanexy\Cms\Facades\Dashboard::addItem(DashboardTileWidget::class,900);
        \Kanexy\Cms\Facades\Dashboard::addItem(TransactionGraphWidget::class,10000);
        \Kanexy\Cms\Facades\Dashboard::addItem(CardWidget::class,20000);
        \Kanexy\Cms\Facades\Dashboard::addItem(TransactionWidget::class,30000);

        Livewire::component('transaction-detail', TransactionDetail::class);
        Livewire::component('transaction-list-dashboard', TransactionListDashboard::class);
        Livewire::component('total-transaction-dashboard', TotalTransactionDashboard::class);
        Livewire::component('transaction-graph-dashboard', TransactionGraphDashboard::class);
        Livewire::component('statementexport-pdf', StatementExportPdf::class);
        Livewire::component('create-beneficiaries', CreateBeneficiariesComponent::class);
        Livewire::component('otp-verification', OtpVerificationComponent::class);


        \Kanexy\Cms\Facades\Cms::setRegistrationFlow(function (User $user) {
            if ($user->is_banking_user == 1) {
                $workspace = $user->workspaces()->first();
                if ($workspace?->type == WorkspaceType::INDIVIDUAL && is_null($user->officer())) {
                    $type = 'personal_banking_account';
                } else if ($workspace?->type == WorkspaceType::BUSINESS && is_null($user->officer())) {
                    $type = 'business_banking_account';
                }

                return $type;
            }
            return false;
        }, 900);

        \Kanexy\Cms\Facades\Topbar::addItem(TopbarSendMoney::class);

        // \Kanexy\Cms\Facades\Cms::setRedirectRouteAfterRegistrationVerification(function (Request $request, User $user) {

        //     if ($user->is_banking_user == 1 && is_null(session('contactId'))) {

        //         $nextRoute = $user->getNextRegistrationRoute();
        //         redirect($nextRoute->getUrl());
        //     }

        //     return false;
        // }, 900);


    }
}
