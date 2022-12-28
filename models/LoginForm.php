<?php

namespace app\models;

use app\core\Application;
use app\core\DB;
use app\core\Hash;
use app\core\Model;

class LoginForm extends Model
{
    public string $login = '';
    public string $password = '';
    public function rules(): array {
        return [
            'login' => [self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function login(): bool {
        $user = User::find(['login' => $this->login]);
        if ($user === null) {
            $this->addError('login', 'Błędne dane logowania');
            $this->addError('password', 'Błędne dane logowania');
            return false;
        }
        if (!Hash::Verify($this->password, $user->password)) {
            $this->addError('login', 'Błędne dane logowania');
            $this->addError('password', 'Błędne dane logowania');
            return false;
        }
        return Application::$app->login($user);
    }

    public function labels() : array {
        return [
            'login' => 'Twój login',
            'password' => 'Twoje hasło'
        ];
    }
}