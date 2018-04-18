<?php
// Модель для просмотра жалоб в режиме администратора
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers;

class Vcomplaint_ex extends \yii\db\ActiveRecord
{
    public $message;
    public $date_a;
    
    public static function tableName()
    {
        return 'vw_complaint';
    }

    public function attributeLabels()
    {
        return [
            'id' => '',
            'email_group' => 'Ел. пошта підрозд.:',
            'email_spr' => 'Ел. пошта з тел. довід.:',
            'email' => 'Ел. пошта',
            'text' => 'Текст скарги:',
            'name_p' => 'П.І.Б.',
            'tel_mob' => 'Телефон моб.:',
            'tel_in' => 'Телефон внутр.:',
            'tel_town' => 'Телефон міський.:',
            'tab_nom' => 'Таб. №:',
            'send_result' => 'Надіслати результат',
            'post' => 'Посада',
            'main_unit' => 'Головний підрозділ',
            'unit_1' => 'Підпор. підрозділ',
            'unit_2' => 'Група',
            'date' => 'Дата скарги',
            'date_a' => 'Дата скарги',
            'time' => 'Час',
            'id_unique' => 'Наявність файлу',
            'item_status' => 'Статус',
            'status' => 'Статус',
            'message' => 'Відповідь на скаргу',
            'remote_addr' => 'IP-адреса'
        ];
    }

    public function rules()
    { 
        return [
            [['text'],'safe'],
            [['tel_mob','tel_in','tel_town','email_spr','name_p','send_result','status','item_status',
              'id','id_person','id_unique','date','time','vid','email_group','email','message',
               'tab_nom','main_unit','unit_1','unit_2','post','date','date_a','time','remote_addr'], 'safe'],
             
            ];
    }
    
   public function search($params,$sql)
    {
        $query = vcomplaint_ex::findBySql($sql);
        $query->sql = $sql;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            
        ]);
       
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

//        $query->andFilterWhere(['like', 'fio', $this->fio]);
//        $query->andFilterWhere(['like', 'post', $this->post]);
//        $query->andFilterWhere(['like', 'tel_mob', only_digit($this->tel_mob)]);
//       
//        $query->andFilterWhere(['like', 'tel', $this->tel]);
//        $query->andFilterWhere(['like', 'tel_town', only_digit($this->tel_town)]);
        return $dataProvider;
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

