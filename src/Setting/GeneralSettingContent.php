<?php

namespace Kanexy\Banking\Setting;

use Kanexy\Cms\Components\Contracts\Component;
use Kanexy\Cms\Setting\Models\Setting;

class GeneralSettingContent extends Component
{
    public function render()
    {
        $settings = Setting::pluck('value', 'key');
        return view("banking::setting.general-setting-content", compact('settings'));
    }
}
