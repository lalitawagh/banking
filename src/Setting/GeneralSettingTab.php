<?php

namespace Kanexy\Banking\Setting;

use Kanexy\Cms\Components\Contracts\Component;

class GeneralSettingTab extends Component
{
    public function render()
    {
        return view("banking::setting.general-setting-tab");
    }
}
