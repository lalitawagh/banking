<x-mail::message>
    Dear {{ $user->first_name }} {{ $user->last_name }},
    <br>
    <br>
    <p>
        We are writing to confirm that a credit transaction has been successfully processed. The details of the
        transaction
        are as follows:
        <br>
        <br>
        Transaction Date: {{ $transaction->created_at->format('d-m-Y  H:i A') }}
        <br>
        <br>
        Transaction Amount: {{ $transaction->amount }}
        <br>
        <br>
        Transaction Type: Credit
        <br>
        <br>
        Transaction Reference Number: {{ $transaction->meta['reference'] }}
        <br>
        <br>
        If you have any questions or concerns regarding this transaction, please do not hesitate to contact our customer
        support team at {{ env('SUPPORT_EMAIL') }}.
        <br>
        <br>
        Thank you for using {{ config('app.name') }} for your financial needs. We look forward to continuing to serve
        you in the
        future.
    </p>
    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>
