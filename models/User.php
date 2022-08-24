<?php

namespace app\models;

use app\core\DbModel;
use app\core\Model;
use app\models\UserModel;

class User extends UserModel
{
    public int $id = 0;
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';

    public static function tableName(): string
    {
        return 'users';
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function attributes(): array
    {
        return ['firstName', 'lastName', 'email', 'password'];
    }

    public function labels(): array
    {
        return [
            'firstName' => 'First name',
            'lastName' => 'Last name',
            'email' => 'Your Email',
            'password' => 'Password',
            'confirmPassword' => 'Password Confirm'
        ];
    }

    public function rules(): array
    {
        return [
            'firstName' => [self::RULES_REQUIRED],
            'lastName' => [self::RULES_REQUIRED],
            'email' => [self::RULES_REQUIRED, self::RULES_EMAIL, [
                self::RULES_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULES_REQUIRED, [self::RULES_MIN, 'min' => 8]],
            'confirmPassword' => [[self::RULES_MATCH, 'match' => 'password']],
        ];
    }

    public function save()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function getDisplayName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}