<?php 
namespace app\models;

use app\core\Model;

class ContactForm extends Model
{
    public string $subject = '';
    public string $email = '';
    public string $body = '';

    public function rules():array
    {
        return [
            'subject' => [self::RULES_REQUIRED],
            'email' => [self::RULES_REQUIRED],
            'body' => [self::RULES_REQUIRED],
        ];
    }

    public function labels():array
    {
        return [
            'subject' => 'Enter your subject',
            'email' => 'your Email',
            'body' => 'Body',
        ];
    }

    public function send()
    {
        return true;
    }
}
?>