<?php

    use Tets\Oop\DataBase;
    use Tets\Oop\Membre;
    use Tets\Oop\Groupe;
    use Tets\Oop\Frais;
    use Tets\Oop\Mission;
    use Tets\Oop\ChiffreEnLettre;
    use Tets\Oop\Historique;

    session_start();

    include "vendor/autoload.php";

    require('./fpdf/fpdf.php');
    date_default_timezone_set('Africa/Casablanca');

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
    //Traitement de connexion
    if(isset($_POST['con'])) {
        $Email=strtolower(trim($_POST['Email']));
        setcookie('Email', $Email, time() + 600);
        try {
            $row=DataBase::getDataWhere('membres', "Email='$Email'");
            if (! $row) {
                setcookie('Email', '', time() - 1);
                $_SESSION['errEmailLog'] = 'Email incorrecte ! Veuillez réssayer';
                header("location:index.php");
            } else {
                $Mdps = $_POST['Mdps'];
                if (Membre::password_decrypt($Mdps, $row[0]['Mdps'])) {
                    //Stocker les informations du membre dans la session
                    foreach ($row[0] as $indice => $element) {
                        if ($indice != 'Mdps') {
                            $_SESSION['membre'][$indice] = $element;
                        }
                    }
                    //Redirection selon le type de membre (Admin ou collab)
                    if(Membre::Admin()){
                        if(DataBase::insertData("historique",array(
                            "TypeAction" => "Connexion",
                            "DateAction" => date("d/m/20y H:i:s"),
                            "ElementAction" => null,
                        ))){
                            header("location:./Admin/index.html");
                        }
                        else{
                            echo "erreur";
                        }
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
    //Traitements sur la table Membre
    /*Ajouter un collaborateur*/
    elseif (isset($_POST['ajtCollab'])) {
        try{
            $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT, ['cost' => 14]);
            $membre=new Membre('',$_POST['Nom'],$_POST['Prénom'],$_POST['TitreCivilité'],$_POST['Grp'],$_POST['Email'],$hashedPassword,$_POST['CIN'], $_POST['Profil']);
            if($membre->ajouterCollaborateur()){
                $id=Membre::getLastId();
                $historique=new Historique('Ajout',date("d/m/20y H:i:s"),"Collaborateur $id");
                if($historique->insertHistorique()){
                    $_SESSION['erreurAjt']=true;
                    $_SESSION['success'] = "Collaborateur ajouté avec succès !";
                    header("location:./admin/collabs.php");
                }
                else{
                    $_SESSION['erreur']='Erreur lors de l\'ajout du collaborateur ! Veuillez réssayer.';
                    header("location:./admin/collabs.php");
                }
            }
            else{
                $_SESSION['erreur']='Erreur lors de l\'ajout du collaborateur ! Veuillez réssayer.';
                header("location:./admin/collabs.php");
            }
        }
        catch(Exception | Error $e){
            $_SESSION['erreur']='Erreur lors de l\'ajout du collaborateur ! Veuillez réssayer.';
            header("location:./admin/collabs.php");
        }
    }
    /*Modifier un collaborateur*/
    elseif (isset($_POST['modifCollab'])) {
            $con=DataBase::connect();
            $rqt="update membres set Nom='$_POST[Nom]',Prénom='$_POST[Prénom]',TitreCivilité='$_POST[TitreCivilité]',IdG=$_POST[IdG],Email='$_POST[Email]',CIN='$_POST[CIN]',Profil='$_POST[Profil]' where IdMb=$_POST[IdMb]";
            if (isset($_POST['Mdps']) && !empty($_POST['Mdps'])) {
                $mdps=Membre::password_encrypt($_POST['Mdps']);
                $rqt="update membres set Nom='$_POST[Nom]',Prénom='$_POST[Prénom]',TitreCivilité='$_POST[TitreCivilité]',IdG=$_POST[IdG],Email='$_POST[Email]',CIN='$_POST[CIN]',Profil='$_POST[Profil]',Mdps='$mdps' where IdMb=$_POST[IdMb]";
            }
            //$ancienMembre=Membre::getById($_POST['IdMb']);
                    $con->exec($rqt);
                    $historique=new Historique('Modification',date("d/m/20y H:i:s"),"Collaborateur $_POST[IdMb]");
                    if($historique->insertHistorique()){
                        $_SESSION['success'] = "Collaborateur modifié avec succès !";
                    }
                    else{
                        $_SESSION['erreur']='Erreur lors de ma modification du collaborateur ! Veuillez réssayer.';
                    }
            if(Membre::Admin()){
                header("location:./admin/collabs.php");
            }
            else{
                header("location:./collab/profil.php");
            }        
        
    }
    elseif(isset($_GET['suppCollab']) && isset($_GET['IdMb'])){
        $IdCollab = $_GET['IdMb'];
        $page = $_GET['page'];
        try{
            if(Membre::delete($IdCollab)){
                $historique=new Historique("Suppression",date("d/m/20y H:i:s"),"Collaborateur $IdCollab");
                if($historique->insertHistorique()){
                    $_SESSION['success'] = "Collaborateur supprimé avec succès !";
                    header("Location: ./admin/groupes.php?page=$page");
                }
            }
            else{
                $_SESSION['erreur'] = "Erreur à la suppression du collaborateur ! Veuillez réssayer.";
                header("Location: ./admin/collabs.php?page=$page");
            }
        }
        catch(Error | Exception $e){
            $_SESSION['erreur'] = "Erreur à la suppression du collaborateur ! Veuillez réssayer.";
            header("Location: ./admin/collabq.php?page=$page");
        }
    }
    /*Modifier les informations de l'administrateur*/
    elseif(isset($_POST['modifAdmin'])){
        try{
            $id=$_SESSION['membre']['IdMb'];
            $ancienMembre=Membre::getById($id);
            $ancienNom=$ancienMembre->getNom();
            $ancienPrénom=$ancienMembre->getPrénom();
            $ancienEmail=$ancienMembre->getEmail();
            if($_POST['Nom']==$ancienNom && $_POST['Prénom']==$ancienPrénom && $_POST['Email']==$ancienEmail){
                $_SESSION['success']="Aucun changement détecté. Pas de mise à jour effectuée.";
                header("location:./admin/profil.php");
            }
            else{
                $membre=new Membre($id,$_POST['Nom'],$_POST['Prénom'],'','',$_POST['Email'],"","","");
                if($membre->modifierAdmin()){
                    $historique=new Historique('Modification',date("d/m/20y H:i:s"),'Administrateur');
                    if($historique->insertHistorique()){
                        $_SESSION['success']="Informations modifiées avec succès";
                        header("location:./admin/profil.php");
                    }
                    else{
                        $_SESSION['erreur']="Erreur à la modification des Informations ! Veuillez réssayer";
                        header("location:./admin/profil.php");
                    }
                }else{
                    $_SESSION['erreur']="Erreur à la modification des Informations ! Veuillez réssayer";
                    header("location:./admin/profil.php");
                }
            }
        }
        catch(Exception | Error $e){
            $_SESSION['erreur']="Erreur à la modification des Informations ! Veuillez réssayer";
            header("location:./admin/profil.php");
        }
        
    }
    /*Modifier mot de passe collaborateur*/
    elseif(isset($_POST['modifMdpsCollab'])){
        $IdMb=$_POST['IdMb'];
        $Mdps=password_hash($_POST['Mdps'],PASSWORD_DEFAULT, ['cost' => 14]);
        try{
            if(DataBase::updateData('membres',array('Mdps'=>$Mdps,),"IdMb=$IdMb")){
                $_SESSION['success']="Mot de passe modifié avec succès ! ";
            }
            else{
                $_SESSION['erreur']="Erreur lors la modification de mot de passe ! Veuillez réssayer. ";
            }
        }catch(Exception|Error $e){
                $_SESSION['erreur']="Erreur lors la modification de mot de passe ! Veuillez réssayer. ";
        }
        header("location:./collab/profil.php");
    }
    //Traitements sur la table mission
    /*Ajouter une mission*/
    elseif(isset($_POST['ajtMiss'])){
        try{
            $Départ = new DateTime("$_POST[Départ]"); // date de début
            $Retour = new DateTime("$_POST[Retour]"); // date de fin
            if($_POST["TypeMiss"]=='Journalière'){
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
            $DateMiss=date("d/m/20y H:i:s");
            $ref=Mission::generate_ref();
            $mission=new Mission('',$ref,$_POST['IdCollab'],$_POST['ObjMiss'],$_POST['LieuDép'],$_POST['MoyTrans'],$_POST['Départ'],$_POST['Retour'],$nbJours,$DateMiss,$_POST['TypeMiss'],$_POST['Accomp'],$_POST['Note'],"0");
            if($mission->ajouterMission()){
                $historique=new Historique('Ajout',date("d/m/20y H:i:s"),"Mission $ref");
                if($historique->insertHistorique()){
                    $_SESSION['success']="Mission ajoutée avec succés ! ";
                }
                else{
                    $_SESSION['erreur']="Erreur à l'ajout de la mission ! Veuillez réssayer. ";
                }
            }
            else{
                $_SESSION['erreur']="Erreur à l'ajout de la mission ! Veuillez réssayer. ";
            }
        }catch(Exception | Error $e){
            $_SESSION['erreur']="Erreur à l'ajout de la mission ! Veuillez réssayer. ";
        }
        if(Membre::Admin()){
            header("location:./admin/missions.php");
        }
        elseif(Membre::Collab()){
            header("location:./collab/missions.php");
        }
    }
    /*Modifier une mission*/
    elseif (isset($_POST['modifMiss'])){
        $IdMiss=$_POST["IdMiss"];
        $ref=Mission::getRef($_POST['IdMiss']);
        try{
            $Départ = new DateTime("$_POST[Départ]"); // date de début
            $Retour = new DateTime("$_POST[Retour]"); // date de fin
            if($_POST["TypeMiss"]=='Journalière'){
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
            $DateMiss=date("d/m/20y H:i:s");
            $mission=new Mission($IdMiss,$ref,$_POST['IdMb'],$_POST['ObjMiss'],$_POST['LieuDép'],$_POST['MoyTrans'],$_POST['Départ'],$_POST['Retour'],$nbJours,$DateMiss,$_POST['TypeMiss'],$_POST['Accomp'],$_POST['Note'],"0");
            if($mission->modifierMission()){
                $historique=new Historique('Modification',date("d/m/20y H:i:s"),"Mission $ref");
                if($historique->insertHistorique()){
                    $_SESSION['success']="Mission modifiée avec succés ! ";
                }
                else{
                    $_SESSION['erreur']="Erreur à la modification de la mission ! Veuillez réssayer. 1";
                }
            }
            else{
                $_SESSION['erreur']="Erreur à la modification de la mission ! Veuillez réssayer.2 ";
            }
        }catch(Exception | Error $e){
            $_SESSION['erreur']="Erreur à la modification de la mission ! Veuillez réssayer. 3";
        }
        if(Membre::Admin()){
            header("location:./admin/missions.php");
        }
        elseif(Membre::Collab()){
            header("location:./collab/missions.php");
        }
    }
    /*Archiver une mission*/
    elseif(isset($_GET['archMiss']) && isset($_GET['IdMiss'])){
        try{
            $Date=date("20y/m/d h:m:s");
            $ref=Mission::getRef($_GET['IdMiss']);
            if(Mission::archiverMission($_GET['IdMiss'],$Date)){
                $historique=new Historique('Archivage',$Date,"Mission $ref");
                if($historique->insertHistorique()){
                    $_SESSION['success']="Mission archivée avec succés ! ";
                }
                else{
                    $_SESSION['erreur']="Erreur lors l'archivage de la mission ! Veuillez réssayer. ";
                }
            }
            else{
                $_SESSION['erreur']="Erreur lors l'archivage de la mission ! Veuillez réssayer. ";
            }
        }catch(Exception | Error $e){
            $_SESSION['erreur']="Erreur lors l'archivage de la mission ! Veuillez réssayer. ";
        }
        header("location:./admin/missions.php?page=$_GET[page]");
    }
    /*Restaurer une mission*/
    elseif(isset($_GET['restMiss']) && isset($_GET['IdMiss'])){
        try{
            $Date=date("20y/m/d h:m:s");
            $ref=Mission::getRef($_GET['IdMiss']);
            if(Mission::restaurerMission($_GET['IdMiss'])){
                $historique=new Historique('Restauration',$Date,"Mission $ref");
                if($historique->insertHistorique()){
                    $_SESSION['success']="Mission restaurée avec succés ! ";
                }
                else{
                    $_SESSION['erreur']="Erreur lors la restauration de la mission ! Veuillez réssayer. ";
                }
            }
            else{
                $_SESSION['erreur']="Erreur lors la restauration de la mission ! Veuillez réssayer. ";
            }
        }catch(Exception | Error $e){
            $_SESSION['erreur']="Erreur lors la restauration de la mission ! Veuillez réssayer. ";
        }
        header("location:./admin/archives.php?page=$_GET[page]");
    }
    /*Supprimer une mission*/
    elseif(isset($_GET['suppMiss']) && isset($_GET['IdMiss'])){
        try{
            $ref=Mission::getRef($_GET['IdMiss']);
            $Date=date("20y/m/d h:m:s");
            if(Mission::supprimerMission($_GET['IdMiss'])){
                $historique=new Historique('Suppression',$Date,"Mission $ref");
                    if($historique->insertHistorique()){
                        $_SESSION['success']="Mission supprimée avec succés ! ";
                    }
                    else{
                        $_SESSION['erreur']="Erreur lors la suppression de la mission ! Veuillez réssayer. ";
                    }
            }
            else{
                $_SESSION['erreur']="Erreur lors la suppression de la mission ! Veuillez réssayer. ";
            }
        }
        catch(Exception | Error $e){
            $_SESSION['erreur']="Erreur lors la suppression de la mission ! Veuillez réssayer. ";
        }
        header("location:./admin/archives.php?page=$_GET[page]");
    }
    /*Valider une mission*/
    elseif(isset($_GET['validerMiss'])){
        $IdMiss=$_GET['IdMiss'];
        $mission=Mission::getMissById($IdMiss);        
        $DateMiss=explode(" ",$mission->getDateMiss());
        $ref=$mission->getRéfMiss();
        $membre=Membre::getById($mission->getIdMb());
        $nom=$membre->getNom();
        $prenom=$membre->getPrénom();
        $LieuDép=$mission->getLieuDép();
        $ObjMiss=$mission->getObjMiss();
        $Départ=$mission->getDépart();
        $Retour=$mission->getRetour();
        {
            $pdf = new PDF('P','mm','A4');
            // Nouvelle page A4 (incluant ici logo, titre et pied de page)
            $pdf->AddPage();
            // Polices par défaut : Helvetica taille 9
            $pdf->SetFont('Helvetica','',9);
            // Couleur par défaut : noir
            $pdf->SetTextColor(0);
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(10,75,iconv("UTF-8", "CP1252//TRANSLIT", "Réf : $ref"));
            $pdf->Text(160,75,iconv("UTF-8", "CP1252//TRANSLIT", "Oujda le : $DateMiss[0]"));
            $pdf->SetFont('Helvetica','b',20);
            $pdf->Text(70,100,iconv("UTF-8", "CP1252//TRANSLIT", "ORDRE DE MISSION"));
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(10,135,"Collaborateur : ");
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(70,135,"$prenom $nom");
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(10,155,iconv("UTF-8", "CP1252//TRANSLIT", "Lieu de déplacement : "));
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(70,155,"$LieuDép");
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(10,175,"Objet de la mission : ");
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(70,175,iconv("UTF-8", "CP1252//TRANSLIT","$ObjMiss"));
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(10,195,iconv("UTF-8", "CP1252//TRANSLIT", "Date de départ : "));
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(70,195,"$Départ");
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(120,195,"Date de retour : ");
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(180,195,"$Retour");
            $pdf->Text(120,225,"Signature");
            $pdf->Text(120,235,"Salah-dine KHLIFI");
            $pdf->Output('F',"PDF/Ordre_Mission/Ordre_Mission_$IdMiss.pdf",true);
        }
        if(Mission::validerMission($IdMiss)){
            $historique=new Historique('Validation',date("d/m/20y H:i:s"),"Mission $row[RéfMiss]");
            if($historique->insertHistorique()){
                $_SESSION['success']="Mission validée avec succés ! ";
            }
            else{
                $_SESSION['erreur']="Erreur lors la validation de la mission ! Veuillez réssayer.";
            }
        }
        else{
            $_SESSION['erreur']="Erreur lors la validation de la mission ! Veuillez réssayer.";
        }
        header("location:./admin/missions.php?page=$_GET[page]");
    }
    /*Valider le remboursement d'une mission*/
    elseif(isset($_POST['validerRemb']) ){
        $IdMiss=$_POST['IdMiss'];
        $Remb=$_POST['Remb'];
        $Date=Date("d-m-20y");
        if(isset($_POST['nomFile'])){
            $file=$_FILES['file'];
            $Description=$_POST['nomFile'];
            $typeFile=$_POST['typeFile'];
            for($i=0;$i<count($typeFile);$i++){
                $Montant=Frais::getMontantFrais($typeFile[$i]);
                $Remb+=$Montant;
                $array=explode('.',$file['name'][$i]);
                $ext=end($array);
                $filename=(time()+$i)."_img.$ext";
                if(Mission::ajouterPièceJointe($_POST['IdMiss'],$typeFile[$i],$Description[$i],$filename)){
                    $tmp_name = $file['tmp_name'][$i];
                    move_uploaded_file($tmp_name,"./PJ/$filename");
                }
            }
        }
        $mission=Mission::getMissById($IdMiss);
        $membre=Membre::getById($mission->getIdMb());
        $groupe=Groupe::getGroupeById($membre->getGrp());
        $Ref=$mission->getRéfMiss();
        $IdMb=$mission->getIdMb();
        $ObjMiss=$mission->getObjMiss();
        $LieuDép=$mission->getLieuDép();
        $nom=$membre->getNom();
        $prenom=$membre->getPrénom();
        $CIN=$membre->getCIN();
        $Civilité=$membre->getTitreCivilité();
        $Départ=$mission->getDépart();
        $Retour=$mission->getRetour();
        $DateFormat=explode("-",$Départ);
        $mois=mois($DateFormat[1]);
        $année=$DateFormat[2];
        $Remb += $Remb *($groupe->getTaux()/100);
        if(Mission::validerRemb($IdMiss,$Remb,$_POST['Paiement'])){
            $mission=Mission::getMissById($IdMiss);
            $paiement=Mission::getTypePaiement($_POST['Paiement']);
            $historique=new Historique("Validation de remboursement",date("d/m/20y H:i:s"),"Mission $Ref");
            if($historique->insertHistorique()){
                $_SESSION['success']="Remboursement validé avec succés ! ";
            }
            else{
                $_SESSION['erreur']="Erreur lors la validation du remboursement ! Veuillez réssayer.";
            }
        }
        {
            $lettre=new ChiffreEnLettre();
            $pdf = new PDF('P','mm','A4');
            $pdf->AddPage();
            $pdf->SetFont('Helvetica','',9);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(10,75,iconv("UTF-8", "CP1252//TRANSLIT", "Réf : $Ref"));
            $pdf->Text(160,75,iconv("UTF-8", "CP1252//TRANSLIT", "Oujda le : $Date"));
            $pdf->SetFont('Helvetica','b',20);
            $pdf->Text(27,100,iconv("UTF-8", "CP1252//TRANSLIT", "DEMANDE DE REMBOURSEMENT DES FRAIS"));
            $pdf->Text(75,112,iconv("UTF-8", "CP1252//TRANSLIT", "DE DÉPLACEMENT"));
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(10,135,"Collaborateur : ");
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(70,135,"$nom $prenom");
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(10,150,iconv("UTF-8", "CP1252//TRANSLIT", "Lieu de déplacement : "));
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(70,150,"$LieuDép");
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(10,165,"Objet de la mission : ");
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(70,165,iconv("UTF-8", "CP1252//TRANSLIT","$ObjMiss"));
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(10,180,iconv("UTF-8", "CP1252//TRANSLIT", "Date : "));
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(70,180,"$Date");
            $pdf->SetFont('Helvetica','b',12);
            $pdf->Text(15,195,"*");
            $pdf->Text(20,195,iconv("UTF-8", "CP1252//TRANSLIT", "Montant à rembourser : "));
            $pdf->Text(80,195,iconv("UTF-8", "CP1252//TRANSLIT", "$Remb DH"));
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(80,205);
            $pdf->MultiCell(100,6,iconv("UTF-8", "CP1252//TRANSLIT", ucfirst($lettre->Conversion($Remb))." Dirhams"),0,1,0,false);
            //$pdf->Text(90,205,iconv("UTF-8", "CP1252//TRANSLIT", "$text"));
            $pdf->Text(120,240,"Signature");
            $pdf->Text(120,250,"Salah-dine KHLIFI");
            $pdf->AddPage();
            $pdf->SetFont('Helvetica','',12);
            $pdf->Text(10,75,iconv("UTF-8", "CP1252//TRANSLIT", "Réf : $Ref"));
            $pdf->Text(160,75,iconv("UTF-8", "CP1252//TRANSLIT", "Oujda le : $Date"));
            $pdf->SetFont('Helvetica','b',20);
            $pdf->Text(88,100,iconv("UTF-8", "CP1252//TRANSLIT", "DECHARGE"));
            $pdf->SetFont('Helvetica','',12);
            $pdf->Ln('30');
            $pdf->SetX('10');
            $pdf->MultiCell(190,9,iconv("UTF-8", "CP1252//TRANSLIT", "Je soussiné $Civilité $nom $prenom titulaire de la CIN n° $CIN reconnais avoir reçu de la société exen consulting sarl la somme de $Remb DH ".ucfirst($lettre->Conversion($Remb))."Dirhams par $paiement au titre des remboursements de frais de mon deplacement à $LieuDép durant le mois de $mois $année"),0,'J');
            $pdf->Output('F',"PDF/Demande_Remboursement/Demande_Remboursement_$IdMiss.pdf",true);
        }
        header("location:./admin/missions.php");
    }
    //Traitement sue la table pièces jointes
    /*Modifier une pièce jointe*/
    elseif (isset($_POST['modifPJ'])) {
        $file = $_FILES['file'];
        $idPJ = $_POST['IdPJ'];
        
        $array = explode('.', $file['name']);
        $ext = end($array);
        $filename = time() . "_" . $idPJ . "_img.$ext";
        
        DataBase::updateData('piècesjointes', array(
            "NomPJ" => $filename,
        ), "IdPJ=$idPJ");
        
        $tmp_name = $file['tmp_name'];
        $destination = "./PJ/$filename";
        move_uploaded_file($tmp_name, $destination);
        
        echo json_encode(array('success' => true));
        exit;
    }
    //Traitements sur la table groupe
    /*Ajouter un groupe*/
    elseif (isset($_POST['ajtGroupe'])) {
        $libelle = $_POST['Libellé'];
        $tauxG = $_POST['TauxG'];
        try{
            $groupe=new Groupe('',$libelle, $tauxG);
            if($groupe->ajouterGroupe()){
                $historique=new Historique("Ajout",date("d/m/20y H:i:s"),"Groupe $libelle");
                if($historique->insertHistorique()){
                    $_SESSION['success'] = "Groupe ajouté avec succès !";
                    header("Location:./admin/groupes.php");
                }else{
                    $_SESSION['erreur'] = "Erreur à l'ajout du groupe ! Veuillez réssayer.";
                    header("Location:./admin/groupes.php");
                }
            }
            else{
                $_SESSION['erreur'] = "Erreur à l'ajout du groupe ! Veuillez réssayer.";
                header("Location:./admin/groupes.php");
            }
        }
        catch(Error | Exception $e){
            $_SESSION['erreur'] = "Erreur à l'ajout du groupe ! Veuillez réssayer.";
            header("Location:./admin/groupes.php");
        }
    }
    /*Modifier un groupe*/
    elseif (isset($_POST['modifGroupe'])) {
        $IdG = $_POST['IdG'];
        $Libellé = $_POST['Libellé'];
        $taux = $_POST['TauxG'];
        // Récupérer les anciennes valeurs du groupe
        $ancienGroupe = Groupe::getGroupeById($IdG);
        $ancienLibelle = $ancienGroupe->getLibellé();
        $ancienTauxG = $ancienGroupe->getTaux();
        // Vérifier si les données ont changé
        try{
            if ($Libellé == $ancienLibelle && $taux == $ancienTauxG) {
                $_SESSION['success'] = "Aucun changement détecté. Pas de mise à jour effectuée.";
                header("Location: ./admin/groupes.php");
            } 
            else {
                $groupe=new Groupe($IdG,$Libellé,$taux);
                if($groupe->modifierGroupe()){
                    if($historique=new Historique("Ajout",date("d/m/20y H:i:s"),"Groupe $Libellé")){
                        $_SESSION['success'] = "Groupe modifié avec succès !";
                        header("Location: ./admin/groupes.php");
                    }else{
                        $_SESSION['erreur'] = "Erreur à la modification du groupe ! Veuillez réssayer.";
                        header("Location: ./admin/groupes.php");
                    }
                }
                else{
                    $_SESSION['erreur'] = "Erreur à la modification du groupe ! Veuillez réssayer.";
                    header("Location: ./admin/groupes.php");
                }
            }
        }
        catch(Error | Exception $e){
            $_SESSION['erreur'] = "Erreur à la modification du groupe ! Veuillez réssayer.";
            header("Location: ./admin/groupes.php");
        }
    }
    /*Supprimer une groupe*/
    elseif (isset($_GET['suppGroupe']) && isset($_GET['IdG'])) {
        $idG = $_GET['IdG'];
        $page = $_GET['page'];
        try{
            if(Groupe::supprimerGroupe($idG, $page)){
                $_SESSION['success'] = "Groupe supprimé avec succès !";
                header("Location: ./admin/groupes.php?page=$page");
            }
            else{
                $_SESSION['erreur'] = "Erreur à la suppression de groupe ! Veuillez réssayer.";
                header("Location: ./admin/groupes.php?page=$page");
            }
        }
        catch(Error | Exception $e){
            $_SESSION['erreur'] = "Erreur à la suppression de groupe ! Veuillez réssayer.";
            header("Location: ./admin/groupes.php?page=$page");
        }
    }
    //Traitements sur la table Frais
    /*Ajouter un frais*/
    elseif (isset($_POST['ajtFrais'])) {
        $libelleFrais = $_POST['LibelléFrais'];
        $montantFrais = $_POST['MontantFrais'];
        try{
            if(Frais::vérifierLibellé($libelleFrais)){
                $_SESSION['erreurAjt']=true;
                $_SESSION['erreurLibelle'] = "Libellé existe déjà !";
                header("Location: ./admin/frais.php");
            }
            else{
                if(Frais::ajouterFrais($libelleFrais, $montantFrais)){
                    $_SESSION['success'] = "Frais ajouté avec succès !";
                    header("Location: ./admin/frais.php");
                }
                else{
                    $_SESSION['erreur'] = "Erreur à la modification du frais ! Veuillez réssayer.";
                    header("Location: ./admin/frais.php");
                }
            }
        }
        catch(Error | Exception $e){
            $_SESSION['erreur'] = "Erreur à la modification du frais ! Veuillez réssayer.";
            header("Location: ./admin/frais.php");
        }
        
    } 
    /*Modifier un frais*/
    elseif (isset($_POST['modifFrais'])) {
        $idFrais = $_POST['IdFrais'];
        $libelleFrais = $_POST['LibelléFrais'];
        $montantFrais = $_POST['MontantFrais'];
        $ancienFrais=Frais::getFraisById($idFrais);
        $ancienLibelle=$ancienFrais[0]['LibelléFrais'];
        $ancienMontant=$ancienFrais[0]['MontantFrais'];
        try{
            if($libelleFrais == $ancienLibelle && $montantFrais == $ancienMontant){
                $_SESSION['success'] = "Aucun changement détecté. Pas de mise à jour effectuée.";
                header("Location: ./admin/frais.php");
            }
            else{
                if(Frais::vérifierLibelléId($libelleFrais,$idFrais)){
                    $_SESSION['erreurModif']=true;
                    $_SESSION['erreurLibelle'] = "Libellé existe déjà !";
                    header("Location: ./admin/frais.php");
                }
                else{
                    if(Frais::modifierFrais($idFrais, $libelleFrais, $montantFrais)){
                        $_SESSION['success'] = "Frais modifié avec succès !";
                        header("Location: ./admin/frais.php");
                    }
                    else{
                        $_SESSION['erreur'] = "Erreur à la modification du frais ! Veuillez réssayer .";
                        header("Location: ./admin/frais.php");
                    }
                }
                }
        } catch(Exception | Error $e){
            $_SESSION['erreur'] = "Erreur à la modification du frais ! Veuillez réssayer .";
            header("Location: ./admin/frais.php");
        }
    } 
    /*Supprimer un frais*/
    elseif (isset($_GET['suppFrais']) && isset($_GET['IdFrais'])) {
        $idFrais = $_GET['IdFrais'];
        $page = $_GET['page'];
        if(Frais::supprimerFrais($idFrais, $page)){
            $_SESSION['success'] = "Frais supprimé avec succès !";
            header("Location: ./admin/frais.php?page=$page");
        }
    }
    //Traitement de sélection de données de la base de données
    /*Selection des informations d'un collaborateur*/
    elseif(isset($_POST['IdMb']) && isset($_POST['getCollab'])){
        $con=DataBase::connect();
        $rslt=$con->query("select * from membres natural join groupes where IdMb=$_POST[IdMb]");
        $row=$rslt->fetch();
        $rslt2=$con->query("select * from missions where IdMb=$row[IdMb]");
        $row2=$rslt2->fetch();
        $nbrMiss=$rslt2->rowCount();
        $infoCollab = array(
            'Nom' => $row['Nom'],
            'Prénom' => $row['Prénom'],
            'TitreCivilité' => $row['TitreCivilité'],
            'IdG' => $row['IdG'],
            'Email' => $row['Email'],
            'CIN' => $row['CIN'],
            'Profil' => $row['Profil'],
            'nbrMiss' => $nbrMiss,
        );
        echo json_encode($infoCollab);
    }
    /*Selection des informations d'une mission*/
    elseif(isset($_POST['IdMiss']) && isset($_POST['getMiss'])){
        $con=DataBase::connect();
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
    /*Selection de tous les groupes / informations d'un groupe*/
    elseif (isset($_POST['getGroupes'])) {
        $con = DataBase::connect();
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
    /*Selection de tous les frais / informations d'un frais*/
    elseif(isset($_GET['getFrais'])){
        $con=DataBase::connect();
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
    /*Selection de toutes les missions effectuées par un collaborateur*/
    elseif(isset($_POST['getCollabMiss']) && isset($_POST['IdMb'])){
        $con=DataBase::connect();
        $rslt=$con->query("select * from missions natural join membres where IdMb=$_POST[IdMb] and deletedAt is null");
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
    /*Selection de toutes les pièces jointes d'ne mission*/
    elseif(isset($_POST['getMissPJ']) && isset($_POST['IdMiss'])){
        $con=DataBase::connect();
        $rslt=$con->query("select * from piècesjointes natural join frais where IdMiss=$_POST[IdMiss]");
        $infoMiss = array();
        while ($row = $rslt->fetch()) {
            $info = array(
                'IdPJ' => $row['IdPJ'],
                'NomPJ' => $row['NomPJ'],
                'Frais' => $row['LibelléFrais'],
            );
            $infoMiss[] = $info;
        }
        echo json_encode($infoMiss);
    }
    //Traitements de vérifications des informations déjà existante
    /*Vérification de l'Email et CIN pour le formulaire de modification*/
    elseif(isset($_POST['vérifEmailCinModif'])){
        $response = array();
        if (Membre::vérifCINlId($_POST['CIN'], $_POST['IdMb'])) {
            $response['erreurCIN'] = "CIN déjà existant";
        }
        if (Membre::vérifEmailId($_POST['Email'], $_POST['IdMb'])) {
            $response['erreurEmail'] = "Email déjà existant";
        }
        echo json_encode($response);
    }
    /*Vérification de l'Email et CIN pour le formulaire d'ajout'*/
    elseif(isset($_POST['vérifEmailCinAjt'])){
        $response = array();
        if (Membre::vérifCIN($_POST['CIN'])) {
            $response['erreurCIN'] = "CIN déjà existant";
        }
        if (Membre::vérifEmail($_POST['Email'])) {
            $response['erreurEmail'] = "Email déjà existant";
        }
        echo json_encode($response);
    }
    /*Vérification de Libellé pour le formulaire d'ajout d'un groupe*/
    elseif(isset($_POST['vérifLibelleGrp'])){
        $response = array();
        if(Groupe::vérifierLibellé($_POST['Libellé'])){
            $response['erreurLibelle'] = "Libellé déjà existante";
        }
        echo json_encode($response);
    }
    /*Vérification de Libellé pour le formulaire de modification d'un groupe*/
    elseif(isset($_POST['vérifLibelleGrpModif'])){
        $response = array();
        if(Groupe::vérifierLibelléId($_POST['Libellé'],$_POST['IdG'])){
            $response['erreurLibelle'] = "Libellé déjà existante";
        }
        echo json_encode($response);
    }
    /*Vérification de Libellé pour le formulaire d'ajout d'un frais*/
    elseif(isset($_POST['vérifLibelleFrais'])){
        $response = array();
        if(Frais::vérifierLibellé($_POST['Libellé'])){
            $response['erreurLibelle'] = "Libellé déjà existante";
        }
        echo json_encode($response);
    }
    /*Vérification de Libellé pour le formulaire de modification d'un frais*/
    elseif(isset($_POST['vérifLibelleFraisModif'])){
        $response = array();
        if(Frais::vérifierLibelléId($_POST['Libellé'],$_POST['IdFrais'])){
            $response['erreurLibelle'] = "Libellé déjà existante";
        }
        echo json_encode($response);
    }
    /*Vérifictaion de mot de passe actuelle*/
    elseif(isset($_POST['vérifMdps'])){
        $IdMb=$_POST['IdMb'];
        $Mdps=$_POST['Mdps'];
        if(! Membre::vérifMdps($IdMb,$Mdps)){
            echo "1";
        }
    }
    //Traitement de déconnexion
    elseif(isset($_GET['décon'])){
        $_SESSION = array();
        setcookie(session_name(),' ', time()-1);
        session_destroy();
        DataBase::insertData("historique",array(
            "TypeAction" => "Déconnexion",
            "DateAction" => date("d/m/20y H:i:s"),
            "ElementAction" => null,
        ));
        header("location:index.php");
    }
?>