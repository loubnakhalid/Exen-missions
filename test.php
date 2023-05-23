<span>
                            <lord-icon src='https://cdn.lordicon.com/dnmvmpfk.json' class='info' trigger='hover' data-toggle='modal' data-target='#infoMiss' colors='primary:#0d6efd' data-id='$row[IdMiss]' style='width:25px;height:25px;margin-top: 5px'></lord-icon>
                        </span>
            ";
                if($row['StatutMiss']==0){
                    echo "
                        <span>
                            <i class='bx bx-edit icnModifMiss'  data-target='#formModif' data-toggle='modal' data-id='$row[IdMiss]' style='color:orange;margin-top: 4px;font-size: 25px;'></i>
                        </span>
                    ";
                }
                else{
                    echo "
                        <span class='dropdown'>
                            <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='fa-sharp fa-solid fa-print' style='color: #ffffff;'></i>
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' href='../PDF/Ordre_Mission/$row[OrdreMiss]' target='_blank'>Ordre de mission</a>";
                    if($row['Montant']!=NULL){
                        echo "  <a class='dropdown-item' href='../PDF/Demande_Remboursement/$row[DemandeRemb]' target='_blank'>Demande de remboursement</a>";
                    }
                    else{
                        echo "  <a class='dropdown-item disabled'>Demande de remboursement</a>";
                    }
                        echo"
                            </div>
                        </span>
                    ";
                }
                if($row['StatutMiss']==1 && $row['Montant']!=NULL){
                    echo "
                        <span class='dropdown'>
                            <button class='btn btn-secondary btn-sm dropdown-toggle green disabled' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fa-sharp fa-regular fa-circle-check'></i>
                            </button>
                        </span>
                    ";
                }
                else{
                    if($row['StatutMiss']==0){
                        echo "
                        <span class='dropdown'>
                            <button class='btn btn-secondary btn-sm dropdown-toggle red' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='fa-sharp fa-regular fa-circle-check'></i>
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <input type='hidden' value='$row[TypeMiss]' id='TypeMiss'>
                                <a class='dropdown-item' href='../controller.php?validerMiss&IdMiss=$row[IdMiss]&page=$page'>Valider la mission</a>
                                <a class='dropdown-item disabled' href='javascript:void(0)'  data-id='$row[IdMiss]'  data-toggle='modal' data-target='#validerRemb'>Valider le remboursement</a>
                        ";
                    }
                    else{
                        echo "  
                        <span class='dropdown'>
                            <button class='btn btn-secondary btn-sm dropdown-toggle green' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='fa-sharp fa-regular fa-circle-check'></i>
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <input type='hidden' value='$row[TypeMiss]' id='TypeMiss'>
                                <a class='dropdown-item disabled' href='javascript:void(0)'>Valider la mission</a>
                            ";
                        if($row['Montant']==NULL){
                            echo "<a class='dropdown-item' id='lienValiderRemb' href='../controller.php?validerRemb&IdMiss=$row[IdMiss]&page=$page' data-TypeMiss='$row[TypeMiss]' data-id='$row[IdMiss]' data-toggle='modal' data-target='#validerRemb'>Valider le remboursement</a>";
                        }
                        else{
                            echo "<a class='dropdown-item disabled' href='javascript:void(0)'>Valider le remboursement</a>";
                        }
                    }
                }
                echo"
                            </div>
                        </span>
                        <span>
                            <i class='fa-sharp fa-solid fa-box-archive fa-2x' style='color:red' onclick='document.location.href=\"../controller.php?archMiss&IdMiss=$row[IdMiss]&page=$page\"'></i>
                        </span>