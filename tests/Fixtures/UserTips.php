<?php declare(strict_types=1);

namespace App\Tests\Fixtures;

class UserTips
{
    public const USER = 'ninja';

    public const DATA = [
        'name' => self::USER,
        'position' => 1,
        'scoreSum' => 24,
        'tips' => [
            0 => [
                'matchId' => '2000-06-16:2100:FR-DE',
                'matchDatetime' => '2000-06-16 21:00',
                'tipTeam1' => 2,
                'tipTeam2' => 3,
                'scoreTeam1' => 1,
                'scoreTeam2' => 4,
                'team1' => 'FR',
                'team2' => 'DE',
                'score' => 2,
            ],
            1 => [
                'matchId' => ':IT-SP',
                'matchDatetime' => '-1 minute',
                'tipTeam1' => 1,
                'tipTeam2' => 0,
                'scoreTeam1' => 1,
                'scoreTeam2' => 4,
                'team1' => 'IT',
                'team2' => 'SP',
                'score' => 0,
            ],
            2 => [
                'matchId' => ':PR-AU',
                'matchDatetime' => 'now',
                'tipTeam1' => 2,
                'tipTeam2' => 3,
                'team1' => 'PR',
                'team2' => 'AU',
            ],
            3 => [
                'matchId' => ':CZ-NL',
                'matchDatetime' => '+ 1 minute',
                'tipTeam1' => 4,
                'tipTeam2' => 5,
                'team1' => 'CZ',
                'team2' => 'NL',
            ],
            4 => [
                'matchId' => '2999-06-20:1800:RU-EN',
                'matchDatetime' => '2999-06-20 18:00',
                'tipTeam1' => 4,
                'tipTeam2' => 2,
                'team1' => 'RU',
                'team2' => 'EN',
            ],
        ],
    ];

    public array $expectedDate = [];

    public function getDummyData(): array
    {
        $data = self::DATA;

        $keys = [1, 2, 3];
        foreach ($keys as $key) {
            $dataTime = new \DateTime($data['tips'][$key]['matchDatetime']);
            $data['tips'][$key]['matchId'] = $dataTime->format('Y-m-d:Hi') . $data['tips'][$key]['matchId'];
            $data['tips'][$key]['matchDatetime'] = $dataTime->format('Y-m-d H:i');

            $this->expectedDate[$key] = [
                'matchId' => $data['tips'][$key]['matchId'],
                'matchDatetime' => $data['tips'][$key]['matchDatetime'],
            ];
        }
        return $data;
    }
}
