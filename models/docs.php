<?php
/**
 * Модель для ссылок на документы
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class Docs extends ActiveRecord
{
    public static function tableName()
    {
        return 'docs';
    }    


    public function rules()
    {
        return [
            [['id','id_person','item_id','file_path','id_unique'], 'safe'],
        ];
    }
}
