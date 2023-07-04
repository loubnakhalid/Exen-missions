<?php
namespace Tets\Oop;
class Membre
{

    private $id;
    private $Nom;
    private $Prénom;
    private $TitreCivilité;
    private $Grp;
    private $Email;
    private $Mdps;
    private $CIN;
    private $Profil;

    public function __construct($id, $Nom, $Prénom,$TitreCivilité,$Grp, $Email,$Mdps, $CIN, $Profil){
        $this->id = $id;
        $this->Nom = $Nom;
        $this->Prénom = $Prénom;
        $this->TitreCivilité=$TitreCivilité;
        $this->Grp = $Grp;
        $this->Email = $Email;
        $this->Mdps = $Mdps;
        $this->CIN = $CIN;
        $this->Profil = $Profil;
    }
    public function setMdps($Mdps){
        $this->Mdps=$Mdps;
    }
    public function getNom(){
        return $this->Nom;
    }
    public function getPrénom(){
        return $this->Prénom;
    }
    public function getEmail(){
        return $this->Email;
    }
    public function getCIN(){
        return $this->CIN;
    } 
    public function getGrp(){
        return $this->Grp;
    }
    public function getTitreCivilité(){
        return $this->TitreCivilité;
    }
    public function getProfil(){
        return $this->Profil;
    }
    public function ajouterCollaborateur(){
        if(!empty($this->Grp)){
            return DataBase::insertData('membres',array(
                'IdG' => $this->Grp,
                'Statut' => 0,
                'Nom' => $this->Nom,
                'PréNom' => $this->Prénom,
                'TitreCivilité' => $this->TitreCivilité,
                'Email' => $this->Email,
                'Mdps' => $this->Mdps,
                'CIN' => $this->CIN,
                'Profil' => $this->Profil,
            ));
        }
        else{
            return DataBase::insertData('membres',array(
                'Statut' => 0,
                'Nom' => $this->Nom,
                'PréNom' => $this->Prénom,
                'TitreCivilité' => $this->TitreCivilité,
                'Email' => $this->Email,
                'Mdps' => $this->Mdps,
                'CIN' => $this->CIN,
                'Profil' => $this->Profil,
            ));
        }
    } 
    public function modifierCollaborateur(){
        $updateData = array(
            "Nom" => $this->Nom,
            "PréNom" => $this->Prénom,
            'TitreCivilité' => $this->TitreCivilité,
            "IdG" => $this->Grp,
            "Email" => $this->Email,
            "CIN" => $this->CIN,
            "Profil" => $this->Profil,
        );
        if (isset($this->Mdps) && $this->Mdps !== '') {
            $updateData['Mdps'] = $this->Mdps;
        }
        $id=$this->id;
        return DataBase::updateData('membres', $updateData, "IdMb=$id");
    }
    public function modifierAdmin(){
        $id=$this->id;
        return DataBase::updateData('membres',array(
            "Nom" => $this->Nom,
            "PréNom" =>$this->Prénom,
            "Email" =>$this->Email,
        ),"IdMb=$id");
    }
    public static function delete($id){
        return DataBase::deleteData('membres', "IdMb='$id'");
    }
    public static function getById($id){
        $row = DataBase::getDataWhere('membres', "IdMb='$id'");
        if ($row) {
            return new Membre(
                $row[0]['IdMb'],
                $row[0]['Nom'],
                $row[0]['Prénom'],
                $row[0]['TitreCivilité'],
                $row[0]['IdG'],
                $row[0]['Email'],
                $row[0]['Mdps'],
                $row[0]['CIN'],
                $row[0]['Profil']
            );
        } else {
            return null;
        }
    }
    public function comparer(Membre $autreMembre) {
        if ($this->id == $autreMembre->id &&
            $this->Nom == $autreMembre->Nom &&
            $this->Prénom == $autreMembre->Prénom &&
            $this->TitreCivilité == $autreMembre->TitreCivilité &&
            $this->Grp == $autreMembre->Grp &&
            $this->Email == $autreMembre->Email &&
            $this->CIN == $autreMembre->CIN &&
            $this->Profil == $autreMembre->Profil) {
            return true;
        }
        
        return false;
    }

    public static function vérifEmail($Email){
        return DataBase::getDataWhere('membres',"Email='$Email'");
    }
    public static function vérifEmailId($Email,$id){
        return DataBase::getDataWhere('membres',"Email='$Email' and IdMb != '$id'");
    }
    public static function vérifCIN($CIN){
        return DataBase::getDataWhere('membres',"CIN='$CIN'");
    }
    public static function vérifCINId($CIN,$id){
        return DataBase::getDataWhere('membres',"CIN='$CIN' and IdMb != '$id'");
    }
    public static function vérifMdps($IdMb,$Mdps){
        $row=DataBase::getDataWhere('membres',"IdMb=$IdMb");
        return password_verify($Mdps,$row[0]['Mdps']);
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
    public static function getLastId(){
        $con=DataBase::connect();
        $query=$con->query("select * from membres where Statut=0 order by IdMb desc limit 1");
        $row=$query->fetch();
        return $row['IdMb'];
    }
    public static function Connect():bool{
        if (isset($_SESSION['membre'])) {
            return true;
        } else {
            return false;
        }
    }
    public static function Admin():bool{
        if (self::Connect() && ($_SESSION['membre']['Statut'] == 1)) {
            return true;
        } else {
            return false;
        }
    }
    public static function Collab():bool{
        if (self::Connect() && ($_SESSION['membre']['Statut'] == 0)) {
            return true;
        } else {
            return false;
        }
    }
    public static function countMissions($IdMb){
        $con=DataBase::connect();
        $query=$con->query("select * from missions where IdMb='$IdMb' and deletedAt is NULL and StatutMiss='1'");
        return $query->rowCount();
    }
    public static function countTotalRemb($IdMb){
        $row=DataBase::getDataWhere('missions',"IdMb=$IdMb and Montant is not null and deletedAt is null and StatutMiss='1'");
        $remb=0;
        foreach($row as $row){
            $remb+=$row['Montant'];
        }
        return $remb;
    }
}
