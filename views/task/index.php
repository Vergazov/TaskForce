<?php
/** @var yii\web\View $this */

/** @var  $tasks */
/** @var  $task */
/** @var  $categories */

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Просмотр новых заданий';
?>
<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?php foreach ($tasks as $task): ?>
            <div class="task-card">
                <div class="header-task">
                    <a href="<?=Url::to(['task/view','id' => $task->id])?>" class="link link--block link--big"><?= Html::encode($task->name) ?></a>
                    <p class="price price--task"><?= $task->budget ?></p>
                </div>
                <p class="info-text"><span
                            class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->dt_add) ?> </span>назад
                </p>
                <p class="task-text"><?= Html::encode(BaseStringHelper::truncate($task->description, 200)) ?>
                </p>
                <div class="footer-task">
                    <?php if ($task->location): ?>
                        <p class="info-text town-text"><?= $task->location ?></p>
                    <?php endif; ?>
                    <p class="info-text category-text"><?= $task->category->name ?></p>
                    <a href="<?=Url::to(['task/view','id' => $task->id])?>" class="button button--black">Смотреть Задание</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="pagination-wrapper">
            <ul class="pagination-list">
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">1</a>
                </li>
                <li class="pagination-item pagination-item--active">
                    <a href="#" class="link link--page">2</a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">3</a>
                </li>
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <?php $form = ActiveForm::begin(); ?>
                <h4 class="head-card">Категории</h4>
                <div class="checkbox-wrapper">
                    <?= Html::activeCheckboxList($task, 'category_id', ArrayHelper::map($categories, 'id', 'name'),
                        ['tag' => null, 'itemOptions' => ['labelOptions' => ['class' => 'control-label']]]) ?>
                </div>
                <h4 class="head-card">Дополнительно</h4>
                <div class="checkbox-wrapper">
                    <?= $form->field($task, 'noResponses')->checkbox(['labelOptions' => ['class' => 'control-label']]) ?>
                </div>
                <div class="checkbox-wrapper">
                    <?= $form->field($task, 'noLocation')->checkbox(['labelOptions' => ['class' => 'control-label']]) ?>

                    <h4 class="head-card">Период</h4>
                    <?= $form->field($task, 'filterPeriod', ['template' => '{input}'])
                        ->dropDownList(
                            [
                                '3600' => 'За последний час',
                                '86400' => 'За сутки',
                                '604800' => 'За неделю'],
                            [
                                'prompt' => 'Выбрать'
                            ]
                        ) ?>
                </div>
                <input type="submit" class="button button--blue" value="Искать">
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</main>