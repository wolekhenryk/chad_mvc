<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\DB;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

class AuthController extends Controller
{
    public function login(Request $request, Response $response) {
        if (Application::Auth()) {
            Application::$app->response->redirect('/');
        }
        $loginForm = new LoginForm();
        if ($request->isPost()) {
            $loginForm->loadData($request->getRequestData());
            /*foreach ($_POST as $userData) {
                $loginForm->$userData = $userData;
            }*/
            if ($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/');
                return;
            }
        }

        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }

    public function register(Request $request) {
        if (Application::Auth()) {
            Application::$app->response->redirect('/');
        }
        $user = new User();
        $this->setLayout('auth');
        if ($request->isPost()) {
            $user->loadData($request->getRequestData());
            if ($user->validate() && $user->save()) {
                Application::$app->session->flash('success', 'Zarejestrowano pomyślnie');
                Application::$app->response->redirect('/');
                exit;
            }
            return $this->render('register', [
                'model' => $user
            ]);
        }
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response) {
        Application::$app->logout();
        $response->redirect('/');
    }
}