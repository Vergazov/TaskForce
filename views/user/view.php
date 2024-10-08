<?php
/** @var yii\web\View $this */
/** @var  $user */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Профиль пользователя';

?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main"><?= Html::encode($user->name)?></h3>
        <div class="user-card">
            <div class="photo-rate">
                <img class="card-photo" src="img/man-glasses.png" width="191" height="190" alt="Фото пользователя">
                <div class="card-rate">
                    <div class="stars-rating big"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                    <span class="current-rate">4.23</span>
                </div>
            </div>
            <p class="user-description">
                <?= Html::encode($user->info)?>
            </p>
        </div>
        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <ul class="special-list">
                    <?php foreach($user->specializations as $specialization): ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular"><?=$specialization->name ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info"><span class="country-info">Россия</span>, <span class="town-info">Петербург</span>, <span class="age-info">30</span> лет</p>
            </div>
        </div>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($user->feedback as $feedback): ?>
        <div class="response-card">
            <img class="customer-photo" src="img/man-coat.png" width="120" height="127" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <p class="feedback"><?=Html::encode($feedback->comment)?></p>
                <p class="task">Задание «<a href="<?=Url::to(['task/view','id' => $feedback->task->id])?>" class="link link--small"><?=$feedback->task->name?></a>» <?=$feedback->task->status->name?></p>
            </div>
            <div class="feedback-wrapper">
                <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($feedback->dt_add,) ?> </span></p>
            </div>
        </div>
        <?php endforeach; ?>

    <div class="right-column">
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                <dt>Всего заказов</dt>
                <dd><?=$user->getCompletedTasks($user->id)?>, <?=$user->failed_tasks?> провалено</dd>
                <dt>Место в рейтинге</dt>
                <dd>25 место</dd>
                <dt>Дата регистрации</dt>
                <dd><?= Yii::$app->formatter->asDateTime($user->created_at) ?></dd>
                <dt>Статус</dt>
                <dd><?=$user->status->name ?? ''?></dd>
            </dl>
        </div>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--phone"><?=$user->phone?></a>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--email"><?=$user->email?></a>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--tg"><?=$user->telegram?></a>
                </li>
            </ul>
        </div>
    </div>
</main>
