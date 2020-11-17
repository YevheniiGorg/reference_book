<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Writer */

$this->title = 'Create Writer';
$this->params['breadcrumbs'][] = ['label' => 'Writers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="writer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
