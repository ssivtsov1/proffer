<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;
use yii\helpers\Url;

$this->title = 'Перегляд скарг';
//$this->params['breadcrumbs'][] = $this->title;
//echo Yii::$app->user->identity->role;
$zag= 'Всього знайдено: '.$kol;
?>

<div class="site-spr1">
    <h5><?= Html::encode($zag) ?></h5>
    <h3 class="top-view"><?= Html::encode($this->title) ?></h3>
    
    <span class="ex-find"> 
        <?= Html::a(' Розширений пошук',["exfind?mode=1"], ['class' => 'btn btn-default glyphicon glyphicon-search']); ?></span>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                /**
                 * Указываем класс колонки
                 */
            'class' => \yii\grid\ActionColumn::class,
            'buttons'=>[

                'update'=>function ($url, $model) {
                    $customurl=Yii::$app->getUrlManager()->createUrl(['/site/upd_complaint','id'=>$model['id'],'mod'=>'complaint']); //$model->id для AR
                    return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                        ['title' => Yii::t('yii', 'Редагувати'), 'data-pjax' => '0']);
                }
            ],
            /**
             * Определяем набор кнопочек. По умолчанию {view} {update} {delete}
             */
            'template' => '{update}',
        ],  
            
            //'item_status',
             [
                'attribute' => 'status',
                'label' => 'Статус:',
                //'format' =>  ['date', 'php:d.m.Y'],
                //'value' => 'date',
                 'value' => function($model) {
                 switch ($model->status){
                    case 1: {
                        $label = "Нове";
                        break;
                    }
                    case 2: {
                        $label = "На розгляді";
                        break;
                    }
                    case 3: {
                        $label = "Закрито";
                        break;
                    }
                    
                    default: {
                        $label = "";
                    }
                }
                return $label;
                },
                   'filter'=>array(1 => 'Нове',
                        2 => 'На розгляді',
                        3 => 'Закрито',
                       ),
                 
                 'format' => 'html',
                
            ],    
                        
            ['attribute' =>'id_unique',
                'value' => function ($model){
                    if(empty($model->id_unique))
                        return '';
                    else
                        return 'так';

                },
            ],
            'name_p',
            'tel_mob',
            ['attribute' =>'text',
                'value' => function ($model){
                    // отсечение строки до ближайшего слова
                    $q = trim($model->text);
                    $y = mb_strlen($q,'UTF8');
                    $cut_str = 50; // Позиция, где отсекать строку до ближайшего слова
                    if($y>$cut_str){
                        $p = mb_substr($q,$cut_str-1,1,'UTF8');
                        if($p==' '){
                            $q = mb_substr($q,0,$cut_str,'UTF8').'...';
                        }
                        else{
                            $q1 = mb_substr($q,$cut_str,$y-$cut_str,'UTF8');
                            $p1 = mb_strpos($q1,' ',0,'UTF8');
                            if($p1>0)
                                $q = mb_substr($q,0,$cut_str,'UTF8') . mb_substr($q1,0,$p1,'UTF8').'...';
                            else {
                                $q = trim($model->text);
                            }
                          
                        }
                    }
                            
                    
                    return $q;
                },
            ],
            'tab_nom',
            'main_unit',
            'unit_1',
            'unit_2',
             [
                'attribute' => 'date',
                'label' => 'Дата скарги:',
                //'format' =>  ['date', 'php:d.m.Y'],
                //'value' => 'date',
                 'value' => function ($model){
                                           
                        return date("d.m.Y", strtotime($model->date));

                },
                  'filter' => \yii\jui\DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'date',
                    'language' => 'uk',
                    //'dateFormat' => 'yy-mm-dd',
                ]),
                 'format' => 'html',
                
            ],
            'time',
            'remote_addr',            
        ],
    ]); ?>

</div>



