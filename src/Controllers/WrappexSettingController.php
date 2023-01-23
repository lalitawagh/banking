<?php

namespace Kanexy\PartnerFoundation\Setting\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Kanexy\Cms\Setting\Models\Setting;
use Kanexy\PartnerFoundation\Setting\Requests\StoreWrappexSetting;

class WrappexSettingController extends Controller
{
    public function store(StoreWrappexSetting $request)
    {
        $data = $request->validated();
        $baseUrl = config('partner-foundation.wrappex_dev_url');

        if (Setting::getValue('wrappex_environment') === 'production') {
            $baseUrl = config('partner-foundation.wrappex_live_url');
        }

        $result = Http::asForm()->post($baseUrl . '/oauth/token', [
            "grant_type" => "password",
            "client_id" => $data['wrappex_client_id'],
            "client_secret" => $data['wrappex_client_secret'],
            "username" => $data['wrappex_email'],
            "password" => $data['wrappex_password'],
            "scope" => "*",
        ])->json('access_token');

        if(is_null($result))
        {
            return back()->withError('Wrappex Credentials is invalid please enter valid details');

        } else {
            $data['wrappex_access_token'] = $result;
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        session(['tab' => 'wrappexsetting']);

        return redirect()->route("dashboard.settings.index")->with([
            'status' => 'success',
            'message' => 'Wrappex settings updated successfully.',
        ]);
    }
}
