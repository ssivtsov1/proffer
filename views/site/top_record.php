<?php
use yii\helpers\Html;
$this->title = "Останні записи";
$this->params['breadcrumbs'][] = $this->title;
$q_rec = 10;  // Количество записей на странице
?>


<script>
  
window.addEventListener('load', function(){
          var init=  <?php echo $init;?>;
          var kol=   <?php echo $kol;?>;
          var q_rec= <?php echo $q_rec;?>;
          var last=Math.floor(kol/q_rec);
          $(".rh1").css('padding-left', '16%');
          $(".rh2").css('padding-left', '16%');
          $(".rh3").css('padding-left', '16%');
//          alert('init='+init);
//          alert('last='+last);
          
          if(init==1) $(".prev_nav_r").hide();
          if(init>last) $(".next_nav_r").hide();
          
          $(".next_nav_r").bind('click', function(){
              init++;
              localStorage.setItem("mem_page", init);
              if(init>1) $(".prev_nav_r").show();
              if(init>last) $(".next_nav_r").hide();
           });
           $(".prev_nav_r").bind('click', function(){
              init--;
              localStorage.setItem("mem_page", init);
              if(init==1) $(".prev_nav_r").hide();
              if(init<=last) $(".next_nav_r").show();
              
           });
              
          
         });
</script>

    <div class="all_records">
        </br>
       <?php if (empty($data[0]->date)): ?>
           Нiчого не знайдено
       <?php endif; ?> 
       <?php
       $i=0;
           foreach($data as $v) {
           $i++;
           $date = $v->date;
           $time = $v->time;
           $text = $v->text;
           $q = trim($text);
                    $y = mb_strlen($q,'UTF8');
                    $cut_str = 70; // Позиция, где отсекать строку до ближайшего слова
                    if($y>$cut_str){
                        $p = mb_substr($q,$cut_str-1,1,'UTF8');
                        if($p==' '){
                            $q = mb_substr($q,0,$cut_str,'UTF8').'...';
                        }
                        else{
                            $q1 = trim(mb_substr($q,$cut_str,$y-$cut_str,'UTF8'));
                            $p1 = mb_strpos($q1,' ',0,'UTF8');
                            if($p1>0)
                                $q = mb_substr($q,0,$cut_str,'UTF8') . mb_substr($q1,0,$p1,'UTF8').'...';
                            else {
                                $q = $text;
                            }
                          
                        }
                    }

           $text = $q;
           $vid  = $v->vid;
           $id   = $v->id;
           $ip = $v->remote_addr;
           if(empty($ip))
                $person = $v->name_p;
           else
                $person = $v->name_p.' ['.$ip.']';
           
           $r_id1 = "r_vid1_".$id;
           $vr_id1 = "'"."r_vid1_".$id."'";
           $r_id2 = "r_vid2_".$id;
           $vr_id2 = "'"."r_vid2_".$id."'";
       ?>
       <div class="records">
           <div class="rec_date">
               <?= Html::encode(date("d.m.y", strtotime($date))) ?>
            </div>
            <div class="rec_time">
                   <?= Html::encode($time); ?>
            </div>
           <?php if($vid == 1): ?> 
           <div class="rec_vid1" id="<?php echo $r_id1; ?>">
                   <?= Html::encode("Пропозиція"); ?>
            </div>
            <?php endif; ?>
           <?php if($vid == 2): ?> 
           <div class="rec_vid2" id="<?php echo $r_id2; ?>">
                   <?= Html::encode("Скарга"); ?>
            </div>
            <?php endif; ?>
            <div class="rec_person">
                   <?= Html::encode($person); ?>
            </div>
           <div class="rec_text">
               <?php if($vid == 1): ?> 
                   <?= Html::a($text,["upd_proffer?&id=$id&mod=proffer"],['onmouseover' => 'showSelect('.$vr_id1.')'.';',
                       'onmouseout' => 'showUnselect('.$vr_id1.',1)'.';']); ?>
               <?php endif; ?>
               <?php if($vid == 2): ?> 
                   <?= Html::a($text,["upd_complaint?&id=$id&mod=complaint"],['onmouseover' => 'showSelect('.$vr_id2.')'.';',
                       'onmouseout' => 'showUnselect('.$vr_id2.',2)'.';']); ?>
               <?php endif; ?>
            </div>
        </div>
       <?php } ?>
        </br>
    </div>

<br>
<?php if ($kol>10): ?>
<ul class="pager">
  <li><a class="prev_nav_r" href="/proffer/web/navprev">Попередня</a></li>
  <li><a class="next_nav_r" href="/proffer/web/navnext">Слідуюча</a></li>
</ul>
 <?php endif; ?> 
<br>


<script>
    // Подсветка вида обращения
    function showSelect(id){
        var d = "$('#"+id+"')"+".css('opacity','1');" ;  
        eval(d);
    }
    // Убрать подсветку вида обращения
    function showUnselect(id,vid){
        if (vid==1)
            var d = "$('#"+id+"')"+".css('opacity','0.8');" ; 
        if (vid==2)
            var d = "$('#"+id+"')"+".css('opacity','0.5');" ;
        eval(d);
    }
</script>