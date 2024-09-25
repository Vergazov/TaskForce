<?php
/** @var yii\web\View $this */
/** @var $categories */
/** @var $taskFile */

/** @var $task */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Новое задание';
?>

<div class="add-task-form regular-form">

    <?php $form = ActiveForm::begin() ?>

    <h3 class="head-main head-main">Публикация нового задания</h3>

    <?= $form
        ->field($task, 'name')
        ->input('text', ['id' => 'essence-work'])
        ->label('Опишите суть работы', ['for' => 'essence-work'])
    ?>
    <?= $form
        ->field($task, 'description')
        ->textarea(['id' => 'description', 'rows' => '4'])
        ->label('Подробности задания', ['for' => 'description'])
    ?>
    <?= $form
        ->field($task, 'category_id')
        ->dropDownList(ArrayHelper::map($categories, 'id', 'name'), ['class' => 'control-label', 'id' => 'town-user'])
        ->label('Категория', ['for' => 'town-user'])
    ?>
    <?= $form
        ->field($task, 'location')
        ->input('text', ['class' => 'control-label', 'id' => 'location'])
        ->label('Локация', ['for' => 'location'])
    ?>

    <div class="half-wrapper">
        <?= $form
            ->field($task, 'budget')
            ->input('text', ['class' => 'control-label', 'id' => 'budget'])
            ->label('Бюджет', ['for' => 'budget'])
        ?>
        <?= $form
            ->field($task, 'expire_dt')
            ->input('date', ['class' => 'control-label', 'id' => 'period-execution'])
            ->label('Срок исполнения', ['for' => 'period-execution'])
        ?>
    </div>

    <?= $form
        ->field($taskFile, 'task_id')
        ->fileInput(['class' => 'new-file', 'id' => 'new-file'])
        ->label('Файлы', ['for' => 'new-file', 'class' => 'form-label'])
    ?>

    <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']) ?>

    <?php ActiveForm::end() ?>
</div>
