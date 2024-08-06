<?php

namespace AvailableActions;


class CancelAction extends AbstractAction
{

    public static function getPublicName(): string
    {
        return 'Отменить';
    }

    public static function getActionName(): string
    {
        return 'act_cancel';
    }

    public static function checkRights(int $userId, int $performerId, int $clientId): bool
    {
        return $userId === $clientId;
    }
}