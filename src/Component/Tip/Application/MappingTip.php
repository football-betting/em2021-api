<?php declare(strict_types=1);

namespace App\Component\Tip\Application;

use App\DataTransferObject\TipEventDataProvider;
use Symfony\Component\Security\Core\User\UserInterface;

final class MappingTip
{
    /**
     * @param string $content
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \App\DataTransferObject\TipEventDataProvider
     */
    public function map(string $content, UserInterface $user): TipEventDataProvider
    {
        $tipInfo = (array)json_decode($content, true);

        $tipEventDataProvider = new TipEventDataProvider();
        $tipEventDataProvider->fromArray($tipInfo);

        $tipEventDataProvider->setUser($user->getUsername());

        return $tipEventDataProvider;
    }
}
