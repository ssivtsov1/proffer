<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;
use yii\helpers\Url;

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = $this->title;

?>


<script>
  
window.addEventListener('load', function(){
        
          $(".rh1").css('padding-left', '16%');
          $(".rh2").css('padding-left', '16%');
          $(".rh3").css('padding-left', '16%');
          
         });
</script>

<div class="site-spr1">
    <?= Html::a(' Задати умови',["case_stat"], ['class' => 'btn btn-default glyphicon glyphicon-stats case-statist']); ?></span>     
  <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'emptyText' => 'Нічого не знайдено',
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            ['attribute' =>'vid',
                'value' => function ($model){
                    if($model->vid==1)
                        return 'Пропозиція';
                    else
                        return 'Скарга';

                },
            ],
            'item_status',
            'kol',
            
        ],
    ]); ?>

  
</div>



