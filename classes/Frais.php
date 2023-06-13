<?php 
namespace Tets\Oop;
use Tets\Oop\DataBase;
class Frais {
    private $IdFrais;
    private $LibelléFrais;
    private $Montant;

    public static function vérifierLibellé($libelle){
        return DataBase::getDataWhere('Frais',"LibelléFrais='$libelle'");
    }

    public static function vérifierLibelléId($libelle,$id){
        return DataBase::getDataWhere('Frais',"LibelléFrais='$libelle' and IdFrais != '$id'");
    }

    public static function ajouterFrais($libelle, $montant) {
        return DataBase::insertData('frais', array(
            "LibelléFrais" => $libelle,
            "MontantFrais" => $montant,
        ));
    }

    public static function modifierFrais($idFrais, $libelle, $montant) {
        return DataBase::updateData('frais', array(
            "LibelléFrais" => $libelle,
            "MontantFrais" => $montant,
        ), "IdFrais=$idFrais");
    }

    public static function supprimerFrais($idFrais, $page) {
        return DataBase::deleteData('frais', "IdFrais=$idFrais");
    }

    public static function getFraisById($id){
        return DataBase::getDataWhere('frais',"IdFrais=$id");
    }

    public static function getFrais(){
        return DataBase::getData('frais');
    }
    public static function getMontantFrais($IdFrais){
        $row2=DataBase::getDataWhere('frais',"IdFrais=$IdFrais");
        return $row2[0]['MontantFrais'];
    }
}
?>