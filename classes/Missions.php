<?php
namespace Tets\Oop;
class Missions
{
    public static function generate_ref()
    {
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
}
?>