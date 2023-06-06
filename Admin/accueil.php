<?php

    use Tets\Oop\DataBase;

    require "../vendor/autoload.php";
    include "./inc/header.php";
    $con=DataBase::connect();
    $rqt1=$con->query('select * from missions where DeletedAt is null');
    $rslt1=$rqt1->rowCount();
    $rqt2=$con->query("select * from missions where Montant is not null and DeletedAt is null");
    $rslt2=$rqt2->fetchAll();
    $total=0;
    foreach($rslt2 as $row){
        $total+=$row['Montant'];
    }
    $rqt3=$con->query('select * from missions where StatutMiss=1 and Montant is null and DeletedAt is null');
    $rslt3=$rqt3->rowCount();
    $rqt4=$con->query('select * from membres where Statut=0');
    $rslt4=$rqt4->rowCount();
?>
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Collaborateurs</div>
                        <div class="h5 mb-0 font-weight-bold gray"><?=$rslt4?></div>
                    </div>
                    <div class="col-auto gray">
                        <i class="fas fa-users fa-2x" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Missions</div>
                        <div class="h5 mb-0 font-weight-bold gray"><?=$rslt1?></div>
                    </div>
                    <div class="col-auto gray">
                        <i class="fas fa-calendar fa-2x" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold greenText text-uppercase mb-1">Remboursement</div>
                        <div class="h5 mb-0 font-weight-bold gray"><?=$total?> DHS</div>
                    </div>
                    <div class="col-auto gray">
                        <i class="fas fa-dollar-sign fa-2x" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Missions non remb
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold gray"><?= $rslt3?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x gray" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Missions par mois</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="chart1"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Missions par collaborateur</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="chart2"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Missions par groupe</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="chart3"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Remboursement par collaborateur</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="chart4"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    $row = DataBase::getDataWhere('missions','DeletedAt is null');

    $currentYear = date('Y');

    // Tableau pour stocker les données des missions de l'année courante
    $missionsByMonth = array_fill(0, 12, 0);

    // Parcourir les données des missions
    foreach ($row as $mission) {
        $dateMiss = $mission['DateMiss'];
        $div=substr($dateMiss,0,10);
        $date = explode("/", $div, 3);
        $year = $date[2];
        if ($year == $currentYear) {
            $month = (int)$date[1]; // Récupère le mois (1 pour janvier, 2 pour février, etc.)
            $missionsByMonth[$month - 1]++; // Incrémente le compteur pour le mois correspondant
        }
    }
    //print_r($missionsByMonth);
?>

<script>
    // Utilisation des données PHP dans le code JavaScript
    const missionsByMonth = <?php echo json_encode($missionsByMonth); ?>;
    // Générer les labels et les données du graphique à partir du tableau des missions par mois
    const monthLabels = [
        "Janvier",
        "Février",
        "Mars",
        "Avril",
        "Mai",
        "Juin",
        "Juillet",
        "Août",
        "Septembre",
        "Octobre",
        "Novembre",
        "Décembre"
    ];
    // Configurer le graphique avec les labels et les données
    let ctx1 = document.getElementById("chart1").getContext("2d");
    let myChart1 = new Chart(ctx1, {
        type: "bar", // Utiliser le type "bar" pour un bar chart
        data: {
            labels: monthLabels,
            datasets: [{
                label: "Missions effectuées",
                data: missionsByMonth,
                backgroundColor: "#8ec6ff"
            }]
        }
    });
</script>

<?php
    $rowMembers = DataBase::getDataWhere('membres', 'Statut <> 1');
    $con=DataBase::connect();
    $rslt=$con->query("select * from missions natural join membres where Statut <> 1 and DeletedAt is null");
    $rowMissions=$rslt->fetchAll();
    $missionsByCollaborator = array();

    // Parcourir les membres pour initialiser le tableau avec des compteurs à zéro
    foreach ($rowMembers as $member) {
        $memberId = $member['IdMb'];
        $missionsByCollaborator[$memberId] = 0;
    }

    // Parcourir les missions et incrémenter les compteurs correspondants
    foreach ($rowMissions as $mission) {
        $memberId = $mission['IdMb'];
        if (isset($missionsByCollaborator[$memberId])) {
            $missionsByCollaborator[$memberId]++;
        }
    }
?>

<script>
    // Convertir le tableau PHP en JSON pour l'utiliser en JavaScript
    const missionsByCollaborator = <?php echo json_encode($missionsByCollaborator); ?>;

    // Récupérer les noms des collaborateurs à partir des données des membres
    const memberNames = <?php echo json_encode(array_map(function($member) {
        return $member['Nom'] . ' ' . $member['Prénom'];}, $rowMembers)); ?>;
    // Générer les labels et les données du graphique
    const collaboratorLabels = memberNames;
    const missionCounts = Object.values(missionsByCollaborator);
    missionCounts.sort((a, b) => b - a);
    // Configurer le graphique avec les labels et les données
    let ctx2 = document.getElementById("chart2").getContext("2d");
    let myChart2 = new Chart(ctx2, {
        type: "bar",
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true // Définir l'axe y à partir de zéro
                    }
                }]
            }
        },
        data: {
            labels: collaboratorLabels,
            datasets: [{
                label: "Missions effectuées",
                data :missionCounts,
                backgroundColor: "#8ec6ff"
            }]
        }
    });
</script>

<?php
    $rowGroupes = DataBase::getData('groupes');
    $con = DataBase::connect();
    $rslt = $con->query("SELECT * FROM missions NATURAL JOIN membres NATURAL JOIN groupes WHERE Statut <> 1 and DeletedAt is null");
    $rowMissions = $rslt->fetchAll();
    $missionsByGroupe = array();

    // Parcourir les groupes pour initialiser le tableau avec des compteurs à zéro
    foreach ($rowGroupes as $groupe) {
        $groupeId = $groupe['IdG'];
        $missionsByGroupe[$groupeId] = 0;
    }

    // Parcourir les missions et incrémenter les compteurs correspondants
    foreach ($rowMissions as $mission) {
        $groupeId = $mission['IdG'];
        if (isset($missionsByGroupe[$groupeId])) {
            $missionsByGroupe[$groupeId]++;
        }
    }
?>

<script>
    // Convertir le tableau PHP en JSON pour l'utiliser en JavaScript
    const missionsByGroupe = <?php echo json_encode($missionsByGroupe); ?>;

    // Récupérer les noms des groupes à partir des données des groupes
    const groupesLabels = <?php echo json_encode(array_column($rowGroupes, 'Libellé')); ?>;

    // Générer les labels et les données du graphique
    const missionGCounts = Object.values(missionsByGroupe);

    // Configurer le graphique avec les labels et les données
    let ctx3 = document.getElementById("chart3").getContext("2d");
    let myChart3 = new Chart(ctx3, {
        type: "bar",
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true // Définir l'axe y à partir de zéro
                    }
                }]
            }
        },
        data: {
            labels: groupesLabels,
            datasets: [{
                label: "Missions effectuées",
                data: missionGCounts,
                backgroundColor: "#8ec6ff"
            }]
        }
    });
</script>

<?php
    $rowMembers = DataBase::getDataWhere('membres', 'Statut <> 1');
    $rowMissions = DataBase::getDataWhere('missions','Montant is not null and DeletedAt is null');
    $remboursementsByCollaborator = array();

    // Parcourir les membres pour initialiser le tableau avec des montants à zéro
    foreach ($rowMembers as $member) {
        $memberId = $member['IdMb'];
        $remboursementsByCollaborator[$memberId] = 0;
    }

    // Parcourir les missions et calculer la somme des montants remboursés par collaborateur
    foreach ($rowMissions as $mission) {
        $memberId = $mission['IdMb'];
        $montantRembourse = $mission['Montant'];
        if (isset($remboursementsByCollaborator[$memberId])) {
            $remboursementsByCollaborator[$memberId] += $montantRembourse;
        }
    }
?>

<script>
    // Convertir le tableau PHP en JSON pour l'utiliser en JavaScript
    const remboursementsByCollaborator = <?php echo json_encode($remboursementsByCollaborator); ?>;

    // Récupérer les noms des collaborateurs à partir des données des membres
    const memberNames2 = <?php echo json_encode(array_map(function($member) {
        return $member['Nom'] . ' ' . $member['Prénom'];}, $rowMembers)); ?>;

    // Générer les labels et les données du graphique
    const collaboratorLabels1 = memberNames2;
    const remboursementAmounts = Object.values(remboursementsByCollaborator);

    // Configurer le graphique avec les labels et les données
    let ctx4 = document.getElementById("chart4").getContext("2d");
    let myChart4 = new Chart(ctx4, {
        type: "bar",
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true // Définir l'axe y à partir de zéro
                    }
                }]
            }
        },
        data: {
            labels: collaboratorLabels1,
            datasets: [{
                label: "Montants remboursés en DHS",
                data: remboursementAmounts,
                backgroundColor: "#8ec6ff"
            }]
        }
    });
</script>

<?php 
include("./inc/footer.html");
?>