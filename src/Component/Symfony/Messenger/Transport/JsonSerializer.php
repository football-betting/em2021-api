<?php declare(strict_types=1);

namespace App\Component\Symfony\Messenger\Transport;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;

class JsonSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        return new Envelope((object)$encodedEnvelope);
    }

    public function encode(Envelope $envelope): array
    {
        /** @var DataProviderInterface $message */
        $message = $envelope->getMessage();
        if (!$message instanceof DataProviderInterface) {
            throw new \RuntimeException('Message should be DataProviderInterface');
        }

        $eventMessage = [
            'data' => $message->toArray()
        ];

        $stamps = $envelope->all();
        foreach ($stamps as $stampList) {
            foreach ($stampList as $stamp) {
                if($stamp instanceof SentStamp) {
                    $alias = $stamp->getSenderAlias();
                    if ($alias !== null) {
                        $eventMessage['event'] = $alias;
                    }
                }
            }
        }

        return [
            'body' => json_encode($eventMessage),
        ];
    }

}
