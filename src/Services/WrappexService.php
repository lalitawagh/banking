<?php

namespace Kanexy\Banking\Services;

use Illuminate\Support\Facades\Http;
use Kanexy\Cms\Setting\Models\Setting;
use Kanexy\Banking\Dtos\CardCloseDto;
use Kanexy\Banking\Dtos\CreateAccountDto;
use Kanexy\Banking\Dtos\CreateBeneficiaryDto;
use Kanexy\Banking\Dtos\CreateCardDto;
use Kanexy\Banking\Dtos\CreateTransactionDto;
use Kanexy\Banking\Dtos\CreateUserDto;
use Kanexy\Banking\Dtos\InitiateKYCDto;
use Kanexy\Banking\Dtos\UpdateUserDto;
use Kanexy\Banking\Dtos\UploadKYCDto;

class WrappexService
{
    private string $apiUrl;

    private string $baseUrl;

    private string $token;

    public function __construct() {
        $baseUrl = config('partner-foundation.wrappex_dev_url');

        if (config('partner-foundation.services.wrappex.environment') === 'production') {
            $baseUrl = config('partner-foundation.wrappex_live_url');
        }

        $this->baseUrl = $baseUrl;
        $this->apiUrl = $baseUrl . '/api/v1';

        $this->setupAccessToken();
    }

    public function setupAccessToken()
    {
        $this->token = Setting::getValue('wrappex_access_token');
    }

    public function createAccount(CreateAccountDto $createAccountDto)
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customer/partner/accounts', $createAccountDto->toArray())
            ->throw()
            ->json('data');
    }

    public function closeAccount($accountId)
    {
        $data = ['ref_id' => $accountId];

        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customer/partner/accounts/' . $accountId . '/close', $data)
            ->throw()
            ->json('data');
    }

    public function createUser(CreateUserDto $createUserDto)
    {

        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customer/partner/users', $createUserDto->toArray())
            ->throw()
            ->json('data.id');
    }

    public function createBeneficiary(CreateBeneficiaryDto $createBeneficiaryDto)
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customer/beneficiaries', $createBeneficiaryDto->toArray())
            ->throw()
            ->json('data.id');
    }

    public function activateCard($cardId)
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customer/cards/' . $cardId . '/activate')
            ->throw()
            ->json('data');
    }

    public function closeCard($cardId, CardCloseDto $cardCloseDto)
    {
        $data = $cardCloseDto->toArray();

        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customer/cards/' . $cardId . '/close', $data)
            ->throw()
            ->json('data');
    }

    public function createCard(CreateCardDto $createCardDto)
    {
        $data = $createCardDto->toArray();
        $data['type'] = $data['mode']; // TODO: Fix the bug.

        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customer/cards', $data)
            ->throw()
            ->json('data');

    }

    public function fetchCardImage($cardId): string
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->get($this->apiUrl . '/customer/cards/' . $cardId . '/image')
            ->throw()
            ->json('data.url');
    }

    public function createTransaction(CreateTransactionDto $createTransactionDto)
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customer/transactions', $createTransactionDto->toArray())
            ->throw()
            ->json('data');
    }

    public function getAccount($ledgerId)
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->get($this->apiUrl . '/customer/partner/accounts/' . $ledgerId)
            ->throw()
            ->json('data');
    }

    public function initiateKYC(InitiateKYCDto $initiateKYCDto)
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customers/' . $initiateKYCDto->ref_id . '/kyc/check', $initiateKYCDto->toArray())
            ->throw()
            ->json('data');
    }

    public function uploadKYC(UploadKYCDto $uploadKYCDto)
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->post($this->apiUrl . '/customers/' . $uploadKYCDto->ref_id . '/kyc/upload', $uploadKYCDto->toArray())
            ->throw()
            ->json('data');
    }

    public function updateUser(UpdateUserDto $updateUserDto)
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->put($this->apiUrl . '/customer/partner/users/' . $updateUserDto->ref_id, $updateUserDto->toArray())
            ->throw()
            ->json('data');
    }
}
