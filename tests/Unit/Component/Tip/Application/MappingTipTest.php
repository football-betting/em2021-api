<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Tip\Application;

use App\Component\Tip\Application\MappingTip;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class MappingTipTest extends TestCase
{
    public function testSuccess()
    {
        $mapping = new MappingTip();

        $expectedData = [
            "matchId" => "2021-06-15:2100:DE-FR",
            "tipTeam1" => 2,
            "tipTeam2" => 3,
        ];
        $user = new User();
        $user->setUsername('JohnDoe');

        $tipDataProvider = $mapping->map(
            json_encode($expectedData, JSON_THROW_ON_ERROR),
            $user
        );

        self::assertSame($user->getUsername(), $tipDataProvider->getUser());
        self::assertSame($expectedData['matchId'], $tipDataProvider->getMatchId());
        self::assertFalse($tipDataProvider->hasTipDatetime());
        self::assertSame($expectedData['tipTeam1'], $tipDataProvider->getTipTeam1());
        self::assertSame($expectedData['tipTeam2'], $tipDataProvider->getTipTeam2());
    }

    public function testMapForMutataion()
    {
        $mapping = new MappingTip();

        $user = new User();
        $user->setUsername('JohnDoe');

        $tipDataProvider = $mapping->map('', $user);

        self::assertSame($user->getUsername(), $tipDataProvider->getUser());
        self::assertFalse($tipDataProvider->hasMatchId());
        self::assertFalse($tipDataProvider->hasTipDatetime());
        self::assertFalse($tipDataProvider->hasTipTeam1());
        self::assertFalse($tipDataProvider->hasTipTeam2());

    }

}
