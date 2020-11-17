<?php

use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')
        ->hint('Если вы оставите это поле пустым, слаг будет создан автоматически')
        ->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_description')->widget(vova07\imperavi\Widget::class, [
        'settings' => [
            'minHeight' => 200,
        ],
    ]) ?>

    <?= $form->field($model, 'date__event_at_formatted')->widget(
        DatePicker::class,
        [
//            'type' => DatePicker::TYPE_INLINE,
            'pluginOptions' => [
                    'todayHighlight' => true,
                     'format' => 'dd.mm.yyyy'
            ],
        ]
    )->label('Дата та час проведення') ?>

    <?= $form->field($model, 'writers_arr')->widget(Select2::className(), [
        'data' => \common\models\Writer::getMapFullName(),
        'options' => ['placeholder' => 'Выбери авторов ...', 'multiple' => true],
    ])->label('Автора') ?>

    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'maxFileSize' => 2000000, // 2 MiB,
            'allowedFileExtensions'=>['jpg','png'],
            'initialPreview'=>[
                Html::img("/uploads/books/" . $model->image_web_filename)
            ],
            'overwriteInitial'=>true
        ]
    ]);
    ?>

    <?= $form->field($model, 'status')->checkbox()?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
