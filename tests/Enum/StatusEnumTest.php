<?php

namespace App\Tests\Enum;

use App\Enum\ProjectStatusEnum;
use App\Enum\StatusEnum;
use PHPUnit\Framework\TestCase;

final class StatusEnumTest extends TestCase
{
    public function testEveryTaskStatusHasLabelAndColor(): void
    {
        foreach (StatusEnum::cases() as $status) {
            self::assertNotSame('', $status->label());
            self::assertNotSame('', $status->colorClass());
        }
    }

    public function testEveryProjectStatusHasLabelAndColor(): void
    {
        foreach (ProjectStatusEnum::cases() as $status) {
            self::assertNotSame('', $status->label());
            self::assertNotSame('', $status->colorClass());
        }
    }

    public function testUnknownTaskStatusIsRejected(): void
    {
        self::assertNull(StatusEnum::tryFrom('deleted'));
    }

    public function testUnknownProjectStatusIsRejected(): void
    {
        self::assertNull(ProjectStatusEnum::tryFrom('En cours'));
    }

    public function testStoredValuesFitTheColumnLength(): void
    {
        foreach ([...StatusEnum::cases(), ...ProjectStatusEnum::cases()] as $status) {
            self::assertLessThanOrEqual(32, strlen($status->value));
        }
    }
}
