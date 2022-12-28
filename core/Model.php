<?php

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_MAX_SIZE = 'size';
    public const RULE_EXT = 'type';
    public const ALLOWED_SIZE = 1048576;
    public const RULE_UNIQUE = 'unique';
    public const WRONG_IMAGE = 'wrong';

    public function loadData($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function uploadFile($filename, $post)
    {
        $tempFileName = explode('.', $_FILES['imgFile']['name']);
        $newFileName = $filename . '.' . end($tempFileName);
        $folder = __DIR__.'/../web/uploads/img/';
        $miniFolder = __DIR__.'/../web/uploads/mini/';
        $watermarkFolder = __DIR__.'/../web/uploads/watermark/';
        $post->imgFile['name'] = $newFileName;
        move_uploaded_file($_FILES['imgFile']['tmp_name'], $folder . $newFileName);

        $mimeType = $_FILES['imgFile']['type'];
        if ($mimeType === 'image/jpeg') {
            $img = imagecreatefromjpeg($folder . $newFileName);
            $imgMini = imagecreatefromjpeg($folder . $newFileName);
            $imgWatermark = imagecreatefromjpeg($folder . $newFileName);;
        } else {
            $img = imagecreatefrompng($folder . $newFileName);
            $imgMini = imagecreatefrompng($folder . $newFileName);
            $imgWatermark = imagecreatefrompng($folder . $newFileName);
        }

        $imgMini = imagescale($imgMini, 200, 125, IMG_BILINEAR_FIXED);

        $color = imagecolorallocate($imgWatermark, 0, 0, 255);
        imagestring($imgWatermark, 20, 0, 0, $post->watermark, $color);

        if ($mimeType === 'image/jpeg') {
            imagejpeg($imgWatermark, $watermarkFolder . $newFileName);
            imagejpeg($imgMini, $miniFolder . $newFileName);
        } else {
            imagepng($imgWatermark, $watermarkFolder . $newFileName);
            imagepng($imgMini, $miniFolder . $newFileName);
        }
    }

    abstract public function rules() ;

    public array $errors = [];

    public function labels(): array {
        return [];
    }

    public function validate() {
        /*echo "<pre>";
        var_dump($this->rules());
        echo "<pre>";
        exit;*/
        $valid = true;
        foreach ($this->rules() as $attr => $rules) {
            $value = $this->{$attr};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attr, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attr, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attr, self::RULE_MIN, $rule);
                }
                if (!is_array($value) && $ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attr, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addErrorForRule($attr, self::RULE_MATCH, $rule);
                }
                /*if ($ruleName === self::RULE_UNIQUE) {
                    if (DB::get()->users->findOne(['email' => $attr])) {
                        $this->addErrorForRule($attr, self::RULE_UNIQUE, [
                            'field' => 'email'
                        ]);
                    }
                }*/
            }
            if ($attr === 'imgFile') {
                $fileValues = $this->{'imgFile'};
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileName = $fileValues['tmp_name'];
                $mimeType = finfo_file($fileInfo, $fileName);
                if ($mimeType !== 'image/png' && $mimeType !== 'image/jpeg' && $mimeType !== 'image/jpg') {
                    $this->addErrorForRule('type', self::RULE_EXT);
                }
                if ($fileValues['size'] >= self::ALLOWED_SIZE || $fileValues['size'] === 0) {
                    $this->addErrorForRule('size', self::RULE_MAX_SIZE);
                }
            }
        }
        return empty($this->errors);
    }

    private function addErrorForRule(string $attr, string $rule, $params = []) {
        $msg = $this->errorMsg()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $msg = str_replace("{{$key}}",$value , $msg);
        }
        $this->errors[$attr][] = $msg;
    }

    public function addError(string $attr, string $msg) {
        $this->errors[$attr][] = $msg;
    }

    public function errorMsg() {
        return [
            self::RULE_REQUIRED => 'To pole jest wymagane',
            self::RULE_EMAIL => 'Pole musi być poprawnym adresem email',
            self::RULE_MIN => 'Minimalna długość wynosi {min}',
            self::RULE_MAX => 'Maksymalna długość wynosi {max}',
            self::RULE_MATCH => 'Pole musi być zgodne z poprzednim',
            self::RULE_MAX_SIZE => 'Rozmiar nie może przkraczać 1MB',
            self::RULE_EXT => 'Zdjęcie musi być formatu jpg/png',
            self::RULE_UNIQUE => 'Rekord z takim adresem email już istnieje',
            self::WRONG_IMAGE => 'Zdjęcie jest niepoprawne'
        ];
    }

    public function hasError($attr) {
        return $this->errors[$attr] ?? false;
    }

    public function getFirstError(string $attr) {
        return $this->errors[$attr][0] ?? false;
    }
}