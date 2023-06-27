
$(document).ready(function() {
    $('button[data-target="#formPJ"]').on('click', function() {
        var missionId = $(this).data('id');
        console.log(missionId);
        $.ajax({
            url: '../controller.php',
            type: 'POST',
            data: { IdMiss: missionId, getMissPJ: true },
            dataType: 'json',
            success: function(response) {
                var text = "";
                for (var i = 0; i < response.length; i++) {
                    var row = response[i];
                    text += "<div class='col mb-3' style='margin-top: 28px'><input type = 'hidden' name ='IdPJ[]' value ='" + row.IdPJ + "' > <div class = 'image-container' onmouseover = 'showOverlay(this)' onmouseout = 'hideOverlay(this)'> <img src = '../PJ/" + row.NomPJ + "' class = 'PJ' id = 'image" + i + "'> <div class = 'overlay-content d-none'> <input type ='file' name ='file[]' id = 'fileInput" + i + "' style = 'display:none' onchange = 'updateImage(\"image" + i + "\",\"fileInput" + i + "\",\"" + row.IdPJ + "\")' accept = 'image/*,application/pdf'> <label for='fileInput" + i + "'> <i class = 'fa fa-pen '> </i></label> </div> </div> <div class = 'caption '> <b class = 'ms-4' >"+row.Frais+"</b></div></div>";
                }
                $("#rowMissPJ").html(text);
            },
            error: function() {
                alert("Une erreur s'est produite lors de la récupération des informations de la mission.");
            }
        });
    });
});
$(document).ready(function() {
    var navbar = document.getElementById('main-navbar');
    var navbarBrand = document.getElementById('navbar-brand');
    var navbarList = document.getElementById('navbar-list');
    var sidebar = document.getElementById('sidebarMenu');
    var windowWidth = window.innerWidth;
    if (windowWidth < 992) {
        navbar.setAttribute("style", "left:0 !important");
        navbarBrand.remove();
        sidebar.setAttribute("style", "padding:58px 0 0");
    }
});
window.addEventListener('resize', function() {
    var navbar = document.getElementById('main-navbar');
    var navbarBrand = document.getElementById('navbar-brand');
    var navbarList = document.getElementById('navbar-list');
    var sidebar = document.getElementById('sidebarMenu');
    var windowWidth = window.innerWidth;
    if (windowWidth < 992) {
        navbar.setAttribute("style", "left:0 !important");
        navbarBrand.remove();
        sidebar.setAttribute("style", "padding:58px 0 0");
    } else {
        navbar.style.left = '';
        sidebar.style.padding = "";
        if (!navbarList.contains(navbarBrand)) {
            navbarList.innerHTML = '<a id="navbar-brand" class="navbar-brand " href="#"> <img src = "../inc/img/logo.svg" alt = "" loading = "lazy" width = "100px" ></a>' + navbarList.innerHTML;
        }
    }
});

$(document).on("click", "#lienValiderRemb", function() {
    var IdMiss = $(this).data('id');
    var TypeMiss = $(this).attr('data-TypeMiss');
    $(".modal-body #IdMissRemb").val(IdMiss);
    $(" .modal-body h6").html("Type de mission : " + TypeMiss);

});

$(document).ready(function() {
    var table = $('#tableMission').DataTable({
        lengthChange: false,
        ordering: false,
        info: false,
        paging: false,
        language: {
            "zeroRecords": "Aucun résultat correspondant trouvé",
            // Autres traductions...
        }
    });
    $('#searchInput').on('keyup', function() {
        var searchText = $(this).val();
        table.search(searchText).draw();
    });
});

$(document).on("change", "#checkAccomp", function() {
    var Accomp = document.getElementById("Accomp");
    var check = document.getElementById("checkAccomp");
    if (check.checked) {
        Accomp.style.display = 'block';
    } else {
        Accomp.style.display = 'none';
    }
});

$(document).ready(function() {
    $('.icnModifMiss').click(function() {
        var IdMiss = $(this).data('id');
        $('#IdMiss').val(IdMiss);
        $.ajax({
            url: '../controller.php',
            type: 'POST',
            data: { IdMiss: IdMiss, getMiss: true },
            dataType: 'json',
            success: function(response) {
                var selectElement = document.getElementById("collaborateurModif");
                var valueToSelect = response.IdCollab;
                // Parcourir les options de l'élément select
                for (var i = 0; i < selectElement.options.length; i++) {
                    var option = selectElement.options[i];
                    // Comparer la valeur de l'option avec la valeur à sélectionner
                    if (option.value == valueToSelect) {
                        option.selected = true;
                        break;
                    }
                }
                $('#ObjMissModif').val(response.ObjMiss);
                $('#LieuDépModif').val(response.LieuDép);
                $('#DépartModif').val(response.Départ);
                //$('#TypeMissModif').val(response.TypeMiss);
                $('#RetourModif').val(response.Retour);
                $('#MoyTransModif').val(response.MoyTrans);
                $('#NoteModif').text(response.Note);
                $('#AccompModif').val(response.Accomp);
                if (response.TypeMiss == "Journalière") {
                    var options = "<option value='Journalière' selected>Journalière</option><option value='Mensuelle'>Mensuelle</option>"
                } else {
                    var options = "<option value='Journalière'>Journalière</option><option value='Mensuelle' selected>Mensuelle</option>"
                }
                $('#TypeMissModif').html(options);
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la récupération des informations de la mission.');
            }
        });
    });
});

$(document).ready(function() {
    $('.info').click(function() {
        var IdMiss = $(this).data('id');
        $.ajax({
            url: '../controller.php',
            type: 'POST',
            data: { IdMiss: IdMiss, getMiss: true },
            dataType: 'json',
            success: function(response) {
                $('#infoMissTitle').text("Détails mission : " + response.RéfMiss);
                $('#infoCollab').text(response.Collab);
                $('#infoObjMiss').text(response.ObjMiss);
                $('#infoTypeMiss').text(response.TypeMiss);
                $('#infoDépart').text(response.Départ);
                $('#infoRetour').text(response.Retour);
                $('#infoLieuDép').text(response.LieuDép);
                $('#infoMoyTrans').text(response.MoyTrans);
                $('#infoDurée').html('<b>' + response.Durée + ' Jour(s)</b>');
                if (response.Montant !== null) {
                    $('.trInfoMontant').css('display', 'contents');
                    $('#infoMontant').html('<b style="color:#3ec93e" > ' + response.Montant + " DHS </b>");
                } else {
                    $('.trInfoMontant').css('display', 'none');
                    $('#infoMontant').text('');
                }
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la récupération des informations de la mission.');
            }
        });
    });
});

$(document).ready(function() {
    $('.infoCollab').click(function() {
        var IdMb = $(this).data('id');
        $.ajax({
            url: '../controller.php',
            type: 'POST',
            data: { IdMb: IdMb, getCollab: true },
            dataType: 'json',
            success: function(response) {
                $('#NomCollab').text(response.Nom);
                $('#PrénomCollab').text(response.Prénom);
                $('#EmailCollab').text(response.Email);
                $('#CINCollab').text(response.CIN);
                $('#ProfileCollab').text(response.Profil);
                $('#nbrMiss').text(response.nbrMiss);
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la récupération des informations de la mission.');
            }
        });
    });
});

$(document).ready(function() {
    $('.collabMiss').click(function() {
        var IdMb = $(this).data('id');
        $.ajax({
            url: '../controller.php',
            type: 'POST',
            data: { IdMb: IdMb, getCollabMiss: true },
            dataType: 'json',
            success: function(response) {
                $("#collabMissTitle").text('Les missions effectuées par : ' + response[0].Collab);
                var text = "<thead><tr><th>#</th><th>Réf miss</th><th>Objet mission</th><th>Lieu déplacement</th></tr></thead><tbody>";
                for (var i = 0; i < response.length; i++) {
                    var row = response[i];
                    text += "<tr><td>" + row.IdMiss + "</td><td>" + row.RéfMiss + "</td><td>" + row.ObjMiss + "</td><td>" + row.LieuDép + "</td></tr>";
                }
                text += "</thead>";
                $("#tableCollabMiss").html(text);
            },
            error: function() {
                alert("Une erreur s'est produite lors de la récupération des informations de la mission.");
            }
        });
    });
});

$(document).ready(function() {
    $('.icnModifCollab').click(function() {
        var IdMb = $(this).data('id');
        $('#IdMb').val(IdMb);
        $.ajax({
            url: '../controller.php',
            type: 'POST',
            data: { IdMb: IdMb, getCollab: true },
            dataType: 'json',
            success: function(response) {
                $('#NomModif').val(response.Nom);
                $('#PrénomModif').val(response.Prénom);
                $('#EmailModif').val(response.Email);
                $('#ProfileModif').val(response.Profil);
                $('#CINModif').val(response.CIN);
                selectElementGrp = $('#IdG');
                selectElementCivilité = $('#CivilitéModif');
                // Récupérer la valeur de IdG de la réponse
                var selectedIdG = response.IdG;
                var selectedivilité = response.TitreCivilité;
                // Sélectionner l'option correspondante dans le sélecteur #IdG
                selectElementGrp.val(selectedIdG);
                selectElementCivilité.val(selectedivilité);
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la récupération des informations de la mission.');
            }
        });
    });
});

$(document).ready(function() {
    $('.icnModifGroupe').click(function() {
        var IdG = $(this).data('id');
        $('#IdGModif').val(IdG);
        $.ajax({
            url: '../controller.php',
            type: 'POST',
            data: { IdG: IdG, getGroupes: true },
            dataType: 'json',
            success: function(response) {
                $('#LibelléModif').val(response[0].Libellé);
                $('#TauxModif').val(response[0].TauxG);
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la récupération des informations de la mission.');
            }
        });
    });
});

$(document).ready(function() {
    $('.icnModifFrais').click(function() {
        var IdFrais = $(this).data('id');
        $('#IdFraisModif').val(IdFrais);
        $.ajax({
            url: '../controller.php',
            type: 'GET',
            data: { IdFrais: IdFrais, getFrais: true },
            dataType: 'json',
            success: function(response) {
                $('#LibelléFraisModif').val(response[0].LibelléFrais);
                $('#MontantFraisModif').val(response[0].MontantFrais);
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la récupération des informations de la mission.');
            }
        });
    });
});

$(function() {
    $(".Départ").datepicker({
        altField: "#datepicker",
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        dateFormat: 'dd-mm-yy',
        firstDay: '1',
        onSelect: function(selectedDate) {
            $(".Retour").datepicker("option", "minDate", selectedDate);
            var retourValue = $(".Retour").val();
            if (retourValue !== "") {
                var minRetourDate = $(".Retour").datepicker("option", "minDate");
                var departDate = $.datepicker.parseDate(dateFormat, selectedDate);
                if (minRetourDate < departDate) {
                    $(".Retour").val("").datepicker("refresh");
                }
            }
        },
        beforeShowDay: function(date) {
            var day = date.getDay();
            return [(day != 0 && day != 6)];
        },
    });
    $(".Retour").datepicker({
        altField: "#datepicker",
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        dateFormat: 'dd-mm-yy',
        firstDay: '1',
    });
});
$(document).ready(function() {
    $("#linkModifInfo").click(function(e) {
        e.preventDefault();
        $("#cardInfoPerson").hide();
        $("#cardModifMdsp").show();
    });

    $("#linkInfoPerso").click(function(e) {
        e.preventDefault();
        $("#cardModifMdsp").hide();
        $("#cardInfoPerson").show();
    });
});
var button = document.querySelector('.navbar-toggler');
button.addEventListener('click', function() {
    // Récupérer l'élément cible du bouton
    var target = document.querySelector(button.getAttribute('data-bs-target'));

    // Vérifier si l'élément cible est déjà affiché ou masqué
    var isExpanded = button.getAttribute('aria-expanded') === 'true';

    // Afficher ou masquer l'élément cible en fonction de son état actuel
    if (isExpanded) {
        target.classList.remove('show');
        button.setAttribute('aria-expanded', 'false');
    } else {
        target.classList.add('show');
        button.setAttribute('aria-expanded', 'true');
        document.getElementsByClassName('navbar').style.left = "0px !important";
    }
});
const links = document.querySelectorAll('#navbar-list a');
// Parcourir les liens et vérifier si l'URL correspond à la page active
links.forEach(link => {
    if (link.href === window.location.href) {
        link.classList.add('active');
    }
});
var editIcons = document.querySelectorAll('.edit');
var buttons = document.getElementById('buttons');
var input = document.querySelectorAll('.inpt-txt');
// Parcourir toutes les icônes de modification
editIcons.forEach(function(icon) {
    // Ajouter un écouteur d'événement de clic à chaque icône de modification
    icon.addEventListener('click', function() {
        // Récupérer l'élément parent de l'icône de modification
        var parentRow = icon.parentNode;

        // Récupérer l'élément input dans la même ligne que l'icône de modification
        var inputElement = parentRow.querySelector('input[type="text"]');

        // Rendre la bordure de l'input visible
        inputElement.style.border = '1px solid gray';
        inputElement.removeAttribute('readonly');
        // Rendre l'icône de validation visible
        buttons.classList.remove('d-none');
    });
});

var btnAnn = document.getElementById('btn-annuler');
btnAnn.addEventListener('click', function() {
    input.forEach(function(input) {
        input.style.border = 'none';
        input.setAttribute('readonly', '');
        buttons.classList.add("d-none");
    })

});