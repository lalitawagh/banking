<x-mail::message>
    Dear {{$user->first_name}} {{$user->last_name}},
    <p>
    We are sorry to hear that you have decided to close your account with us. We appreciate your business and would like
    to thank you for choosing our fintech service. We would like to confirm that we have received your request to close
    your account, and we are processing it as quickly as possible.
    <br>
    <br>
    Please note that closing your account will result in the termination of all services associated with your account.
    This includes access to our platform, account history, and any balances in your account. Before we proceed with the
    closure, we want to make sure that you are aware of any outstanding balances or pending transactions that may affect
    your account.
    <br>
    <br>
    If you have any outstanding balances, we ask that you settle them before we proceed with the closure. Additionally,
    if you have any pending transactions that have not yet been processed, we may need to delay the closure until these
    transactions have been completed.
    <br>
    <br>
    If you have any questions or concerns about the account closure process, please do not hesitate to contact us at
    {{env('SUPPORT_EMAIL')}}. We are committed to making the process as smooth and seamless as possible, and we will work with you
    to address any issues that may arise.
    <br>
    <br>
    Thank you for choosing our fintech service, and we wish you all the best in your future endeavors.
    <br>
    <br>
    </p>
    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>
