<?php
namespace Tets\Oop;
session_start();

class Membre
{
    public $IdMb,$Statut,$Nom,$Prénom,$Email,$Mdps,$CIN,$Profile;

    public function construct($Statut,$Nom,$Prénom,$Email,$Mdps,$CIN,$Profile):void{
        $this->Statut=$Statut;
        $this->Nom=$Nom;
        $this->Prénom=$Prénom;
        $this->Email=$Email;
        $this->Mdps=$Mdps;
        $this->CIN=$CIN;
        $this->Profile=$Profile;
    }
    public static function password_encrypt($password):string{
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }
    public static function password_decrypt($password,$hash):bool{
        if(password_verify($password,$hash)){
            return true;
        }
        else{
            return false;
        }
    }
    public static function Connect():bool
    {
        if (isset($_SESSION['membre'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function Admin():bool
    {
        if (self::Connect() && ($_SESSION['membre']['Statut'] == 1)) {
            return true;
        } else {
            return false;
        }
    }

    public static function Collab():bool
    {
        if (self::Connect() && ($_SESSION['membre']['Statut'] == 0)) {
            return true;
        } else {
            return false;
        }
    }
}