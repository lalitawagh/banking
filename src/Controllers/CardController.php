<?php

namespace Kanexy\Banking\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Kanexy\Banking\Dtos\CardCloseDto;
use Kanexy\Banking\Dtos\CreateCardDto;
use Kanexy\Cms\Controllers\Controller;
use Kanexy\Cms\I18N\Models\Country;
use Kanexy\Banking\Exceptions\FailedToActivateCardException;
use Kanexy\Banking\Exceptions\FailedToApproveCardException;
use Kanexy\Banking\Exceptions\FailedToCloseCardException;
use Kanexy\Banking\Models\Account;
use Kanexy\Banking\Models\Card;
use Kanexy\Cms\Rules\AlphaSpaces;
use Kanexy\PartnerFoundation\Core\Models\Transaction;
use Kanexy\Banking\Policies\CardPolicy;
use Kanexy\Banking\Requests\StoreCardAddressRequest;
use Kanexy\PartnerFoundation\Core\Models\Address;
use Kanexy\Banking\Services\WrappexService;
use Kanexy\PartnerFoundation\Membership\Models\MembershipLog;
use Kanexy\PartnerFoundation\Saas\Models\PlanSubscription;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CardController extends Controller
{
    private WrappexService $service;

    public function __construct(WrappexService $wrappexService)
    {
        $this->service = $wrappexService;
    }

    public function index(Request $request)
    {
        $this->authorize(CardPolicy::INDEX, Card::class);

        $cards = QueryBuilder::for(Card::class)->with('account')
            ->allowedFilters([
                AllowedFilter::exact('workspace_id'),
            ])
            ->latest()
            ->paginate();

        $workspace = null;

        if ($request->has('filter.workspace_id')) {
            $workspace = Workspace::findOrFail($request->input('filter.workspace_id'));
        }

        return view("banking::cards.index", compact('cards', 'workspace'));
    }

    public function show(Card $card)
    {
        $this->authorize(CardPolicy::SHOW, $card);

        $cardImage = null;

        try {
            $cardImage = $this->service->fetchCardImage($card->ref_id);
        } catch (\Exception $ex) {
            //
        }

        $transactions = Transaction::forCard($card)->where('status', '!=', 'pending-confirmation')->latest()->take(15)->get();

        return view("banking::cards.show", compact('card', 'cardImage', 'transactions'));
    }

    public function create(Request $request)
    {
        $this->authorize(CardPolicy::CREATE, Card::class);

        $workspace = Workspace::findOrFail($request->input('workspace_id'));

        $countries = Country::get();
        $accounts = Account::forHolder($workspace)->get();

        return view('banking::cards.request-new.create', compact('countries', 'accounts', 'workspace'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'workspace_id' => ['required', 'exists:workspaces,id'],
            'account_id' => ['required', 'exists:accounts,id'],
        ]);

        $this->authorize(CardPolicy::CREATE, Card::class);

        $workspace = Workspace::findOrFail($request->input('workspace_id'));
        $feature = PlanSubscription::checkFeatureLimit($workspace, 'Max Virtual Cards');
        $membershipLog = MembershipLog::where(['key' => 'Max Virtual Cards', 'holder_id' => $workspace->memberships()->first()->id])->first();

        if (!is_null(@$feature['used']) && @$feature['used'] <= 0 || $membershipLog?->value >= $feature['used']) {
            throw ValidationException::withMessages(['account_id' => 'The maximum card limit is over for this subscription.']);
        } else if (is_null(@$feature['used']) && $feature?->status == 'active') {
            if ($feature?->used <= 0) {
                throw ValidationException::withMessages(['account_id' => 'The maximum card limit is over for this subscription.']);
            }
        }

        session(['card_request' => $data]);

        return redirect()->route('dashboard.cards.show-card-mode', ['workspace_id' => $workspace->id]);
    }

    public function showCardMode()
    {
        $this->authorize(CardPolicy::CREATE, Card::class);

        $workspace = Workspace::findOrFail(session()->get('card_request.workspace_id'));

        return view('banking::cards.request-new.card-mode', compact('workspace'));
    }

    public function storeCardMode(Request $request)
    {
        $this->authorize(CardPolicy::CREATE, Card::class);

        $request->validate(['mode' => 'required']);

        $requestCard = session('card_request');
        $requestCard['mode'] = $request->input('mode');

        session(['card_request' => $requestCard]);

        return redirect()->route('dashboard.cards.show-card-address', ['workspace_id' => $requestCard['workspace_id']]);
    }

    public function showCardAddress()
    {
        $this->authorize(CardPolicy::CREATE, Card::class);

        $workspace = Workspace::findOrFail(session()->get('card_request.workspace_id'));

        $shippingAddresses = Address::forTarget($workspace)->ofType('shipping')->get();
        $billingAddresses = Address::forTarget($workspace)->ofType('billing')->get();

        return view('banking::cards.request-new.card-address', compact('workspace', 'shippingAddresses', 'billingAddresses'));
    }

    public function storeCardAddress(StoreCardAddressRequest $request)
    {
        $this->authorize(CardPolicy::CREATE, Card::class);

        // $request->validate([
        //     'billing_address_id' => ['required', 'exists:addresses,id'],
        //     'shipping_address_id' => ['required', 'exists:addresses,id'],
        // ]);

        $requestCard = session('card_request');
        $requestCard['billing_address_id'] = $request->input('billing_address_id');
        $requestCard['shipping_address_id'] = $request->input('shipping_address_id');

        session(['card_request' => $requestCard]);

        return redirect()->route('dashboard.cards.show-card-detail', ['workspace_id' => $requestCard['workspace_id']]);
    }

    public function showCardDetail()
    {
        $this->authorize(CardPolicy::CREATE, Card::class);

        $workspace = Workspace::findOrFail(session()->get('card_request.workspace_id'));

        return view('banking::cards.request-new.card-detail', compact('workspace'));
    }

    public function storeCardDetail(Request $request)
    {

        $this->authorize(CardPolicy::CREATE, Card::class);

        $request->validate([
            'card_holder_name' => ['required', 'string', 'max:21', new AlphaSpaces()],
            'card_type' => ['required', Rule::in(['debit'])],
        ]);

        $requestCard = session('card_request');
        $requestCard['card_holder_name'] = $request->input('card_holder_name');
        $requestCard['card_type'] = $request->input('card_type');

        session(['card_request' => $requestCard]);

        return redirect()->route('dashboard.cards.show-finalize-card', ['workspace_id' => $requestCard['workspace_id']]);
    }

    public function showFinalizeCard()
    {
        $this->authorize(CardPolicy::CREATE, Card::class);

        $workspace = Workspace::findOrFail(session()->get('card_request.workspace_id'));

        $requestCard = session('card_request');
        $cardDeliveryAddress = null;
        $cardBillingAddress = null;

        if (isset($requestCard['shipping_address_id'])) {
            $cardDeliveryAddress = Address::findOrFail($requestCard['shipping_address_id']);
        }
        if (isset($requestCard['billing_address_id'])) {
            $cardBillingAddress = Address::findOrFail($requestCard['billing_address_id']);
        }


        return view('banking::cards.request-new.card-finalize', compact('workspace', 'requestCard', 'cardBillingAddress', 'cardDeliveryAddress'));
    }

    public function finalizeCard()
    {
        $this->authorize(CardPolicy::CREATE, Card::class);

        $workspace = Workspace::findOrFail(session()->get('card_request.workspace_id'));

        $requestCard = session('card_request');

        Card::create([
            'account_id' => $requestCard['account_id'],
            'type' => $requestCard['card_type'],
            'name' => $requestCard['card_holder_name'],
            'mode' => $requestCard['mode'],
            'status' => 'requested',
            'billing_address_id' => $requestCard['billing_address_id'],
            'delivery_address_id' => $requestCard['shipping_address_id'],
            'workspace_id' => $workspace->id,
            'holder_type' => auth()->user()->getMorphClass(),
            'holder_id' => auth()->id(),
        ]);


        PlanSubscription::reduceFeatureLimit($workspace, 'Max Virtual Cards');

        session()->forget('card_request');

        return redirect()
            ->route('dashboard.cards.index', ['filter' => ['workspace_id' => $workspace->id]])
            ->with(['message' => 'A new card request has been successfully created.', 'status' => 'success']);
    }

    public function approve(Card $card)
    {
        $this->authorize(CardPolicy::APPROVE, $card);

        try {

            $serviceResponse = $this->service->createCard(new CreateCardDto($card));
        } catch (\Exception $exception) {

            throw FailedToApproveCardException::create();
        }

        $card->update([
            'ref_id' => $serviceResponse['id'],
            'ref_type' => 'wrappex',
            'status' => 'approved',
        ]);

        return redirect()->route("dashboard.cards.index")->with([
            'message' => 'Processing the card. It may take a while.',
            'status' => 'success',
        ]);
    }

    public function activate(Card $card)
    {
        $this->authorize(CardPolicy::ACTIVATE, $card);

        try {
            $this->service->activateCard($card->ref_id);
        } catch (\Exception $exception) {
            throw FailedToActivateCardException::create();
        }

        $card->update(['status' => 'attempting-activation']);

        return redirect()->route("dashboard.cards.index")->with([
            'message' => 'Activating the card. It may take a while.',
            'status' => 'success',
        ]);
    }

    public function close(Request $request, Card $card)
    {
        $this->authorize(CardPolicy::CLOSE, $card);

        $data = $request->validate([
            'close_reason' => 'required'
        ]);

        try {
            $this->service->closeCard($card->ref_id, new CardCloseDto($data));
        } catch (\Exception $exception) {
            throw FailedToCloseCardException::create();
        }

        $card->update(['status' => 'attempting-close']);

        return redirect()->route("dashboard.cards.index")->with([
            'message' => 'Close the card. It may take a while.',
            'status' => 'success',
        ]);
    }

    public function requestCardClose(Card $card)
    {

        $card->update(['status' => 'request-close']);

        return redirect()->route(
            "dashboard.cards.index",
            ['filter' => ['workspace_id' => app('activeWorkspaceId')]]
        )->with(['message' => 'Card close request submitted successfully . It may take a while.', 'status' => 'success']);
    }
}
