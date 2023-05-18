<?php

    use Tets\Oop\DataBase;

    require "../vendor/autoload.php";
    include "./inc/header.php";
    $con=\Tets\Oop\DataBase::connect();
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
    <div class="entete">
        <h1>Liste des missions</h1>
        <div class="search-add">
            <form class="d-flex" style="/* margin-top: 37px; */margin-right: 14px;" action="missions.php" method="get">
                <input class="form-control me-sm-2" type="search" id="searchInput" name="search" placeholder="Search" style="margin-right: -55px!important;border-radius: 11px;height: 41px;">
                <button class="btn btn-secondary my-2 my-sm-0" type="submit" style="width: 53px;height: 29px;margin-top: 5px !important;margin-right: 3px;background-color: white !important;border: none !important;color: gray !important;">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                </button>
            </form>
            <button type="button" class="btn btn-lg btn-primary" style="width: 177px;padding: 5px 2px;height: 38px;font-size: 17px;" data-toggle="modal" data-target="#exampleModalCenter">+ Ajouter mission</button>
        </div>
    </div>
    <hr>
    <div class="table table-responsive">
        <table id="tableMiss" cellspacing='0'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Réf mission</th>
                    <th>Nom collaborateur</th>
                    <th>Objet mission</th>
                    <th>Lieu déplacement</th>
                    <th>Moyen transport</th>
                    <th>Départ</th>
                    <th>Retour</th>
                    <th>Durée</th>
                    <th>Nuité</th>
                    <th>Date mission</th>
                    <th style="width: 168.984px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($row as $row) {
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
                    <td class='action'>
                        <span>
                            <lord-icon src='https://cdn.lordicon.com/dnmvmpfk.json' class='info' trigger='hover' data-toggle='modal' data-target='#infoMiss' colors='primary:#0d6efd' data-id='$row[IdMiss]' style='width:25px;height:25px;margin-top: 5px'></lord-icon>
                        </span>
            ";
                if($row['StatutMiss']==0){
                    echo "
                        <span>
                            <i class='bx bx-edit icnModifMiss'  data-target='#formModif' data-toggle='modal' data-id='$row[IdMiss]' style='color:orange;margin-top: 4px;font-size: 25px;'></i>
                        </span>
                    ";
                }
                else{
                    echo "
                        <span class='dropdown'>
                            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='fa-sharp fa-solid fa-print' style='color: #ffffff;'></i>
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' href='../PDF/Ordre_Mission/$row[OrdreMiss]' target='_blank'>Ordre de mission</a>";
                    if($row['Montant']!=NULL){
                        echo "  <a class='dropdown-item' href='../PDF/Demande_Remboursement/$row[DemandeRemb]' target='_blank'>Demande de remboursement</a>";
                    }
                    else{
                        echo "  <a class='dropdown-item disabled'>Demande de remboursement</a>";
                    }
                        echo"
                            </div>
                        </span>
                    ";
                }
                if($row['StatutMiss']==1 && $row['Montant']!=NULL){
                    echo "
                        <span class='dropdown'>
                            <button class='btn btn-secondary dropdown-toggle green disabled' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fa-sharp fa-regular fa-circle-check'></i>
                            </button>
                        </span>
                    ";
                }
                else{
                    echo "
                        <span class='dropdown'>
                            <button class='btn btn-secondary dropdown-toggle green' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='fa-sharp fa-regular fa-circle-check'></i>
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <input type='hidden' value='$row[TypeMiss]' id='TypeMiss'>
                    ";
                    if($row['StatutMiss']==0){
                        echo "
                                <a class='dropdown-item' href='../controller.php?validerMiss&IdMiss=$row[IdMiss]&page=$page'>Valider la mission</a>
                                <a class='dropdown-item disabled' href='javascript:void(0)'  data-id='$row[IdMiss]'  data-toggle='modal' data-target='#validerRemb'>Valider le remboursement</a>
                        ";
                    }
                    else{
                        echo "  <a class='dropdown-item disabled' href='javascript:void(0)'>Valider la mission</a>
                            ";
                        if($row['Montant']==NULL){
                            echo "<a class='dropdown-item' id='lienValiderRemb' href='../controller.php?validerRemb&IdMiss=$row[IdMiss]&page=$page' data-TypeMiss='$row[TypeMiss]' data-id='$row[IdMiss]' data-toggle='modal' data-target='#validerRemb'>Valider le remboursement</a>";
                        }
                        else{
                            echo "<a class='dropdown-item disabled' href='javascript:void(0)'>Valider le remboursement</a>";
                        }
                    }
                }
                echo"
                            </div>
                        </span>
                        <span>
                            <i class='fa-sharp fa-solid fa-box-archive fa-2x' style='color:red' onclick='document.location.href=\"../controller.php?archMiss&IdMiss=$row[IdMiss]&page=$page\"'></i>
                        </span>
                    </td>
                </tr>";
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

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                            <select name="IdCollab" class="form-select" id="collaborateur">
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
                                <input type="text" name="ObjMiss" id="ObjMiss" placeholder="Objet de la mission" class="form-control">
                            </div>
                            <div class="col">
                                <label>Lieu de déplacement</label>
                                <input type="text" name="LieuDép" id="LieuDép" placeholder="Lieu de déplacement" class="form-control">
                            </div>
                            <div class="col">
                                <label>Moyen de transport</label>
                                <input type="text" name="MoyTrans" placeholder="Moyen de transport" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3" style="margin-top: 15px;">
                            <div class="col-xl col-lg">
                                <label>Date de départ</label>
                                <input type="text" name="Départ" id="Départ" placeholder="Date de départ" class="form-control" autocomplete="false">
                            </div>
                            <div class="col-xl col-lg">
                                <label>Date de retour</label>
                                <input type="text" name="Retour" id="Retour" placeholder="Date de retour" class="form-control">
                            </div>
                            <div class="col-xl col-lg">
                                <label>Type mission</label>
                                <select type="text" name="TypeMiss" id="TypeMission" class="form-select">
                                    <option value="">Choisir</option>
                                    <option value="Journaliere">Journaliere</option>
                                    <option value="Mensuel">Mensuel</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center" style="margin-top: 15px;">
                            <div class="col-xl col-lg border-light">
                                <div class="form-check">
                                    <input type="hidden" value="0" name="accompagnateur">
                                    <input name="accompagnateur" class="form-check-input" type="checkbox" value="1" id="checkAccomp">
                                    <label class="form-check-label" for="checkAccomp">Accompagnateur</label>
                                </div>
                            </div>
                            <div class="col-xl col-lg nom_accompagnateur" id="Accomp" style="display: none;">
                                <label for="nomAccomp">Accompagnateur</label>
                                <input type="text" class="form-control" name="Accomp" id="nomAccomp" placeholder="Nom de l'accompagnateur">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                        <button type="submit" name="ajtMiss" class="btn btn-primary" style="width: 145px;background-color: #69c1ec !important;border-color: #69c1ec !important;height: 36px;">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                    </table>
                </div>
            </div>
        </div>
    </div>
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
                                <select name="IdMb" class="form-select" id="collaborateurModif">
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
                                <input type="text" name="Accomp" class="form-control" id="AccompModif" placeholder="Nom de l'accompagnateur">
                            </div>
                        </div>
                        <div class="row g-3 mb-3" style="margin-top: 15px">
                            <div class="col">
                                <label>Objet de la mission</label>
                                <input type="text" name="ObjMiss" id="ObjMissModif" placeholder="Objet de la mission" class="form-control">
                            </div>
                            <div class="col">
                                <label>Lieu de déplacement</label>
                                <input type="text" name="LieuDép" id="LieuDépModif"  placeholder="Lieu de déplacement" class="form-control">
                            </div>
                            <div class="col">
                                <label>Moyen de transport</label>
                                <input type="text" name="MoyTrans" id="MoyTransModif"  placeholder="Moyen de transport" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3" style="margin-top: 15px;">
                            <div class="col-xl col-lg">
                                <label>Date de départ</label>
                                <input type="text" name="Départ" id="DépartModif"  placeholder="Date de départ" class="form-control">
                            </div>
                            <div class="col-xl col-lg">
                                <label>Date de retour</label>
                                <input type="text" name="Retour" id="RetourModif"  placeholder="Date de retour" class="form-control">
                            </div>
                            <div class="col-xl col-lg">
                                <label>Type mission</label>
                                <select type="text" name="TypeMiss" id="TypeMissModif" class="form-select">

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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;" onclick="history.back()">Fermer</button>
                        <button type="submit" name="modifMiss" class="btn btn-primary" style="width: 145px;background-color: #69c1ec !important;border-color: #69c1ec !important;height: 36px;">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                    <h5 style="color: #5a5a5a">Type de mission : Journaliere</h5><hr style="width: 663px;margin-left: 0px !important;">
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
                            <?php
                            $row=\Tets\Oop\DataBase::getData('frais');
                            ?>
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                    <button type="submit" name="validerRemb" class="btn btn-primary" style="width: 145px;height: 36px;" onclick='document.getElementById("montant-rembourser").enabled="enabled";'>Enregistrer</button>
                </div>
                </form>
            </div>
        </div>
    </div>

<?php
    if(isset($_SESSION['erreur'])){
        echo "<script>erreur(\"$_SESSION[erreur]\");</script>";
        unset($_SESSION['erreur']);
    }
    if(isset($_SESSION['success'])){
        echo "<script>success('$_SESSION[success]');</script>";
        unset($_SESSION['success']);
    }
    include "./inc/footer.html";
?>