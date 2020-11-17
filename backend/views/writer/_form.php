<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Writer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="writer-form">
    <?php Pjax::begin(['id' => 'writer_form_pjax', 'enablePushState' => false]) ?>

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'id' => 'writer_form']]); ?>

    <input type="hidden" name="action" value="">

    <input type="hidden" name="id" value="">

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->checkbox()?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
