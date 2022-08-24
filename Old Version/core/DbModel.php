<?php
namespace app\core;

use app\core\Model;


abstract class DbModel extends Model
{
    abstract public function tableName(): string;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = [];
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).") 
        VALUES (".implode(',' , $params).")");
        
        
        foreach($attributes as $attribute)  {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }

    public function prepare($sql)
    {
        return Application::$app->db->pdp->prepare();
    }

    public function attributes() : array
    {
        return ['firstname', 'lastname', 'email', 'password'];
    
    }
}