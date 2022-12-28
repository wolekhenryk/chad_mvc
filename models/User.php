<?php

namespace app\models;
use app\core\DB;
use app\core\DbModel;
use app\core\Hash;
use app\core\Model;

class User extends DbModel {
    public string $login = '';
    public string $email = '';
    public string $password = '';
    public string $_id = '';
    public string $password_confirmation = '';
    public int $status = 0;
    public string $when = '';

    public function userData() : array {
        return array('login', 'email', 'password', 'status', 'when');
    }

    public function save(): bool {
        $this->when = round(microtime(true));
        $this->password = Hash::Make($this->password);
        $this->password_confirmation = '';
        return parent::save();
    }

    public function rules(): array {
        return [
            'login' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, self::RULE_UNIQUE],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 64]],
            'password_confirmation' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function labels() : array {
        return [
            'login' => 'Login',
            'email' => 'Adres email',
            'password' => 'Hasło',
            'password_confirmation' => 'Powtórz hasło'
        ];
    }

    public static function userKey(): string {
        return '_id';
    }
}