<?php
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\spr_status1;
$role = Yii::$app->user->identity->role;
$this->title = 'Умови для статистики';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-3">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false,]); ?>

   
    <?= $form->field($model, 'date1')->
        widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>  
    <?= $form->field($model, 'date2')->
        widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>     
    <?=$form->field($model, 'anonim')->dropDownList([
                        '1' => 'Всі',
                        '2' => 'Аноніми',
                        '3' => 'Не аноніми',
                        ]); ?>
    <div class="form-group">
        <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>


