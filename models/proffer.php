<?php
// Модель для предложений
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers;

class Proffer extends \yii\db\ActiveRecord
{
    
    public $doc1;
    public $name_p;
    public $email;
    public $tel;
    public $priz_anonim;
    public $vid;
         
    public static function tableName()
    {
        return 'proffer';
    }

    public function attributeLabels()
    {
        return [
            'id' => '',
            'email' => 'Електронна пошта: *',
            'text' => 'Текст пропозиції або побажання:',
            'priz_anonim' => 'Анонім. *',
            'name_p' => 'Ваше ім’я: *',
            'tel' => 'Контактний телефон:',
            'doc1' => 'Завантажити документ чи фото. Формати файлів: doc,docx,pdf,xls,xlsx,png,jpg',
            'send_result' => 'Надіслати результат розгляду скарги',
            
        ];
    }

    public function rules()
    {   date_default_timezone_set('Europe/Kiev');
        return [
            [['text'],'required','message'=>'Поле обов’язкове'],
            [['tel','priz_anonim','email','name_p','doc1','send_result',
              'id','id_person','id_unique','status'], 'safe'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['time'], 'default', 'value' => date('H:i')],
            [['name_p','email','tel'], 'required', 'when' => function ($model) {
                return ($model->priz_anonim == 0) ;
            }, 'whenClient' => 'function (attribute, value) {
                return ($("#proffer-priz_anonim").is(":checked") == false);
            }'], 
            [['doc1'],'file','skipOnEmpty' => true,'extensions'=>'doc,docx,pdf,xls,xlsx,png,jpg']        
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

