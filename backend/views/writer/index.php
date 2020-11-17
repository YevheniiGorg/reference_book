<?php

use common\grid\EnumColumn;
use common\models\Writer;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\WriterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->registerJs(
    <<<JS
    
    $(document).on('click', '.btn_create_writer', function(e){
        e.preventDefault();  
         $('#writer-first_name').val(null);
         $('#writer-second_name').val(null);
         $('#writer-middle_name').val(null);
          $("#writer_form input[name='action']").val('create');
      
         $('#modal-writer').modal('show');    
    });

     $(document).on('click', '.pjax-update-writer-link', function(e) {
        e.preventDefault();    
        
         $("#writer-first_name").val($(this).parent().siblings("td.gw-first_name").text());
         $("#writer-second_name").val($(this).parent().siblings("td.gw-second_name").text());
         $("#writer-middle_name").val($(this).parent().siblings("td.gw-middle_name").text());
         $("#writer_form input[name='action']").val('update');
         $("#writer_form input[name='id']").val($(this).data('id'));
         
         $('#modal-writer').modal('show');   
    });

    $("#writer_form_pjax").on("pjax:end", function() {
          $.pjax.reload({container:"#notes-writers"});  //Reload GridView
          $("#modal-writer").modal("hide");
     }); 

JS
    , View::POS_READY);



$this->title = 'Writers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="writer-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Writer', ['create'], ['class' => 'btn btn-success btn_create_writer']) ?>
    </p>

    <?php Pjax::begin(['id' => 'notes-writers']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'first_name',
                'contentOptions' => ['class' => 'gw-first_name'],
            ],
            [
                'attribute' => 'second_name',
                'contentOptions' => ['class' => 'gw-second_name'],
            ],
            [
                'attribute' => 'middle_name',
                'contentOptions' => ['class' => 'gw-middle_name'],
            ],
            [
                'class' => EnumColumn::class,
                'attribute' => 'status',
                'options' => ['style' => 'width: 10%', 'class' => 'gw-status'],
                'enum' => Writer::statuses(),
                'filter' => Writer::statuses(),
            ],
            [
                'attribute' => 'created_at',
                'options' => ['style' => 'width: 10%'],
                'format' => 'datetime',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'showMeridian' => true,
                        'todayBtn' => true,
                        'endDate' => '0d',
                    ]
                ]),
            ],
            [
                'attribute' => 'created_by',
                'options' => ['style' => 'width: 10%'],
                'value' => function ($model) {
                    return $model->author->username;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['style' => 'width: 5%'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', false, [
                            'class' => 'pjax-update-writer-link btn btn-xs',
                            'data-id' => $model->id,
                            'update-url' => '/',
                            'pjax-container' => 'notes-writers',
                            'title' => 'Update',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

<?php Modal::begin([
    'header' => 'Автора книги',
    'id' => 'modal-writer',
]);
?>
<?= $this->render('_form', ['model' => $writer]) ?>
<?php Modal::end() ?>

