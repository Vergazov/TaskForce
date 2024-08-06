<?php

namespace AvailableActions;


class RespondAction extends AbstractAction
{
    public static function getPublicName(): string
    {
        return 'Откликнуться';
    }

    public static function getActionName(): string
    {
        return 'act-respond';
    }

    public static function checkRights(int $userId, int $performerId, int $clientId): bool
    {
        return $userId === $performerId;
    }
}