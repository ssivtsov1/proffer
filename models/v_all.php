<?php
// Модель для просмотра жалоб и предложений в режиме администратора
namespace app\models;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers;

class V_all extends \yii\db\ActiveRecord
{ 
    public $kol;
        
    public static function tableName()
    {
        return 'vw_all';
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
            'tel_mob' => 'Телефон мобільний:',
            'tel_in' => 'Телефон внутр.:',
            'tel_town' => 'Телефон міський.:',
            'tab_nom' => 'Табельний №:',
            'send_result' => 'Надіслати результат',
            'post' => 'Посада',
            'main_unit' => 'Головний підрозділ',
            'unit_1' => 'Підпор. підрозділ',
            'unit_2' => 'Група',
            'date' => 'Дата пропозиції',
            'time' => 'Час',
            'id_unique' => 'Наявність файлу',
            'item_status' => 'Статус звернення',
            'status' => 'Статус звернення',
            'message' => 'Відповідь на пропозицію',
            'remote_addr'=> 'IP-адреса',
            'kol'=> 'Кількість',
            'vid'=> 'Звернення'
            
        ];
    }

    public function rules()
    { 
        return [
            [['text'],'safe'],
            [['tel_mob','tel_in','tel_town','email_spr','name_p','send_result','status','item_status',
              'id','id_person','id_unique','date','time','vid','email_group','email','message',
               'tab_nom','main_unit','unit_1','unit_2','post','date','time','remote_addr','kol'], 'safe'],
            ];
    }
    
     public function search($params,$role)
    {
        switch($role) {
            case 3: // Полный доступ
                $query = v_all::find();
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

