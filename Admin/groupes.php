<?php
    require "../vendor/autoload.php";
    include "./inc/header.php";
    use \Tets\Oop\DataBase;
    $con=\Tets\Oop\DataBase::connect();
    if(isset($_GET['search'])){
        $count=$con->query("select count(IdG) as cpt from groupes where (IdG like '%$_GET[search]%' or Libellé like '%$_GET[search]%' or TauxG like '%$_GET[search]%') ");
    }
    else{
        $count=$con->query("select count(IdG) as cpt from groupes");
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
        $rslt=$con->query("select * from groupes where (IdG like '%$_GET[search]%' or Libellé like '%$_GET[search]%' or TauxG like '%$_GET[search]%') order by IdG limit $debut,$nbr_elements_par_page");

    }
    else{
        $rslt=$con->query("select * from groupes order by IdG limit $debut,$nbr_elements_par_page");
    }
    $row=$rslt->fetchAll();
?>

    <div class="card">
        <div class="card-header">
            <h4>Liste des groupes</h4>
            <div class="entete">
                <div class="search-add">
                    <form class="d-flex" style="/* margin-top: 37px; */margin-right: 14px;" action="groupes.php" method="get">
                        <input class="form-control me-sm-2" type="search" id="searchInput" name="search" placeholder="Search" style="margin-right: -55px!important;border-radius: 11px;height: 41px;">
                        <button class="btn btn-secondary my-2 my-sm-0" type="submit" style="width: 53px;height: 29px;margin-top: 5px !important;margin-right: 3px;background-color: white !important;border: none !important;color: gray !important;">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        </button>
                    </form>
                    <button type="button" class="btn btn-lg btn-primary" style="width: 177px;padding: 5px 2px;height: 38px;font-size: 17px;" data-toggle="modal" data-target="#ajtGroupe">+ Ajouter groupe</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table id="tableMiss" cellspacing='0'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Libellé</th>
                            <th>Taux</th>
                            <th style="width:70.375px"><i class="fa-solid fa-gear" style="color: #5a5a5a;"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($row as $row) {
                        echo "
                        <tr>
                            <td>$row[IdG]</td>
                            <td>$row[Libellé]</td>
                            <td>$row[TauxG]</td>
                            <td class='action'>
                                <span>
                                    <i class='fa-solid fa-pen icnModifGroupe'  data-toggle='modal' data-target='#validerRemb' data-id='$row[IdG]' style='color: orange;font-size: 19px;'></i>
                                </span>
                                <span>
                                    <i class='fa-solid fa-trash fa-2x' style='color: #e82626;font-size: 20px !important' onclick=\"confirmSupp('groupes',$row[IdG],$page)\"></i>
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
        </div>
    </div>

    <div class="modal fade" id="ajtGroupe" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un groupe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="../controller.php" method="post" enctype="multipart/form-data" onsubmit="return vérifAjtGroupe()">
                        <input type="hidden" name="IdG" id="IdG">
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label for="Libellé">Libellé </label>
                                <input type="text" id="LibelléAjt" name="Libellé" class="form-control" placeholder="Libellé groupe">
                                <div class="invalid-feedback" id="errLibelléAjt" style="display: none;">Champ requis</div>
                            </div>
                            <div class="col">
                                <label for="Taux">Taux </label>
                                <input type="number" id="TauxAjt" name="TauxG" class="form-control" placeholder="Taux de remboursement">
                                <div class="invalid-feedback" id="errTauxAjt" style="display: none;">Champ requis</div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                    <button type="submit" name="ajtGroupe" class="btn btn-primary" style="width: 145px;height: 36px;">Enregistrer</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modifGroupe" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier groupe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../controller.php?page=1" method="post" enctype="multipart/form-data" onsubmit="return vérifModifGroupe()">
                        <input type="hidden" name="IdG" id="IdGModif">
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label for="Libellé">Libellé </label>
                                <input type="text" id="LibelléModif" name="Libellé" class="form-control" placeholder="Libellé groupe">
                                <div class="invalid-feedback" id="errLibelléModif" style="display: none;">Champ requis</div>
                            </div>
                            <div class="col">
                                <label for="Taux">Taux </label>
                                <input type="number" id="TauxModif" name="TauxG" class="form-control" placeholder="Taux de remboursement">
                                <div class="invalid-feedback" id="errTauxModif" style="display: none;">Champ requis</div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                    <button type="submit" name="modifGroupe" class="btn btn-primary" style="width: 145px;height: 36px;">Enregistrer</button>
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
                        <h5 style="color: #5a5a5a">Type de mission :</h5><hr style="width: 663px;margin-left: 0px !important;">
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