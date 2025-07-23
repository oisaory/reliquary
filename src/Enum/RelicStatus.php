<?php

namespace App\Enum;

enum RelicStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'Pending Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

    public function getTitleTransKey(): string
    {
        return match($this) {
            self::PENDING => 'relic.status.pending',
            self::APPROVED => 'relic.status.approved',
            self::REJECTED => 'relic.status.rejected',
        };
    }
}