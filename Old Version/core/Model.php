<?php

namespace app\core;

abstract class Model
{
    public const RULES_REQUIRED = 'required';
    public const RULES_EMAIL = 'email';
    public const RULES_MIN = 'min';
    public const RULES_MAX = 'max';
    public const RULES_MATCH = 'match';
    public const RULES_UNIQUE = 'unique';


    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;

    public array $errors = [];

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULES_REQUIRED && !$value) {
                    $this->addError($attribute, self::RULES_REQUIRED);
                }
                if ($ruleName === self::RULES_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULES_EMAIL);
                }
                if ($ruleName === self::RULES_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute, self::RULES_MIN, $rule);
                }
                if ($ruleName === self::RULES_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute, self::RULES_MAX, $rule);
                }
                if ($ruleName === self::RULES_MATCH && $value !== $this->{$rule ['match']}) {
                    $this->addError($attribute, self::RULES_MATCH, $rule);
                }
                if ($ruleName === self::RULES_UNIQUE)   {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * $tableName WHERE $uniqueAttr = :$uniqueAttr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if($record) {
                        $this->addError($attribute, self::RULES_UNIQUE, ['field' => $attribute]);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }
    public function errorMessages()
    {
        return [
            self::RULES_REQUIRED => 'This field is required',
            self::RULES_EMAIL => 'This field must be valid email address',
            self::RULES_MIN => 'Min length of this field must be {min}',
            self::RULES_MAX => 'Max length of this field must be {max}',
            self::RULES_MATCH => 'This field must be the same as {match}',
            self::RULES_UNIQUE => 'Record with same email exists',
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }
    public function getFirstError($attribute)   {
        return $this->errors[$attribute][0] ?? false;
    }
}
