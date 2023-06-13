<?php 
namespace Tets\Oop;

use Tets\Oop\DataBase;

class Groupe {
    private $IdG;
    private $Libellé;
    private $Taux;

    public function __construct($IdG,$Libellé,$Taux){
        $this->IdG=$IdG;
        $this->Libellé=$Libellé;
        $this->Taux=$Taux;
    }
    public function getLibellé(){
        return $this->Libellé;
    }
    public function getTaux(){
        return $this->Taux;
    }
    public static function vérifierLibellé($libelle){
        return DataBase::getDataWhere('groupes',"Libellé='$libelle'");
    }

    public static function vérifierLibelléId($libelle,$id){
        return DataBase::getDataWhere('groupes',"Libellé='$libelle' and IdG != '$id'");
    }

    public function ajouterGroupe() {
        return DataBase::insertData('groupes', array(
            "Libellé" => $this->Libellé,
            "TauxG" => $this->Taux
        ));
    }

    public  function modifierGroupe() {
        $id=$this->IdG;
        return DataBase::updateData('groupes', array(
            "`Libellé`" => $this->Libellé,
            "TauxG" => $this->Taux
        ), "IdG=$id");
    }

    public static function supprimerGroupe($idGroupe, $page) {
        return  DataBase::deleteData('groupes', "IdG=$idGroupe");
    }

    public static function getGroupeById($id){
        $row=DataBase::getDataWhere('groupes',"IdG=$id");
        $groupe=new Groupe($row[0]['IdG'],$row[0]['Libellé'],$row[0]['TauxG']);
        return $groupe;
    }
}
?>