<?php
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\spr_status1;
$role = Yii::$app->user->identity->role;
if($mode==1)
    $this->title = 'Розширений пошук по скаргам';
if($mode==2)
    $this->title = 'Розширений пошук по пропозиціям';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-3">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false,]); ?>

    <?php
          echo $form->field($model, 'status')->dropDownList(ArrayHelper::map(spr_status1::find()->all(), 'id', 'status'));
          
    ?>
    <?= $form->field($model, 'date1')->
        widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>  
    <?= $form->field($model, 'date2')->
        widget(\yii\jui\DatePicker::classname(), [
            'language' => 'uk'
        ]) ?>     
    <?= $form->field($model, 'text')->textarea() ?>
    <?= $form->field($model, 'name_p')->textInput() ?>
    <?= $form->field($model, 'tab_nom')->textInput() ?>
    <?= $form->field($model, 'post')->textInput() ?>
    <?= $form->field($model, 'main_unit')->textInput() ?>
    <?= $form->field($model, 'unit_1')->textInput() ?>  
    <?= $form->field($model, 'unit_2')->textInput() ?>  
    <?= $form->field($model, 'message')->textarea() ?>    
    <?= $form->field($model, 'remote_addr')->textInput() ?>   
    <?= $form->field($model, 'send_result')->checkbox([
                      'disabled' => false
            ]); ?>   
    <?= $form->field($model, 'priz_doc')->checkbox([
                      'disabled' => false
            ]); ?> 
    <?= $form->field($model, 'priz_message')->checkbox([
                      'disabled' => false
            ]); ?>     
    <div class="form-group">
        <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>


