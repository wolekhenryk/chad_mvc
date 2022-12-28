<?php

namespace app\models;
use app\core\Application;
use app\core\DB;
use app\core\Model;
use MongoDB\Database;

class Post extends Model
{
    public string $imgTitle = '';
    public string $watermark = '';
    public array $imgFile = [
        'name' => '',
        'type' => '',
        'tmp_name' => '',
        'error' => '',
        'size' => 0
    ];
    public string $when = '';
    public string $author = '';
    public bool $private = false;

    public function fillData($file) {
        foreach ($file as $key => $value) {
            $this->imgFile[$key] = $value;
        }
    }

    public function upload($filename)
    {
        /*$im = imagecreate(100, 30);
        $bg = imagecolorallocate($im, 255, 255, 255);
        $txtcolor = imagecolorallocate($im, 0, 0, 255);
        imagestring($im, 5, 0, 0, $this->watermark, $txtcolor);
        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($im);
        exit;*/
        $response = DB::get()->posts->insertOne([
            'imgTitle' => $this->imgTitle,
            'imgFile' => $this->imgFile['name'],
            'when' => $filename,
            'author' => $this->author,
            'private' => $this->private
        ]);
        return true;
    }

    public function rules() {
        return [
            'imgTitle' => [self::RULE_REQUIRED],
            'watermark' => [self::RULE_REQUIRED],
            'imgFile' => [],
            'author' => [self::RULE_REQUIRED]
        ];
    }

    public function labels() : array {
        return [
            'imgTitle' => 'Tytuł zdjęcia',
            'imgFile' => 'Plik ze zdjęciem',
            'watermark' => 'Znak wodny',
            'author' => 'Autor',
            'fav[]' => 'Ulubione',
            'favDelete[]' => 'Usuń z ulubionych',
            'private' => 'Prywatne',
            'searchImgTitle' => 'Tytuł zdjęcia'
        ];
    }
}