<?php
//namespace app\models;
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\spr_status;
$role = Yii::$app->user->identity->role;
?>
<script>
   window.onload=function(){
    $(document).click(function(e){

	  if ($(e.target).closest("#recode-menu").length) return;

	   $("#rmenu").hide();

	  e.stopPropagation();

	  });
               
          
   }        

window.addEventListener('load', function(){
          var t= $("#vcomplaint-name_p").val();
          if (t=='Анонім') {
                $(".field-vcomplaint-tab_nom").hide();
                $(".field-vcomplaint-main_unit").hide();
                $(".field-vcomplaint-unit_1").hide();
                $(".field-vcomplaint-unit_2").hide();
                $(".field-vcomplaint-post").hide();
                $(".field-vcomplaint-tel_mob").hide();
                $(".field-vcomplaint-tel_in").hide();
                $(".field-vcomplaint-tel_town").hide(); 
                $(".field-vcomplaint-email").hide(); 
                $(".field-vcomplaint-email_spr").hide();
                $(".field-vcomplaint-email_group").hide(); 
          }
          $(".rh1").css('padding-left', '16%');
          $(".rh2").css('padding-left', '16%');
          $(".rh3").css('padding-left', '16%');    
          
         });
</script>

<br>
<div class="row">
    <?php if(!empty($model->id_unique)): ?>
    <div class="col-lg-6" id="docs">
    <p><ins>Документи</ins></p>   
    <table class="table table-striped">
        <tr id="tr_doc1">
        <td>
            Прикріплений файл
        </td>
        <td>
            <?= Html::a('...',['site/doc'], [
            'data' => [
                'method' => 'post',
                'params' => [
                    'doc' => $model->id_person,
                    'id' => $model->id_unique,
                    
                ],
            ],'class' => 'btn btn-info']); ?>
      
        </td>
        </tr>
    </table>
    </div> 
    <?php endif; ?>
    <div class="col-lg-4">
    
        
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false,]); ?>

    <?= $form->field($model, 'remote_addr')->textInput() ?>        
    <?php
       
        // Установка статусов в соответствии с доступами
        switch($role) {
        case 3: // Полный доступ
            echo $form->field($model, 'status')->dropDownList(ArrayHelper::map(spr_status::find()->all(), 'id', 'status'));
            break;
        }
    ?>
    
    <?= $form->field($model, 'tab_nom')->textInput() ?>
  
    <?= $form->field($model, 'name_p')->textInput() ?>
    <?= $form->field($model, 'text')->textarea(['rows' => 7, 'cols' => 25]) ?>

    <?= $form->field($model, 'main_unit')->textInput() ?>  
    <?= $form->field($model, 'unit_1')->textInput() ?>
    <?= $form->field($model, 'unit_2')->textInput() ?>
    <?= $form->field($model, 'post')->textInput() ?>
        
            <?= $form->field($model, 'tel_mob',
            ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">'
            . '<span class="glyphicon glyphicon-phone"></span></span>{input}</div>'] )->textInput() ?>
        
    <?= $form->field($model, 'tel_in')->textarea() ?>
    <?= $form->field($model, 'tel_town')->textarea() ?>
    <?= $form->field($model, 'email',
            ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">'
            . '<span class="glyphicon glyphicon-envelope"></span></span>{input}</div>'])->textInput() ?>
    <?= $form->field($model, 'email_spr')->textarea() ?>
    <?= $form->field($model, 'email_group')->textarea() ?>    
     
   
    <?= $form->field($model, 'date'
                    )->textInput() ?>

    <?= $form->field($model, 'time')->textInput() ?>
    <?= $form->field($model, 'send_result')->checkbox() ?>
    <?= $form->field($model, 'message')->textarea(['rows' => 7, 'cols' => 25]) ?>  
   
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'ОК' : 'OK', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>


