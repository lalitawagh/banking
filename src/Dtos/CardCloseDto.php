<?php

namespace Kanexy\Banking\Dtos;


class CardCloseDto
{
    public string $close_reason;

    public function __construct(array $data)
    {
        $this->close_reason = $data['close_reason'];
    }

    public function toArray()
    {
        return [
            'close_reason' => $this->close_reason,
        ];
    }
}
