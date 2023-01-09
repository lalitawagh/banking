<?php

namespace Kanexy\Banking\Dashboard;

use Kanexy\Cms\Components\Contracts\Component;

class TransactionGraphWidget extends Component
{
    public function render()
    {
        return view("banking::widget.transaction-chart");
    }
}
