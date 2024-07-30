<?php

namespace TaskService;
class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancel';
    const STATUS_IN_PROGRESS = 'in-progress';
    const STATUS_DONE = 'done';
    const STATUS_FAIL = 'fail';

    const ACTION_CANCEL = 'act_cancel';
    const ACTION_RESPOND = 'act_respond';
    const ACTION_DENY = 'act_deny';
    const ACTION_COMPLETE = 'act_complete';

    private ?int $performerId; // Исполнитель
    private int $clientId; // Заказчик

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
     * @return string[]
     */
    public function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_CANCEL => 'Отменено',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAIL => 'Провалено',
        ];
    }

    /**
     * @return string[]
     */
    public function getActionsMap(): array
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_COMPLETE => 'Выполнено',
            self::ACTION_DENY => 'Отказаться',
        ];
    }

    /**
     * @param $status
     * @return string|null
     */
    public function getNexStatus($status): ?string
    {
        $map = [
            self::ACTION_COMPLETE => self::STATUS_DONE,
            self::ACTION_CANCEL => self::STATUS_CANCEL,
            self::ACTION_DENY => self::STATUS_CANCEL,
        ];

        return $map[$status] ?? null;
    }

    /**
     * @param string $status
     * @return void
     */
    private function setStatus(string $status): void
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
     * Возвращает действия, доступные для указанного статуса
     * @param string $status
     * @return array
     */
    public function statusAllowedActions(string $status): array
    {
        $map = [
            self::STATUS_NEW => [self::STATUS_CANCEL, self::ACTION_RESPOND],
            self::STATUS_IN_PROGRESS => [self::ACTION_DENY, self::ACTION_COMPLETE],
        ];

        return $map[$status] ?? [];
    }
}