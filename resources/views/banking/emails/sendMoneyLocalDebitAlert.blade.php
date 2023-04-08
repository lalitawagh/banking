<x-mail::message>
    Dear {{ $user->first_name }} {{ $user->last_name }},
    <br>
    <br>
    <p>
    We are writing to confirm that a debit transaction has been made from your account to another bank account.
    <br>
    <br>
    Transaction Details:
    <br>
    <br>
    Transaction Date: {{ $transaction->created_at->format('d-m-Y  H:i A') }}
    <br>
    <br>
    Transaction Amount: {{ $transaction->amount }}
    <br>
    <br>
    Transaction Type: Debit
    <br>
    <br>
    Transaction Reference: {{ $transaction->meta['reference'] }}
    <br>
    <br>
    Name: {{ $transaction->meta['beneficiary_name'] }}
    <br>
    <br>
    Account Number: {{ $transaction->meta['sender_bank_account_number'] }}
    <br>
    <br>
    Sort Code: {{ $transaction->meta['beneficiary_bank_code'] }}
    <br>
    <br>
    Please note that the debited amount will be reflected in your account statement, and if you have any questions or
    concerns regarding this transaction, please don't hesitate to contact us at {{ env('SUPPORT_EMAIL') }}. Our customer
    support team is always available to assist you with any einquiries or clarifications.
    <br>
    <br>
    Thank you for choosing our services for your banking needs. We appreciate your business and look forward to serving
    you in the future.
    <p>

    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>
