<?php
// Модель для просмотра жалоб в режиме администратора
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers;

class Vproffer extends \yii\db\ActiveRecord
{
        
    public static function tableName()
    {
        return 'vw_proffer';
    }

    public function attributeLabels()
    {
        return [
            'id' => '',
            'email_group' => 'Ел. пошта підрозд.:',
            'email_spr' => 'Ел. пошта з тел. довід.:',
            'email' => 'Ел. пошта',
            'text' => 'Текст пропозиції:',
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
            'date' => 'Дата пропозиції',
            'time' => 'Час',
            'id_unique' => 'Наявність файлу',
            'item_status' => 'Статус',
            'status' => 'Статус',
            'message' => 'Відповідь на пропозицію',
            'remote_addr' => 'IP-адреса'
        ];
    }

    public function rules()
    { 
        return [
            [['text'],'safe'],
            [['tel_mob','tel_in','tel_town','email_spr','name_p','send_result','status','item_status',
              'id','id_person','id_unique','date','time','vid','email_group','email','message',
               'tab_nom','main_unit','unit_1','unit_2','post','date','time','remote_addr'], 'safe'],
            ];
    }
    
     public function search($params,$role)
    {
        switch($role) {
            case 3: // Полный доступ
                $query = vproffer::find();
                break;
           
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=> ['date'=>SORT_DESC,'time'=>SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'text', $this->text]);
        $query->andFilterWhere(['like', 'main_unit', $this->main_unit]);
        $query->andFilterWhere(['like', 'unit_1', $this->unit_1]);
        $query->andFilterWhere(['like', 'unit_2', $this->unit_2]);
        $query->andFilterWhere(['like', 'tel_mob', $this->tel_mob]);
        $query->andFilterWhere(['=', 'tab_nom', $this->tab_nom]);
        $query->andFilterWhere(['=', 'date', $this->date]);
        $query->andFilterWhere(['=', 'status', $this->status]); 
        
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

