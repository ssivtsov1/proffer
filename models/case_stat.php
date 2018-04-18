<?php
/*Ввод данных для условий по статистике*/

namespace app\models;

use Yii;
use yii\base\Model;

class Case_stat extends Model
{
    public $date1;              // Начальная дата для задания периода
    public $date2;              // Конечная дата для задания периода
    public $anonim;             // Признак аноним/не аноним 

    public function attributeLabels()
    {
        return [
            'date1' => 'Початкова дата періоду',
            'date2' => 'Кінцева дата періоду',
            'anonim' =>'Признак особи:',
            ];
    }

    public function rules()
    {
        return [
            ['date1', 'safe'],
            ['date2', 'safe'],
            ['anonim', 'safe'],
        ];
    }

}
