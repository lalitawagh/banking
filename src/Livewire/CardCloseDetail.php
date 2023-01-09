<?php

namespace Kanexy\Banking\Livewire;

use Kanexy\Banking\Models\Card;
use Livewire\Component;

class CardCloseDetail extends Component
{
    public string $card;

    public string $cardId;

    public string $close_reason;

    public string $card_holder_name;

    protected $listeners = [
        'cardCloseId',
    ];

    public function cardCloseId(Card $card)
    {
        $this->card = $card;
        $this->cardId = $card->id;
        $this->card_holder_name = $card->name;
    }

    public function render()
    {
        return view('banking::Livewire.card-close-detail');
    }
}
