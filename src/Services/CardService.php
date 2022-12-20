<?php

namespace Kanexy\Banking\Services;

use Kanexy\Banking\Exceptions\FailedToActivateCardException;
use Kanexy\Banking\Exceptions\FailedToApproveCardException;
use Kanexy\Banking\Exceptions\FailedToCloseCardException;
use Kanexy\Banking\Models\Card;
use Kanexy\PartnerFoundation\Core\Dtos\CardCloseDto;
use Kanexy\PartnerFoundation\Core\Dtos\CreateCardDto;
use Kanexy\PartnerFoundation\Core\Services\WrappexService;

class CardService
{
    private WrappexService $service;

    public function __construct(WrappexService $service)
    {
        $this->service = $service;
    }

    public function fetchImage(Card $card)
    {
        $cardImage = null;

        try {
            $cardImage = $this->service->fetchCardImage($card->ref_id);
        } catch (\Exception $exception) {
            //
        }

        return $cardImage;
    }

    public function approve(Card $card)
    {
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

        return $card;
    }

    public function activate(Card $card)
    {
        try {

            $this->service->activateCard($card->ref_id);
        } catch (\Exception $exception) {

            throw FailedToActivateCardException::create();
        }

        $card->update(['status' => 'attempting-activation']);

        return $card;
    }

    public function close(Card $card, array $data)
    {
        try {

            $this->service->closeCard($card->ref_id, new CardCloseDto($data));
        } catch (\Exception $exception) {

            throw FailedToCloseCardException::create();
        }

        $card->update(['status' => 'attempting-close']);

        return $card;
    }
}
