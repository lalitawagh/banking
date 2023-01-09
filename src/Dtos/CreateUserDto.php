<?php

namespace Kanexy\Banking\Dtos;

use Kanexy\Cms\Helper;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class CreateUserDto
{
    public string $first_name;

    public ?string $middle_name;

    public string $last_name;

    public string $name;

    public string $type;

    public string $email;

    public string $address_city;

    public string $address_postal_code;

    public string $address_street;

    public string $address_iso_country;

    public string $country;

    public string $country_code;

    public string $phone;

    public string $date_of_birth;

    public function __construct(Workspace $workspace) {
        /** @var User $user */
        $user = $workspace->users()->first();

        /** @var Address $address */
        $address = $user->addresses()->first();

        $this->first_name = $user->first_name;
        $this->middle_name = $user->middle_name;
        $this->last_name = $user->last_name;
        $this->name = $workspace->name;
        $this->type = $this->getUserType($workspace);
        $this->email = $workspace->email;
        $this->address_city = $address->city;
        $this->address_postal_code = $address->postcode;
        $this->address_street = $address->street;
        $this->address_iso_country = Helper::getCountryISOFromCountryName($user->country->name);
        $this->country = $user->country->name;
        $this->country_code = $user->country_code;
        $this->phone = $workspace->phone;
        $this->date_of_birth = $user->date_of_birth;
    }

    public function toArray()
    {
        return [
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'type' => $this->type,
            'email' => $this->email,
            'address_city' => $this->address_city,
            'address_postal_code' => $this->address_postal_code,
            'address_street' => $this->address_street,
            'address_iso_country' => $this->address_iso_country,
            'country' => $this->country,
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
        ];
    }

    private function getUserType(Workspace $workspace)
    {
        return $workspace->type === 'individual' ? 'person' : 'company';
    }

}
