<?php

use Tets\Oop\DataBase;

require "../vendor/autoload.php";
include "./inc/header.php";
$con=\Tets\Oop\DataBase::connect();
//Nbre d'enregistrement
if(isset($_GET['search'])){
    $count=$con->query("select count(IdMb) as cpt from membres where  (Nom like '%$_GET[search]%' or Prénom like '%$_GET[search]%' or CIN like '%$_GET[search]%' or Email like '%$_GET[search]%' or Profile like '%$_GET[search]%') ");
}
else{
    $count=$con->query("select count(IdMb) as cpt from membres");
}
$tcount=$count->fetchAll();
//pagination
if(isset($_GET["page"])){
    @$page=$_GET["page"];
}
else{
    @$page=1;
}
$nbr_elements_par_page=6;
$nbr_de_pages=ceil($tcount[0]["cpt"]/$nbr_elements_par_page);
$debut=($page-1)*$nbr_elements_par_page;
//les enregistrements
if(isset($_GET['search'])){
    $rslt=$con->query("select * from membres natural join groupes where (Nom like '%$_GET[search]%' or Prénom like '%$_GET[search]%' or CIN like '%$_GET[search]%' or Email like '%$_GET[search]%' or Profile like '%$_GET[search]%')  limit $debut,$nbr_elements_par_page");
}
else{
    $rslt=$con->query("select * from membres natural join groupes limit $debut,$nbr_elements_par_page");
}
$row=$rslt->fetchAll();
?>
<div class="entete">
    <h1>Liste des collaborateurs</h1>
    <div class="search-add">
        <form action="collabs.php" method="get" class="d-flex" style="/* margin-top: 37px; */margin-right: 14px;">
            <input class="form-control me-sm-2" name="search" type="search" placeholder="Search" style="margin-right: -55px!important;border-radius: 11px;height: 41px;">
            <button class="btn btn-secondary my-2 my-sm-0" type="submit" style="
                width: 53px;
                /* padding: 0px !important; */
                height: 29px;
                margin-top: 5px !important;
                margin-right: 3px;
                background-color: white !important;
                border: none !important;
                color: gray !important;
        "><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
        </form>
        <button type="button" id="btnAjtCollab" class="btn btn-lg btn-primary" data-toggle='modal' data-target='#ajtCollab' style="width: 207px;padding: 5px 2px;height: 38px;font-size: 17px;">+ Ajouter collaborateur</button>
    </div>
</div>
<hr>
<div class="table">
    <table cellspacing='0'>
        <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Groupe</th>
            <th>Email</th>
            <th>CIN</th>
            <th>Profile</th>
            <th>Missions</th>
            <th style="width:129px">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($row as $row) {
            $con=\Tets\Oop\DataBase::connect();
            $rslt=$con->query("select * from missions where IdMb=$row[IdMb]");
            $nbrMiss=$rslt->rowCount();
            if($nbrMiss != 0){
                $style="<button class='collabMiss'  data-id='$row[IdMb]' data-toggle='modal' data-target='#collabMiss' style='text-decoration:underline;background-color: white;border: none;'>$nbrMiss</button>";
            }
            else{
                $style="$nbrMiss";
            }
            echo "
            <tr>
                <td>$row[IdMb]</td>
                <td>$row[Nom]</td>
                <td>$row[Prénom]</td>
                <td>$row[Libellé]</td>
                <td>$row[Email]</td>
                <td>$row[CIN]</td>
                <td>$row[Profile]</td>
                <td>$style</td>
                <td class='action'>
                    <span>
                        <lord-icon src='https://cdn.lordicon.com/dnmvmpfk.json' class='infoCollab' data-toggle='modal' data-target='#infoCollab' trigger='hover' colors='primary:#0d6efd' data-id='$row[IdMb]' style='width:25px;height:25px;margin-top: 5px'></lord-icon>
                    </span>
                    <span>
                        <i class='bx bx-edit icnModifCollab'  data-toggle='modal' data-target='#modifCollab' data-id='$row[IdMb]' style='color: orange;margin-top: 4px;font-size: 25px;'></i>
                    </span>
                    <span>
                        <i class='fa-solid fa-trash fa-2x' style='color: #e82626;font-size: 24px !important' onclick=\"document.location.href='../controller.php?suppCollab&IdMb=$row[IdMb]'\"></i>
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

<div class="modal fade" id="ajtCollab" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="infoMissTitle">Ajouter collaborateur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form action="../controller.php" method="post" onsubmit="return vérifAjtCollab()">
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-xl xol-lg">
                        <label for="" class="form-label">Nom :</label>
                        <input type="text" name="Nom" id="NomAjt" placeholder="Nom" class="form-control">
                        <div class="invalid-feedback" id="errNomAjt" style="display: none;"></div>
                    </div>
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Prénom :</label>
                        <input type="text" name="Prénom" id="PrénomAjt" placeholder="Prénom" class="form-control">
                        <div class="invalid-feedback" id="errPrénomAjt" style="display: none;"></div>
                    </div>
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Groupe :</label>
                        <select name="Grp" id="GrpAjt" class="form-select">
                        <?php
                            $row=DataBase::getData('groupes');
                            foreach($row as $row){
                                if($row['Libellé']=='Aucun'){
                                    echo "<option value='$row[IdG]' selected>$row[Libellé]</option>";
                                }
                                else{
                                    echo "<option value='$row[IdG]'>$row[Libellé]</option>";
                                }
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Adresse email :</label>
                        <input type="text" name="Email" id="EmailAjt" placeholder="Email" class="form-control">
                        <div class="invalid-feedback" id="errEmailAjt" style="display: none;">
                        </div>
                    </div>
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Mot de passe :</label>
                        <input type="password" name="Mdps" id="MdpsAjt" placeholder="Mot de passe" class="form-control">
                        <div class="invalid-feedback" id="errMdpsAjt"></div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">CIN :</label>
                        <input type="text" name="CIN" id="CINAjt" placeholder="CIN" class="form-control">
                        <div class="invalid-feedback" id="errCINAjt" style="display: none;"></div>
                    </div>
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Profile :</label>
                        <input type="text" name="Profile" id="ProfileAjt" placeholder="Profile" class="form-control">
                        <div class="invalid-feedback" id="errProfileAjt" style="display: none;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                <button type="submit" name="ajtCollab" class="btn btn-primary" style="width: 145px;background-color: #69c1ec !important;border-color: #69c1ec !important;height: 36px;">Ajouter</button>
            </div>
        </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modifCollab" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="infoMissTitle">Modifier collaborateur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
            <form action="../controller.php" method="post" onsubmit="return vérifModifCollab()">
                <input type="hidden" name="IdMb" id="IdMb" value="">
                <div class="row g-3 mb-3">
                    <div class="col-xl xol-lg">
                        <label for="" class="form-label">Nom :</label>
                        <input type="text" name="Nom" id="NomModif" placeholder="Nom" class="form-control">
                        <div class="invalid-feedback" id="errNomModif" style="display: none;"></div>
                    </div>
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Prénom :</label>
                        <input type="text" name="Prénom" id="PrénomModif" placeholder="Prénom" class="form-control">
                        <div class="invalid-feedback" id="errPrénomModif" style="display: none;"></div>
                    </div>
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Groupe :</label>
                        <select name="Grp" class="form-select" id="IdG">
                        <?php
                            $row=DataBase::getData('groupes');
                            foreach($row as $row){
                                echo "<option value='$row[IdG]'>$row[Libellé]</option>";
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Adresse email :</label>
                        <input type="text" name="Email" id="EmailModif" placeholder="Email" class="form-control">
                        <div class="invalid-feedback" id="errEmailModif" style="display: none;"></div>
                    </div>
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Mot de passe :</label>
                        <input type="password" name="Mdps" id="MdpsModif" placeholder="Mot de passe" class="form-control">
                        <div class="invalid-feedback" id="errMdpsModif" style="display: none;"></div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">CIN :</label>
                        <input type="text" name="CIN" id="CINModif" placeholder="CIN" class="form-control">
                        <div class="invalid-feedback" id="errCINModif" style="display: none;"></div>
                    </div>
                    <div class="col-xl col-lg">
                        <label for="" class="form-label">Profile :</label>
                        <input type="text" name="Profile" id="ProfileModif" placeholder="Profile" class="form-control">
                        <div class="invalid-feedback" id="errProfileModif" style="display: none;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 72px;height: 36px;">Fermer</button>
                <button type="submit" name="modifCollab" class="btn btn-primary" style="width: 145px;background-color: #69c1ec !important;border-color: #69c1ec !important;height: 36px;">Modifier</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="infoCollab" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="infoMissTitle">Détails mission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"  id="modalInfoCollab">
                <table class="infoMiss" id="tableInfoMiss">
                    <tr>
                        <td>
                            Nom :
                        </td>
                        <td id="NomCollab">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Prénom :
                        </td>
                        <td id="PrénomCollab">

                        </td>
                    </tr>
                    <tr>
                        <td>
                            Email :
                        </td>
                        <td id="EmailCollab">

                        </td>
                    </tr>
                    <tr>
                        <td>
                            CIN :
                        </td>
                        <td id="CINCollab">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Profile :
                        </td>
                        <td id="ProfileCollab">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Nombre de missions :
                        </td>
                        <td id="nbrMiss">

                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="collabMiss" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="collabMissTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: auto;max-height: 357px;overflow-y: auto;">
                <table id="tableCollabMiss" class="tableCollabMiss">

                </table>
            </div>
        </div>
    </div>
</div>
<?php 
if(isset($_SESSION['erreurEmail']) || isset($_SESSION['erreurCIN'])){
    echo "<script>document.getElementById('btnAjtCollab').click();</script>";
    if(isset($_SESSION['erreurEmail'])){
        echo "
        <script>
            var Email = document.getElementById('EmailAjt');
            Email.classList+=' is-invalid';
            var err=document.getElementById('errEmailAjt'); 
            err.style.display='block'; 
            err.innerHTML='$_SESSION[erreurEmail]';</script>
        ";
        unset($_SESSION["erreurEmail"]);
    }
    if(isset($_SESSION['erreurCIN'])){
        echo "
        <script>
            var CIN = document.getElementById('CINAjt');
            CIN.classList+=' is-invalid';
            var err=document.getElementById('errCINAjt'); 
            err.style.display='block'; 
            err.innerHTML='$_SESSION[erreurCIN]';
        </script>
        ";
        unset($_SESSION["erreurCIN"]);
    }
}
?>
<?php
include "./inc/footer.html";
?>
