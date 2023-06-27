<?php
    use Tets\Oop\DataBase;
    include "./inc/header.php";
    //Nombre de pages et elements par pages
    $con=DataBase::connect();
    if(isset($_GET['search'])){
        $count=$con->query("select count(IdMiss) as cpt from missions natural join membres where DeletedAt is null and (RéfMiss like '%$_GET[search]%' or Nom like '%$_GET[search]%' or Prénom like '%$_GET[search]%' or LieuDép like '%$_GET[search]%' or ObjMiss like '%$_GET[search]%') ");
    }
    else{
        $count=$con->query("select count(IdMiss) as cpt from missions where DeletedAt is null");
    }
    $tcount=$count->fetchAll();
    $nbr_elements_par_page=6;
    $nbr_de_pages=ceil($tcount[0]["cpt"]/$nbr_elements_par_page);
    //pagination
    if(isset($_GET["page"]) && $_GET["page"] <= $nbr_de_pages && $_GET["page"] >= 1){
        @$page=$_GET["page"];
    }
    elseif(isset($_GET["page"]) && ($_GET["page"] > $nbr_de_pages || $_GET["page"] < 1)){
        @$page=1;
    }
    else{
        @$page=1;
    }
    $debut=($page-1)*$nbr_elements_par_page;
    //les enregistrements
    if(isset($_GET['search'])){
        $rslt=$con->query("select * from missions natural join membres where DeletedAt is null and (RéfMiss like '%$_GET[search]%' or Nom like '%$_GET[search]%' or Prénom like '%$_GET[search]%' or LieuDép like '%$_GET[search]%' or ObjMiss like '%$_GET[search]%')  order by IdMiss desc limit $debut,$nbr_elements_par_page");
    }
    else{
        $rslt=$con->query("select * from missions natural join membres where DeletedAt is null order by IdMiss desc limit $debut,$nbr_elements_par_page");
    }
    $row=$rslt->fetchAll();
?>
    <!--Liste des missions-->
    <div class="card">
        <div class="card-header">
            <h4>Liste des missions</h4>
            <div class="entete">
                <div class="search-add">
                    <form class="d-flex" style="margin-right: 14px;" action="missions.php" method="get">
                        <input class="form-control me-sm-2 inptSearch" type="search" id="searchInput" name="search" placeholder="Rechercher" style="margin-right: -55px !important;">
                        <button class="btn btn-secondary my-2 my-sm-0 subSearch" type="submit" style="margin-top: 5px !important;">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        </button>
                    </form>
                    <button type="button" class="btn btn-lg btn-primary btnAjt" data-toggle="modal" data-target="#formAjt">+ Ajouter mission</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table id="tableMission" cellspacing='0'>
                    <thead>
                        <tr>
                            <th style="width:31.2188px">#</th>
                            <th>Réf</th>
                            <th>Collaborateur</th>
                            <th>Objet</th>
                            <th>Ville</th>
                            <th>Transport</th>
                            <th>Départ</th>
                            <th>Retour</th>
                            <th>Durée</th>
                            <th>Nuitées</th>
                            <th>Date mission</th>
                            <th>Pièces</th>
                            <th style="width:100.3906px"><i class="fa-solid fa-gear" style="color: #5a5a5a;"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($row as $row) {
                        $con=DataBase::connect();
                        $rsltPJ=$con->query("select * from piècesjointes where IdMiss=$row[IdMiss]");
                        if($rsltPJ->rowCount()!=0){
                            $PJ=$rsltPJ->rowCount();
                            $btnPJ="<button data-id='$row[IdMiss]' data-toggle='modal' data-target='#formPJ' class='btnPJ'>$PJ</button>";
                        }
                        else{
                            $btnPJ='-';
                        }
                        $Nuité=$row['Durée']-1;
                        echo "
                        <tr>
                            <td>$row[IdMiss]</td>
                            <td>$row[RéfMiss]</td>
                            <td>$row[Nom] $row[Prénom]</td>
                            <td>$row[ObjMiss]</td>
                            <td>$row[LieuDép]</td>
                            <td>$row[MoyTrans]</td>
                            <td>$row[Départ]</td>
                            <td>$row[Retour]</td>
                            <td>$row[Durée] j</td>
                            <td>$Nuité</td>
                            <td>$row[DateMiss]</td>
                            <td>$btnPJ</td>
                            <td class='action'>
                                <span>
                                    <lord-icon src='https://cdn.lordicon.com/dnmvmpfk.json' class='info' trigger='hover' data-toggle='modal' data-target='#infoMiss' colors='primary:#0d6efd' data-id='$row[IdMiss]'></lord-icon>
                                </span>
                            ";
                            
                            if($row['StatutMiss']==1 && $row['Montant']!=NULL){
                                $class="btn btn-secondary btn-sm dropdown-toggle green disabled";
                                $modifClass="dropdown-item icnModifMiss disabled";
                                $validClass="dropdown-item disabled";
                                $validRembClass="dropdown-item disabled";
                                $ordreClass="dropdown-item";
                                $demandeRembClass="dropdown-item";
                            }
                            elseif($row['StatutMiss']==0){
                                $class="btn btn-sm dropdown-toggle red";
                                $modifClass="dropdown-item icnModifMiss";
                                $validClass="dropdown-item";
                                $validRembClass="dropdown-item disabled";
                                $ordreClass="dropdown-item disabled";
                                $demandeRembClass="dropdown-item disabled";
                            }
                            else{
                                $class="btn btn-sm dropdown-toggle green";
                                $modifClass="dropdown-item disabled";
                                $validClass="dropdown-item disabled";
                                if($row['Montant']==NULL){
                                    $validRembClass="dropdown-item";
                                    $demandeRembClass="dropdown-item disabled";
                                }
                                else{
                                    $validRembClass="dropdown-item disabled";
                                    $demandeRembClass="dropdown-item";
                                }
                                $ordreClass="dropdown-item";
                            }
                            echo "
                                <span class='dropdown'>
                                    <button class='$class' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                        <i class='fa-sharp fa-regular fa-circle-check'></i>
                                    </button>
                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                        <input type='hidden' value='$row[TypeMiss]' id='TypeMiss'>
                                        <a class='$modifClass' data-target='#formModif' data-toggle='modal' data-id='$row[IdMiss]'>Modifier la mission</a>
                                        <a class='$validClass' href='../controller.php?validerMiss&IdMiss=$row[IdMiss]&page=$page'>Valider la mission</a>
                                        <a class='$validRembClass' id='lienValiderRemb' data-TypeMiss='$row[TypeMiss]' data-id='$row[IdMiss]' data-toggle='modal' data-target='#validerRemb'>Valider le remboursement</a>
                                    </div>
                                </span>
                                <span class='dropdown'>
                                    <i class='bx bx-dots-vertical-rounded' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></i>
                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                        <a class='$ordreClass' href='../PDF/Ordre_Mission/$row[OrdreMiss]' target='_blank'>Ordre de mission</a>
                                        <a class='$demandeRembClass' href='../PDF/Demande_Remboursement/$row[DemandeRemb]' target='_blank'>Demande de remboursement</a>
                                        <a class='dropdown-item' onclick='document.location.href=\"../controller.php?archMiss&IdMiss=$row[IdMiss]&page=$page\"'>Archiver</a>
                                    </div>
                                </span>
                            </td>
                        </tr>
                        ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div>
                <ul class="pagination">
                    <li class="page-item <?php if($page==1)echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page-1; ?>">&laquo;</a>
                    </li>
                    <?php
                    global $i;
                    for($i=1;$i<=$nbr_de_pages;$i++){
                        if($page != $i){
                            echo "
                            <li class='page-item'>
                                <a class='page-link' href='?page=$i'>$i</a>
                            </li>";
                        }
                        else{
                            echo "
                            <li class='page-item active'>
                                <a class='page-link' href='?page=$i'>$i</a>
                            </li>";
                        }
                    }
                    ?>
                    <li class="page-item <?php if($page==$i-1)echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>">&raquo;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--Formulaire d'ajout d'une mission-->
    <div class="modal fade" id="formAjt" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter mission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="ajtMiss" action="../controller.php" method="POST" onsubmit="return vérifAjtMiss()">
                    <div class="modal-body">
                        <div class="row-collab">
                            <label>Collaborateur pour la mission :</label>
                            <select name="IdCollab" class="form-select mt-1" id="collaborateur">
                                <option value="">Collaborateur</option>
                                <?php
                                $row=\Tets\Oop\DataBase::getDataWhere('membres','Statut=0');
                                foreach ($row as $row){
                                    echo "<option value='$row[IdMb]'>$row[Nom] $row[Prénom]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row g-3 mb-3" style="margin-top: 15px">
                            <div class="col">
                                <label>Objet de la mission</label>
                                <input type="text" name="ObjMiss" id="ObjMiss" placeholder="Objet de la mission" class="form-control mt-1">
                            </div>
                            <div class="col">
                                <label>Lieu de déplacement</label>
                                <input type="text" name="LieuDép" id="LieuDép" placeholder="Lieu de déplacement" class="form-control mt-1">
                            </div>
                            <div class="col">
                                <label>Moyen de transport</label>
                                <input type="text" name="MoyTrans" placeholder="Moyen de transport" class="form-control mt-1">
                            </div>
                        </div>
                        <div class="row mb-3" style="margin-top: 15px;">
                            <div class="col-xl col-lg">
                                <label>Date de départ</label>
                                <input type="text" id="Départ" name="Départ" placeholder="Date de départ" class="form-control mt-1 Départ" autocomplete="false">
                            </div>
                            <div class="col-xl col-lg">
                                <label>Date de retour</label>
                                <input type="text" id="Retour" name="Retour"  placeholder="Date de retour" class="form-control mt-1 Retour">
                            </div>
                            <div class="col-xl col-lg">
                                <label>Type mission</label>
                                <select type="text" name="TypeMiss" id="TypeMission" class="form-select mt-1">
                                    <option value="">Choisir</option>
                                    <option value="Journalière">Journalière</option>
                                    <option value="Mensuelle">Mensuelle</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center" style="margin-top: 15px;">
                            <div class="col-xl col-lg border-light">
                                <div class="form-check">
                                    <input type="hidden" value="0" name="accompagnateur">
                                    <input name="accompagnateur" class="form-check-input mt-1" type="checkbox" value="1" id="checkAccomp">
                                    <label class="form-check-label" for="checkAccomp">Accompagnateur</label>
                                </div>
                            </div>
                            <div class="col-xl col-lg nom_accompagnateur" id="Accomp" style="display: none;">
                                <label for="nomAccomp">Accompagnateur</label>
                                <input type="text" class="form-control mt-1" name="Accomp" id="nomAccomp" placeholder="Nom de l'accompagnateur">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl col-lg">
                                <label for="desc" class="form-label">Description :</label>
                                <textarea name="Note" class="form-control" id="desc" cols="30" rows="3" placeholder="Donner une petite description de la mission .." data-gramm="false" wt-ignore-input="true" data-quillbot-element="3D-A0C1vd6eH8sNXr1bQQ"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                        <button type="submit" name="ajtMiss" class="btn btnSub">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Formulaire de modification d'une mission-->
    <div class="modal fade" id="formModif" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Modifier la mission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="modifMiss" action="../controller.php?page=<?=$page?>" method="POST" onsubmit="return vérifModifMiss()">
                    <input type="hidden" name="IdMiss" id="IdMiss">
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label>Collaborateur pour la mission :</label>
                                <select name="IdMb" class="form-select mt-1" id="collaborateurModif">
                                    <option value="">Collaborateur</option>
                                    <?php
                                    $row2=\Tets\Oop\DataBase::getDataWhere('membres','Statut=0');
                                    foreach ($row2 as $row2){
                                        echo "<option value='$row2[IdMb]'>$row2[Nom] $row2[Prénom]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="nom_accompagnateur">Accompagnateur</label>
                                <input type="text" name="Accomp" class="form-control mt-1" id="AccompModif" placeholder="Nom de l'accompagnateur">
                            </div>
                        </div>
                        <div class="row g-3 mb-3" style="margin-top: 15px">
                            <div class="col">
                                <label>Objet de la mission</label>
                                <input type="text" name="ObjMiss" id="ObjMissModif" placeholder="Objet de la mission" class="form-control mt-1">
                            </div>
                            <div class="col">
                                <label>Lieu de déplacement</label>
                                <input type="text" name="LieuDép" id="LieuDépModif"  placeholder="Lieu de déplacement" class="form-control mt-1">
                            </div>
                            <div class="col">
                                <label>Moyen de transport</label>
                                <input type="text" name="MoyTrans" id="MoyTransModif"  placeholder="Moyen de transport" class="form-control mt-1">
                            </div>
                        </div>
                        <div class="row mb-3" style="margin-top: 15px;">
                            <div class="col-xl col-lg">
                                <label>Date de départ</label>
                                <input type="text" name="Départ" id="DépartModif"  placeholder="Date de départ" class="form-control Départ mt-1">
                            </div>
                            <div class="col-xl col-lg">
                                <label>Date de retour</label>
                                <input type="text" name="Retour" id="RetourModif"  placeholder="Date de retour" class="form-control Retour mt-1">
                            </div>
                            <div class="col-xl col-lg">
                                <label>Type mission</label>
                                <select type="text" name="TypeMiss" id="TypeMissModif" class="form-select mt-1">

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl col-lg">
                                <label for="desc" class="form-label">Description :</label>
                                <textarea name="Note" id="NoteModif" class="form-control" id="desc" cols="30" rows="3" placeholder="Donner une petite description de la mission .." data-gramm="false" wt-ignore-input="true" data-quillbot-element="3D-A0C1vd6eH8sNXr1bQQ"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" style="width: 72px;height: 36px;" onclick="history.back()">Fermer</button>
                        <button type="submit" name="modifMiss" class="btn btnSub" style="width: 145px;">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Formulaire des information d'une mission-->
    <div class="modal fade" id="infoMiss" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"> 
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoMissTitle">Détails mission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="infoMiss" id="tableInfoMiss">
                        <tr>
                            <td>
                                Collaborateur :
                            </td>
                            <td id="infoCollab">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Objet mission :
                            </td>
                            <td id="infoObjMiss">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Type mission :
                            </td>
                            <td id="infoTypeMiss">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Date départ :
                            </td>
                            <td id="infoDépart">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Date retour :
                            </td>
                            <td id="infoRetour">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Lieu déplacement :
                            </td>
                            <td id="infoLieuDép">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Moyen de tansport :
                            </td>
                            <td id="infoMoyTrans">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Durée :
                            </td>
                            <td id="infoDurée">

                            </td>
                        </tr>
                        <tr class="trInfoMontant">
                            <td>
                                Montant :
                            </td>
                            <td id="infoMontant">

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Formulaire de validation de remboursement d'une mission-->
    <div class="modal fade" id="validerRemb" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Valider le remboursement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../controller.php?page=1" method="post" enctype="multipart/form-data" onsubmit="return vérifRemb()">
                        <h6 style="color: #5a5a5a">Type de mission :</h6><hr style="width: 663px;margin-left: 0px !important;"/>
                            <input type="hidden" name="IdMiss" id="IdMissRemb">
                            <div class="row g-3 mb-3">
                                <div class="col">
                                    <label for="montant">Montant en DHS </label>
                                    <input type="number" id="Montant" name="Remb" class="form-control" placeholder="Montant à rembourser">
                                </div>
                                <div class="col">
                                    <label for="Paiement">Mode de paiement</label>
                                    <select class="form-select" id="Paiement" name="Paiement">
                                    <option value="">Choisir</option>
                                        <?php 
                                            $row=DataBase::getData('paiement');
                                            foreach($row as $row){
                                                echo "<option value='$row[IdPaiement]'>$row[TypePaiement]</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div style="align-items: center;display: flex;" onclick="addFile()">
                                <span>
                                    <i class="fa-solid fa-square-plus fa-2x" id="addF" style="margin-top: 10px;margin-bottom: 10px;color: gray;"></i>
                                </span>
                                <span style="margin-left: 10px;">
                                    Ajouter une pièce jointe
                                </span>
                            </div>
                            <div id="addFile" style="height: auto;overflow-y: auto;overflow-x: hidden;max-height: 158px;">
                                            
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                    <button type="submit" name="validerRemb" class="btn btnSub" onclick='document.getElementById("montant-rembourser").enabled="enabled";'>Enregistrer</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--Formulaire des pièces jointes d'une mission-->
    <div class="modal fade" id="formPJ" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Pièces jointes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--<form action="../controller.php" method="post" enctype="multipart/form-data">-->
                    <div class="modal-body">
                        <div class="row" id="rowMissPJ">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                    </div>
                <!--</form>-->
            </div>
        </div>
    </div>

<?php
    include "./inc/footer.php";
?>