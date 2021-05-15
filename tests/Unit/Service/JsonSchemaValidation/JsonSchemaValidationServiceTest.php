<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\JsonSchemaValidation;

use App\Service\JsonSchemaValidation\JsonSchemaValidationService;
use PHPStan\Testing\TestCase;

class JsonSchemaValidationServiceTest extends TestCase
{
    public function testSuccessJson(): void
    {
        $validation = new JsonSchemaValidationService();

        $json = json_encode([
            "matchId" => "2021-06-15:2100:DE-FR",
            "tipDatetime" => "2020-05-30 14:36",
            "tipTeam1" => 2,
            "tipTeam2" => 3,
        ], JSON_THROW_ON_ERROR);

        $errors = $validation->getErrors($json, 'tip');

        self::assertCount(0, $errors);
    }

    public function testNoExistingJsonSchema(): void
    {
        $validation = new JsonSchemaValidationService();

        $this->expectException(\RuntimeException::class);

        $validation->getErrors('', 'no_exist');
    }

    /**
     * @dataProvider schemaData
     */
    public function testErrorJson(array $data, array $expectedError): void
    {
        $validation = new JsonSchemaValidationService();

        $json = json_encode($data, JSON_THROW_ON_ERROR);

        $errors = $validation->getErrors($json, 'tip');

        self::assertCount(count($expectedError), $errors);

        foreach ($expectedError as $key => $expectedErrorMessage) {
            self::assertSame($expectedErrorMessage, $errors[$key]);
        }
    }

    public function schemaData()
    {
        return [
            [
                'data' => [
                    "matchId" => "2021-06-15:2100:DE-FR",
                    "tipDatetime" => "2020-05-30 14:36",
                    "tipTeam2" => 3,
                ],
                'errors' => [
                    'The required properties (tipTeam1) are missing'
                ],
            ],
            [
                'data' => [
                    "matchId" => "2021-06-15:2100:DE-FR",
                    "tipDatetime" => "2020-05-30 14:36",
                    "tipTeam1" => 3,
                ],
                'errors' => [
                    'The required properties (tipTeam2) are missing'
                ],
            ],
            [
                'data' => [
                    "matchId" => "2021-06-15:2100:DE-FR",
                    "tipDatetime" => "2020-05-30 14:36",
                ],
                'errors' => [
                    'The required properties (tipTeam1) are missing'
                ],
            ],
            [
                'data' => [
                    "matchId" => "2021-06-15:2100:DE-FR",
                    "tipTeam1" => 3,
                ],
                'errors' => [
                    'The required properties (tipDatetime) are missing'
                ],
            ],
            [
                'data' => [
                    "tipTeam1" => 3,
                ],
                'errors' => [
                    'The required properties (matchId) are missing'
                ],
            ],
            [
                'data' => [
                    "matchId" => "20210615:2100:DE-FR",
                    "tipDatetime" => "2020-05-30 14:36",
                    "tipTeam1" => 2,
                    "tipTeam2" => 3,
                ],
                'errors' => [
                    'The string should match pattern: ^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]):(0[0-9]|1[0-9]|2[0-3])[0-5][0-9]:\w{2}-\w{2}$'
                ]
            ],
            [
                'data' => [
                    "matchId" => "2021-06-15:2100:DE-FR",
                    "tipDatetime" => "202005-30 14:36",
                    "tipTeam1" => 2,
                    "tipTeam2" => 3,
                ],
                'errors' => [
                    'The string should match pattern: ^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$'
                ]
            ]
        ];
    }
}
