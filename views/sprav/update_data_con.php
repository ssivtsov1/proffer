<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false,]); ?>
  
    <?= $form->field($model, 'rank')->textInput() ?>
    <?=$form->field($model, 'town')->
            dropDownList([
    '0' => 'Село/смт.',
    '1' => 'Місто']); ?>
    <?= $form->field($model, 'u_a1')->textInput() ?>
    <?= $form->field($model, 'u_a3')->textInput() ?>
    <?= $form->field($model, 'u_b1')->textInput() ?>
    <?= $form->field($model, 'u_b3')->textInput() ?>
    <?= $form->field($model, 'u_c1')->textInput() ?> 
    <?= $form->field($model, 'u_c3')->textInput() ?> 
    <?= $form->field($model, 'u_d1')->textarea() ?>
    <?= $form->field($model, 'u_d3')->textInput() ?>
        
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'ОК' : 'OK', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

