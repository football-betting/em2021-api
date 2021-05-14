<?php declare(strict_types=1);

namespace App\Tests\Acceptance\Component\Tip\Adapter;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Component\Tip\Adapter\TipController
 */
class TipControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

//        static::$kernel->registerContainerConfiguration();
    }

    public function testSendTip()
    {
        $filtersInfo = [
            'filters' => [[
                'ident' => 'tintName',
                'options' => [
                    [
                        'value' => 'Braun',
                        'checked' => true,
                    ],
                ],
            ]],
        ];

        $this->client->request(
            'POST',
            '/tip/send',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($filtersInfo)
        );

        self::assertResponseStatusCodeSame(200);

        $response = $this->client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $contents = json_decode($response->getContent(), true);

    }
}
