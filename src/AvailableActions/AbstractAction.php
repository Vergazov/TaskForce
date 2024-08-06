<?php

namespace AvailableActions;

abstract class AbstractAction
{
    abstract public static function getPublicName(): string;

    abstract public static function getActionName(): string;

    abstract public static function checkRights(int $userId, int $performerId, int $clientId): bool;
}