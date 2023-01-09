<?php

namespace Kanexy\Banking\Dtos;

use InvalidArgumentException;

class CreateAccountDto
{
    public string $holder_id;

    public string $asset_class;

    public string $asset_type;

    public string $partner_product;

    public array $primary_use_types;

    public string $t_and_cs_country_of_jurisdiction;

    public string $type;

    public string $who_owns_assets;

    public function __construct(string $holderId, array $data)
    {
        $this->holder_id = $holderId;
        $this->asset_class = $data['asset_class'];
        $this->asset_type = $data['asset_type'];

        $this->setFromAsset($this->asset_class, $this->asset_type);
    }

    public function toArray()
    {
        return [
            'holder_id' => $this->holder_id,
            'asset_class' => $this->asset_class,
            'asset_type' => $this->asset_type,
            'partner_product' => $this->partner_product,
            'primary_use_types' => $this->primary_use_types,
            't_and_cs_country_of_jurisdiction' => $this->t_and_cs_country_of_jurisdiction,
            'type' => $this->type,
            'who_owns_assets' => $this->who_owns_assets,
        ];
    }

    private function setFromAsset(string $assetClass, string $assetType)
    {
        if ($assetClass !== 'currency' || $assetType !== 'gbp') {
            throw new InvalidArgumentException('We only support [GBP] asset type in the instant.');
        }

        // TODO: Move these on wrappex.
        $this->primary_use_types = ["ledger-primary-use-types-deposit", "ledger-primary-use-types-payments"];
        $this->type = "ledger-type-single-user";
        $this->who_owns_assets = "ledger-assets-owned-by-me";
        $this->partner_product = "PayrNet-GBP-2";
        $this->t_and_cs_country_of_jurisdiction = "GB";
    }

}
