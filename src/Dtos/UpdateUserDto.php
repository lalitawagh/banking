<?php

namespace Kanexy\Banking\Dtos;

use Kanexy\Cms\Helper;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class UpdateUserDto
{
    public ?string $address_street;

    public string $address_iso_country;

    public string $date_of_birth;

    public string $ref_id;

    public string $email;

    public string $phone;

    public function __construct(Workspace $workspace) {
        /** @var User $user */
        $user = $workspace->users()->first();

        /** @var Address $address */
        $address = $user->addresses()->first();

        $this->address_street = $address->street ?? '';
        $this->address_iso_country = Helper::getCountryISOFromCountryName($user->country->name);
        $this->date_of_birth = $user->date_of_birth;
        $this->ref_id = $workspace->ref_id;
        $this->email = $workspace->email;
        $this->phone = $workspace->phone;
    }

    public function toArray()
    {
        return [
            'address_street' => $this->address_street,
            'address_iso_country' => $this->address_iso_country,
            'date_of_birth' => $this->date_of_birth,
            'ref_id' => $this->ref_id,
            'email' => $this->email,
            'phone' => $this->phone
        ];
    }
}
