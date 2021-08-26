<?php
/*Ввод данных для расширенного поиска жалоб и предложений*/

namespace app\models;

use Yii;
use yii\base\Model;

class Find_rec extends Model
{
    public $date1;              // Начальная дата для задания периода
    public $date2;              // Конечная дата для задания периода
    public $priz;               // Признак отправки уведомления 
    public $text;               // Текст жалобы или предложения
    public $post;               // Должность
    public $name_p;             // ФИО
    public $tab_nom;            // Табельный №
    public $remote_addr;        // IP Адрес
    public $main_unit;  
    public $unit_1;
    public $unit_2;
    public $status;
    public $priz_message;
    public $send_result;
    public $message;
    public $priz_doc;
    private $_user;

    public function attributeLabels()
    {
        return [
            'date1' => 'Початкова дата періоду',
            'date2' => 'Кінцева дата періоду',
            'text' => 'Текст скарги:',
            'name_p' => 'П.І.Б.',
            'tab_nom' => 'Таб. №:',
            'send_result' => 'Надіслати результат',
            'post' => 'Посада',
            'main_unit' => 'Головний підрозділ',
            'unit_1' => 'Підпор. підрозділ',
            'unit_2' => 'Група',
            'priz_doc' => 'Наявність файлу',
            'priz_message' => 'Наявність відповіді',
            'status' => 'Статус',
            'message' => 'Відповідь на скаргу',
            'remote_addr' => 'IP-адреса'
            ];
    }

    public function rules()
    {
        return [
           
            ['date1', 'safe'],
            ['date2', 'safe'],
            ['text', 'safe'],
            ['name_p', 'safe'],
            ['tab_nom', 'safe'],
            ['post', 'safe'],
            ['main_unit', 'safe'],
            ['unit_1', 'safe'],
            ['unit_2', 'safe'],
            ['priz_doc', 'safe'],
            ['priz_message', 'safe'],
            ['status', 'safe'],
            ['send_result', 'safe'],
            ['message', 'safe'],
            ['remote_addr', 'safe'],
        ];
    }

}
