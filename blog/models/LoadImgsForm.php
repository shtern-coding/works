<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\FileHelper;

/**
 * Модель отвечающая за загрузку изображений в админке
 */
class LoadImgsForm extends Model
{
    public $imgs;
    
    /**
     * Правила валидации
     */
    public function rules() 
    {
        return [
            [['imgs'], 'file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxFiles' => 0],
        ];
    }
    
    public function upload() 
    {
        if ($this->validate()) {
            foreach ($this->imgs as $img) {
                $img->saveAs('imgs/uploaded' . $this->getImageName() . '.' . $img->extension);
            }
            return true;
        } else {
            return false;
        }
    }
        
    /*
     * Получение списка файлов в папке imgs
     */
    public static function imgsList()
    {
        $imgs = FileHelper::findFiles('imgs', ['only' => ['*.jpeg', '*.jpg']]);
        natsort($imgs);
        return array_reverse($imgs);
    }
    
    /**
     * Создание имени изображения
     */
    public function getImageName() 
    {
        $filesList = self::imgsList();
        $lastFile = array_shift($filesList);
        $lastFileNum = preg_replace("/[^0-9]/", '', $lastFile);
        return "img (" . ($lastFileNum+1) . ")";
    }
    
    /**
     * Получение информации об изображении: высота, щирина и размер 
     * и помещение этого в массив, вместе с именем
     */
    public function imgInfo($imgAdress) 
    {
        $info = [];
        $info['adress'] = $imgAdress;
        $info['height'] = getimagesize($imgAdress)[1];
        $info['width'] = getimagesize($imgAdress)[0];
        $info['size'] = $this->fileSizeConvert(filesize($imgAdress));
        return $info;
    }
    
    /**
     * Обработка массива со списком файлов. Замена каждого адреса файла 
     * на массив с информацией об этом файле, включая и адресс. В итоге этот метод 
     * и отправляется в контроллер и далее в представление
     */
    public static function addInfo()
    {
        $array = array_map(array(new LoadImgsForm(), 'imgInfo'), self::imgsList());
        return $array;
    }
    
    /**
     * Конвертирует байты в килобайты и мегабайты и добавляет эти подписи
     */
    public function fileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
            $arBytes = array(
                0 => array(
                    "UNIT" => "Мб",
                    "VALUE" => pow(1024, 2)
                ),
                1 => array(
                    "UNIT" => "Кб",
                    "VALUE" => 1024
                ),
                2 => array(
                    "UNIT" => "б‘",
                    "VALUE" => 1
                )
            );
        foreach ($arBytes as $arItem)
        {
            if ($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }
}
