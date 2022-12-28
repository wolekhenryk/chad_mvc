<?php

namespace app\core;

use app\models\User;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public DB $database;
    public ?User $user;
    public static Application $app;
    public Controller $controller;
    public function __construct($rootPath) {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->database = new DB();

        if ($this->session->get('user')) {
            $this->user = User::find(['login' => $this->session->get('user')]);
        } else {
            $this->user = null;
        }
    }

    public function run(): void {
        echo $this->router->resolve();
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public static function Auth(): ?User {
       return self::$app->user;
    }

    public function login(DbModel $user): bool {
        $this->session->set('user', $user->login);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
}