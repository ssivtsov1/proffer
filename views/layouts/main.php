<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\Request;
use app\models\schet;
use app\models\max_schet;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>



<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    $flag=1;
    $role=0;
    $department = '';
    if(!isset(Yii::$app->user->identity->role))
    {      $flag=0;}
    else{
    $role=Yii::$app->user->identity->role;
    $department=Yii::$app->user->identity->department;

    }
     $priz_decl = 0; // Признак, что пользователь нажал кнопки "Скарга" или "Пропозиція"
     $session = Yii::$app->session;
     $session->open();
        if($session->has('priz_decl'))
            $priz_decl = $session->get('priz_decl');
        
    $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
          $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(['/book.jpg'])]);


            NavBar::begin([
                'brandLabel' => 'Книга скарг та пропозицій',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

        if($flag){
            if($role==3)
            {echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Головна', 'url' => ['/site/index']],
                    ['label' => 'Перегляд скарг', 'url' => ['/site/vcomplaint']],
                    ['label' => 'Перегляд пропозицій', 'url' => ['/site/vproffer']],
                    ['label' => 'Статистика', 'url' => ['/site/statist']],
                    ['label' => 'Телефонний довідник', 'url' => 'https://192.168.55.1/phone'],
                    ['label' => 'Вийти', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                ],
            ]);}
        else
            {echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Головна', 'url' => ['/site/index']],
                    ['label' => 'Перегляд скарг', 'url' => ['/site/vcomplaint']],
                    ['label' => 'Перегляд пропозицій', 'url' => ['/site/vproffer']],
                    ['label' => 'Телефонний довідник', 'url' => 'https://192.168.55.1/phone'],
                    ['label' => 'Вийти', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],

                ],
            ]);
        }}
        else
        {echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Головна', 'url' => ['/site/index']],
                    ['label' => 'Подати скаргу', 'url' => ['/site/complaint']],
                    ['label' => 'Подати пропозицію', 'url' => ['/site/proffer']],
                    ['label' => 'Телефонний довідник', 'url' => 'https://192.168.55.1/phone'],
                ],
            ]);}
            NavBar::end();
        ?>

        <div class="info-main col-lg-12">   
          <div class="info-header col-lg-12">    
        <!--Вывод логотипа-->
        <?php if(!strpos(Yii::$app->request->url,'/cek')): ?>
        <?php if(strlen(Yii::$app->request->url)==9): ?>
        <img class="logo_site" src="web/Logo.png" alt="ЦЕК" />
        <?php endif; ?>

        <?php if(strlen(Yii::$app->request->url)<>9): ?>
            <img class="logo_site" src="../Logo.png" alt="ЦЕК" />
        <?php endif; ?>
        <?php endif; ?>

        <?php if(strpos(Yii::$app->request->url,'/cek')): ?>
            <?php if(strlen(Yii::$app->request->url)==12): ?>
                <img class="logo_site" src="web/Logo.png" alt="ЦЕК" />
            <?php endif; ?>

            <?php if(strlen(Yii::$app->request->url)<>12): ?>
                <img class="logo_site" src="../Logo.png" alt="ЦЕК" />
            <?php endif; ?>
        <?php endif; ?>
        <div class="name-company">              
            <p class="head_cek"><span>П<i>р</i>ат Підприємство з експлуатації електричних мереж</span>
                <s>Центральна Енергетична Компанія</s></p>
            <div>Твоє джерело енергії</div>
        </div>
        <?php if(!$flag): ?>           
            <div class="head-phone"> 
        <?php endif; ?>   
        <?php if($flag): ?>           
            <div class="head-find"> 
        <?php endif; ?>          
        <div class="call">
        <div class="custom">
         <?php if(!$flag): ?>       
                <p><a href="tel:0800300015">0 800 300 015</a></p>
         <?php endif; ?>  
         <?php if($flag): ?>       
                <p><span class="find_rec"></span></p>
                <form action="find" method="post" class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                      <input type="text" name="search" class="form-control find-custom" placeholder="Пошук">
                    </div>
                    <button type="submit" class="btn btn-default glyphicon glyphicon-search"></button>
                   
               </form>

         <?php endif; ?>        
        <?php if(!$flag): ?>        
            <div><span>Call-центр</span>безкоштовно цілодобово</div></div>
        <?php endif; ?> 
        <?php if($flag): ?>        
            <div><span></span></div></div>
        <?php endif; ?>     
                    <div class="clr"></div>
        </div>
        </div>
        
        <div class="logo_ESFL">
            <div class="custom">
                <p><a href="https://www.esfmc.com/ua/investirovanie/elektroenergetika.html" target="blank">
                        <? if(!strpos(Yii::$app->request->url,'/cek')): ?>
                        <img src="../logo_ESFL.png" alt="" 
                             style="display: block; margin-left: auto; margin-right: auto;" width="157" height="58">
                        <? endif; ?>
                        <? if(strpos(Yii::$app->request->url,'/cek')): ?>
                        <img src="web/logo_ESFL.png" alt="" 
                             style="display: block; margin-left: auto; margin-right: auto;" width="157" height="58">
                        <? endif; ?>
                        <span class="esfl_label">Входить до групи</span> 
                        <span class="esfl_label1">Енергетичний стандарт</span> </a></p>
            </div>
            <div class="clr"></div>
            </div>
        </div>
        
        
        
        
        <?php if(!strpos(Yii::$app->request->url,'/cek')): ?>
        <?php if((!$flag) && ($priz_decl==0)): ?>
            <div class="services col-lg-12">
            <div class="rh1"><a href="/proffer/web/complaint"><img src="../complaint.png" alt="" /></a>
            <div><a href="/proffer/web/complaint">Скарги</a></div>
            </div>
             
            <div class="rh2"><a href="/proffer/web/proffer"><img src="../proffer.png" alt="" /></a>
            <div><a href="/proffer/web/proffer">Пропозиції та побажання</a></div>
            </div>
              
<!--            <div class="rh3"><a href="/Connect/web/cabinet"><img src="../cab1.png" alt="" /></a>
                <div><a href="/Connect/web/cabinet">Особовий кабінет</a></div>
            </div>-->
            </div>
         <?php endif; ?>  
         <?php endif; ?>  
            
        <?php if(strpos(Yii::$app->request->url,'/cek')): ?>
        <?php if((!$flag) && ($priz_decl==0)): ?>
            <div class="services col-lg-12">
            <div class="rh1"><a href="/proffer/web/complaint"><img src="web/complaint.png" alt="" /></a>
            <div><a href="/proffer/web/complaint">Скарги</a></div>
            </div>
             
            <div class="rh2"><a href="/proffer/web/proffer"><img src="web/proffer.png" alt="" /></a>
            <div><a href="/proffer/web/proffer">Пропозиції та побажання</a></div>
            </div>
              
<!--            <div class="rh3"><a href="/Connect/web/cabinet"><img src="../cab1.png" alt="" /></a>
                <div><a href="/Connect/web/cabinet">Особовий кабінет</a></div>
            </div>-->
            </div>
         <?php endif; ?>  
         <?php endif; ?>      
            
        <?php 
        //debug($priz_decl);
        if(($flag) && ($priz_decl==0)): ?>
            <div class="services col-lg-12">
            <div class="rh1"><a href="/proffer/web/vcomplaint"><img src="../complaint.png" alt="" /></a>
            <div><a href="/proffer/web/vcomplaint">Скарги</a></div>
            </div>
             <?php   
                $r=rand();
                if($r%2==0):
             ?>
            <div class="rh2"><a href="/proffer/web/statist"><img src="../stat.png" alt="" /></a>
             <?php endif; ?>
            <?php   
                
                if($r%2==1):
             ?>
            <div class="rh2"><a href="/proffer/web/statist"><img src="../statist_odd.png" alt="" /></a>
             <?php endif; ?>    
            <div><a href="/proffer/web/complaint">Статистика</a></div>
            </div>      
            <div class="rh3"><a href="/proffer/web/vproffer"><img src="../proffer.png" alt="" /></a>
            <div><a href="/proffer/web/vproffer">Пропозиції та побажання</a></div>
            </div>
                
<!--            <div class="rh3"><a href="/Connect/web/cabinet"><img src="../cab1.png" alt="" /></a>
                <div><a href="/Connect/web/cabinet">Особовий кабінет</a></div>
            </div>-->
            </div>
         <?php endif; ?>           
        </div>
        <div class="container">
            
            <?php if($flag): ?>
                <div class="page-header">
                    <small class="text-info">Ви зайшли як: <mark><?php echo $department; ?></mark> </small></h1>
                </div>
            <?php endif; ?>
            
                       
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Головна', 'url' => '/proffer'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) 
                    ?>
            <?php echo $this->context->var; ?>
            <?= $content ?>
        </div>
    </div>

<!--    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; ЦЕК <?= date('Y') ?></p>
            <p class="pull-right"><?//= Yii::powered() ?></p>
        </div>
    </footer>-->

<?php if(!strpos(Yii::$app->request->url,'/cek')): ?>
<div id="footer" class="fix">
      <div class="wrap1">
        <div class="footer-logo">
            <div class="moduletable">


            <div class="custom">
                <p><img src="../Logo-footer.png" alt=""></p>
               
            </div>
            </div>
	
        </div>       
        <div class="footer-menu1">
        <div class="moduletable">
                <ul class="nav1 menu">
                <li class="item-132"><a href="https://cek.dp.ua/index.php/tovarystvo.html"><span>Про підприємство</span></a></li>
                <li class="item-133"><a href="https://cek.dp.ua/index.php/tovarystvo/kerivnytstvo.html"><span>Керівництво</span></a></li>
                <li class="item-134"><a href="https://cek.dp.ua/index.php/cpojivaham/hrafik-pryiomu-hromadian.html"><span>Графік прийому</span></a></li>
                <li class="item-135"><a href="https://cek.dp.ua/index.php/tovarystvo/strukturni-pidrozdily.html"><span>Структурні підрозділи</span></a></li>
                <li class="item-136"><a href="https://cek.dp.ua/index.php/tovarystvo/zakup/investprohrama-zakupivli.html"><span>Інвестиційна программа</span></a></li>
                
                </ul>
        </div>
	
        </div>
        <div class="footer-menu2">
                <div class="moduletable">
                    <ul class="nav1 menu">
                    <li class="item-138"><a href="https://cek.dp.ua/index.php/cpojivaham.html"><span>Споживачам</span></a></li>
                    <li class="item-139"><a href="https://cek.dp.ua/index.php/cpojivaham/pryiednannia-do-elektromerezh.html"><span>Приєднання до електромереж</span></a></li>
                    <li class="item-140"><a href="https://cek.dp.ua/index.php/cpojivaham/vidkliuchennia.html"><span>Відключення</span></a></li>
                    <li class="item-141"><a href="https://cek.dp.ua/index.php/cpojivaham/zahalna-informatsiia/nashi-posluhy.html"><span>Наші послуги</span></a></li>
                    <li class="item-143"><a href="https://cek.dp.ua/index.php/pres-tsentr.html"><span>Прес-центр</span></a></li>
                    <li class="item-137"><a href="https://cek.dp.ua/index.php/tovarystvo/personal/vakansii.html"><span>Вакансії</span></a></li>
                    </ul>
		</div>
	
        </div>
<div class="footer-cont">
<div class="moduletable">
						

<div class="custom">
    <div><span>Контакти:</span></div>
<div>вул. Дмитра Кедріна, 28,&nbsp; м. Дніпро, 49008, Україна</div>
<div class="tel-foot">
    <a href="tel:0562310384"><span>Телефон:</span> 0562 31-03-84</a>
</div>
<div><a href="tel:0562312480"><span>Факс:</span> 0562 31-24-80</a></div>
<div><a href="tel:0800300015"><span>Call-центр:</span> 0800 30-00-15</a></div>
<div><a href="mailto:kanc@cek.dp.ua"><span>E-mail:</span> kanc@cek.dp.ua</a></div>
<p class="soc1">
    <a href="http://www.facebook.com/pratpeemcek" target="_blank" class="soc">
        <img src="/images/face.png" alt="">&nbsp; </a> <a href="https://www.youtube.com/channel/UCLOQrRe56Fkcgph2SdNAfhA?disable_polymer=true" 
           target="_blank" class="yout">
        <img src="/images/youtube.png" alt=""></a></p>
</div>
</div>
	
</div>
        <div class="footer-brands">
            		<div class="moduletable">
						

<div class="custom">
<div class="part1">
<div class="original_lab1">Входить до:</div>
    <a href="http://adsoeukr.org/" target="_blank">
        <img src="../OREM_white.png" alt="" class="origin_img1" title="Асоціація операторів розподільчих електричних мереж України" width="135" height="100">
    </a>
</div>
<div class="chernigiv">
<div>Партнери:</div>
<a href="http://chernigivoblenergo.com.ua/" target="_blank"><img src="../chernigiv.png" alt=""></a></div>
<div class="vse"><a href="http://www.voe.com.ua/" target="_blank"><img src="../vce2.png" alt="vce2" width="239" height="50"></a></div>
<div class="part2"><a href="https://www.soe.com.ua/" target="_blank"><img src="../part2.png" alt=""></a></div>
<div class="part3">&nbsp;</div>
<div class="part4"><a href="http://www.en.lg.ua/" target="_blank"><img src="../Leo_white.png" alt="" title="Луганське енергетичне об`єднання" width="55" height="55"></a></div>
<div class="part4">&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<p>&nbsp;</p></div>
		</div>
	
        </div>
      </div>        
    </div>
 

<div class="footer-copy">
      <div class="wrap1">        
        	
        <div class="moduletable">
					

        <div class="custom">
            <div class="copy-text">© ПрАТ «Підприємство з експлуатації електричних мереж «Центральна енергетична компанія»</div>
                
        </div>
            <div class="clr"></div>
        </div>
	 
      </div>       
</div>
<?php endif; ?>

<?php if(strpos(Yii::$app->request->url,'/cek')): ?>
<div id="footer" class="fix">
      <div class="wrap1">
        <div class="footer-logo">
            <div class="moduletable">


            <div class="custom">
                <p><img src="web/Logo-footer.png" alt=""></p>
               
            </div>
            </div>
	
        </div>       
        <div class="footer-menu1">
        <div class="moduletable">
                <ul class="nav1 menu">
                <li class="item-132"><a href="https://cek.dp.ua/index.php/tovarystvo.html"><span>Про підприємство</span></a></li>
                <li class="item-133"><a href="https://cek.dp.ua/index.php/tovarystvo/kerivnytstvo.html"><span>Керівництво</span></a></li>
                <li class="item-134"><a href="https://cek.dp.ua/index.php/cpojivaham/hrafik-pryiomu-hromadian.html"><span>Графік прийому</span></a></li>
                <li class="item-135"><a href="https://cek.dp.ua/index.php/tovarystvo/strukturni-pidrozdily.html"><span>Структурні підрозділи</span></a></li>
                <li class="item-136"><a href="https://cek.dp.ua/index.php/tovarystvo/zakup/investprohrama-zakupivli.html"><span>Інвестиційна программа</span></a></li>
                
                </ul>
        </div>
	
        </div>
        <div class="footer-menu2">
                <div class="moduletable">
                    <ul class="nav1 menu">
                    <li class="item-138"><a href="https://cek.dp.ua/index.php/cpojivaham.html"><span>Споживачам</span></a></li>
                    <li class="item-139"><a href="https://cek.dp.ua/index.php/cpojivaham/pryiednannia-do-elektromerezh.html"><span>Приєднання до електромереж</span></a></li>
                    <li class="item-140"><a href="https://cek.dp.ua/index.php/cpojivaham/vidkliuchennia.html"><span>Відключення</span></a></li>
                    <li class="item-141"><a href="https://cek.dp.ua/index.php/cpojivaham/zahalna-informatsiia/nashi-posluhy.html"><span>Наші послуги</span></a></li>
                    <li class="item-143"><a href="https://cek.dp.ua/index.php/pres-tsentr.html"><span>Прес-центр</span></a></li>
                    <li class="item-137"><a href="https://cek.dp.ua/index.php/tovarystvo/personal/vakansii.html"><span>Вакансії</span></a></li>
                    </ul>
		</div>
	
        </div>
<div class="footer-cont">
<div class="moduletable">
						

<div class="custom">
    <div><span>Контакти:</span></div>
<div>вул. Дмитра Кедріна, 28,&nbsp; м. Дніпро, 49008, Україна</div>
<div class="tel-foot">
    <a href="tel:0562310384"><span>Телефон:</span> 0562 31-03-84</a>
</div>
<div><a href="tel:0562312480"><span>Факс:</span> 0562 31-24-80</a></div>
<div><a href="tel:0800300015"><span>Call-центр:</span> 0800 30-00-15</a></div>
<div><a href="mailto:kanc@cek.dp.ua"><span>E-mail:</span> kanc@cek.dp.ua</a></div>
<p class="soc1">
    <a href="http://www.facebook.com/pratpeemcek" target="_blank" class="soc">
        <img src="/images/face.png" alt="">&nbsp; </a> <a href="https://www.youtube.com/channel/UCLOQrRe56Fkcgph2SdNAfhA?disable_polymer=true" 
           target="_blank" class="yout">
        <img src="/images/youtube.png" alt=""></a></p>
</div>
</div>
	
</div>
        <div class="footer-brands">
            		<div class="moduletable">
						

<div class="custom">
<div class="part1">
<div class="original_lab1">Входить до:</div>
    <a href="http://adsoeukr.org/" target="_blank">
        <img src="web/OREM_white.png" alt="" class="origin_img1" title="Асоціація операторів розподільчих електричних мереж України" width="135" height="100">
    </a>
</div>
<div class="chernigiv">
<div>Партнери:</div>
<a href="http://chernigivoblenergo.com.ua/" target="_blank"><img src="../chernigiv.png" alt=""></a></div>
<div class="vse"><a href="http://www.voe.com.ua/" target="_blank"><img src="web/vce2.png" alt="vce2" width="239" height="50"></a></div>
<div class="part2"><a href="https://www.soe.com.ua/" target="_blank"><img src="web/part2.png" alt=""></a></div>
<div class="part3">&nbsp;</div>
<div class="part4"><a href="http://www.en.lg.ua/" target="_blank"><img src="web/Leo_white.png" alt="" title="Луганське енергетичне об`єднання" width="55" height="55"></a></div>
<div class="part4">&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<p>&nbsp;</p></div>
		</div>
	
        </div>
      </div>        
    </div>
 

<div class="footer-copy">
      <div class="wrap1">        
        	
        <div class="moduletable">
					

        <div class="custom">
            <div class="copy-text">© ПрАТ «Підприємство з експлуатації електричних мереж «Центральна енергетична компанія»</div>
                
        </div>
            <div class="clr"></div>
        </div>
	 
      </div>       
</div>
<?php endif; ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
