<?php

use Tets\Oop\DataBase;
use \Tets\Oop\Membre;

session_start();

include "vendor/autoload.php";

if(!Membre::Admin()){
    header("location:../index.php");
}

require('./fpdf/fpdf.php');

function mois($mois){
    switch($mois){
        case '01' : return 'Janvier';
            break;
        case '02' : return 'Février';
            break;
        case '03' : return 'Mars';
            break;
        case '04' : return 'Avril';
            break;
        case '05' : return 'Mai';
            break;
        case '06' : return 'Juin';
            break;
        case '07' : return 'Juillet';
            break;
        case '08' : return 'Août';
            break;
        case '09' : return 'Septembre';
            break;
        case '10' : return 'Octobre';
            break;
        case '11' : return 'Novembre';
            break;
        case '12' : return 'Décembre';
            break;
    }
}

if(isset($_POST['con'])) {
    $Email=strtolower(trim($_POST['Email']));
    setcookie('Email', $Email, time() + 600);
    try {
        $row=\Tets\Oop\DataBase::getDataWhere('membres', "Email='$Email'");
        if (! $row) {
            setcookie('Email', '', time() - 1);
            $_SESSION['errEmailLog'] = 'Email incorrecte ! Veuillez réssayer';
            header("location:index.php");
        } else {
            $Mdps = $_POST['Mdps'];
            if (Membre::password_decrypt($Mdps, $row[0]['Mdps'])) {
                //Stocker les informations du membre dans la session--------------------------------------------------------------------//
                foreach ($row[0] as $indice => $element) {
                    if ($indice != 'Mdps') {
                        $_SESSION['membre'][$indice] = $element;
                    }
                }
                //Redirection selon le type de membre (Admin ou collab)----------------------------------------------------------------------------------//
                if(Membre::Admin()){
                    header("location:./Admin/index.html");
                }
                elseif(Membre::Collab()){
                    header("location:./Collab/index.html");
                }
            } else {
                $_SESSION['errMdpsLog'] = 'Mot de passe incorrecte ! Veuillez réssayer';
                header("location:index.php");
            }
        }
    }
    catch(Exception | Error $e){
        $_SESSION['erreurLog']="Erreur à la connexion ! Veuillez réssayer plut tard ou contactez-nous";
    }
}
elseif(isset($_POST['ajtCollab'])){
    $row=\Tets\Oop\DataBase::getDataWhere('membres',"Email='$_POST[Email]'");
    $row1=\Tets\Oop\DataBase::getDataWhere('membres',"CIN='$_POST[CIN]'");
    if(!$row && !$row1){
        if(\Tets\Oop\DataBase::insertData('membres',array(
            "IdG" => $_POST['Grp'],
            "Statut" => 0,
            "Nom" => $_POST['Nom'],
            "Prénom" => $_POST['Prénom'],
            "Email" => $_POST['Email'],
            "Mdps" => password_hash($_POST['Mdps'],PASSWORD_DEFAULT,['cost'=>14]),
            "CIN" => $_POST['CIN'],
            "Profil" => $_POST['Profil'],
        ))){
            $_SESSION['success']="Collaborateur ajouté avec succés ! ";
            header("location:./admin/collabs.php");
        }
        else{
            echo "erreur";
        }
    }
    else{
        if($row){
            $_SESSION['erreurEmail']="Email déjà utilisé ! Veuillez réessayer";
        }
        if($row1){
            $_SESSION['erreurCIN']="CIN existe déjà ! Veuillez réessayer";
        }
        header("location:./admin/collabs.php");
    }
}
elseif(isset($_POST['modifCollab'])){
    $row=\Tets\Oop\DataBase::getDataWhere('membres',"Email='$_POST[Email]' and IdMb != '$_POST[IdMb]'");
    if(!$row){
        $row1=\Tets\Oop\DataBase::getDataWhere('membres',"CIN='CIN' and IdMb<>$_POST[IdMb]");
        if(!$row1){
            if(isset($_POST['Mdps'])){
                if(\Tets\Oop\DataBase::updateData('membres',array(
                    "Nom" => $_POST['Nom'],
                    "Prénom" => $_POST['Prénom'],
                    "IdG" => $_POST['Grp'],
                    "Email" => $_POST['Email'],
                    "Mdps" => \Tets\Oop\Membre::password_encrypt($_POST['Mdps']),
                    "CIN" => $_POST['CIN'],
                    "Profil" => $_POST['Profil'],
                ),"IdMb=$_POST[IdMb]")){
                    $_SESSION['success']="Collaborateur modifié avec succés ! ";
                    header("location:./admin/collabs.php");
                }
                else{
                    echo "erreur";
                }
            }
            else{
                if(\Tets\Oop\DataBase::updateData('membres',array(
                    "Nom" => $_POST['Nom'],
                    "Prénom" => $_POST['Prénom'],
                    "IdG" => $_POST['Grp'],
                    "Email" => $_POST['Email'],
                    "CIN" => $_POST['CIN'],
                    "Profil" => $_POST['Profil'],
                ),"IdMb=$_POST[IdMb]")){
                    header("location:./admin/collabs.php");
                }
                else{
                    echo "erreur";
                }
            }
        }
        else{
            echo "CIN existe déjà ! Veuillez réessayer";
            $_SESSION['erreurCIN']="CIN existe déjà ! Veuillez réessayer";
        }
    }
    else{
        echo "Email déjà utilisé ! Veuillez réessayer";
        $_SESSION['erreurEmail']="Email déjà utilisé ! Veuillez réessayer";
    }
}
elseif(isset($_GET['suppCollab']) && isset($_GET['IdMb'])){
    \Tets\Oop\DataBase::deleteData('membres',"IdMb=$_GET[IdMb]");
    $_SESSION['success']="Collaborateur supprimé avec succés ! ";
    header("location:./admin/collabs.php?page=$_GET[page]");
}
elseif(isset($_POST['ajtMiss'])){
    $Départ = new DateTime("$_POST[Départ]"); // date de début
    $Retour = new DateTime("$_POST[Retour]"); // date de fin
    if($_POST["TypeMiss"]=='Journalier'){
        $nbJours = 0;
        while ($Départ <= $Retour) {
            // on vérifie si la date courante est un samedi ou un dimanche
            if ($Départ->format('N') < 6) {
                $nbJours++;
            }
            // on incrémente la date de 1 jour
            $Départ->modify('+1 day');
        }
    }
    else{
        $nbJours = 0;
        while ($Départ <= $Retour) {
            // on vérifie si la date courante est un samedi ou un dimanche
            $nbJours++;
            // on incrémente la date de 1 jour
            $Départ->modify('+1 day');
        }
    }
    date_default_timezone_set('Africa/Casablanca');
    $DateMiss=date("d/m/20y H:i:s");
    $rslt=\Tets\Oop\DataBase::insertData('missions',array(
        "RéfMiss" => \Tets\Oop\Missions::generate_ref(),
        "IdMb" =>  $_POST['IdCollab'],
        "ObjMiss" => $_POST['ObjMiss'],
        "LieuDép" => $_POST['LieuDép'],
        "MoyTrans" => $_POST['MoyTrans'],
        "Départ" => $_POST['Départ'],
        "Retour" => $_POST['Retour'],
        "Durée" => $nbJours,
        "DateMiss" => $DateMiss,
        "TypeMiss" => $_POST['TypeMiss'],
        "Accomp" => $_POST['Accomp'],
        "Note" => $_POST['Note'],
        "StatutMiss" => "0",
    ));

    if($rslt){
        $_SESSION['success']="Mission ajoutée avec succés ! ";
        header("location:./admin/missions.php");
    }
    else{
        header("location:./admin/missions.php");
    }
    exit;
}
elseif (isset($_POST['modifMiss'])){
    $IdMiss=$_POST["IdMiss"];
    $Départ = new DateTime("$_POST[Départ]"); // date de début
    $Retour = new DateTime("$_POST[Retour]"); // date de fin
    if($_POST["TypeMiss"]=='Journalier'){
        $nbJours = 0;
        while ($Départ <= $Retour) {
            // on vérifie si la date courante est un samedi ou un dimanche
            if ($Départ->format('N') < 6) {
                $nbJours++;
            }
            // on incrémente la date de 1 jour
            $Départ->modify('+1 day');
        }
    }
    else{
        $nbJours = 0;
        while ($Départ <= $Retour) {
            // on vérifie si la date courante est un samedi ou un dimanche
            $nbJours++;
            // on incrémente la date de 1 jour
            $Départ->modify('+1 day');
        }
    }
    \Tets\Oop\DataBase::updateData('missions',array(
        "IdMb" => $_POST['IdMb'],
        "Accomp" => $_POST['Accomp'],
        "ObjMiss" => $_POST['ObjMiss'],
        "LieuDép" => $_POST["LieuDép"],
        "MoyTrans" => $_POST['MoyTrans'],
        "Départ" => $_POST['Départ'],
        "Retour" => $_POST['Retour'],
        "Durée" => $nbJours,
        "TypeMiss" => $_POST['TypeMiss'],
        "Note" => $_POST['Note'],
    ),"IdMiss=$IdMiss");
    $_SESSION['success']="Mission modifiée avec succés ! ";
    header("location:./admin/missions.php?page=$_GET[page]");
}
elseif(isset($_GET['validerMiss'])){
    $IdMiss=$_GET['IdMiss'];
    $con=\Tets\Oop\DataBase::connect();
    $rslt=$con->query("select * from missions natural join membres where IdMiss=$IdMiss");
    $row=$rslt->fetch();
    $DateMiss=explode(" ",$row['DateMiss']);
    $pdf = new PDF('P','mm','A4');
    // Nouvelle page A4 (incluant ici logo, titre et pied de page)
    $pdf->AddPage();
    // Polices par défaut : Helvetica taille 9
    $pdf->SetFont('Helvetica','',9);
    // Couleur par défaut : noir
    $pdf->SetTextColor(0);
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(10,75,iconv("UTF-8", "CP1250//TRANSLIT", "Réf : $row[RéfMiss]"));
    $pdf->Text(160,75,iconv("UTF-8", "CP1250//TRANSLIT", "Oujda le : $DateMiss[0]"));
    $pdf->SetFont('Helvetica','b',20);
    $pdf->Text(70,100,iconv("UTF-8", "CP1250//TRANSLIT", "ORDRE DE MISSION"));
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(10,135,"Collaborateur : ");
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(70,135,"$row[Prénom] $row[Nom]");
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(10,155,iconv("UTF-8", "CP1250//TRANSLIT", "Lieu de déplacement : "));
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(70,155,"$row[LieuDép]");
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(10,175,"Objet de la mission : ");
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(70,175,"$row[ObjMiss]");
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(10,195,iconv("UTF-8", "CP1250//TRANSLIT", "Date de départ : "));
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(70,195,"$row[Départ]");
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(120,195,"Date de retour : ");
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(180,195,"$row[Retour]");
    $pdf->Text(120,225,"Signature");
    $pdf->Text(120,235,"Salah-dine KHLIFI");
    $pdf->Output('F',"PDF/Ordre_Mission/Ordre_Mission_$IdMiss.pdf",true);
    \Tets\Oop\DataBase::updateData('missions',array(
        "StatutMiss" => 1,
        "OrdreMiss" => "Ordre_Mission_$IdMiss.pdf",
    ),"IdMiss=$IdMiss");
    $_SESSION['success']="Mission validée avec succés ! ";
    header("location:./admin/missions.php?page=$_GET[page]");
}
elseif(isset($_POST['validerRemb']) ){
    $IdMiss=$_POST['IdMiss'];
    $Remb=$_POST['Remb'];
    $Date=Date("d-m-20y");
    if(isset($_POST['nomFile'])){
        $file=$_FILES['file'];
        $Description=$_POST['nomFile'];
        $typeFile=$_POST['typeFile'];
        for($i=0;$i<count($typeFile);$i++){
            $row2=\Tets\Oop\DataBase::getDataWhere('frais',"IdFrais=$typeFile[$i]");
            $Remb+=$row2[0]['MontantFrais'];
            $array=explode('.',$file['name'][$i]);
            $ext=end($array);
            $filename=time()."_img.$ext";
            \Tets\Oop\DataBase::insertData('piècesjointes',array(
                "IdMiss" => $_POST['IdMiss'],
                "IdFrais" => $typeFile[$i],
                "DescriptionPJ" => $Description[$i],
                "NomPJ" => $filename
            ));
            $tmp_name = $file['tmp_name'][$i];
            move_uploaded_file($tmp_name,"./PJ/$filename");
        }
    }
    $con=\Tets\Oop\DataBase::connect();
    $rslt=$con->query("select * from missions natural join membres where IdMiss=$IdMiss");
    $row=$rslt->fetch();
    $IdMb=$row['IdMb'];
    $rslt2=$con->query("select * from membres natural join groupes where IdMb=$IdMb");
    $row2=$rslt2->fetch();
    $DateFormat=explode("-",$row['Départ']);
    $mois=mois($DateFormat[1]);
    $année=$DateFormat[2];
    $Remb += $Remb *($row2['TauxG']/100);
    \Tets\Oop\DataBase::updateData('missions',array(
        "Montant" => $Remb,
        "IdPaiement" => $_POST['Paiement'],
        "DemandeRemb" => "Demande_Remboursement_$IdMiss.pdf",
    ),"IdMiss=$IdMiss");
    $rslt3=$con->query("select * from missions natural join paiement where IdMiss=$IdMiss");
    $row3=$rslt3->fetch();
    $lettre=new \Tets\Oop\ChiffreEnLettre();
        $pdf = new PDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Helvetica','',12);
        $pdf->Text(10,75,iconv("UTF-8", "CP1250//TRANSLIT", "Réf : $row[RéfMiss]"));
        $pdf->Text(160,75,iconv("UTF-8", "CP1250//TRANSLIT", "Oujda le : $Date"));
        $pdf->SetFont('Helvetica','b',20);
        $pdf->Text(27,100,iconv("UTF-8", "CP1250//TRANSLIT", "DEMANDE DE REMBOURSEMENT DES FRAIS"));
        $pdf->Text(75,112,iconv("UTF-8", "CP1250//TRANSLIT", "DE DÉPLACEMENT"));
        $pdf->SetFont('Helvetica','b',12);
        $pdf->Text(10,135,"Collaborateur : ");
        $pdf->SetFont('Helvetica','',12);
        $pdf->Text(70,135,"$row[Nom] $row[Prénom]");
        $pdf->SetFont('Helvetica','b',12);
        $pdf->Text(10,150,iconv("UTF-8", "CP1250//TRANSLIT", "Lieu de déplacement : "));
        $pdf->SetFont('Helvetica','',12);
        $pdf->Text(70,150,"$row[LieuDép]");
        $pdf->SetFont('Helvetica','b',12);
        $pdf->Text(10,165,"Objet de la mission : ");
        $pdf->SetFont('Helvetica','',12);
        $pdf->Text(70,165,"$row[ObjMiss]");
        $pdf->SetFont('Helvetica','b',12);
        $pdf->Text(10,180,iconv("UTF-8", "CP1250//TRANSLIT", "Date : "));
        $pdf->SetFont('Helvetica','',12);
        $pdf->Text(70,180,"$Date");
        $pdf->SetFont('Helvetica','b',12);
        $pdf->Text(15,195,"*");
        $pdf->Text(20,195,iconv("UTF-8", "CP1250//TRANSLIT", "Indemnités : "));
        $pdf->Text(80,195,iconv("UTF-8", "CP1250//TRANSLIT", "$Remb DH"));
        $pdf->SetFont('Helvetica','',12);
        $pdf->SetXY(80,205);
        $pdf->MultiCell(100,6,iconv("UTF-8", "CP1250//TRANSLIT", ucfirst($lettre->Conversion($Remb))." Dirhams"),0,1,0,false);
        //$pdf->Text(90,205,iconv("UTF-8", "CP1250//TRANSLIT", "$text"));
        $pdf->Text(120,240,"Signature");
        $pdf->Text(120,250,"Salah-dine KHLIFI");
        $pdf->AddPage();
        $pdf->SetFont('Helvetica','',12);
        $pdf->Text(10,75,iconv("UTF-8", "CP1250//TRANSLIT", "Réf : $row[RéfMiss]"));
        $pdf->Text(160,75,iconv("UTF-8", "CP1250//TRANSLIT", "Oujda le : $Date"));
        $pdf->SetFont('Helvetica','b',20);
        $pdf->Text(88,100,iconv("UTF-8", "CP1250//TRANSLIT", "DECHARGE"));
        $pdf->SetFont('Helvetica','',12);
        $pdf->Ln('30');
        $pdf->SetX('10');
        $pdf->MultiCell(200,9,iconv("UTF-8", "CP1250//TRANSLIT", "Je soussiné Mr. $row[Nom] $row[Prénom] titulaire de la CIN n° $row[CIN] reconnais avoir reçu de la société exen consulting sarl la somme de $Remb DH ".ucfirst($lettre->Conversion($Remb))." Dirhams par $row3[TypePaiement] au titre des remboursements de frais de mon deplacement à $row[LieuDép] durant le mois de $mois $année"),0,1,0,false);
    $pdf->Output('F',"PDF/Demande_Remboursement/Demande_Remboursement_$IdMiss.pdf",true);
    $_SESSION['success']="Remboursement validé avec succés ! ";
    header("location:./admin/missions.php");
}
elseif(isset($_GET['archMiss']) && isset($_GET['IdMiss'])){
    $Date=date("20y/m/d h:m:s");
    \Tets\Oop\DataBase::updateData('missions',array(
        "DeletedAt" => $Date,
    ),"IdMiss=$_GET[IdMiss]");
    $_SESSION['success']="Mission archivée avec succés ! ";
    header("location:./admin/missions.php?page=$_GET[page]");
}
elseif(isset($_GET['restMiss']) && isset($_GET['IdMiss'])){
    $con=\Tets\Oop\DataBase::connect();
    $rslt=$con->query("update missions set DeletedAt=DEFAULT where IdMiss=$_GET[IdMiss]");
    $_SESSION['success']="Mission restaurée avec succés ! ";
    header("location:./admin/archives.php?page=$_GET[page]");
}
elseif(isset($_GET['suppMiss']) && isset($_GET['IdMiss'])){
    \Tets\Oop\DataBase::deleteData('missions',"IdMiss=$_GET[IdMiss]");
    $_SESSION['success']="Mission supprimée avec succés ! ";
    header("location:./admin/archives.php?page=$_GET[page]");
}
elseif(isset($_POST['ajtGroupe'])){
    $Libellé=$_POST['Libellé'];
    $TauxG=$_POST['TauxG'];
    DataBase::insertData('groupes',array(
        "Libellé" => $Libellé,
        "TauxG" => $TauxG,
    ));
    $_SESSION['success']="Groupe ajouté avec succés ! ";
    header("location:./admin/groupes.php");
}
elseif(isset($_POST['modifGroupe'])){
    $IdG=$_POST['IdG'];
    $Libellé=$_POST['Libellé'];
    $TauxG=$_POST['TauxG'];
    DataBase::updateData('groupes',array(
        "Libellé" => $Libellé,
        "TauxG" => $TauxG,
    ),"IdG=$IdG");
    $_SESSION['success']="Groupe modifié avec succés ! ";
    header("location:./admin/groupes.php");
}
elseif(isset($_GET['suppGroupe']) && isset($_GET['IdG'])){
    \Tets\Oop\DataBase::deleteData('groupes',"IdG=$_GET[IdG]");
    $_SESSION['success']="Groupe supprimée avec succés ! ";
    header("location:./admin/groupes.php?page=$_GET[page]");
}
elseif(isset($_POST['ajtFrais'])){
    $LibelléFrais=$_POST['LibelléFrais'];
    $MontantFrais=$_POST['MontantFrais'];
    DataBase::insertData('frais',array(
        "LibelléFrais" => $LibelléFrais,
        "MontantFrais" => $MontantFrais,
    ));
    $_SESSION['success']="Frais ajouté avec succés ! ";
    header("location:./admin/frais.php");
}
elseif(isset($_POST['modifFrais'])){
    $IdFrais=$_POST['IdFrais'];
    $LibelléFrais=$_POST['LibelléFrais'];
    $MontantFrais=$_POST['MontantFrais'];
    DataBase::updateData('frais',array(
        "LibelléFrais" => $LibelléFrais,
        "MontantFrais" => $MontantFrais,
    ),"IdFrais=$IdFrais");
    $_SESSION['success']="frais modifié avec succés ! ";
    header("location:./admin/frais.php");
}
elseif(isset($_GET['suppFrais']) && isset($_GET['IdFrais'])){
    \Tets\Oop\DataBase::deleteData('frais',"IdFrais=$_GET[IdFrais]");
    $_SESSION['success']="Frais supprimée avec succés ! ";
    header("location:./admin/frais.php?page=$_GET[page]");
}
elseif(isset($_POST['IdMb']) && isset($_POST['getCollab'])){
    $con=\Tets\Oop\DataBase::connect();
    $rslt=$con->query("select * from membres natural join groupes where IdMb=$_POST[IdMb]");
    $row=$rslt->fetch();
    $rslt2=$con->query("select * from missions where IdMb=$row[IdMb]");
    $row2=$rslt2->fetch();
    $nbrMiss=$rslt2->rowCount();
    $infoCollab = array(
        'Nom' => $row['Nom'],
        'Prénom' => $row['Prénom'],
        'IdG' => $row['IdG'],
        'Email' => $row['Email'],
        'CIN' => $row['CIN'],
        'Profil' => $row['Profil'],
        'nbrMiss' => $nbrMiss,
    );
    echo json_encode($infoCollab);
}
elseif(isset($_POST['IdMiss']) && isset($_POST['getMiss'])){
    $con=\Tets\Oop\DataBase::connect();
    $rslt=$con->query("select * from missions natural join membres where IdMiss=$_POST[IdMiss]");
    $row=$rslt->fetch();
    $infoMiss = array(
        'RéfMiss' => $row['RéfMiss'],
        'IdCollab' => $row['IdMb'],
        'Collab' => $row['Nom']." ".$row['Prénom'],
        'ObjMiss' => $row['ObjMiss'],
        'TypeMiss' => $row['TypeMiss'],
        'Départ' => $row['Départ'],
        'Retour' => $row['Retour'],
        'LieuDép' => $row['LieuDép'],
        'MoyTrans' => $row['MoyTrans'],
        'Durée' => $row['Durée'],
        'Montant' => $row['Montant'],
        'Note' => $row['Note'],
        'Accomp' => $row['Accomp'],
    );
    echo json_encode($infoMiss);
}
elseif (isset($_POST['getGroupes'])) {
    $con = \Tets\Oop\DataBase::connect();
    if(isset($_POST['IdG'])){
        $rslt = $con->query("SELECT * FROM groupes where IdG=$_POST[IdG]");
    }
    else{
        $rslt = $con->query("SELECT * FROM groupes");
    }    
    $groupes = array();

    while ($row = $rslt->fetch()) {
        $groupes[] = array(
            'IdG' => $row['IdG'],
            'Libellé' => $row['Libellé'],
            'TauxG' => $row['TauxG'],
        );
    }

    echo json_encode($groupes);
}
elseif(isset($_GET['getFrais'])){
    $con=\Tets\Oop\DataBase::connect();
    if(isset($_GET['IdFrais'])){
        $rslt=$con->query("select * from frais where IdFrais=$_GET[IdFrais]");
    }
    else{
        $rslt=$con->query('select * from frais');
    }
    $options = array();
    if ($rslt->rowCount() > 0) {
        while ($row = $rslt->fetch()) {
            $options[] = array(
                'LibelléFrais' => $row['LibelléFrais'],
                'MontantFrais' => $row['MontantFrais'],
                'IdFrais' => $row['IdFrais'],
            );
        }
    }
    // Renvoyer les options au format JSON
    header('Content-Type: application/json');
    echo json_encode($options);
}
elseif(isset($_POST['getCollabMiss']) && isset($_POST['IdMb'])){
    $con=\Tets\Oop\DataBase::connect();
    $rslt=$con->query("select * from missions natural join membres where IdMb=$_POST[IdMb]");
    $infoCollab = array();
    while ($row = $rslt->fetch()) {
        $info = array(
            'IdMiss' => $row['IdMiss'],
            'RéfMiss' => $row['RéfMiss'],
            'ObjMiss' => $row['ObjMiss'],
            'LieuDép' => $row['LieuDép'],
            "Collab" => $row["Nom"]." ".$row["Prénom"],
        );
        $infoCollab[] = $info;
    }
    echo json_encode($infoCollab);
}
elseif(isset($_GET['décon'])){
    $_SESSION = array();
    setcookie(session_name(),' ', time()-1);
    session_destroy();
    header("location:index.php");
}
?>

