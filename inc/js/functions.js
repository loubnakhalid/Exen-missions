function vérifLog() {
    var Email = document.getElementById("EmailLog");
    var Mdps = document.getElementById("MdpsLog");
    var errEmail = document.getElementById("errEmail");
    var errMdps = document.getElementById("errMdps");
    var validate = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var aide = true;
    if (Email.classList.contains("is-invalid")) {
        Email.classList.remove("is-invalid");
        errEmail.innerHTML = "";
        errEmail.style.display = "none";
    }
    if (Mdps.classList.contains("is-invalid")) {
        Mdps.classList.remove("is-invalid");
        errMdps.innerHTML = "";
        errMdps.style.display = "none";

    }
    if (Email.value == "") {
        Email.classList.add('is-invalid');
        errEmail.style.display = "block";
        errEmail.innerHTML = "Champs requis";
        aide = false;
    } else if (!validate.test(Email.value)) {
        Email.classList.add("is-invalid");
        errEmail.style.display = "block";
        errEmail.innerHTML = "Veuillez entrer une adresse email valide ";
        aide = false;
    }
    if (Mdps.value == "") {
        Mdps.classList.add('is-invalid');
        errMdps.style.display = "block";
        errMdps.innerHTML = "Champs requis";
        aide = false;
    }
    return aide;
}

function vérifAjtMiss() {
    var collab = document.getElementById('collaborateur');
    var ObjMiss = document.getElementById('ObjMiss');
    var LieuDép = document.getElementById('LieuDép');
    var Départ = document.getElementById('Départ');
    var Retour = document.getElementById('Retour');
    var TypeMiss = document.getElementById('TypeMission');
    var array = [collab, ObjMiss, LieuDép, Départ, Retour, TypeMiss];
    var aide = true;
    for (let input of array) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }
    for (let input of array) {
        if (input.value == '') {
            aide = false;
            input.classList += " is-invalid";
        }
    }
    return aide;
}

function vérifModifMiss() {
    var collab = document.getElementById('collaborateurModif');
    var ObjMiss = document.getElementById('ObjMissModif');
    var LieuDép = document.getElementById('LieuDépModif');
    var Départ = document.getElementById('DépartModif');
    var Retour = document.getElementById('RetourModif');
    var TypeMiss = document.getElementById('TypeMissModif');
    var array = [collab, ObjMiss, LieuDép, Départ, Retour, TypeMiss];
    var aide = true;
    for (let input of array) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }
    for (let input of array) {
        if (input.value == '') {
            aide = false;
            input.classList += " is-invalid";
        }
    }
    return aide;
}

function vérifAjtCollab() {
    var Nom = document.getElementById("NomAjt");
    var Prénom = document.getElementById("PrénomAjt");
    var Email = document.getElementById("EmailAjt");
    var Mdps = document.getElementById("MdpsAjt");
    var Profile = document.getElementById("ProfileAjt");
    var CIN = document.getElementById("CINAjt");
    var Civilité = document.getElementById("CivilitéAjt");
    var array = [Nom, Prénom, Email, Mdps, Profile, CIN, Civilité];
    var aide = true;
    for (let input of array) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }
    for (let input of array) {
        document.getElementById("err" + input.id).innerHTML = "";
    }
    for (let input of array) {
        if (input.value == '') {
            aide = false;
            input.classList += " is-invalid";
            var err = document.getElementById("err" + input.id);
            if (err) {
                err.style.display = "block";
                err.innerHTML = "Champ requis";
            }
        } else if (input == Email) {
            var validate = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!validate.test(input.value)) {
                aide = false;
                input.classList += " is-invalid";
                document.getElementById("errEmailAjt").style.display = "block";
                document.getElementById("errEmailAjt").innerHTML = "Veuillez entrer une adresse email valide";
            }
        } else if (input == CIN) {
            var cinRegex = /^[A-Z]{1,2}\d{6}$/;
            if (!cinRegex.test(input.value)) {
                aide = false;
                input.classList += " is-invalid";
                document.getElementById("errCINAjt").style.display = "block";
                document.getElementById("errCINAjt").innerHTML = "Veuillez entrer un CIN valide";
            }
        }

        $.ajax({
            url: '../controller.php',
            type: 'POST',
            data: { Email: Email.value, CIN: CIN.value, vérifEmailCinAjt: true },
            dataType: 'json',
            async: false,
            success: function(response) {
                if (response.hasOwnProperty('erreurCIN')) {
                    CIN.classList.add('is-invalid');
                    errPasswordActuelle.style.display = 'block';
                    errPasswordActuelle.innerHTML = response.erreurCIN;
                    aide = false;
                }
                if (response.hasOwnProperty('erreurEmail')) {
                    Email.classList.add('is-invalid');
                    document.getElementById('errEmailAjt').style.display = 'block';
                    document.getElementById('errEmailAjt').innerHTML = response.erreurEmail;
                    aide = false;
                }
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la vérification du CIN et de l\'email.');
            }
        });
    }
    return aide;
}

function annulerVérifModifCollab() {
    var Nom = document.getElementById("NomModif");
    var Prénom = document.getElementById("PrénomModif");
    var Email = document.getElementById("EmailModif");
    var Profile = document.getElementById("ProfileModif");
    var CIN = document.getElementById("CINModif");
    var Civilité = document.getElementById('CivilitéModif');
    var array = [Nom, Prénom, Email, Profile, CIN, Civilité];
    var aide = true;

    for (let input of array) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }

    for (let input of array) {
        document.getElementById("err" + input.id).innerHTML = "";
    }

}

function vérifModifCollab() {
    var IdMb = document.getElementById("IdMb").value;
    var Nom = document.getElementById("NomModif");
    var Prénom = document.getElementById("PrénomModif");
    var Email = document.getElementById("EmailModif");
    var Profile = document.getElementById("ProfileModif");
    var CIN = document.getElementById("CINModif");
    var Civilité = document.getElementById('CivilitéModif');
    var array = [Nom, Prénom, Email, Profile, CIN, Civilité];
    var aide = true;

    for (let input of array) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }

    for (let input of array) {
        document.getElementById("err" + input.id).innerHTML = "";
    }

    for (let input of array) {
        if (input.value == '') {
            aide = false;
            input.classList.add("is-invalid");
            var err = document.getElementById("err" + input.id);
            if (err) {
                err.style.display = "block";
                err.innerHTML = "Champ requis";
            }
        } else if (input == Email) {
            var validate = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!validate.test(input.value)) {
                aide = false;
                input.classList.add("is-invalid");
                document.getElementById("errEmailModif").style.display = "block";
                document.getElementById("errEmailModif").innerHTML = "Veuillez entrer une adresse email valide";
            }
        } else if (input == CIN) {
            var cinRegex = /^[A-Z]{1,2}\d{6}$/;
            if (!cinRegex.test(input.value)) {
                aide = false;
                input.classList.add("is-invalid");
                document.getElementById("errCINModif").style.display = "block";
                document.getElementById("errCINModif").innerHTML = "Veuillez entrer un CIN valide";
            }
        }
    }

    $.ajax({
        url: '../controller.php',
        type: 'POST',
        data: { IdMb: IdMb, Email: Email.value, CIN: CIN.value, vérifEmailCinModif: true },
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.hasOwnProperty('erreurCIN')) {
                CIN.classList.add('is-invalid');
                document.getElementById('errCINModif').style.display = 'block';
                document.getElementById('errCINModif').innerHTML = response.erreurCIN;
                aide = false;
            }
            if (response.hasOwnProperty('erreurEmail')) {
                Email.classList.add('is-invalid');
                document.getElementById('errEmailModif').style.display = 'block';
                document.getElementById('errEmailModif').innerHTML = response.erreurEmail;
                aide = false;
            }
        },
        error: function() {
            alert('Une erreur s\'est produite lors de la vérification du CIN et de l\'email.');
        }
    });
    return aide;
}

function vérifAjtGroupe() {
    var Libellé = document.getElementById('LibelléAjt');
    var Taux = document.getElementById('TauxAjt');
    var array = [Libellé, Taux];
    var aide = true;
    for (let input of array) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }
    for (let input of array) {
        document.getElementById("err" + input.id).innerHTML = "";
    }
    for (let input of array) {
        if (input.value == '') {
            aide = false;
            input.classList += " is-invalid";
            var err = document.getElementById("err" + input.id);
            if (err) {
                err.innerHTML = "Champ requis";
                err.style.display = "block";
            }
        }
    }
    $.ajax({
        url: '../controller.php',
        type: 'POST',
        data: { Libellé: Libellé.value, vérifLibelleGrp: true },
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.hasOwnProperty('erreurLibelle')) {
                aide = false;
                Libellé.classList.add('is-invalid');
                document.getElementById('errLibelléAjt').style.display = 'block';
                document.getElementById('errLibelléAjt').innerHTML = response.erreurLibelle;
            }
        },
        error: function() {
            alert('Une erreur s\'est produite lors de la vérification de libellé');
        }
    });
    return aide;
}

function vérifModifGroupe() {
    var IdG = document.getElementById('IdGModif');
    var Libellé = document.getElementById('LibelléModif');
    var Taux = document.getElementById('TauxModif');
    var array = [Libellé, Taux];
    var aide = true;
    for (let input of array) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }
    for (let input of array) {
        document.getElementById("err" + input.id).innerHTML = "";
    }
    for (let input of array) {
        if (input.value == '') {
            aide = false;
            input.classList += " is-invalid";
            var err = document.getElementById("err" + input.id);
            if (err) {
                err.innerHTML = "Champ requis";
                err.style.display = "block";
            }
        }
    }
    $.ajax({
        url: '../controller.php',
        type: 'POST',
        data: { IdG: IdG.value, Libellé: Libellé.value, vérifLibelleGrpModif: true },
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.hasOwnProperty('erreurLibelle')) {
                aide = false;
                Libellé.classList.add('is-invalid');
                document.getElementById('errLibelléModif').style.display = 'block';
                document.getElementById('errLibelléModif').innerHTML = response.erreurLibelle;
            }
        },
        error: function() {
            alert('Une erreur s\'est produite lors de la vérification de libellé');
        }
    });
    return aide;
}

function vérifAjtFrais() {
    var Libellé = document.getElementById('LibelléFraisAjt');
    var Montant = document.getElementById('MontantFraisAjt');
    var array = [Libellé, Montant];
    var aide = true;
    for (let input of array) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }
    for (let input of array) {
        document.getElementById("err" + input.id).innerHTML = "";
    }
    for (let input of array) {
        if (input.value == '') {
            aide = false;
            input.classList += " is-invalid";
            var err = document.getElementById("err" + input.id);
            if (err) {
                err.innerHTML = "Champ requis";
                err.style.display = "block";
            }
        }
    }
    $.ajax({
        url: '../controller.php',
        type: 'POST',
        data: { Libellé: Libellé.value, vérifLibelleFrais: true },
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.hasOwnProperty('erreurLibelle')) {
                aide = false;
                Libellé.classList.add('is-invalid');
                document.getElementById('errLibelléFraisAjt').style.display = 'block';
                document.getElementById('errLibelléFraisAjt').innerHTML = response.erreurLibelle;
            }
        },
        error: function() {
            alert('Une erreur s\'est produite lors de la vérification de libellé');
        }
    });
    return aide;
}

function vérifModifFrais() {
    var IdFrais = document.getElementById('IdFraisModif');
    var Libellé = document.getElementById('LibelléFraisModif');
    var Montant = document.getElementById('MontantFraisModif');
    var array = [Libellé, Montant];
    var aide = true;
    for (let input of array) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }
    for (let input of array) {
        document.getElementById("err" + input.id).innerHTML = "";
    }
    for (let input of array) {
        if (input.value == '') {
            aide = false;
            input.classList += " is-invalid";
            var err = document.getElementById("err" + input.id);
            if (err) {
                err.innerHTML = "Champ requis";
                err.style.display = "block";
            }
        }
    }
    $.ajax({
        url: '../controller.php',
        type: 'POST',
        data: {
            IdFrais: IdFrais.value,
            Libellé: Libellé.value,
            vérifLibelleFraisModif: true
        },
        dataType: 'json',
        async: false,
        success: function(response) {
            if (response.hasOwnProperty('erreurLibelle')) {
                aide = false;
                Libellé.classList.add('is-invalid');
                document.getElementById('errLibelléFraisModif').style.display = 'block';
                document.getElementById('errLibelléFraisModif').innerHTML = response.erreurLibelle;
            }
        },
        error: function() {
            alert('Une erreur s\'est produite lors de la vérification de libellé');
        }
    });
    return aide;
}

function vérifRemb() {
    var Montant = document.getElementById("Montant");
    var Paiement = document.getElementById("Paiement");
    var divPièce = document.getElementById('addFile');
    var aide = true;

    if (Montant.classList.contains("is-invalid")) {
        Montant.classList.remove("is-invalid");
    }

    if (Paiement.classList.contains("is-invalid")) {
        Paiement.classList.remove("is-invalid");
    }

    if (Montant.value === '') {
        aide = false;
        Montant.classList.add("is-invalid");
    }

    if (Paiement.value === '') {
        aide = false;
        Paiement.classList.add("is-invalid");
    }

    if (divPièce.getElementsByTagName('label').length > 0) {
        var pièces = divPièce.getElementsByClassName("piece-select");
        var label = document.getElementsByClassName("piece-label");
        var noms = divPièce.getElementsByClassName("nom-piece");
        var types = divPièce.getElementsByClassName("type-piece-select");

        for (let i = 0; i < types.length; i++) {
            if (types[i].value === '') {
                aide = false;
                types[i].classList.add("is-invalid");
            }
        }

        for (let i = 0; i < noms.length; i++) {
            if (noms[i].value === '') {
                aide = false;
                noms[i].classList.add("is-invalid");
            }
        }

        for (let i = 0; i < pièces.length; i++) {
            var file = pièces[i].files[0];
            if (!file) {
                aide = false;
                label[i].classList.add("is-invalid");
            }
        }
    }

    return aide;
}

var index = 0;

function addFile() {
    var div = document.getElementById('addFile');

    fetch('../controller.php?getFrais')
        .then(response => response.json())
        .then(options => {
            // Créer un nouvel élément div pour chaque pièce jointe
            var newDiv = document.createElement('div');
            newDiv.classList.add('row', 'g-3', 'mb-3');
            newDiv.id = 'fileRow-' + index;
            newDiv.name = 'fileRow-' + index;

            // Créer l'élément de sélection de fichier (input)
            var pieceSelectDiv = document.createElement('div');
            pieceSelectDiv.classList.add('form-group', 'col');

            var label = document.createElement('label');
            label.htmlFor = 'formFile' + index;
            label.classList.add('form-control', 'piece-label');
            label.id = 'labelFile' + index;
            label.textContent = 'Choisir un fichier';
            label.style.backgroundColor = '#dbdbdb';

            var input = document.createElement('input');
            input.classList.add('form-control', 'piece-select');
            input.type = 'file';
            input.id = 'formFile' + index;
            input.name = 'file[]';
            input.setAttribute('accept', 'image/*,.pdf');
            input.style.display = 'none';
            input.setAttribute('onchange', 'nameFile(' + index + ')');

            pieceSelectDiv.appendChild(label);
            pieceSelectDiv.appendChild(input);

            newDiv.appendChild(pieceSelectDiv);

            // Créer l'élément de saisie du nom de fichier
            var nomPieceDiv = document.createElement('div');
            nomPieceDiv.classList.add('form-group', 'col');

            var nomPieceInput = document.createElement('input');
            nomPieceInput.classList.add('form-control', 'nom-piece');
            nomPieceInput.type = 'text';
            nomPieceInput.name = 'nomFile[]';
            nomPieceInput.placeholder = 'Description';

            nomPieceDiv.appendChild(nomPieceInput);

            newDiv.appendChild(nomPieceDiv);

            // Créer l'élément de sélection du type de pièce
            var typePieceDiv = document.createElement('div');
            typePieceDiv.classList.add('col');
            typePieceDiv.style.display = 'flex';
            typePieceDiv.style.alignItems = 'center';

            var select = document.createElement('select');
            select.classList.add('form-select', 'type-piece-select');
            select.id = 'selectFile-' + index;
            select.name = 'typeFile[]';

            var optionsHTML = getOptionsHTML(options);
            select.innerHTML = optionsHTML;

            var removeIcon = document.createElement('i');
            removeIcon.classList.add('fa-solid', 'fa-xmark');
            removeIcon.style.marginLeft = '10px';
            removeIcon.style.backgroundColor = 'red';
            removeIcon.style.color = 'white';
            removeIcon.style.padding = '2px 4px';

            removeIcon.setAttribute('onclick', 'removeFile(' + index + ')');

            typePieceDiv.appendChild(select);
            typePieceDiv.appendChild(removeIcon);

            newDiv.appendChild(typePieceDiv);

            div.appendChild(newDiv);
            index++;
        })
}

function getOptionsHTML(options) {
    var optionsHTML = "<option value=\"\"  data-taux='0'>Type de pièce</option>\n";
    // Parcourir les options et les ajouter au HTML
    options.forEach(option => {
        optionsHTML += "<option  value=\"" + option.IdFrais + "\" data-taux=\"" + option.MontantFrais + "\">" + option.LibelléFrais + "</option>\n";
    });
    return optionsHTML;
}

function nameFile(fileIndex) {
    var fileInput = document.getElementById('formFile' + fileIndex);
    var labelFile = document.getElementById('labelFile' + fileIndex);
    var selectedFile = fileInput.files[0];
    labelFile.textContent = selectedFile.name;
}

function removeFile(index) {
    var element = document.getElementById('fileRow-' + index);
    element.remove();
}

function confirmSupp(table, id, page) {
    switch (table) {
        case "missions":
            vText = "Voulez-vous vraiment supprimer cette mission définitivement ?";
            url = "../controller.php?suppMiss&page=" + page + "&IdMiss=" + id;
            break;
        case "membres":
            vText = "Voulez-vous vraiment supprimer ce collaborateur définitivement ?";
            url = "../controller.php?suppCollab&page=" + page + "&IdMb=" + id;
            break;
        case "groupes":
            vText = "Voulez-vous vraiment supprimer ce groupe définitivement ?";
            url = "../controller.php?suppGroupe&page=" + page + "&IdG=" + id;
            break;
        case "frais":
            vText = "Voulez-vous vraiment supprimer ce frais définitivement ?";
            url = "../controller.php?suppFrais&page=" + page + "&IdFrais=" + id;
            break;
    }
    swal({
            title: "",
            text: vText,
            icon: "warning",
            buttons: [
                "Annuler",
                "Supprimer",
            ],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                document.location.href = url;
            }
        });
}

function success(success) {
    swal({
        title: '',
        text: success,
        icon: 'success',
        button: false,
        timer: 3000,
    });
}

function erreur(erreur) {
    swal({
        title: '',
        text: erreur,
        icon: 'warning',
        button: false,
        timer: 3000,
    });
}

function showOverlay(element) {
    element.classList.add('overlay');
    var overlay = element.querySelector('.overlay-content');
    overlay.classList.remove('d-none');
}

function hideOverlay(element) {
    element.classList.remove('overlay');
    var overlay = element.querySelector('.overlay-content');
    overlay.classList.add('d-none');
}

function updateImage(imageId, fileInputId, IdPJ) {
    var fileInput = document.getElementById(fileInputId);
    if (fileInput.files && fileInput.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var imageElement = document.getElementById(imageId);
            imageElement.src = e.target.result;
        };
        swal({
                title: "",
                text: "voulez-vous vraiment modifier l\'image ?",
                icon: "warning",
                buttons: [
                    "Annuler",
                    "modifier",
                ],
            })
            .then((willDelete) => {
                if (willDelete) {
                    saveModifiedImage(IdPJ, fileInput.files[0]);
                    reader.readAsDataURL(fileInput.files[0]);
                } else {
                    exit;
                }
            });
    }
}

function saveModifiedImage(IdPJ, file) {
    var formData = new FormData();
    formData.append('modifPJ', true);
    formData.append('IdPJ', IdPJ);
    formData.append('file', file);

    $.ajax({
        url: '../controller.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Image modified and saved successfully!');
        },
        error: function() {
            alert("Une erreur s'est produite lors de la modification de l'image.");
        }
    });
}

function vérifModifMdpsCollab() {
    var IdMb = document.getElementById('IdMb');
    var passwordActuelle = document.getElementById("PasswordActuelle");
    var newPassword = document.getElementById("NewPassword");
    var confirmNewPassword = document.getElementById("ConfirmNewPassword");
    var errPasswordActuelle = document.getElementById("errPasswordActuelle");
    var errNewPassword = document.getElementById("errNewPassword");
    var errConfirmNewPassword = document.getElementById("errConfirmNewPassword");
    var arrayInput = [passwordActuelle, newPassword, confirmNewPassword];
    var arrayErr = [errPasswordActuelle, errNewPassword, errConfirmNewPassword];
    var aide = true;
    for (let input of arrayInput) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }
    for (let input of arrayErr) {
        input.innerHTML = "";
    }
    if (passwordActuelle.value == '') {
        aide = false;
        passwordActuelle.classList.add('is-invalid');
        errPasswordActuelle.style.display = 'block';
        errPasswordActuelle.innerHTML = 'champs requis';
    }
    if (aide) {
        $.ajax({
            url: '../controller.php',
            type: 'POST',
            data: { Mdps: passwordActuelle.value, IdMb: IdMb.value, vérifMdps: true },
            dataType: 'text',
            async: false,
            success: function(response) {
                if (response == "1") {
                    passwordActuelle.classList.add('is-invalid');
                    errPasswordActuelle.style.display = 'block';
                    errPasswordActuelle.innerHTML = "Mot de passe incorrect";
                    aide = false;
                }
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la vérification du CIN et de l\'email.');
            }
        });
    }
    if (newPassword.value == '') {
        aide = false;
        newPassword.classList.add('is-invalid');
        errNewPassword.style.display = 'block';
        errNewPassword.innerHTML = 'champs requis';
    }
    if (confirmNewPassword.value == '') {
        aide = false;
        confirmNewPassword.classList.add('is-invalid');
        errConfirmNewPassword.style.display = 'block';
        errConfirmNewPassword.innerHTML = 'champs requis';
    } else if (newPassword.value != confirmNewPassword.value) {
        aide = false;
        confirmNewPassword.classList.add('is-invalid');
        errConfirmNewPassword.style.display = 'block';
        errConfirmNewPassword.innerHTML = 'Les deux mots de passes ne sont pas identiques !';
    }

    return aide;
}

function annulerVérifModifMdpsCollab() {
    var passwordActuelle = document.getElementById("PasswordActuelle");
    var newPassword = document.getElementById("NewPassword");
    var confirmNewPassword = document.getElementById("ConfirmNewPassword");
    var errPasswordActuelle = document.getElementById("errPasswordActuelle");
    var errNewPassword = document.getElementById("errNewPassword");
    var errConfirmNewPassword = document.getElementById("errConfirmNewPassword");
    var arrayInput = [passwordActuelle, newPassword, confirmNewPassword];
    var arrayErr = [errPasswordActuelle, errNewPassword, errConfirmNewPassword];
    var aide = true;
    for (let input of arrayInput) {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
        }
    }
    for (let input of arrayErr) {
        input.innerHTML = "";
    }
}