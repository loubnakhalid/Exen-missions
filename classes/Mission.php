<?php
namespace Tets\Oop;
class Mission
{
    private $IdMiss,$RéfMiss,$IdMb,$ObjMiss,$LieuDép,$MoyTrans,$Départ,$Retour,$Durée,$DateMiss,$TypeMiss,$Accomp,$Note,$StatutMiss,$IdPaiement;
    public function __construct($IdMiss,$RéfMiss,$IdMb,$ObjMiss,$LieuDép,$MoyTrans,$Départ,$Retour,$Durée,$DateMiss,$TypeMiss,$Accomp,$Note,$StatutMiss){
        $this->IdMiss=$IdMiss;
        $this->RéfMiss=$RéfMiss;
        $this->IdMb=$IdMb;
        $this->ObjMiss=$ObjMiss;
        $this->LieuDép=$LieuDép;
        $this->MoyTrans=$MoyTrans;
        $this->Départ=$Départ;
        $this->Retour=$Retour;
        $this->Durée=$Durée;
        $this->DateMiss=$DateMiss;
        $this->TypeMiss=$TypeMiss;
        $this->Accomp=$Accomp;
        $this->Note=$Note;
        $this->StatutMiss=$StatutMiss;
    }
    public function getIdMb(){
        return $this->IdMb;
    }
    public function getRéfMiss(){
        return $this->RéfMiss;
    }
    public function getObjMiss(){
        return $this->ObjMiss;
    }
    public function getLieuDép(){
        return $this->LieuDép;
    }
    public function getMoyTrans(){
        return $this->MoyTrans;
    }
    public function getDépart(){
        return $this->Départ;
    }
    public function getRetour(){
        return $this->Retour;
    }
    public function getDurée(){
        return $this->Durée;
    }
    public function getDateMiss(){
        return $this->DateMiss;
    }
    public function getTypeMiss(){
        return $this->TypeMiss;
    }
    public function getAccomp(){
        return $this->Accomp;
    }
    public function getNote(){
        return $this->Note;
    }
    public function getStatutMiss(){
        return $this->StatutMiss;
    }
    public function getIdPaiement(){
        return $this->IdPaiement;
    }
    public function setIdPaiement($id){
        $this->IdPaiement=$id;
    }
    public function ajouterMission(){
        return DataBase::insertData('missions',array(
            "RéfMiss" => $this->RéfMiss,
            "IdMb" =>  $this->IdMb,
            "ObjMiss" => $this->ObjMiss,
            "LieuDép" => $this->LieuDép,
            "MoyTrans" => $this->MoyTrans,
            "Départ" => $this->Départ,
            "Retour" => $this->Retour,
            "Durée" => $this->Durée,
            "DateMiss" => $this->DateMiss,
            "TypeMiss" => $this->TypeMiss,
            "Accomp" => $this->Accomp,
            "Note" => $this->Note,
            "StatutMiss" => "0",
        ));
    }
    public function modifierMission(){
        $id=$this->IdMiss;
        return DataBase::updateData('missions',array(
            "IdMb" =>  $this->IdMb,
            "ObjMiss" => $this->ObjMiss,
            "LieuDép" => $this->LieuDép,
            "MoyTrans" => $this->MoyTrans,
            "Départ" => $this->Départ,
            "Retour" => $this->Retour,
            "Durée" => $this->Durée,
            "DateMiss" => $this->DateMiss,
            "TypeMiss" => $this->TypeMiss,
            "Accomp" => $this->Accomp,
            "Note" => $this->Note,
            "StatutMiss" => "0",
        ),"IdMiss=$id");
    }
    public static function validerMission($IdMiss){
        return  DataBase::updateData('missions',array(
            "StatutMiss" => 1,
            "OrdreMiss" => "Ordre_Mission_$IdMiss.pdf",
        ),"IdMiss=$IdMiss");
    } 
    public static function validerRemb($IdMiss,$Remb,$IdPaiement){
        return DataBase::updateData('missions',array(
            "Montant" => $Remb,
            "IdPaiement" => $IdPaiement,
            "DemandeRemb" => "Demande_Remboursement_$IdMiss.pdf",
        ),"IdMiss=$IdMiss");
    }
    public static function ajouterPièceJointe($IdMiss,$IdFrais,$Description,$filename){
        return DataBase::insertData('piècesjointes',array(
            "IdMiss" => $IdMiss,
            "IdFrais" => $IdFrais,
            "DescriptionPJ" => $Description,
            "NomPJ" => $filename
        ));
    }
    public static function archiverMission($IdMiss,$Date){
        return DataBase::updateData('missions',array(
            "DeletedAt" => $Date,
        ),"IdMiss=$IdMiss");
    }
    public static function restaurerMission($IdMiss){
        $con=DataBase::connect();
        $rslt=$con->query("update missions set DeletedAt=DEFAULT where IdMiss=$_GET[IdMiss]");
        return $rslt;
    }
    public static function supprimerMission($IdMiss){
        return DataBase::deleteData('missions',"IdMiss=$IdMiss");
    }
    public static function generate_ref(){
        // Récupère la dernière référence de mission existante depuis votre source de stockage
        $con = DataBase::connect();
        $rslt = $con->query("select * from missions order by RéfMiss desc limit 1");
        if ($rslt->rowCount() > 0) {
            $row = $rslt->fetch();
            $derniere_ref = $row["RéfMiss"]; // exemple de valeur initiale
        } else {
            $derniere_ref = 'REF-MS-00000';
        }
        // Extrait le numéro de séquence actuel de la référence
        $numero_sequence = intval(substr($derniere_ref, -5));
        // Incrémente le numéro de séquence
        $numero_sequence++;
        // Génère la nouvelle référence en combinant "REF-MS-" avec le numéro de séquence mis à jour
        $nouvelle_ref = "REF-MS-" . str_pad($numero_sequence, 5, "0", STR_PAD_LEFT);
        return $nouvelle_ref;
    }
    public static function getRef($IdMiss){
        $row=DataBase::getDataWhere('missions',"IdMiss=$IdMiss");
        $ref=$row[0]['RéfMiss'];
        return $ref;
    }
    public static function getTypePaiement($IdPaiement){
        $row=DataBase::getDataWhere('paiement',"IdPaiement=$IdPaiement");
        return $row[0]['TypePaiement'];
    }
    public static function getMissById($IdMiss){
        $row=DataBase::getDataWhere('missions',"IdMiss=$IdMiss");
        $mission=new Mission($IdMiss,$row[0]['RéfMiss'],$row[0]['IdMb'],$row[0]['ObjMiss'],$row[0]['LieuDép'],$row[0]['MoyTrans'],$row[0]['Départ'],$row[0]['Retour'],$row[0]['Durée'],$row[0]['DateMiss'],$row[0]['TypeMiss'],$row[0]['Accomp'],$row[0]['Note'],$row[0]['StatutMiss']);
        return $mission;
    }
}
?>