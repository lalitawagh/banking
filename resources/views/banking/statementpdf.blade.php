<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement PDF</title>
</head>

<body style="margin: 0; padding: 0; font-family: 'Rubik', sans-serif; color: #444e5e;">
    <div style="max-width: 100%; margin: auto; border-radius: 0.375rem; box-shadow: 0px 3px 20px #0000000b;">

        @if (!$user->isSubscriber())
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%"
                style="background-color: #fff; max-width: 6000px;">
                <tbody>
                    <tr style="padding-left: 2.5rem; padding-right: 2.5rem;">
                        <td bgcolor="#ffffff"
                            style="padding:20px 20px; text-align: left; font-family: sans-serif; font-size: 16px; line-height: 26px; color: #666666;">
                            <!-- Content for non-subscribers -->
                        </td>
                        <td style="text-align:right;padding:20px 20px;font-family: 'Rubik', sans-serif">
                            <div class="lg:text-right mt-10 lg:mt-0 lg:ml-auto">
                                <div style="font-size: 1.5rem;line-height: 2rem; font-weight:600;">GBP Statement</div>
                                <div style="font-size: 1rem;line-height: 2rem; font-weight:500;"> Date: <span
                                        class="font-medium">12/12/2021</span> </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        @if ($user->isSubscriber())
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%"
                style="background-color: #fff; max-width: 6000px;">
                <tbody>
                    <tr style="padding-left: 2.5rem; padding-right: 2.5rem;">
                        <td bgcolor="#ffffff"
                            style="padding:20px 20px; text-align: left; font-family: sans-serif; font-size: 16px; line-height: 26px; color: #666666;">
                            <img src="https://dev.kanexy.com/img/core-img/logo.png" width="13rem" height=""
                                alt="alt_text" border="0" class="fluid"
                                style="width: 100%; max-width: 13rem; background: #fff; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                        </td>
                        <td style="text-align:right;padding:20px 20px;font-family: 'Rubik', sans-serif">
                            <div class="lg:text-right mt-10 lg:mt-0 lg:ml-auto">
                                <div style="font-size: 1.5rem;line-height: 2rem; font-weight:600;">GBP Statement</div>
                                <div style="font-size: 1rem;line-height: 2rem; font-weight:500;"> Date: <span
                                        class="font-medium">12/12/2021</span> </div>
                            </div>
                        </td>
                    </tr>
                    <tr style="padding-left: 2.5rem; padding-left: 2.5rem;">
                        <td style="text-align:left;padding:20px 20px;">
                            <div>
                                <div style="font-size: 1.5rem;line-height: 2rem; font-weight:600; padding-bottom:10px">
                                    {{ @$account?->name }}</div>
                            </div>
                        </td>
                        <td style="text-align:right;padding:20px 20px;">
                            <div class="lg:text-right mt-10 lg:mt-0 lg:ml-auto">
                                <div style="font-size: 1rem;line-height: 1.5; font-weight:600;">Account Number:&nbsp;
                                    {{ @$account?->account_number }}</div>
                                <div style="font-size: 1rem;line-height: 2rem; font-weight:500;">Sort code:&nbsp;
                                    {{ @$account?->bank_code }}</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        <h2 style="text-align: center; margin-top: 20px; margin-bottom: 10px;">Statements</h2>
        <div style="overflow-x: auto;">
            <table cellspacing="0" cellpadding="10" border="0" style="width: 100%; font-size: 16px;">
                <thead style="text-align: left; font-weight: 600;">
                    <tr>
                        <th>Transaction Id</th>
                        <th>Third Party</th>
                        <th>Date & Time</th>
                        <th>Account No</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Reference</th>
                        <th>Payment method</th>
                    </tr>
                </thead>
                <tbody style="font-size: 16px;">
                    @foreach ($transactions as $transaction)
                        <tr style="border-top: 1px solid #f1f5f9; font-size: 16px;">
                            <td style="width: 10%; word-wrap: break-word;">{{ $transaction->urn }}</td>
                            @if ($transaction->type === 'debit')
                                <td style="width: 15%; word-wrap: break-word;">
                                    {{ @$transaction->meta['beneficiary_name'] }}</td>
                            @else
                                <td style="width: 15%; word-wrap: break-word;">{{ @$transaction->meta['sender_name'] }}
                                </td>
                            @endif
                            <td style="width: 15%; word-wrap: break-word;">{{ $transaction->created_at }}</td>
                            <td style="width: 10%; word-wrap: break-word;">
                                {{ @$transaction->workspace->account?->account_number }}</td>
                            @if ($transaction->type === 'debit')
                                <td style="width: 10%; word-wrap: break-word;">
                                    {{ \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($transaction->amount) }}
                                </td>
                            @else
                                <td style="width: 10%; word-wrap: break-word; text-align: center;"> - </td>
                            @endif
                            @if ($transaction->type === 'credit')
                                <td style="width: 10%; word-wrap: break-word;">
                                    {{ \Kanexy\PartnerFoundation\Core\Helper::getFormatAmount($transaction->amount) }}
                                </td>
                            @else
                                <td style="width: 10%; word-wrap: break-word; text-align: center;"> - </td>
                            @endif
                            <td style="width: 15%; word-wrap: break-word;">{{ @$transaction->meta['reference'] }}</td>
                            <td style="width: 15%; word-wrap: break-word;">{{ ucfirst($transaction->payment_method) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</body>
</html>
