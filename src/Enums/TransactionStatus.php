<?php

namespace Kanexy\Banking\Enums;

use MyCLabs\Enum\Enum;

class TransactionStatus extends Enum
{
    public const PENDING = 'pending';
    public const PENDING_CONFIRMATION = 'pending-confirmation';
    public const DRAFT = 'draft';
    public const ACCEPTED = 'accepted';
    public const DECLINED = 'declined';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';
}
