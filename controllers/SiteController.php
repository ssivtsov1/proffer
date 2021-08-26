<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\ContactForm;
use app\models\InputDataForm;
use app\models\complaint;
use app\models\proffer;
use app\models\person;
use app\models\vcomplaint;
use app\models\vcomplaint_ex;
use app\models\vproffer_ex;
use app\models\vproffer;
use app\models\v_all;
use app\models\requestsearch;
use app\models\tofile;
use app\models\forExcel;
use app\models\info;
use app\models\find_rec;
use app\models\case_stat;
use app\models\docs;
use app\models\User;
use app\models\loginform;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;

class SiteController extends Controller
{  /**
 * 
 * @return type
 *
 */

    //public $defaultAction = 'index';
    public $var;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    
    public function beforeAction($action)
    {
        if ($this->action->id == 'find')
        {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);

    }
    
    
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    //  Происходит при запуске сайта
    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['site/more']);
        }
        if(strpos(Yii::$app->request->url,'/cek')==0)
            return $this->redirect(['site/more']);
        $model = new loginform();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['site/more']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    //  Происходит после ввода пароля (главная страница)
    public function actionMore()
    {
        $session = Yii::$app->session;
        $session->open();
        $session->set('priz_decl', 0);
        if(!isset(Yii::$app->user->identity->role))
            $role=0;
        else
            $role=Yii::$app->user->identity->role;
        
        if($role<>3)
            return $this->render('face_page',['style_title' => 'd9']);
        else
        {
           // Если вход под администратором
             $session = Yii::$app->session;
             $session->open();
             $session->set('mem_page', 1);
             $sql='select * from vw_all order by date desc,time desc';
             $data = v_all::findBySql($sql)->all();
             $kol = count($data);
             $session->set('mem_all', $kol);
             $sql='select * from vw_all order by date desc,time desc limit 10';
             $data1 = v_all::findBySql($sql)->all();
             $init=1;
             return $this->render('top_record',['data' => $data1,'kol' => $kol,'init' => $init]);
        }
    }
    
    //  Происходит при нажатии кнопки "Следующая" запись
    public function actionNavnext(){
        $session = Yii::$app->session;
        $session->open();
        if($session->has('mem_page')){
            $mem_page = $session->get('mem_page');  //Страница навигации
            $mem_page++;
            $session->set('mem_page', $mem_page);
            $sql='select * from vw_all order by date desc,time desc LIMIT 10 OFFSET '.($mem_page-1)*10;
            $data1 = v_all::findBySql($sql)->all();
            $init=$mem_page;
            $kol = $session->get('mem_all');
            return $this->render('top_record',['data' => $data1,'kol' => $kol,'init' => $init]);
        }
    }
    
    //  Происходит при нажатии кнопки "Предыдущая" запись
    public function actionNavprev(){
        $session = Yii::$app->session;
        $session->open();
        if($session->has('mem_page')){
            $mem_page = $session->get('mem_page');  //Страница навигации
            $mem_page--;
            if($mem_page<=1) $mem_page=1;
            $session->set('mem_page', $mem_page);
            $sql='select * from vw_all order by date desc,time desc LIMIT 10 OFFSET '.($mem_page-1)*10;
            $data1 = v_all::findBySql($sql)->all();
            $init=$mem_page;
            $kol = $session->get('mem_all');
            return $this->render('top_record',['data' => $data1,'kol' => $kol,'init' => $init]);
        }
    }
        
   //  Происходит при нажатии кнопки расширенный поиск
    public function actionExfind()
    {   $mode = Yii::$app->request->get('mode');
                
        $model = new find_rec();  
        if ($model->load(Yii::$app->request->post()))
        {
            // Поиск по жалобам
            if($mode==1){
                $searchModel = new vcomplaint_ex();
                $sql='';
                if(!empty($model->status)){
                    $sql=$sql.' and status='.$model->status;}
                else  {
                    $sql=$sql.' and 1=1';
                }
                if(!empty($model->date1)){
                    $sql=$sql.' and date>='."'".$model->date1."'";
                    
                }
                if(!empty($model->date2)){
                    $sql=$sql.' and date<='."'".$model->date2."'";
                }
                if(!empty($model->send_result)){
                    $sql=$sql.' and send_result=1';
                }
                if(!empty($model->priz_doc)){
                    $sql=$sql." and id_unique<>''";
                }
                if(!empty($model->priz_message)){
                    $sql=$sql." and message<>''";
                }
                if(!empty($model->text)){
                    $sql=$sql." and text like "."'%".$model->text."%'";
                }
                if(!empty($model->message)){
                    $sql=$sql." and message like "."'%".$model->message."%'";
                }
                if(!empty($model->post)){
                    $sql=$sql." and post like "."'%".$model->post."%'";
                }
                if(!empty($model->main_unit)){
                    $sql=$sql." and main_unit like "."'%".$model->main_unit."%'";
                }
                if(!empty($model->unit_1)){
                    $sql=$sql." and unit_1 like "."'%".$model->unit_1."%'";
                }
                if(!empty($model->unit_2)){
                    $sql=$sql." and unit_2 like "."'%".$model->unit_2."%'";
                }
                if(!empty($model->name_p)){
                    $sql=$sql." and name_p like "."'%".$model->name_p."%'";
                }
                if(!empty($model->remote_addr)){
                    $sql=$sql." and remote_addr="."'".$model->remote_addr."'";
                }
                if(!empty($model->tab_nom)){
                    $sql=$sql." and tab_nom="."'".$model->tab_nom."'";
                }
                $sql=trim($sql);
                $fc = substr($sql,0,4);
                if($fc=='and ') $sql=substr($sql,3);
                $sql='select * from vw_complaint where '.$sql.' order by date desc,time desc';
                
                $data = vcomplaint::findBySql($sql)->all();
                $kol = count($data);
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $sql);
                return $this->render('vcomplaint', [
                'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,'kol' => $kol
        ]);
            }
            // Поиск по предложениям
            if($mode==2){
                $searchModel = new vproffer_ex();
                $sql='';
                if(!empty($model->status)){
                    $sql=$sql.' and status='.$model->status;}
                else  {
                    $sql=$sql.' and 1=1';
                }
                if(!empty($model->date1)){
                    $sql=$sql.' and date>='."'".$model->date1."'";
                    
                }
                if(!empty($model->date2)){
                    $sql=$sql.' and date<='."'".$model->date2."'";
                }
                if(!empty($model->send_result)){
                    $sql=$sql.' and send_result=1';
                }
                if(!empty($model->priz_doc)){
                    $sql=$sql." and id_unique<>''";
                }
                if(!empty($model->priz_message)){
                    $sql=$sql." and message<>''";
                }
                if(!empty($model->text)){
                    $sql=$sql." and text like "."'%".$model->text."%'";
                }
                if(!empty($model->message)){
                    $sql=$sql." and message like "."'%".$model->message."%'";
                }
                if(!empty($model->post)){
                    $sql=$sql." and post like "."'%".$model->post."%'";
                }
                if(!empty($model->main_unit)){
                    $sql=$sql." and main_unit like "."'%".$model->main_unit."%'";
                }
                if(!empty($model->unit_1)){
                    $sql=$sql." and unit_1 like "."'%".$model->unit_1."%'";
                }
                if(!empty($model->unit_2)){
                    $sql=$sql." and unit_2 like "."'%".$model->unit_2."%'";
                }
                if(!empty($model->name_p)){
                    $sql=$sql." and name_p like "."'%".$model->name_p."%'";
                }
                if(!empty($model->remote_addr)){
                    $sql=$sql." and remote_addr="."'".$model->remote_addr."'";
                }
                if(!empty($model->tab_nom)){
                    $sql=$sql." and tab_nom="."'".$model->tab_nom."'";
                }
                $sql=trim($sql);
                $fc = substr($sql,0,4);
                if($fc=='and ') $sql=substr($sql,3);
                $sql='select * from vw_proffer where '.$sql.' order by date desc,time desc';
                
                $data = vcomplaint::findBySql($sql)->all();
                $kol = count($data);
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $sql);
                return $this->render('vproffer', [
                'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,'kol' => $kol
        ]);
            }
        }
         else {
            return $this->render('find_complaint',['model' => $model,'mode' => $mode]);
         }
    }
    
    //  ~~ Происходит при нажатии кнопки задать условия для статистики
    public function actionCase_stat()
    {   
        //$this->var="Статистика";
        $this->on(yii\web\Controller::EVENT_AFTER_ACTION, [$this, 'fun_test']);
        $session = Yii::$app->session;
        $session->open();
        $session->set('priz_decl', 1);
        $model = new case_stat();  
        if ($model->load(Yii::$app->request->post()))
        {
            $sql='';
            if(!empty($model->date1)){
                    $sql=$sql.' and date>='."'".$model->date1."'";}
                else  {
                    $sql=$sql.' and 1=1';
                }
            if(!empty($model->date2)){
                    $sql=$sql.' and date<='."'".$model->date2."'";}
                else  {
                    $sql=$sql.' and 1=1';
                }   
            if($model->anonim == 2){  
                $sql=$sql.' and name_p='."'Анонім'";
            }  
            if($model->anonim == 3){  
                $sql=$sql.' and name_p<>'."'Анонім'";
            } 
            $sql=trim($sql);
            $fc = substr($sql,0,4);
            if($fc=='and ') $sql=substr($sql,3);
            
            $sql1='select vid,item_status,count(item_status) as kol from vw_all ';
            $sql2= ' group by vid,item_status order by vid';
            $sql = $sql1.' where '.$sql.$sql2;
             $query = v_all::findBySql($sql);
             $query->sql = $sql;

                 $dataProvider = new ActiveDataProvider([
                 'query' => $query,
                 'sort' => false,
                 ]);
             return $this->render('statist',['dataProvider' => $dataProvider]);
        }
         else {
            return $this->render('case_stat',['model' => $model]);
         }
    }
        
     // ~~ Подача жалобы 
    public function actionComplaint()
    {
        $session = Yii::$app->session;
        $session->open();
        $session->set('priz_decl', 1);
        $model = new Complaint();
        $model->vid = 0;
        $ar_doc=[];
        $i=0;
        if ($model->load(Yii::$app->request->post()))
        {    
            $rand = rand();
            $id_person = md5(uniqid(rand(),1));

                $model->doc1 = UploadedFile::getInstance($model,'doc1');;
                if($model->doc1) {
                    $model->upload('doc1',$rand);
                    $ar_doc[$i]=1;
                }
             
            // Запись данных в базу
            
            $person=new Person();
            $person->name_p=$model->name_p;
            $person->email=$model->email;
            $person->tel=$model->tel;
//            $person->remote_addr = $_SERVER['REMOTE_ADDR'];
            $person->remote_addr = '';
//            $person->remote_host = $_SERVER['REMOTE_HOST'];
//            $person->remote_user = $_SERVER['REMOTE_USER'];
            $person->id_person = $id_person;
            $person->r_tel = str_replace(' ', '', $model->tel);
            $person->r_tel = trim(str_replace('-', '', $person->r_tel));
            $person->r_tel = substr($person->r_tel,1);
            
            if($model->priz_anonim==1)
                $person->name_p = 'Анонім';
            
            $person->save();

              //$doc->id_unique = $rand;
              if(!empty($model->doc1)) { 
                 $doc=new Docs(); 
                 $doc->file_path = '1'.'_'.$rand.'-'.$model->doc1->name;
                 $doc->id_unique = $rand;
                 $doc->id_person = $id_person;
                 $doc->save();
              }
                 
//            if($model->priz_anonim==1){
//                $model->id_person = '1'; 
//                $model->name_p = 'Анонім';
//            }
//            else
//                $model->id_person = $id_person;
              
            $model->id_person = $id_person;
            if(!empty($model->doc1))
                $model->id_unique = $rand;
            
            $model->date = date('Y-m-d');
            $model->time = date('H:i');
            $model->status = 1;
            $model->save(false);
            $is=0;
            // Отправка писем о появлении новой жалобы
            $mail_cek = 'ekobzar@cek.dp.ua';
           
             Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail_cek)
                ->setSubject('Нова скарга в сервісі книга скарг та пропозицій')
                ->setHtmlBody('<b>Увага! З`явилась нова скарга від '.$model->date.' '.$model->time
                             . '<a target="_blank" href="http://192.168.55.1/proffer/cek"> Перейти в адміністрування книгою скарг та пропозицій.</a> ')
                ->send();
             $mail_cek = 'nkorsa@cek.dp.ua';
             Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail_cek)
                ->setSubject('Нова скарга в сервісі книга скарг та пропозицій')
                ->setHtmlBody('<b>Увага! З`явилась нова скарга від '.$model->date.' '.$model->time
                             . '<a target="_blank" href="http://192.168.55.1/proffer/cek"> Перейти в адміністрування книгою скарг та пропозицій.</a> ')
                ->send();
             $mail_cek = 'ikovalenko@cek.dp.ua';
             Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail_cek)
                ->setSubject('Нова скарга в сервісі книга скарг та пропозицій')
                ->setHtmlBody('<b>Увага! З`явилась нова скарга від '.$model->date.' '.$model->time
                             . '<a target="_blank" href="http://192.168.55.1/proffer/cek"> Перейти в адміністрування книгою скарг та пропозицій.</a> ')
                ->send();
        $session = Yii::$app->session;
        $session->open();
        $session->set('priz_decl', 0);
            return $this->render('process_ok', [
            'is' => $is
        ]);
        }
        else {

            return $this->render('inputcomplaint', [
                'model' => $model,
            ]);
        }
    }
    
     // ~~ Подача предложения 
    public function actionProffer()
    {
        $session = Yii::$app->session;
        $session->open();
        $session->set('priz_decl', 1);
        $model = new Proffer();
        $model->vid = 1;
        $ar_doc=[];
        $i=0;
        if ($model->load(Yii::$app->request->post()))
        {    
            $rand = rand();
            $id_person = md5(uniqid(rand(),1));

                $model->doc1 = UploadedFile::getInstance($model,'doc1');;
                if($model->doc1) {
                    $model->upload('doc1',$rand);
                    $ar_doc[$i]=1;
                }
            
            // Запись данных в базу
            
            $person=new Person();
            $person->name_p=$model->name_p;
            $person->email=$model->email;
            $person->tel=$model->tel;
//            $person->remote_addr = $_SERVER['REMOTE_ADDR'];
            $person->remote_addr = '';
            $person->id_person = $id_person;
            $person->r_tel = str_replace(' ', '', $model->tel);
            $person->r_tel = trim(str_replace('-', '', $person->r_tel));
            $person->r_tel = substr($person->r_tel,1);
            
            if($model->priz_anonim==1)
                $person->name_p = 'Анонім';
            
            $person->save();
            

              //$doc->id_unique = $rand;
              if(!empty($model->doc1)) { 
                 $doc=new Docs(); 
                 $doc->file_path = '1'.'_'.$rand.'-'.$model->doc1->name;
                 $doc->id_unique = $rand;
                 $doc->id_person = $id_person;
                 $doc->save();
              }
                 
//            if($model->priz_anonim==1){
//                $model->id_person = '1'; 
//                $model->name_p = 'Анонім';
//            }
//            else
//                $model->id_person = $id_person;
              
            $model->id_person = $id_person;
            if(!empty($model->doc1))
                $model->id_unique = $rand;
            
            $model->date = date('Y-m-d');
            $model->time = date('H:i');
            $model->status = 1;
            $model->save(false);
            $is=1;
            // Отправка писем о появлении нового предложения
            $mail_cek = 'ekobzar@cek.dp.ua';
             Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail_cek)
                ->setSubject('Нова пропозиція в сервісі книга скарг та пропозицій')
                ->setHtmlBody('<b>Увага! З`явилась нова пропозиція від '.$model->date.' '.$model->time
                             . '<a target="_blank" href="http://192.168.55.1/proffer/cek"> Перейти в адміністрування книгою скарг та пропозицій.</a> ')
                ->send();
             $mail_cek = 'nkorsa@cek.dp.ua';
             Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail_cek)
                ->setSubject('Нова пропозиція в сервісі книга скарг та пропозицій')
                ->setHtmlBody('<b>Увага! З`явилась нова пропозиція від '.$model->date.' '.$model->time
                             . '<a target="_blank" href="http://192.168.55.1/proffer/cek"> Перейти в адміністрування книгою скарг та пропозицій.</a> ')
                ->send();
             $mail_cek = 'ikovalenko@cek.dp.ua';
             Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail_cek)
                ->setSubject('Нова пропозиція в сервісі книга скарг та пропозицій')
                ->setHtmlBody('<b>Увага! З`явилась нова пропозиція від '.$model->date.' '.$model->time
                             . '<a target="_blank" href="http://192.168.55.1/proffer/cek"> Перейти в адміністрування книгою скарг та пропозицій.</a> ')
                ->send();
            $session = Yii::$app->session;
            $session->open();
            $session->set('priz_decl', 0);
            return $this->render('process_ok', [
            'is' => $is
        ]);
        }
        else {

            return $this->render('inputproffer', [
                'model' => $model,
            ]);
        }
    }
    
    // Подгрузка населенных пунктов - происходит при наборе первых букв
    public function actionGet_search_town($name)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
                
        $name1 = mb_strtolower($name,"UTF-8");
        $name2 = mb_strtoupper($name,"UTF-8");
        if (Yii::$app->request->isAjax) {
            $sql = 'select min(id) as id,district,town from spr_towns where town like '.'"'.$name1.'%"'.
                    ' and length('.'"'.$name1.'")>3'.' group by district,town order by town,district';
             $cur = spr_towns::findBySql($sql)->all();

            return ['success' => true, 'cur' => $cur];

        }
    }

    // Подгрузка улиц - происходит при наборе первых букв
    public function actionGet_search_street($name,$str)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
                
        $name1 = mb_strtolower($name,"UTF-8");
        $name2 = mb_strtoupper($name,"UTF-8");
        $n = strpos($str, ',');
        $town=substr($str,0,$n);
        $n1 = strpos($str, 'р-н');
        $district=trim(substr($str,$n+1,($n1-$n-1)));
        if (Yii::$app->request->isAjax) {
            $sql = 'select min(id) as id,street from spr_towns where street like '.'"%'.$name1.'%"'.
                    ' and length('.'"'.$name1.'")>3'.' and town='.'"'.$town.'"'.
                    ' and district='.'"'.$district.'"'.
                    ' group by street order by street';
             $cur = spr_towns::findBySql($sql)->all();

            return ['success' => true, 'cur' => $cur];

        }
    }

    // Формирование инф. сообщения для исполнителя
    public function actionInfo_exec()
    {
        $sch = Yii::$app->request->post('sch');
        $mail = Yii::$app->request->post('mail');
        $sql = 'select a.*,b.Director,b.parrent_nazv,b.mail from vschet a,spr_res b'
            . ' where a.res=b.nazv and schet=:search';
        $model = viewschet::findBySql($sql,[':search'=>"$sch"])->one();
        return $this->render('info_exec',['model' => $model,'style_title' => 'd9','mail' => $mail]);
    }

    // Просмотр жалоб
    public function actionVcomplaint($item='')
    {
        $session = Yii::$app->session;
        $session->open();
        $session->set('priz_decl', 1);
        $searchModel = new vcomplaint();
       
        $flag=1;
        $role=0;
        if(!isset(Yii::$app->user->identity->role))
        {      $flag=0;}
        else{
            $role=Yii::$app->user->identity->role;
        }

        switch($role) {
             case 3: // Полный доступ
                $data = $searchModel::find()->all();
                $kol=count($data); 
                break;
             case 2:  // финансовый отдел
                $data = $searchModel::find()->where('status=:status',[':status' => 2])->
                orderBy(['status' => SORT_ASC])->all();
                break;
             case 1:  // бухгалтерия
                $data = $searchModel::find()->where('status=:status',[':status' => 5])->
                orderBy(['status' => SORT_ASC])->all();
                break;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$role,0);

       
        if (Yii::$app->request->get('item') == 'Excel' )
        {
            $newQuery = clone $dataProvider->query;
            $models = $newQuery->orderby(['date' => SORT_DESC])->all();
            $kind=1;
            $k1 = 'Інформація по рахункам';
//             Сброс в Excel
            if($kind==1){
                \moonland\phpexcel\Excel::widget([
                    'models' => $models,
                   
                    'mode' => 'export', //default value as 'export'
                    'format' => 'Excel2007',
                    'hap' => $k1,    //cтрока шапки таблицы
                    'data_model' => 1,
                    'columns' => ['status_sch','inn','nazv','addr','tel','schet','contract',
                        'usluga','summa','summa_beznds','summa_work','summa_delivery','summa_transport','res','date'],
                    'headers' => ['status_sch' => 'Cтатус заявки','inn' => 'ІНН','nazv' => 'Споживач','addr'=> 'Адрес','tel' => 'Телефон',
                        'schet' => 'Рахунок','contract' => '№ договору', 'usluga' => 'Послуга','summa' => 'Сума,грн.:','summa_beznds' => 'Сума без ПДВ,грн.:',
                        'summa_work' => 'Вартість робіт,грн.:','summa_delivery' => 'Доставка бригади,грн.:',
                        'summa_transport' => 'Транспорт всього,грн.:',
                        'res' => 'Виконавча служба:','date' => 'Дата'],
                ]);}
            return;
        }

        return $this->render('vcomplaint', [
            'model' => $searchModel,'dataProvider' => $dataProvider,'searchModel' => $searchModel,'kol'=>$kol
        ]);
    }

    // Просмотр предложений
    public function actionVproffer($item='')
    {
        $session = Yii::$app->session;
        $session->open();
        $session->set('priz_decl', 1);
        $searchModel = new vproffer();
       
        $flag=1;
        $role=0;
        if(!isset(Yii::$app->user->identity->role))
        {      $flag=0;}
        else{
            $role=Yii::$app->user->identity->role;
        }

        switch($role) {
             case 3: // Полный доступ
                $data = $searchModel::find()->all();
                 $kol=count($data);
                break;
             case 2:  // финансовый отдел
                $data = $searchModel::find()->where('status=:status',[':status' => 2])->
                orderBy(['status' => SORT_ASC])->all();
                break;
             case 1:  // бухгалтерия
                $data = $searchModel::find()->where('status=:status',[':status' => 5])->
                orderBy(['status' => SORT_ASC])->all();
                break;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$role);

       
        if (Yii::$app->request->get('item') == 'Excel' )
        {
            $newQuery = clone $dataProvider->query;
            $models = $newQuery->orderby(['date' => SORT_DESC])->all();
            $kind=1;
            $k1 = 'Інформація по рахункам';
//             Сброс в Excel
            if($kind==1){
                \moonland\phpexcel\Excel::widget([
                    'models' => $models,
                   
                    'mode' => 'export', //default value as 'export'
                    'format' => 'Excel2007',
                    'hap' => $k1,    //cтрока шапки таблицы
                    'data_model' => 1,
                    'columns' => ['status_sch','inn','nazv','addr','tel','schet','contract',
                        'usluga','summa','summa_beznds','summa_work','summa_delivery','summa_transport','res','date'],
                    'headers' => ['status_sch' => 'Cтатус заявки','inn' => 'ІНН','nazv' => 'Споживач','addr'=> 'Адрес','tel' => 'Телефон',
                        'schet' => 'Рахунок','contract' => '№ договору', 'usluga' => 'Послуга','summa' => 'Сума,грн.:','summa_beznds' => 'Сума без ПДВ,грн.:',
                        'summa_work' => 'Вартість робіт,грн.:','summa_delivery' => 'Доставка бригади,грн.:',
                        'summa_transport' => 'Транспорт всього,грн.:',
                        'res' => 'Виконавча служба:','date' => 'Дата'],
                ]);}
            return;
        }

        return $this->render('vproffer', [
            'model' => $searchModel,'dataProvider' => $dataProvider,'searchModel' => $searchModel,'kol'=>$kol
        ]);
    }

     // Запись данных в файл
    public function actionTofile()
    {
    $model = new tofile();

    if (Yii::$app->request->isPost) {
      $file = \yii\web\UploadedFile::getInstance($model, 'file');
    var_dump($file);
    return;
    $file->saveAs(\Yii::$app->basePath . $file);
    }
    return $this->render('tofile', ['model' => $model]);
    }  
    
    public function actionDownload($f)
    {
        $file = Yii::getAlias($f);
        return Yii::$app->response->sendFile($file);
    }

//    Страница о программе
    public function actionAbout()
    {
        $model = new info();
        $model->title = 'Про програму';
        $model->info1 = "Ця програма здійснює розрахунок робіт відповідно вибраному виду роботи, а також транспортні витрати.";
        $model->style1 = "d15";
        $model->style2 = "info-text";
        $model->style_title = "d9";

        return $this->render('about', [
            'model' => $model]);
    }

    //    Сброс в Excel результатов рассчета
    public function actionExcel($kind,$nazv,$rabota,$delivery,$transp,$all,$nds,$all_nds)
    {
        $k1='Результат розрахунку для послуги: '.$nazv;
        $param = 0;
        $model = new forExcel();
        $model->nazv = $nazv;
        $model->rabota = $rabota;
        $model->delivery = $delivery;
        $model->transp = $transp;
        $model->all = $all;
        $model->nds = $nds;
        $model->all_nds = $all_nds;
        if ($kind == 1) {
            \moonland\phpexcel\Excel::widget([
                'models' => $model,
                'mode' => 'export', //default value as 'export'
                'format' => 'Excel2007',
                'hap' => $k1, //cтрока шапки таблицы'hap' => $k1,
                'data_model' => $param,
                'columns' => ['nazv', 'rabota', 'delivery', 'transp', 'all', 'nds', 'all_nds',
                ],
            ]);
        }
        if ($kind == 2) {
            \moonland\phpexcel\Excel::widget([
                'models' => $model,
                'mode' => 'export', //default value as 'export'
                'format' => 'Excel2007',
                'hap' => $k1, //cтрока шапки таблицы'hap' => $k1,
                'data_model' => $param,
                'columns' => ['nazv', 'all', 'nds', 'all_nds',
                ],
            ]);
        }
        return;
    }

//    Обновление статуса жалобы
    public function actionUpd_complaint($id,$mod)
    {
        // $id  id записи
        // $mod - название модели
        if($mod=='complaint')
            $model = vcomplaint::find()->where('id=:id',[':id'=>$id])->one();
            $message_old = $model->message;
            $mail = $model->email;
            if(!empty($model->date))
                $model->date = date("d.m.Y", strtotime($model->date));
            
                        
        if ($model->load(Yii::$app->request->post()))
        {
            
             $message = $model->message;
             
            // Выявление изменения данных
            $priz_upd=0;
            $priz_status=0;
            $upd=[];
            $i=0;
            if($message_old!=$model->message) {
                $priz_upd=1;
                $upd[$i]='message';
                $i++;
            }    
           
            $model1 = complaint::find()->where('id=:id',[':id'=>$id])->one();
            $model1->status = $model->status;
            $model1->message = $message;
            
            if($priz_upd==1){
                
            }            
            if(!$model1->save(false))
            {  var_dump($model1);return;}

            // Отправка письма потребителю при изменении информации
            if($priz_upd==1){
                 
                
                Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail)
                ->setSubject('Відповідь на скаргу від ПрАТ «ПЕЕМ «ЦЕК»')
                ->setHtmlBody($message)
                ->send();
                
            }
            
            
            
            if($mod=='complaint')
                return $this->redirect(['site/vcomplaint']);

        } else {
            if($mod=='complaint')
                return $this->render('update_complaint', [
                    'model' => $model
                ]);
        }
    }
    
    //    Обновление статуса предложения
    public function actionUpd_proffer($id,$mod)
    {
        // $id  id записи
        // $mod - название модели
        if($mod=='proffer')
            $model = vproffer::find()->where('id=:id',[':id'=>$id])->one();
            $message_old = $model->message;
            $mail = $model->email;
            if(!empty($model->date))
                $model->date = date("d.m.Y", strtotime($model->date));
            
                    
        if ($model->load(Yii::$app->request->post()))
        {
            
             $message = $model->message;
             
            // Выявление изменения данных
            $priz_upd=0;
            $priz_status=0;
            $upd=[];
            $i=0;
            if($message_old!=$model->message) {
                $priz_upd=1;
                $upd[$i]='message';
                $i++;
            }    
           
            $model1 = proffer::find()->where('id=:id',[':id'=>$id])->one();
            $model1->status = $model->status;
            $model1->message = $message;
            
            if($priz_upd==1){
                
            }            
            if(!$model1->save(false))
            {  var_dump($model1);return;}

            // Отправка письма потребителю при изменении информации
            if($priz_upd==1){
                 
                
                Yii::$app->mailer->compose()
                ->setFrom('usluga@cek.dp.ua')
                ->setTo($mail)
                ->setSubject('Відповідь на скаргу від ПрАТ «ПЕЕМ «ЦЕК»')
                ->setHtmlBody($message)
                ->send();
                
            }
            
            
            
            if($mod=='proffer')
                return $this->redirect(['site/vproffer']);

        } else {
            if($mod=='proffer')
                return $this->render('update_proffer', [
                    'model' => $model
                ]);
        }
    }

    //   Статистика для администратора
    public function actionStatist()
    {
             $sql='select vid,item_status,count(item_status) as kol from vw_all group by vid,item_status order by vid';
            // $data = v_all::findBySql($sql)->all();
             $query = v_all::findBySql($sql);
             $query->sql = $sql;

                 $dataProvider = new ActiveDataProvider([
                 'query' => $query,
                 'sort' => false,
                     
                 ]);
             return $this->render('statist',['dataProvider' => $dataProvider]);
    }

    
       //   Поиск по сайту
    public function actionFind()
    {
             $s = Yii::$app->request->post('search');
             $session = Yii::$app->session;
             $session->open();
             $session->set('mem_page', 1);
             $search = '%'.$s.'%';
             if (preg_match("/\d{2}\.\d{2}/",$s)){
                $s=date("Y-m-d", strtotime($s));
                $sql='select * from vw_all where text like :search or date='."'".$s."'".' order by date desc,time desc';
             }
             else
                $sql='select * from vw_all where text like :search or name_p like '."'%".$s."%'".
                    // ' or post like '."'%".$s."%'".
                     ' order by date desc,time desc'; 
             $data = v_all::findBySql($sql,[':search'=>$search])->all();
             $kol = count($data);
             $session->set('mem_all', $kol);
             $init=1;
             return $this->render('top_record',['data' => $data,'kol' => $kol,'init' => $init]);
    }



    //    Просмотр документов
    public function actionDoc(){
        date_default_timezone_set('Europe/Kiev');
        $doc = Yii::$app->request->post('doc');
        $id = Yii::$app->request->post('id');

        $sql = 'select a.*from docs a'
            . ' where a.id_person=:search_doc and a.id_unique=:search_id';
        $model = docs::findBySql($sql,[':search_doc'=>$doc,':search_id'=>$id])->one();
        
        $f="store/".$model->file_path;
        
        $file = Yii::getAlias($f);
        return Yii::$app->response->sendFile($file);
        

    }    
       
// Добавление новых пользователей
    public function actionAddAdmin() {
        $model = User::find()->where(['username' => 'major'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'major';
            $user->email = 'major_cek@ukr.net';
            $user->setPassword('25xfcjdenhf');
            $user->generateAuthKey();
            if ($user->save()) {
                echo 'good';
            }
        }
        
    }

// Выход пользователя
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    
    public function fun_test(){
        $this->var="Результат";
    }
}
