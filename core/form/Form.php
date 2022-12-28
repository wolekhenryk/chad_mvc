<?php

namespace app\core\form;

use app\core\Model;

class Form
{
    public static function begin($action, $method, $id = ''): Form {
        echo '<form id="' . $id . '" action="' . $action . '" method="' . $method . '" enctype="multipart/form-data">';
        return new Form();
    }

    public static function end(): void {
        echo "</form>";
    }

    public function field(Model $model, $attr, $val = ''): Field {
        return new Field($model, $attr, $val);
    }
}