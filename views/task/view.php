<?php
/** @var yii\web\View $this */

/** @var  $task */
/** @var  $feedback */

/** @var  $response */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Просмотр задания';
$user = Yii::$app->user->identity;

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
            <p class="map-address town"><?= ($task->city->name) ?></p>
            <p class="map-address"><?= ($task->location) ?></p>
        </div>
    <?php endif ?>

    <h4 class="head-regular">Отклики на задание</h4>

    <?php foreach ($task->getResponsesQuery($user)->all() as $response): ?>

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
            <?php if ($user->id === $task->author_id && $task->isInProgress()) : ?>
                <div class="button-popup">
                    <a href="<?= Url::toRoute(['task/accept-performer', 'performerId' => $response->user_id, 'taskId' => $task->id]) ?>"
                       class="button button--blue button--small">Принять</a>
                    <a href="<?= Url::to(['task/deny_performer', 'id' => $response->id]) ?>"
                       class="button button--orange button--small">Отказать</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>


    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= $task->category->name ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= Yii::$app->formatter->asRelativeTime($task->dt_add, '+3 hours') ?></dd>
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
                        <a href=".<?= $file->path ?>"
                           class="link link--block link--clip"><?= $file->name ?></a>
                        <p class="file-size"><?= $file->size ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>


    <section class="pop-up pop-up--refusal pop-up--close">
        <div class="pop-up--wrapper">
            <h4>Отказ от задания</h4>
            <p class="pop-up-text">
                <b>Внимание!</b><br>
                Вы собираетесь отказаться от выполнения этого задания.<br>
                Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
            </p>
            <a href="<?= Url::toRoute('task/deny-task') ?>" class="button button--pop-up button--orange">Отказаться</a>
            <div class="button-container">
                <button class="button--close" type="button">Закрыть окно</button>
            </div>
        </div>
    </section>

    <section class="pop-up pop-up--completion pop-up--close">
        <div class="pop-up--wrapper">
            <h4>Завершение задания</h4>
            <p class="pop-up-text">
                Вы собираетесь отметить это задание как выполненное.
                Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
            </p>

            <div class="completion-form pop-up--form regular-form">
                <?php $form = ActiveForm::begin(['action' => '?r=task/complete-task']) ?>

                <?= $form
                    ->field($feedback, 'comment')
                    ->textarea(['id' => 'completion-comment', 'rows' => '3'])
                    ->label('Ваш комментарий', ['for' => 'completion-comment', 'class' => 'control-label'])
                ?>

                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars">
                    <span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
                </div>

                <?= Html::submitInput('Завершить', ['class' => 'button button--pop-up button--blue']) ?>

                <?php ActiveForm::end() ?>
            </div>

            <div class="button-container">
                <button class="button--close" type="button">Закрыть окно</button>
            </div>
        </div>
    </section>

    <section class="pop-up pop-up--act_response pop-up--close">
        <div class="pop-up--wrapper">
            <h4>Добавление отклика к заданию</h4>
            <p class="pop-up-text">
                Вы собираетесь оставить свой отклик к этому заданию.
                Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
            </p>

            <div class="addition-form pop-up--form regular-form">
                <?php $form = ActiveForm::begin(['action' => '?r=task/response-task']) ?>

                <?= $form
                    ->field($response, 'comment')
                    ->textarea(['id' => 'addition-comment', 'rows' => '3'])
                    ->label('Ваш комментарий', ['for' => 'addition-comment', 'class' => 'control-label'])
                ?>

                <?= $form
                    ->field($response, 'price')
                    ->textInput(['id' => 'addition-price'])
                    ->label('Стоимость', ['for' => 'addition-price', 'class' => 'control-label'])
                ?>

                <?= Html::submitInput('Завершить', ['class' => 'button button--pop-up button--blue']) ?>

                <?php ActiveForm::end() ?>
            </div>
            <div class="button-container">
                <button class="button--close" type="button">Закрыть окно</button>
            </div>
        </div>
    </section>

<?php $this->registerJsFile('@web/js/main.js'); ?>