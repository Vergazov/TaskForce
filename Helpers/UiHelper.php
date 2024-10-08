<?php

namespace app\Helpers;

use app\models\Task;
use app\models\User;
use AvailableActions\AvailableActions;
use AvailableActions\CancelAction;
use AvailableActions\CompleteAction;
use AvailableActions\DenyAction;
use AvailableActions\RespondAction;
use CustomExceptions\StatusActionException;
use yii\helpers\Html;
use yii\helpers\Url;

class UiHelper
{
    public static function showStarRating($value, $size = 'small', $starsCount = 5, $active = false)
    {
        $stars = '';

        for($i = 0; $i < $starsCount; $i++) {
            $className = $i <= $value ? 'fill-star' : '';
            $stars .= Html::tag('span', '&nbsp;', ['class' => $className]);
        }

        $className = 'stars-rating ' . $size;

        if($active) {
            $className .= ' active-stars';
        }

        $result = Html::tag('div', $stars, ['class' => $className]);

        return $result;
    }

    public static function getActionButtons(Task $task, User $user)
    {
        $buttons = [];

        $colorsMap = [
            CancelAction::getActionName() => 'orange',
            RespondAction::getActionName() => 'blue',
            DenyAction::getActionName() => 'pink',
            CompleteAction::getActionName() => 'yellow',
        ];

        $roleName = $user->is_performer ? AvailableActions::ROLE_PERFORMER : AvailableActions::ROLE_CLIENT;

        try {
            $availableActionsManager = new AvailableActions($task->status->slug, $task->author_id, $task->performer_id);
            $actions = $availableActionsManager->getAvailableActions($roleName, $user->id);

            foreach ($actions as $action) {
                $color = $colorsMap[$action::getActionName()];
                $label = $action::getPublicName();

                $options = [
                    'data-action' => $action::getActionName(),
                    'class' => 'button action-btn button--' . $color,
                ];

                if($action::getActionName() === 'act_cancel') {
                    $options['href'] = Url::toRoute(['task/cancel-task', 'id' => $task->id]);
                }

                $btn = Html::tag('a', $label, $options);

                $buttons[] = $btn;
            }
        }catch(StatusActionException $e){
            error_log($e->getMessage());
        }

        return $buttons;
    }
}