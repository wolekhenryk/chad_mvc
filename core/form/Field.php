<?php

namespace app\core\form;

use app\core\Application;
use app\core\Model;

class Field
{
    public const TYPE_TEXT = 'text';
    public const TYPE_EMAIL = 'email';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_FILE = 'file';
    public const TYPE_CHECKBOX = 'checkbox';

    public string $type;
    public Model $model;
    public string $attr;
    public string $val;
    public string $checked = '';

    public function __construct(Model $model, string $attr, string $val) {
        $this->type = self::TYPE_TEXT;
        $this->model = $model;
        $this->attr = $attr;
        $this->val = $val;
    }

    public function passwordField() {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function emailField() {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    public function fileField() {
        $this->type = self::TYPE_FILE;
        return $this;
    }

    public function checkBox($checked) {
        $this->type = self::TYPE_CHECKBOX;
        if ($checked) {
            $this->checked = 'checked';
        }
        return $this;
    }
    public function __toString() {
        if ($this->type === self::TYPE_CHECKBOX) {
            if ($this->checked === '') {
                return '
                 <div class="mb-3">
                    <label class="form-label">' . ($this->model->labels()[$this->attr] ?? $this->attr) . '</label>
                    <input type="' . $this->type . '" name="' . $this->attr . '" value="' . ($this->val ?? $this->model->{$this->attr}) . '"  class="form-check-input">
                    <div class="invalid-feedback">
                        ' . ($this->model->hasError($this->attr) ? 'is-invalid' : '') . '
                    </div>
                </div>
                ';
            } else {
                return '
                 <div class="mb-3">
                    <label class="form-label">' . ($this->model->labels()[$this->attr] ?? $this->attr) . '</label>
                    <input type="' . $this->type . '" name="' . $this->attr . '" value="' . ($this->val ?? $this->model->{$this->attr}) . '" checked  class="form-check-input">
                    <div class="invalid-feedback">
                        ' . ($this->model->hasError($this->attr) ? 'is-invalid' : '') . '
                    </div>
                </div>
                ';
            }
        }
        if ($this->type !== self::TYPE_FILE) {
            return sprintf('
             <div class="mb-3">
                <label class="form-label">%s</label>
                <input type="%s" name="%s" value="%s" id="%s"  class="form-control %s">
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
            ', $this->model->labels()[$this->attr] ?? $this->attr,
                    $this->type,
                    $this->attr,
                    $this->val ?? $this->model->{$this->attr},
                    $this->attr,
                    $this->model->hasError($this->attr) ? 'is-invalid' : '',
                    $this->model->getFirstError($this->attr)
            );
        } else {
            $invalidFile = false;
            $fileErrors = '';

            if ($this->model->hasError('type')) {
                $invalidFile = true;
                $fileErrors .= $this->model->getFirstError('type') . "<br>";
            }
            if ($this->model->hasError('size')) {
                $invalidFile = true;
                $fileErrors .= $this->model->getFirstError('size');
            }

            return sprintf('
             <div class="mb-3">
                <label class="form-label">%s</label>
                <input type="%s" name="%s" required class="form-control %s">
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
            ', $this->model->labels()[$this->attr] ?? $this->attr,
                    $this->type,
                    $this->attr,
                    $invalidFile ? 'is-invalid' : '',
                    $fileErrors
            );
        }
    }
}