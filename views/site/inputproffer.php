<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

if ($model->vid==0)
    $this->title = 'Подання скарги';
if ($model->vid==1)
    $this->title = 'Подання пропозиції або побажання';
$this->params['breadcrumbs'][] = $this->title;
$model->priz_anonim=0;

?>

<script>
    window.onload=function(){
        localStorage.setItem("anonim", 1);
      
    }
    
</script>
<div class="site-login">

    <h4><?= Html::encode($this->title) ?></h4>

    <div class="row row_proffer">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'inputproffer',
                'options' => [
                    'class' => 'form-horizontal col-lg-25',
                    'enctype' => 'multipart/form-data',
                    //'enableClientValidation' => false,
                    'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']
                    
                ]]]); ?>

            <?= $form->field($model, 'priz_anonim')->checkbox(['onchange' => 'showfields(this.checked);']) ?>

           
            <?= $form->field($model, 'name_p')->textarea(['rows' => 1, 'cols' => 25,
                'onDblClick' => 'rmenu($(this).val(),"#proffer-name_p")']) ?>
            <div class='rmenu' id='rmenu-proffer-name_p'></div>
            
             <?= $form->field($model, 'email') ?>
            
            <?= $form->field($model, 'tel')->textInput(
                ['maxlength' => true,'onBlur' => 'norm_tel($(this).val())']) ?>
            
            <?= $form->field($model, 'text')->textarea(['rows' => 7, 'cols' => 25,
                'onDblClick' => 'rmenu($(this).val(),"#proffer-text")']) ?>
             <div class='rmenu' id='rmenu-proffer-text'></div>  
             
            
            <?= $form->field($model, 'doc1')->fileInput(); ?>
                       
             
            <?= $form->field($model, 'send_result')->checkbox(['disabled' => false]); ?>
            
            <?php if(Yii::$app->session->hasFlash('success')):?>
                <span class="label label-success" ><?php echo Yii::$app->session->getFlash('success'); ?></span>
            <?php endif; ?>

            <?php if(Yii::$app->session->hasFlash('Error')):?>
                <span class="label label-danger" ><?php echo Yii::$app->session->getFlash('Error'); ?></span>

            <?php endif; ?>
            <?php if(!Yii::$app->session->hasFlash('Error')):?>
                <?php echo ' '; ?>

            <?php endif; ?>
            
            <p class="text-warning">Увага! Перевірте правильність заповнення даних.</p>
                        
            <div class="form-group">
                <?= Html::submitButton('OK', ['class' => 'btn btn-primary']); ?>

            </div>
            <?php

            ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    function showfields(p){
            if (p == 0) {
                $('.field-proffer-name_p').hide();
                $('.field-proffer-email').hide();
                $('.field-proffer-tel').hide();
                $('.field-proffer-send_result').show();
                $(".field-proffer-name_p").slideDown("slow");
                $('.field-proffer-email').slideDown("slow");
                $('.field-proffer-tel').slideDown("slow");
            }
            else {
                $('.field-proffer-name_p').show();
                $('.field-proffer-email').show();
                $('.field-proffer-tel').show();
                $('.field-proffer-send_result').hide();
                $(".field-proffer-name_p").slideUp("slow");
                $('.field-proffer-email').slideUp("slow");
                $('.field-proffer-tel').slideUp("slow");        
            }
    }

// Срабатывает при изменении значения радиокнопок
    function showfields_person(p){
        var nds = true;
        if(nds==true) {
            if (p == 1) {
                localStorage.setItem("person", 1);
                $('.field-klient-okpo').hide();
                $('.field-klient-regsvid').hide();
                $('.field-klient-inn').show();
                $('.field-klient-fio_dir').hide();
                $('.field-klient-contact_person').hide();
                $('.control-label[for=klient-addr]').text("Адреса проживання:");
                $('.control-label[for=klient-nazv]').text("Прізвище, ім’я та по батькові:");
                $('.control-label[for=klient-inn]').text("Індивід. податковий №:");
            }
            else {
                localStorage.setItem("person", 2);
                $('.field-klient-okpo').show();
                $('.field-klient-fio_dir').show();
                $('.field-klient-contact_person').show();
                $('.field-klient-regsvid').hide();
                $('.field-klient-inn').hide();
                $('.control-label[for=klient-addr]').text("Юридична адреса:");
                $('.control-label[for=klient-nazv]').text("Повна назва юридичної особи:");
            }
        }

        if(nds=='false') {
            if (p == 1) {
                localStorage.setItem("person", 1);
                $('.field-klient-okpo').hide();
                $('.field-klient-regsvid').hide();
                $('.field-klient-fio_dir').hide();
                $('.field-klient-contact_person').hide();
                $('.field-klient-inn').hide();
                $('.control-label[for=klient-addr]').text("Адреса проживання:");
                $('.control-label[for=klient-nazv]').text("Прізвище, ім’я та по батькові:");
                $('.control-label[for=klient-inn]').text("Індивід. податковий №:");
            }
            else {

                localStorage.setItem("person", 2);
                $('.field-klient-okpo').show();
                $('.field-klient-regsvid').hide();
                $('.field-klient-fio_dir').show();
                $('.field-klient-contact_person').show();
                $('.field-klient-inn').hide();
                $('.control-label[for=klient-addr]').text("Юридична адреса:");
                $('.control-label[for=klient-nazv]').text("Повна назва юридичної особи:");
            }
        }
    }
    
    function sel_town(elem,id) {
        localStorage.setItem("id_town", id);
        $("#klient-search_town").val(elem);
        $(".field-klient-id_t").hide();
        $("#klient-id_t").hide();
        $("#klient-search_street").val('');
        
    }
    
    function sel_street(elem,town) {
        $("#klient-search_street").val(elem);
        $(".field-klient-id_street").hide();
        $("#klient-id_street").hide();
        
    }

    function sel_town1(elem,event) {
        alert(event.keyCode);
        if(event.keyCode==13) {
            $("#klient-search_town").val(elem);
            $("#klient-id_t").hide();
        }
    }
    
    function sel_variant2(p) {
        $(".field-klient-doc1").show();
        $(".field-klient-doc2").show();
        $(".field-klient-doc3").show();
        $(".text-warning-doc").show();
        if (p == 1) {
            $(".field-klient-doc4").show();
            $(".field-klient-doc5").show();
            $(".field-klient-doc6").hide();
            $(".field-klient-doc7").hide();
        }
        if (p == 2) {
            $(".field-klient-doc4").show();
            $(".field-klient-doc5").show();
            $(".field-klient-doc6").show();
            $(".field-klient-doc7").hide();
        }
        if (p == 3) {
            $(".field-klient-doc4").hide();
            $(".field-klient-doc5").hide();
            $(".field-klient-doc6").hide();
            $(".field-klient-doc7").show();
        }
        if (p == 4) {
            $(".field-klient-doc4").hide();
            $(".field-klient-doc5").hide();
            $(".field-klient-doc6").show();
            $(".field-klient-doc7").hide();
        }
        if (p == 5) {
            $(".field-klient-doc4").show();
            $(".field-klient-doc5").hide();
            $(".field-klient-doc6").show();
            $(".field-klient-doc7").show();
        }
    }
    
    function f_okpo(p){
        $('#klient-inn').val(p);
    }
    
    function norm_tel(p){
        var y,i,c,tel = '',kod,op,flag=0,rez='';
        y = p.length;
        
        for(i=0;i<y;i++)
        {
            c = p.substr(i,1);
            kod=p.charCodeAt(i);
            if(kod>47 && kod<58) tel+=c;
        }
        op = tel.substr(0,3);
        y = tel.length;
        if(y<10) {
            return 1;
        }
       
            switch(op) {
                case '050':  flag = 1;
                    break;
                case '096':  flag = 1;
                    break;
                case '097':  flag = 1;
                    break;
                case '098':  flag = 1;
                    break;
                case '099':  flag = 1;
                    break;

                case '091':  flag = 1;
                    break;
                case '063':  flag = 1;
                    break;
                case '073':  flag = 1;
                    break;
                case '067':  flag = 1;
                    break;
                case '066':  flag = 1;
                    break;

                case '093':  flag = 1;
                    break;
                case '095':  flag = 1;
                    break;
                case '039':  flag = 1;
                    break;
                case '068':  flag = 1;
                    break;
                case '092':  flag = 1;
                    break;
                case '094':  flag = 1;
                    break;
            }

            var add = tel.substr(3,3);
            rez+=add+'-';
            add = tel.substr(6,2);
            rez+=add+'-';
            add = tel.substr(8);
            rez+=add;

        if(flag) {
            rez = op+' '+rez;
        }
        else{
            rez = '('+op+')'+' '+rez;
        }
        $('#complaint-tel').val(rez);
    }

</script>
    





