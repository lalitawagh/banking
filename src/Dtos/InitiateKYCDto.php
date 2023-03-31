<?php

namespace Kanexy\Banking\Dtos;

use Kanexy\PartnerFoundation\Core\Enums\KycIdvStatus;
use Kanexy\PartnerFoundation\Core\Enums\KycScreeningStatus;
use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class InitiateKYCDto
{
    public string $screening_created_at = '';

    public string $screening_source;

    public string $screening_status = '';

    public string $screening_ref;

    public array $screening_meta = [];

    public string $idv_created_at = '';

    public string $idv_status = '';

    public string $idv_source;

    public string $idv_ref;

    public array $idv_meta = [];

    public string $kyc_partner_product;

    public function __construct(Workspace $workspace, $yotiLogValue)
    {
        $screening_result = self::getKycDetails('WATCHLIST_SCREENING', $yotiLogValue['check_result']);
        $idv_result = self::getKycDetails('ID_DOCUMENT_AUTHENTICITY', $yotiLogValue['check_result']);

        $this->screening_source = $yotiLogValue['screening_source'];
        $this->screening_ref = $yotiLogValue['screening_source'];

        if(! empty($screening_result)){
            $this->screening_status = ($screening_result['recommdation']['value'] == 'APPROVE') ? KycScreeningStatus::APPROVE : KycScreeningStatus::DECLINE;
            $this->screening_meta = ($screening_result['breakdown'] == []) ? ['screening_meta' => 'true'] : $screening_result['breakdown'];
            $this->screening_created_at = $screening_result['created'];
        }

        $this->idv_source = config('partner-foundation.kyc_source');
        $this->idv_ref = config('partner-foundation.kyc_source');

        if(! empty($idv_result)){
            $this->idv_status = ($idv_result['recommdation']['value'] == 'APPROVE') ? KycIdvStatus::APPROVE : KycIdvStatus::DECLINE;;
            $this->idv_meta = ($idv_result['breakdown'] == []) ? ['idv_meta' => 'true'] : $idv_result['breakdown'];
            $this->idv_created_at = $idv_result['created'];
        }

        $this->kyc_partner_product = config('partner-foundation.kyc_partner_product');
        $this->ref_id = $workspace->ref_id;
    }

    public function toArray(): array
    {
        return [
            "screening_created_at" => $this->screening_created_at,
            "screening_source" => $this->screening_source,
            "screening_status" => $this->screening_status,
            "screening_ref" => $this->screening_ref,
            "screening_meta" => $this->screening_meta,
            "idv_created_at" => $this->idv_created_at,
            "idv_status" => $this->idv_status,
            "idv_source" => $this->idv_source,
            "idv_ref" => $this->idv_ref,
            "idv_meta" => $this->idv_meta,
            "kyc_partner_product" => $this->kyc_partner_product,
            "ref_id" => $this->ref_id,
        ];
    }

    public static function getKycDetails($type, $checkResult)
    {
        $result = array_search($type, array_column($checkResult, 'type'));
        return is_numeric($result) ? $checkResult[$result] : [];
    }
}
