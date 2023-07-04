<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="inc/css/login.css">
    <link rel="icon" href="./inc/img/logoB.svg">
    <script src="./inc/js/functions.js"></script>
</head>
<body>
    <section>
        <div class="imgBx">
            <img src="inc/img/log.svg">
        </div>
        <div class="contentBx">
            <div class="formBx">
                <h2>Bienvenue</h2>
                <form action="controller.php" method="post" onsubmit="return vérifLog()">
                    <div class="form-group">
                        <label class="col-form-label col-form-label-lg" for="inputLarge">Email</label>
                        <input class="form-control form-control-lg" type="text" name="Email" id="EmailLog" <?php if(isset($_COOKIE['Email'])) echo "value='".$_COOKIE['Email']."'"; ?>>
                        <div class="invalid-feedback" id="errEmail"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label col-form-label-lg" for="inputLarge">Mot de passe</label>
                        <input class="form-control form-control-lg" type="password" name="Mdps" id="MdpsLog">
                        <div class="invalid-feedback" id="errMdps"></div>
                    </div>
                    <button type="submit" name="con" class="btn btn-primary btn-lg" style="margin-top: 27px;">Se connecter</button>                
                </form>
            </div>
        </div>
    </section>
</body>
<?php 
if(isset($_SESSION["errEmailLog"])){
    echo "
        <script>
            var Email = document.getElementById('EmailLog');
            var errEmail = document.getElementById('errEmail');
            Email.classList.add('is-invalid');
            errEmail.style.display ='block';
            errEmail.innerHTML = '$_SESSION[errEmailLog]';
        </script>
    ";
    unset($_SESSION["errEmailLog"]);
}
elseif(isset($_SESSION["errMdpsLog"])){
    echo "
        <script>
            var Mdps = document.getElementById('MdpsLog');
            var errMdps = document.getElementById('errMdps');
            Mdps.classList.add('is-invalid');
            errMdps.style.display ='block';
            errMdps.innerHTML = '$_SESSION[errMdpsLog]';
        </script>
    ";
    unset($_SESSION["errMdpsLog"]);
}
?>
</html>