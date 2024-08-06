<?php

namespace AvailableActions;


use CustomExceptions\AvailableActionsException;

class AvailableActions
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancel';
    const STATUS_IN_PROGRESS = 'in-progress';
    const STATUS_DONE = 'done';
    const STATUS_FAIL = 'fail';

    const ROLE_PERFORMER = 'performer';
    const ROLE_CLIENT = 'customer';

    private ?int $performerId;
    private int $clientId;
    private string $status;

    /**
     * @param string $status
     * @param int $clientId
     * @param int|null $performerId
     */
    public function __construct(string $status, int $clientId, ?int $performerId = null)
    {
        $this->setStatus($status);

        $this->performerId = $performerId;
        $this->clientId = $clientId;
    }

    /**
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $availableStatuses = [
            self::STATUS_NEW,
            self::STATUS_CANCEL,
            self::STATUS_IN_PROGRESS,
            self::STATUS_DONE,
            self::STATUS_FAIL,
        ];

        if (in_array($status, $availableStatuses)) {
            $this->status = $status;
        }
    }

    /**
     * Возвращает доступные действия с учетом статуса и роли текущего пользователя
     * @param string $role
     * @param int $id
     * @return array
     */
    public function getAvailableActions(string $role, int $id): array
    {
        $statusActions = $this->statusAllowedActions()[$this->status];
        $roleActions = $this->roleAllowedActions()[$role];

        $allowedActions = array_intersect($statusActions, $roleActions);

        $allowedActions = array_filter($allowedActions,function ($action) use ($id){
            return $action::checkRights($id,$this->performerId,$this->clientId);
        });

        return array_values($allowedActions);
    }

    /**
     * Возвращает действия доступные для каждой роли
     * @return array
     */
    private function roleAllowedActions():array
    {
        $map = [
            self::ROLE_CLIENT => [CancelAction::class, CompleteAction::class],
            self::ROLE_PERFORMER => [RespondAction::class, DenyAction::class]
        ];

        return $map;
    }

    /**
     * Возвращает действия, доступные для каждого статуса
     * @return array
     */
    private function statusAllowedActions(): array
    {
        $map = [
            self::STATUS_CANCEL => [],
            self::STATUS_DONE => [],
            self::STATUS_NEW => [CancelAction::class,RespondAction::class],
            self::STATUS_IN_PROGRESS => [DenyAction::class,CompleteAction::class],
            self::STATUS_FAIL => [],
        ];

        return $map;
    }

}