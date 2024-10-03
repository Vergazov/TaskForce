<?php
/** @var yii\web\View $this */

/** @var  $task */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Просмотр задания';

?>

<div class="add-task-form regular-form">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->name) ?></a></h3>
        <p class="price price--big"><?= Html::encode($task->budget) ?></p>
    </div>
    <p class="task-description">
        <?= Html::encode($task->description) ?></a></p>
    <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
    <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
    <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
    <?php if ($task->location): ?>
        <div class="task-map">
            <img class="map" src="img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
            <p class="map-address town"><?= ($task->city) ?></p>
            <p class="map-address"><?= ($task->location) ?></p>
        </div>
    <?php endif ?>

    <h4 class="head-regular">Отклики на задание</h4>

    <?php
    $user = Yii::$app->user->identity;
    $responseByPerformer = $user->getResponseByPerformer($task->id);
    ?>

    <?php if ($task->getIsAuthor($user->id) || $responseByPerformer) : ?>

        <?php foreach ($task->responses as $response): ?>

            <?php if ($response->user_id === $user->id || $task->getIsAuthor($user->id)): ?>

                <div class="response-card">
                    <img class="customer-photo" src="" width="146" height="156" alt="Фото заказчиков">
                    <div class="feedback-wrapper">
                        <a href="<?= Url::to(['user/view', 'id' => $response->user_id]) ?>"
                           class="link link--block link--big"><?= Html::encode($response->user->name) ?></a>
                        <div class="response-wrapper">
                            <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span
                                        class="fill-star">&nbsp;</span><span
                                        class="fill-star">&nbsp;</span><span
                                        class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                            <p class="reviews"><?= $response->user->getFeedbackCount($response->user->id) ?></p>
                        </div>
                        <p class="response-message">
                            <?= Html::encode($response->comment) ?>
                        </p>
                    </div>
                    <div class="feedback-wrapper">
                        <p class="info-text"><span
                                    class="current-time"><?= Yii::$app->formatter->asRelativeTime($response->dt_add) ?></span>
                        </p>
                        <p class="price price--small"><?= Html::encode($response->price) ?></p>
                    </div>
                    <?php if ($task->getIsAuthor($user->id) ): ?>
                    <?php if(($task->status_id !== 3)): ?>
                        <div class="button-popup">
                            <a href="<?= Url::toRoute(['task/accept-performer', 'performerId' => $response->user_id, 'taskId' => $task->id]) ?>"
                               class="button button--blue button--small">Принять</a>
                            <a href="<?= Url::to(['task/deny_performer', 'id' => $response->id]) ?>"
                               class="button button--orange button--small">Отказать</a>
                        </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        <?php endforeach; ?>

    <?php endif; ?>

    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= $task->category->name ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= Yii::$app->formatter->asRelativeTime($task->dt_add) ?></dd>
                <dt>Срок выполнения</dt>
                <dd><?= Yii::$app->formatter->asDate($task->expire_dt, 'long') ?></dd>
                <dt>Статус</dt>
                <dd><?= $task->status->name ?></dd>
            </dl>
        </div>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">
                <?php foreach ($task->taskFiles as $file): ?>
                    <li class="enumeration-item">
                        <a href="uploads/<?= $file->name ?>"
                           class="link link--block link--clip"><?= $file->name ?></a>
                        <p class="file-size">356 Кб</p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
