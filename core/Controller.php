<?php

namespace app\core;

class Controller
{
    public string $layout = 'main';
    public function setLayout($layout) {
        $this->layout = $layout;
    }

    /**
     * @param $view
     * @param array $params
     * @return bool|array|string
     */
    public function render($view, array $params = [])
    {
        return Application::$app->router->renderView($view, $params);
    }
}