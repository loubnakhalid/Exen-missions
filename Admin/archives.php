<?php
    require "../vendor/autoload.php";
    include "./inc/header.php";
    $con=\Tets\Oop\DataBase::connect();
    //Nbre d'enregistrement
    $count=$con->query("select count(IdMiss) as cpt from missions where DeletedAt is not null");
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
    $rslt=$con->query("select * from missions natural join membres where DeletedAt is not null order by IdMiss limit $debut,$nbr_elements_par_page");
    $row=$rslt->fetchAll();
?>
<div class="card">
    <div class="card-header">
        <h4>Liste des missions archivées</h4>
        <div class="entete">
            <div class="search-add">
                <form class="d-flex" style="/* margin-top: 37px; */margin-right: 14px;">
                    <input class="form-control me-sm-2" type="search" placeholder="Search" style="margin-right: -55px!important;border-radius: 11px;height: 41px;">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit" style="width: 53px;height: 29px;margin-top: 5px !important;margin-right: 3px;background-color: white !important;border: none !important;color: gray !important;"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table table-responsive">
            <table cellspacing='0'>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Réf</th>
                    <th>Collaborateur</th>
                    <th>Objet</th>
                    <th>Ville</th>
                    <th>Transport</th>
                    <th>Départ</th>
                    <th>Retour</th>
                    <th>Durée</th>
                    <th>Nuité</th>
                    <th>Date mission</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($row as $row) {
                    $Départ = new DateTime("$row[Départ]"); // date de début
                    $Retour = new DateTime("$row[Retour]"); // date de fin
                    if($row["TypeMiss"]=='Journalier'){
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
                    $Nuité=$nbJours-1;
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
                        <td>$nbJours</td>
                        <td>$Nuité</td>
                        <td>$row[DateMiss]</td>
                        <td class='action'>
                            <span>
                                <i class='fa-sharp fa-solid fa-rotate-left' style='color: #1c9b1c;font-size:20px' onclick='document.location.href=\"../controller.php?restMiss&IdMiss=$row[IdMiss]&page=$page\"'></i>
                            </span>     
                            <span>
                                <i class='fa-solid fa-trash' style='color: red;font-size:20px' onclick=\"confirmSupp('missions',$row[IdMiss],$page)\"></i>
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
