<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
      <!-- utf-8 works for most cases -->
      <meta name="viewport" content="width=device-width">
      <title>CloseLedger PDF</title>
   </head>
   <body width="100%" style="width: 100%; 0;font-family: 'Rubik', sans-serif; color:#444e5e;">
      <!-- Visually Hidden Preheader Text : BEGIN -->
      <div style="width:100%; margin: auto;font-family: box-shadow: 0px 3px 20px #0000000b; border-radius: 0.375rem;">
         <!-- Email Body : BEGIN -->
            @if($user->isSuperAdmin())
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="background-color: #fff; max-width: 6000px;">
                <!-- Hero Image, Flush : BEGIN -->
                <tbody>
                <tr style="padding-left: 2.5rem; padding-right: 2.5rem;">
                    <td bgcolor="#ffffff" style="padding:20px 20px; text-align: left; font-family: sans-serif; font-size: 16px; line-height: 26px; color: #666666;">
                        <img src="https://dev.kanexy.com/img/core-img/logo.png" width="13rem" height="" alt="alt_text" border="0"  class="fluid" style="width: 100%; max-width: 13rem; background: #fff; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                    </td>
                    <td style="text-align:right;padding:20px 20px;font-family: 'Rubik', sans-serif">
                        <div class="lg:text-right mt-10 lg:mt-0 lg:ml-auto">
                            <div style="font-size: 1.5rem;line-height: 2rem; font-weight:600;">CloseLedger Details</div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            @endif
         @if(!$user->isSuperAdmin())
         <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="background-color: #fff; max-width: 6000px;">
            <!-- Hero Image, Flush : BEGIN -->
            <tbody>
            <tr style="padding-left: 2.5rem; padding-right: 2.5rem;">
                <td bgcolor="#ffffff" style="padding:20px 20px; text-align: left; font-family: sans-serif; font-size: 16px; line-height: 26px; color: #666666;">
                    <img src="https://dev.kanexy.com/img/core-img/logo.png" width="13rem" height="" alt="alt_text" border="0"  class="fluid" style="width: 100%; max-width: 13rem; background: #fff; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                </td>
                <td style="text-align:right;padding:20px 20px;font-family: 'Rubik', sans-serif">
                    <div class="lg:text-right mt-10 lg:mt-0 lg:ml-auto">
                        <div style="font-size: 1.5rem;line-height: 2rem; font-weight:600;">CloseLedger Details</div>
                    </div>
                </td>
            </tr>
            <tr style="padding-left: 2.5rem; padding-left: 2.5rem;">
                <td style="text-align:left;padding:20px 20px;">
                    <div>
                        <div style="font-size: 1.5rem;line-height: 2rem; font-weight:600; padding-bottom:10px">Account Name:&nbsp; {{ $account?->name }}</div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        @endif
         <h2 style="text-align: center">CloseLedger Details</h2>
        <table role="presentation" cellspacing="0" cellpadding="10" border="0" width="100%">
            <thead style="text-align:left; font-size: 16px;font-weight: 600;">
                <tr>
                    <th style="white-space: nowrap;">Name</th>
                    <th style="white-space: nowrap;">Email Address</th>
                    <th style="white-space: nowrap;">Mobile No.</th>
                    <th style="white-space: nowrap;">Bank Sort Code</th>
                    <th style="white-space: nowrap;">Bank Account No.</th>
                    <th style="white-space: nowrap;">Status</th>
                </tr>
            </thead>
            <tbody style="text-align:left; font-size: 20px;">
                @foreach ($closeledgers as $closeledger)
                    <tr style="border-top: 1px solid #f1f5f9;font-size: 16px;">
                        <td style="white-space: nowrap;">{{ $closeledger->meta['name'] }}</td>
                        <td style="white-space: nowrap;">{{ $closeledger->meta['email'] }}</td>
                        <td style="white-space: nowrap;">{{ $closeledger->meta['phone'] }}</td>
                        <td style="white-space: nowrap;">{{ @$closeledger?->meta['bank_code'] }}</td>
                        <td style="white-space: nowrap;">{{ @$closeledger?->meta['account_number'] }}</td>
                        <td style="white-space: nowrap;">{{ $closeledger->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
      </div>
   </body>
</html>
