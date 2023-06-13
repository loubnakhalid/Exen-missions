<?php
   use Tets\Oop\DataBase;
   require "../vendor/autoload.php";
   include "./inc/header.php";
?>

<div class="row">
   <div class="col">
      <div class="card shadow mb-4">
         <div class="card-header  py-3"><h6 class="m-0 font-weight-bold text-primary">Historique des modifications</h6></div>
         <div class="card-body" style="max-height: 480px;overflow-y: auto;">
         <?php
            $row=DataBase::getDataWhere('historique',"TypeAction<>'Connexion' and TypeAction<>'Déconnexion' order by IdAction desc");
            $aide=true;
            if($row){
               foreach($row as $row){
                  if($row['TypeAction']=='Suppression'){
                     $color='red';
                     $background='#ffe7e7';
                  }
                  elseif($row['TypeAction']=='Modification'){
                     $color='orange';
                     $background='#fff8ee';
                  }
                  elseif($row['TypeAction']=='Ajout'){
                     $color='#1e8af7';
                     $background='#ebf6ff';
                  }
                  elseif($row['TypeAction']=='Validation de mission' || $row['TypeAction']=='Validation de remboursement' || $row['TypeAction']=='Validation'){
                     $color='#43d90d';
                     $background='#e1ffe1';
                  }
                  else{
                     $color='black';
                     $background='white';
                  }
                  echo "<div class='row mb-2 sec-histo' style='background-color:$background'><div class='col-1'><i class='fa-solid fa-circle' style='font-size:9px;color:$color'></i></div><div class='col'>$row[TypeAction]</div><div class='col-4'>$row[ElementAction]</div><div class='col'>$row[DateAction]</div></div>";
               }
            }
            $id=$_SESSION['membre']['IdMb'];
            $row2=DataBase::getDataWhere('membres',"IdMb=$id");
         ?>
         </div>
      </div>
   </div>
   <div class="col">
      <div class="row">
         <div class="col">
            <div class="card shadow mb-4">
               <div class="card-header  py-3"><h6 class="m-0 font-weight-bold text-primary">Informations personnelles</h6></div>
               <div class="card-body">
                  <form action="../controller.php" method="post">
                     <div class="row mb-2">
                        <div class="col">Nom : </div>
                        <div class="col"><input class="inpt-txt" type="text" name="Nom" value="<?=$row2[0]['Nom']?>" readonly></div>
                        <div class="col edit"><i class='fa-solid fa-pen' style="float: right;color:orange;"></i></div>
                     </div>
                     <div class="row mb-2">
                        <div class="col">Prénom : </div>
                        <div class="col"><input class="inpt-txt" type="text" name="Prénom" value="<?=$row2[0]['Prénom']?>" readonly></div>
                        <div class="col edit"><i class='fa-solid fa-pen' style="float: right;color:orange;"></i></div>
                     </div>
                     <div class="row mb-2">
                        <div class="col">Email : </div>
                        <div class="col"><input class="inpt-txt" type="text" name="Email" value="<?=$row2[0]['Email']?>" readonly></div>
                        <div class="col edit"><i class='fa-solid fa-pen' style="float: right;color:orange;"></i></div>
                     </div>
                     <div class="row">
                        <div class="col d-none" id="buttons">
                           <button name="modifAdmin" class="btn btn-primary" style="width: 85px;float: right;padding: 3px;">Valider</button>
                           <button type="reset" id="btn-annuler" class="btn me-2" style="width: 85px;float: right;padding: 3px;background-color:#afafaf;color:white">Annuler</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col">
            <div class="card shadow mb-4">
               <div class="card-header  py-3"><h6 class="m-0 font-weight-bold text-primary">Historique des connexions</h6></div>
               <div class="card-body" style="max-height: 248px;overflow-y: auto;">
                  <?php
                     $row=DataBase::getDataWhere('historique',"TypeAction='Connexion' or TypeAction='Déconnexion' order by DateAction desc");
                     $aide=true;
                     if($row){
                        foreach($row as $row){
                           if($row['TypeAction']=='Déconnexion'){
                              $color='red';
                              $background='#ffe7e7';
                           }
                           elseif($row['TypeAction']=='Connexion'){
                              $color='#43d90d';
                              $background='#e1ffe1';
                           }
                           echo "<div class='row mb-2 sec-histo' style='background-color:$background'><div class='col-1'><i class='fa-solid fa-circle' style='font-size:9px;color:$color'></i></div><div class='col'>$row[TypeAction]</div><div class='col'>$row[DateAction]</div></div>";
                        }
                     }
                  ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php
    include "./inc/footer.php";
?>