<?php
/** @var yii\web\View $this */
/** @var $user */
/** @var $errors */

/** @var $cities */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';

?>

<div class="center-block">
    <div class="registration-form regular-form">
        <?php $form = ActiveForm::begin(['action' => '?r=registration/store']); ?>

        <h3 class="head-main head-task">Регистрация нового пользователя</h3>
        <?= $form->field($user, 'name')->label('Ваше имя') ?>

        <div class="half-wrapper">
            <?= $form->field($user, 'email')->label('Email') ?>
            <?= $form->field($user, 'city_id')->dropDownList(ArrayHelper::map($cities, 'id', 'name')) ?>
        </div>

        <div class="half-wrapper">
            <?= $form->field($user, 'password')->passwordInput()->label('Пароль') ?>
        </div>

        <div class="half-wrapper">
            <?= $form
                ->field($user, 'password')
                ->passwordInput(['id' => 'user-password-repeat', 'name' => 'User[passwordRepeat]'])
                ->label('Повтор пароля', ['for' => 'user-password-repeat'])
            ?>
        </div>
        <?=
        $form
            ->field($user, 'role_id')
            ->checkbox([
                'label' => 'Я собираюсь откликаться на заказы',
                'labelOptions' => ['class' => 'control-label checkbox-label']
            ])
        ?>

        <?= Html::submitInput('Создать аккаунт', ['class' => 'button button--blue']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>


