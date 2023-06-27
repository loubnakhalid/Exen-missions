<?php 
    session_start();
    use Tets\Oop\Membre;
    require "../vendor/autoload.php";
    if(!Membre::Collab()){
      header("location:../index.php");
    }

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../inc/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="../inc/js/jsBoot/bootstrap.min.js" ></script>
    <script src="https://cdn.lordicon.com/ritcuqlt.js"></script>
    <script src="https://kit.fontawesome.com/9f23a76265.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>  
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.min.js"></script>
    <script src="../inc/js/functions.js"></script>
    <title>GRH_Exen</title>
    <link rel="icon" href="../inc/img/logoB.svg">
</head>
<body>
  <header>
    <!-- Navbar -->
    <nav  class="navbar navbar-expand-lg navbar-light bg-white fixed-top" style="left: 0 !important;">
    <div class="container-fluid">

      <a  class="navbar-brand navbar-collab" href="index.html">
          <img src="../inc/img/logoB.svg" alt="" loading="lazy" style="width:75px">
      </a>
        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto" id="ulNavCollab">
                <li class="nav-item" style="margin-right: 30px;">
                    <a class="nav-link" href="profil.php">
                      <i class="fa-solid fa-user"></i> Mon Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="missions.php">                    
                      <i class="fas fa-clipboard-list gray" aria-hidden="true"></i> Missions effectuées
                    </a>
                </li>
            </ul>
            <div class='d-flex'>
              <a class="nav-link gray" href="../controller.php?décon">
              <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
              </a>
            </div>
        </div>
      </div>
      <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
  </header>
  <!--Main Navigation-->

<!--Main layout-->
  <main style="margin-top: 80px;padding-left: 18px !important;padding-right: 18px;">  
