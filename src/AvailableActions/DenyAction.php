<?php

namespace AvailableActions;


class DenyAction extends AbstractAction
{
    public static function getPublicName(): string
    {
        return 'Отказаться';
    }

    public static function getActionName(): string
    {
        return 'act_deny';
    }

    public static function checkRights(int $userId, int $performerId, int $clientId): bool
    {
        return $userId === $performerId;
    }
}