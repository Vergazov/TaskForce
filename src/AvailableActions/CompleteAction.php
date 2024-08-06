<?php

namespace AvailableActions;


class CompleteAction extends AbstractAction
{
    public static function getPublicName(): string
    {
        return 'Выполнено';
    }

    public static function getActionName(): string
    {
        return 'act_complete';
    }

    public static function checkRights(int $userId, int $performerId, int $clientId): bool
    {
        return $userId === $clientId;
    }
}