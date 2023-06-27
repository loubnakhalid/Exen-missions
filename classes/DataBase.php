<?php

namespace Tets\Oop;

use PDO;

class DataBase
{
    private const DSN = 'mysql:host=localhost;dbname=exenmissions';
    private const USER = 'root';
    private const PASS = '';

    public static function connect():object{
        $con = new PDO(self::DSN, self::USER, self::PASS);
        return $con;
    }
    public static function getData($table):mixed {
        $con=self::connect();
        $rslt=$con->query("select * from $table");
        return $rslt->fetchAll();
    }
    public static function getDataWhere($table,$where){
        $con=self::connect();
        $rslt=$con->query("select * from $table where $where");
        if($rslt->rowCount() != 0){
            return $rslt->fetchAll();
        }
        else{
            return false;
        }
    }
    public static function insertData($table,$assoc_array):bool{
        $con=self::connect();
        $keys = array();
        $values = array();
        foreach($assoc_array as $key => $value){
            $keys[] = $key;
            $values[] = $value;
        }
        $query = "INSERT INTO $table (`".implode("`,`", $keys)."`) VALUES('".implode("','", $values)."')";

        return $con->exec($query);
    }
    public static function updateData($table,$data,$where):bool{
        $con=self::connect();
        $cols = array();
        foreach($data as $key=>$val) {
            $cols[] = "$key = '$val'";
        }
        $query = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";
        return $con->exec($query);
    }
    public static function deleteData($table,$where):bool{
        $con=self::connect();
        return $con->exec("DELETE FROM $table WHERE $where");
    }
}
?>