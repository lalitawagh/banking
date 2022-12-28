<?php

namespace Kanexy\Banking\Dtos;

use Kanexy\Banking\Models\Account;
use Kanexy\Banking\Models\Card;
use Kanexy\PartnerFoundation\Workspace\Enums\WorkspaceType;

class CreateCardDto
{
    public string $account_id;

    public string $name;

    public string $mode;

    public string $type;

    public string $design;

    public string $programme;

    public ?string $carrier_type = null;

    public ?string $delivery_name = null;

    public ?string $delivery_method = null;

    public ?string $delivery_address_region = null;

    public ?string $delivery_address_iso_country = null;

    public ?string $delivery_address_postal_code = null;

    public ?string $delivery_address_refinement = null;

    public ?string $delivery_address_street = null;

    public ?string $delivery_address_city = null;

    public function __construct(Card $card)
    {
        $this->account_id = $card->account->ref_id;
        $this->name = $card->name;
        $this->mode = $card->mode;
        $this->type = $card->type;
        $this->design = config('partner-foundation.card_design');

        $this->setFromMode($card);
        $this->setProgramme($card->account);
    }

    public function setFromMode(Card $card)
    {
        if ($card->mode === 'physical') {
            $this->delivery_name = $card->account->name;
            $this->carrier_type = 'standard';
            $this->delivery_method = 'dhl';
            $this->delivery_address_region = $card->deliveryAddress->county;
            $this->delivery_address_iso_country = $card->deliveryAddress->country->code;
            $this->delivery_address_postal_code = $card->deliveryAddress->postcode;
            $this->delivery_address_refinement = $card->deliveryAddress->house_no;
            $this->delivery_address_street = $card->deliveryAddress->street;
            $this->delivery_address_city = $card->deliveryAddress->city;
        }
    }

    public function toArray(): array
    {
        return [
            'ledger_account_id' => $this->account_id,
            'mode' => $this->mode,
            'type' => $this->type,
            'name' => $this->name,
            'design' => $this->design,
            'programme' => $this->programme,
            'carrier_type' => $this->carrier_type,
            'delivery_name' => $this->delivery_name,
            'delivery_method' => $this->delivery_method,
            'delivery_address_region' => $this->delivery_address_region,
            'delivery_address_iso_country' => $this->delivery_address_iso_country,
            'delivery_address_postal_code' => $this->delivery_address_postal_code,
            'delivery_address_refinement' => $this->delivery_address_refinement,
            'delivery_address_street' => $this->delivery_address_street,
            'delivery_address_city' => $this->delivery_address_city,
        ];
    }

    private function setProgramme(Account $account)
    {
        $this->programme = $account->holder->type === WorkspaceType::INDIVIDUAL ? 'Naxetra-MVP-consumer-GBP' : 'Naxetra-MVP-corporate-GBP';
    }

}
