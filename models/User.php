<?php

namespace app\models;

use app\core\DbModel;
use app\core\Model;

// class RegisterModel extends DbModel
// {
//     public string $firstName = '';
//     public string $lastName = '';
//     public string $email = '';
//     public string $password = '';
//     public string $confirmPassword = '';

//     public function register() {
//         echo 'creating new user';
//     }

//     public function rules(): array {
//         return [
//             'firstName' => [self::RULES_REQUIRED],
//             'lastName' => [self::RULES_REQUIRED],
//             'email' => [self::RULES_REQUIRED, self::RULES_EMAIL],
//             'password' => [self::RULES_REQUIRED, [self::RULES_MIN, 'min' => 8] , [self::RULES_MAX, 'max' => 24]],
//             'confirmPassword' => [self::RULES_REQUIRED, [self::RULES_MATCH, 'match' => 'password']],
//         ];
//     }
// }
class User extends UserModel
{
    public int $id = 0;
    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';

    public static function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return ['firstname', 'lastname', 'email', 'password'];
    }

    public function labels(): array
    {
        return [
            'firstname' => 'First name',
            'lastname' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'passwordConfirm' => 'Password Confirm'
        ];
    }

    public function rules()
    {
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [[self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    public function save()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function getDisplayName(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}