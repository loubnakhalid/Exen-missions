<?php
    require "../vendor/autoload.php";
    include "./inc/header.php";
    $con=\Tets\Oop\DataBase::connect();
    if(isset($_GET['search'])){
        $count=$con->query("select count(IdFrais) as cpt from frais where (IdFrais like '%$_GET[search]%' or LibelléFrais like '%$_GET[search]%' or MontantFrais like '%$_GET[search]%') ");
    }
    else{
        $count=$con->query("select count(IdFrais) as cpt from frais");
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
        $rslt=$con->query("select* from frais where (IdFrais like '%$_GET[search]%' or LibelléFrais like '%$_GET[search]%' or MontantFrais like '%$_GET[search]%')  order by IdFrais limit $debut,$nbr_elements_par_page");

    }
    else{
        $rslt=$con->query("select * from frais order by IdFrais limit $debut,$nbr_elements_par_page");
    }
    $row=$rslt->fetchAll();
?>

    <div class="card">
        <div class="card-header">
            <h4>Liste des frais</h4>
            <div class="entete">
                <div class="search-add">
                    <form class="d-flex" style="margin-right: 14px;" action="frais.php" method="get">
                        <input class="form-control me-sm-2 inptSearch" type="search" id="searchInput" name="search" placeholder="Rechercher" style="margin-right: -55px!important">
                        <button class="btn btn-secondary my-2 my-sm-0 subSearch" type="submit" style="margin-top: 5px !important;">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        </button>
                    </form>
                    <button type="button" id="btnAjtFrais" class="btn btn-lg btn-primary btnAjt" data-toggle="modal" data-target="#ajtFrais">+ Ajouter frais</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table id="tableMission" cellspacing='0'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Libellé</th>
                            <th>Montant</th>
                            <th style="width:70.375px"><i class="fa-solid fa-gear" style="color: #5a5a5a;"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($row as $row) {
                        echo "
                        <tr>
                            <td>$row[IdFrais]</td>
                            <td>$row[LibelléFrais]</td>
                            <td>$row[MontantFrais] DH</td>
                            <td class='action'>
                                <span>
                                    <i class='fa-solid fa-pen icnModifFrais' id='btnModifFrais' data-toggle='modal' data-target='#modifFrais' data-id='$row[IdFrais]' style='color: orange;font-size: 19px;'></i>
                                </span>
                                <span>
                                    <i class='fa-solid fa-trash fa-2x' style='color: #e82626;font-size: 20px !important' onclick=\"confirmSupp('frais',$row[IdFrais],$page)\"></i>
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

    <div class="modal fade" id="ajtFrais" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter frais</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="../controller.php" method="post" enctype="multipart/form-data" onsubmit="return vérifAjtFrais()">
                        <input type="hidden" name="IdFrais" id="IdFraisAjt">
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label for="Libellé">Libellé </label>
                                <input type="text" id="LibelléFraisAjt" name="LibelléFrais" class="form-control" placeholder="Libellé frais">
                                <div class="invalid-feedback" id="errLibelléFraisAjt" style="display: none;">Champ requis</div>
                            </div>
                            <div class="col">
                                <label for="Taux">Montant </label>
                                <input type="number" id="MontantFraisAjt" name="MontantFrais" class="form-control" placeholder="Montant de frais">
                                <div class="invalid-feedback" id="errMontantFraisAjt" style="display: none;">Champ requis</div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                    <button type="submit" name="ajtFrais" class="btn btn-primary" style="width: 145px;height: 36px;">Enregistrer</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modifFrais" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier groupe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../controller.php?page=1" method="post" enctype="multipart/form-data" onsubmit="return vérifModifFrais()">
                        <input type="hidden" name="IdFrais" id="IdFraisModif">
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label for="Libellé">Libellé </label>
                                <input type="text" id="LibelléFraisModif" name="LibelléFrais" class="form-control" placeholder="Libellé frais">
                                <div class="invalid-feedback" id="errLibelléFraisModif" style="display: none;">Champ requis</div>
                            </div>
                            <div class="col">
                                <label for="Taux">Taux </label>
                                <input type="number" id="MontantFraisModif" name="MontantFrais" class="form-control" placeholder="Montant frais">
                                <div class="invalid-feedback" id="errMontantFraisModif" style="display: none;">Champ requis</div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                    <button type="submit" name="modifFrais" class="btn btn-primary" style="width: 145px;height: 36px;">Enregistrer</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    
<?php
    include "./inc/footer.php";
?>