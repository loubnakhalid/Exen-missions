$(document).ready(function() {
    var table = $('#tableMiss').DataTable({
        lengthChange: false,
        ordering: false,
        info: false,
        paging: false,
    });

    $('#searchInput').on('keyup', function() {
        var searchText = $(this).val();
        table.search(searchText).draw();
    });
});
$(document).on("click", "#lienValiderRemb", function() {
    var IdMiss = $(this).data('id');
    var TypeMiss = $(this).attr('data-TypeMiss');
    $(".modal-body #IdMissRemb").val(IdMiss);
    $(" .modal-body h5").html("Type de mission : " + TypeMiss);

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
                if (response.TypeMiss == "Journaliere") {
                    var options = "<option value='Journaliere' selected>Journaliere</option><option value='Mensuel'>Mensuel</option>"
                } else {
                    var options = "<option value='Journaliere'>Journaliere</option><option value='Mensuel' selected>Mensuel</option>"
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
                $('#infoDurée').html(response.Durée + '<b> Jours</b>');
                if (response.Montant !== null) {
                    $('#tableInfoMiss').append('<tr><td>Montant :</td><td id="Montant">' + response.Montant + '<b> DHS</b></td></tr>');
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
                selectElement = $('#IdG');
                // Récupérer la valeur de IdG de la réponse
                var selectedIdG = response.IdG;

                // Sélectionner l'option correspondante dans le sélecteur #IdG
                selectElement.val(selectedIdG);
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la récupération des informations de la mission.');
            }
        });
    });
});

$(function() {
    $("#Départ").datepicker({
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
            $("#Retour").datepicker("option", "minDate", selectedDate);
            var retourValue = $("#Retour").val();
            if (retourValue !== "") {
                var minRetourDate = $("#Retour").datepicker("option", "minDate");
                var departDate = $.datepicker.parseDate(dateFormat, selectedDate);
                if (minRetourDate < departDate) {
                    $("#Retour").val("").datepicker("refresh");
                }
            }
        },
        beforeShowDay: function(date) {
            var day = date.getDay();
            return [(day != 0 && day != 6)];
        },
    });
    $("#Retour").datepicker({
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