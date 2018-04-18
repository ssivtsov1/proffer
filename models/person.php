<?php
// Модель для данных пользователей
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers;

class Person extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'person';
    }

    public function attributeLabels()
    {
        return [
            'id' => '',
            'email' => 'Електронна пошта: *',
            'name_p' => 'Ваше ім’я: *',
            'tel' => 'Контактний телефон:',
            'id_person' => 'Унікальний №',
        ];
    }

    public function rules()
    {
        return [
            [['tel','email','name_p','r_tel',
              'id','id_person','id_unique',
              'remote_addr','remote_host','remote_user'], 'safe'],
            ];
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

