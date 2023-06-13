</main>
<?php
    if(isset($_SESSION['erreur'])){ 
        echo "
            <script>
                erreur(\"$_SESSION[erreur]\");
            </script>
        "; 
        unset($_SESSION['erreur']); 
    } 
    if(isset($_SESSION['success'])){ 
        echo "
            <script>
                success('$_SESSION[success]');
            </script>
        "; 
        unset($_SESSION['success']); 
    }
?>
</body>
<!--Container Main end-->
<script src="../inc/js/script.js"></script>
</html>