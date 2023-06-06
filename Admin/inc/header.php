<?php

  use Tets\Oop\Membre;

  session_start();
  if(!Membre::Admin()){
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
      <link rel="stylesheet" href="../node_modules/font-awesome/css/font-awesome.min.css">
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
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.min.js"></script>
      <script src="../inc/js/functions.js"></script>
      <title>GRH_Exen</title>
      <link rel="icon" href="../inc/img/logoB.svg">
  </head>
  <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">.</span>
    </div>
</div>
<script>
  window.addEventListener('load', function() {
      var spinner = document.getElementById('spinner');
      spinner.classList.add('d-none');
      spinner.classList.remove('show');
  });
</script>
<body>
    <!--Main Navigation-->
  <header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse blue">
      <div class="position-sticky">
        <div id="navbar-list" class="list-group list-group-flush mx-3 mt-4">
          <a id="navbar-brand" class="navbar-brand" href="index.html">
              <img src="../inc/img/logo.svg" alt="" loading="lazy" width="100px">
          </a>
          <a href="accueil.php" class="list-group-item list-group-item-action py-2 ripple" aria-current="true">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i
              ><span>Accueil</span>
          </a>
          <a href="missions.php" class="list-group-item list-group-item-action py-2 ripple left dropdown-toggle" id="miss-drop">
            <i class="fas fa-chart-area fa-fw me-3"></i
              ><span>Missions</span>
          </a>
          <div class="ps-4" id="archives"> <!-- Add a div for the submenu -->
          <a href="archives.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fa-sharp fa-solid fa-box-archive fa-fw me-3"></i><span>Archives</span>
          </a>
        </div>
            <a href="collabs.php" class="list-group-item list-group-item-action py-2 ripple">
              <i class="fas fa-users fa-fw me-3"></i><span>Collaborateurs</span>
            </a>
          <a href="groupes.php" class="list-group-item list-group-item-action py-2 ripple">
              <i class="fas fa-chart-pie fa-fw me-3"></i><span>Groupes</span>
          </a>
          <a href="frais.php" class="list-group-item list-group-item-action py-2 ripple">
              <i class="fas fa-chart-bar fa-fw me-3"></i><span>Frais</span>
          </a>
          <a href="../controller.php?décon" class="list-group-item list-group-item-action py-2 ripple mt-auto" style="margin-top: 53px !important;">
            <i class="fas fa-power-off fa-fw me-3" aria-hidden="true"></i><span>Déconnexion</span>
          </a>
        </div>
    </div>
    </nav>
  <!-- Sidebar -->

  <!-- Navbar -->
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
      <!-- Container wrapper -->
      <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        <!-- Right links -->
        <ul class="navbar-nav ms-auto d-flex flex-row">

            <!-- Avatar -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Bonjour <?= $_SESSION['membre']['Prénom'] ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                <li><a class="dropdown-item" href="./profil.php">Profil</a></li>
                <li><a class="dropdown-item" href="../controller.php?décon">Déconnexion</a></li>
              </ul>
            </li>
          </ul>
      </div>
      <!-- Container wrapper -->
    </nav>
  <!-- Navbar -->
  </header>
  <!-- Main Sidebar Container -->
  <main style="margin-top: 58px">
    <div class="container pt-4">
