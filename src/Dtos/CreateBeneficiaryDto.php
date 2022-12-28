<?php

namespace Kanexy\Banking\Dtos;

class CreateBeneficiaryDto
{
    public string $holder_id;

    public string $bank_code;

    public string $bank_code_type;

    public string $bank_country;

    public string $bank_account_number;

    public ?string $beneficiary_first_name;

    public ?string $beneficiary_last_name;

    public ?string $beneficiary_email;

    public function __construct(string $holderId, array $data)
    {
        $this->holder_id = $holderId;
        $this->bank_code = $data['meta']['bank_code'];
        $this->bank_code_type = $data['meta']['bank_code_type'];
        $this->bank_country = 'GB'; // TODO: Make it dynamic
        $this->bank_account_number = $data['meta']['bank_account_number'];
        $this->beneficiary_first_name = $data['first_name'] ?? null;
        $this->beneficiary_last_name = $data['last_name'] ?? null;
        $this->beneficiary_email = $data['email'];
        $this->beneficiary_display_name = $data['display_name'];
    }

    public function toArray()
    {
        return [
            'holder_id' => $this->holder_id,
            'bank_code' => $this->bank_code,
            'bank_code_type' => $this->bank_code_type,
            'bank_country' => $this->bank_country,
            'bank_account_number' => $this->bank_account_number,
            'beneficiary_first_name' => $this->beneficiary_first_name,
            'beneficiary_last_name' => $this->beneficiary_last_name,
            'beneficiary_email' => $this->beneficiary_email,
            'beneficiary_display_name' => $this->beneficiary_display_name,
        ];
    }
}
