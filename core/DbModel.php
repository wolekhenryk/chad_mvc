<?php

namespace app\core;

use app\models\User;

abstract class DbModel extends Model
{

    abstract public function userData() : array;

    abstract static public function userKey() : string;

    public function save() {
        $userParams = $this->userData();
        $data = array();
        foreach ($userParams as $param) {
            $data[$param] = $this->{$param};
        }
        $response = DB::get()->users->insertOne($data);
        return true;
    }

    public static function find($query): ?User {
        $dbResult = DB::get()->users->findOne($query);
        if (!$dbResult) {
            return null;
        }
        $user = new User();
        foreach ($user->userData() as $attr) {
            $user->{$attr} = $dbResult->{$attr};
        }
        return $user;
    }
}