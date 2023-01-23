<?php

namespace Kanexy\Banking\Dtos;

use Kanexy\PartnerFoundation\Workspace\Models\Workspace;

class UploadKYCDto
{
    public $document;

    public string $type;

    public string $subtypes;

    public ?string $document_issuer;

    public string $document_number;

    public function __construct(array $data, Workspace $workspace)
    {
        $this->document = $data['document'];
        $this->type = $data['type'];
        $this->subtypes = $data['subtypes'];
        $this->document_issuer = $data['document_issuer'] ?? null;
        $this->document_number = $data['document_number'];
        $this->ref_id = $workspace->ref_id;
    }

    public function toArray(): array
    {
        return [
            "document" => $this->document,
            "type" => $this->type,
            "subtypes" => $this->subtypes,
            "document_issuer" => $this->document_issuer,
            "document_number" => $this->document_number,
            "ref_id" => $this->ref_id,
        ];
    }
}
