<?php

namespace Kanexy\Banking;

use App\Models\User;
use Kanexy\Banking\Livewire\CardCloseDetail;
use Kanexy\Banking\Livewire\CloseLedgerModal;
use Kanexy\Banking\Livewire\CreateBeneficiariesComponent;
use Kanexy\Banking\Livewire\OtpVerificationComponent;
use Kanexy\Banking\Livewire\ShowCloseLedger;
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
use Kanexy\PartnerFoundation\Core\Enums\WorkspaceType;
use Kanexy\PartnerFoundation\Core\Facades\PartnerFoundation;
use Kanexy\Banking\Dashboard\TransactionGraphWidget;
use Kanexy\Banking\Setting\GeneralSettingContent;
use Kanexy\Banking\Setting\GeneralSettingTab;

class Banking
{
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

    public function registerDefaultLivewireComponents()
    {
        Livewire::component('transaction-detail', TransactionDetail::class);
        Livewire::component('transaction-list-dashboard', TransactionListDashboard::class);
        Livewire::component('total-transaction-dashboard', TotalTransactionDashboard::class);
        Livewire::component('transaction-graph-dashboard', TransactionGraphDashboard::class);
        Livewire::component('statementexport-pdf', StatementExportPdf::class);
        Livewire::component('create-beneficiaries', CreateBeneficiariesComponent::class);
        Livewire::component('otp-verification', OtpVerificationComponent::class);
        Livewire::component('close-ledger-modal', CloseLedgerModal::class);
        Livewire::component('show-close-ledger', ShowCloseLedger::class);
        Livewire::component('card-close-detail', CardCloseDetail::class);
    }

    public function registerDefaultMenuComponents()
    {
        \Kanexy\Cms\Facades\SidebarMenu::addItem(new BankingMenuItem());
        \Kanexy\Cms\Facades\SidebarMenu::addItem(new CardsMenuItem());
    }

    public function registerDefaultRegistrationFlowComponents()
    {
        \Kanexy\Cms\Facades\RegistrationStep::addItem(new BusinessBankingRegistrationStep());
        \Kanexy\Cms\Facades\RegistrationStep::addItem(new PersonalBankingRegistrationStep());
    }

    public function registerDefaultDashboardComponents()
    {
        \Kanexy\Cms\Facades\Dashboard::addItem(DashboardTileWidget::class,900);
        \Kanexy\Cms\Facades\Dashboard::addItem(TransactionGraphWidget::class,10000);
        \Kanexy\Cms\Facades\Dashboard::addItem(CardWidget::class,20000);
        \Kanexy\Cms\Facades\Dashboard::addItem(TransactionWidget::class,30000);
    }

    public function registerDefaultTopbarComponents()
    {
        \Kanexy\Cms\Facades\Topbar::addItem(TopbarSendMoney::class);
    }

    public function registerDefaultFormComponents()
    {
        \Kanexy\Cms\Facades\MembershipServiceSelection::addItem(new BankingServiceSelectionContent());
        \Kanexy\PartnerFoundation\Membership\Facades\MembershipComponent::addItem(MembershipComponent::class);
        \Kanexy\PartnerFoundation\Membership\Facades\MembershipBankingComponent::addItem(MembershipBankingComponent::class);
        \Kanexy\Cms\Facades\SettingContent::addItem(GeneralSettingContent::class);
        \Kanexy\Cms\Facades\SettingTab::addItem(GeneralSettingTab::class);
    }

    public function registerDefaultRedirectRouteComponents()
    {
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

        PartnerFoundation::setRouteForMembershipVerification(function () {
            return route('dashboard.memberships.ledger-bank-verification');
        });

        PartnerFoundation::setBankingPayment(function (User $user){
            return true;
        });

    }
}
