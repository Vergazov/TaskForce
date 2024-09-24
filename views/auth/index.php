<?php
/** @var yii\web\View $this */
/** @var $user */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Вход';
?>
<div class="center-block">
    <div class="registration-form regular-form">
        <?php $form = ActiveForm::begin(['action' => '?r=auth/login']); ?>
        <h3 class="head-main head-task">Вход на сайт</h3>
        <?= $form->field($user, 'email')->label('Email') ?>
        <?= $form->field($user, 'password')->passwordInput()->label('Пароль') ?>
        <?= Html::submitInput('Войти', ['class' => 'button button--blue']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>