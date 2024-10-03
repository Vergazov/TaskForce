<?php
/** @var yii\web\View $this */
/** @var $categories */
/** @var $taskFile */

/** @var $task */

/** @var $errors */

use app\assets\DropzoneAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Новое задание';

DropzoneAsset::register($this);
Yii::$app->session->set('task_id', $task->id);
dump(Yii::$app->session->get('task_id'));
?>

<div class="add-task-form regular-form">
    <?= dump($errors ?? '') ?>

    <?php $form = ActiveForm::begin(['action' => '?r=task/store']) ?>

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

    <p class="form-label">Файлы</p>
    <div class="new-file">
        Добавить новый файл
    </div>

    <div class="files-previews"></div>

    <!--    --><?php //= $form
    //        ->field($taskFile, 'path[]')
    //        ->fileInput(['class' => 'new-file', 'id' => 'new-file', 'multiple' => true])
    //        ->label('Файлы', ['for' => 'new-file', 'class' => 'form-label'])
    //    ?>

    <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']) ?>

    <?php ActiveForm::end() ?>
</div>

<?php
$uploadUrl = Url::toRoute(['task/upload']);
$this->registerJs(<<<JS
    var myDropzone = new Dropzone(".new-file", 
    {
        maxFiles: 4,
        url: "$uploadUrl",
        previewsContainer: ".files-previews",
        sending: function(none,xhr,formData) {
            formData.append('_csrf', $('input[name=_csrf]').val());
        }
    });
JS
);
?>
