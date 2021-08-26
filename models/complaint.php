<?php
// Модель для жалоб
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers;

class Complaint extends \yii\db\ActiveRecord
{
    
    public $doc1;
    public $name_p;
    public $email;
    public $tel;
    public $priz_anonim;
    public $vid;
         
    public static function tableName()
    {
        return 'complaint';
    }

    public function attributeLabels()
    {
        return [
            'id' => '',
            'email' => 'Електронна пошта: *',
            'text' => 'Текст скарги:',
            'priz_anonim' => 'Анонім. *',
            'name_p' => 'Ваше ім’я: *',
            'tel' => 'Контактний телефон:',
            'doc1' => 'Завантажити документ або фото. Формати файлів: doc,docx,pdf,xls,xlsx,png,jpg',
            'send_result' => 'Надіслати результат розгляду скарги',
        ];
    }

    public function rules()
    { 
        date_default_timezone_set('Europe/Kiev');
        return [
            [['text'],'required'],
            [['tel','priz_anonim','email','name_p','send_result','status',
              'id','id_person','id_unique','date','time','vid'], 'safe'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['time'], 'default', 'value' => date('H:i')],
            ['email', 'email','message'=>'Не корректний адрес почти'],
            [['name_p','email','tel'], 'required', 'when' => function ($model) {
                return ($model->priz_anonim == 0) ;
            }, 'whenClient' => 'function (attribute, value) {
                return ($("#complaint-priz_anonim").is(":checked") == false);
            }'], 
            [['doc1'],'file','skipOnEmpty' => true,'extensions'=>'doc,docx,pdf,xls,xlsx,png,jpg'],
            ];
    }

          
     public function upload($d,$rand)
    {
            $n=substr($d,3);
            $path = "store/".$n.'_'.$rand.'-'.$this->$d->basename.'.'.$this->$d->extension;
            $this->$d->saveas($path);
            return true;
    }
    
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function getDb()
    {
        if (isset(Yii::$app->user->identity->role))
            return Yii::$app->get('db');
        else
            return Yii::$app->get('db');

    }

}

