<?php

namespace app\core;

class Request
{
    /**
     * @return mixed
     */
    public function getPath() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return $path = substr($path, 0, $position);
    }

    /**
     * @return string
     */
    public function method() {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return bool
     */
    public function isGet() {
        return $this->method() === 'get';
    }

    /**
     * @return bool
     */
    public function isPost() {
        return $this->method() === 'post';
    }

    /**
     * @return array
     */
    public function getRequestData() {
        $body = [];
        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->method() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if (isset($_FILES['imgFile'])) {
                $body['imgFile'] = [];
                foreach ($_FILES['imgFile'] as $fileKey => $fileVal) {
                    $body['imgFile'] = filter_input(INPUT_POST, $fileKey, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
        return $body;
    }
}