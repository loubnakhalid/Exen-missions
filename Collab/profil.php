<?php 
include('./inc/header.php');
use Tets\Oop\Membre;
$membre=Membre::getById($_SESSION['membre']['IdMb']);
$Nom=$membre->getNom();
$Prénom=$membre->getPrénom();
$CIN=$membre->getCIN();
$Email=$membre->getEmail();
$Profil=$membre->getProfil();
$Grp=$membre->getGrp();
$TitreCivilité=$membre->getTitreCivilité();
$missions=Membre::countMissions($_SESSION['membre']['IdMb']);
$remb=Membre::countTotalRemb($_SESSION['membre']['IdMb']);
?>
<div class="row">
	<div class="col-sm-12 mt-5">
		<div class="row">
			<div class="col-md-4">
				<div class="card">
					<div class="card-body position-relative">
						<div class="text-center">
							<div class="chat-avtar d-inline-flex mx-auto">
								<?php 
									if($TitreCivilité=='M.'){
									$src="../inc/img/userMale.png";
									}
									else{
									$src="../inc/img/userFemale.png";
								}
								?>
								<img class="rounded-circle img-fluid wid-120" style="width:172px" src="<?=$src?>" alt="User image">
							</div>
							<h5 class="mt-3 gray"><?=$Nom.' '.$Prénom?></h5>
							<p class="text-muted"><?=$Profil?></p>
								<div class="row g-3 my-4">
									<div class="col border border-top-0 border-bottom-0 border-start-0">
										<h5 class="mb-0 gray"><?=$missions?></h5>
										<small class="text-muted">Missions effectuées</small>
									</div>
									<div class="col">
										<h5 class="mb-0 gray"><?=$remb?> DHS</h5>
										<small class="text-muted">Total remboursement</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<form action="../controller.php" method="post" onsubmit="return vérifModifCollab()">
					<input type="hidden" value="<?=$_SESSION['membre']['IdMb']?>" id="IdMb" name="IdMb">
					<input type="hidden" value="<?=$Grp?>" name="IdG">
						<div class="card" id="cardInfoPerson">
							<div class="card-body">
								<div class="row">
									<div class="col-12">
										<h5 style="display: inline;" class="gray">Informations personnelles</h5>
										<a href="" id="linkModifInfo" class="link-secondary" style="float: right;">Modifier le mot de passe</a>
										<hr class="mb-4">
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="form-label">Nom</label>
											<input type="text" id="NomModif" name="Nom" class="form-control" value="<?=$Nom?>">
										</div>
										<div class="invalid-feedback" id="errNomModif" style="display: none;"></div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="form-label">Prénom</label>
											<input type="text" name="Prénom" id="PrénomModif" class="form-control" value="<?=$Prénom?>">
											<div class="invalid-feedback" id="errPrénomModif" style="display: none;"></div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group mt-3">
											<label class="form-label">CIN</label>
											<input type="text" id="CINModif" name="CIN" class="form-control" value="<?=$CIN?>">
											<div class="invalid-feedback" id="errCINModif" style="display: none;"></div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group mt-3">
										<label class="form-label">Civilité</label>
										<select id="CivilitéModif" class="form-select" name="TitreCivilité">
											<option value="M." <?php if ($TitreCivilité === 'M.') echo 'selected'; ?>>Monsieur</option>
											<option value="Mme" <?php if ($TitreCivilité === 'Mme') echo 'selected'; ?>>Madame</option>
											<option value="Mlle" <?php if ($TitreCivilité === 'Mlle') echo 'selected'; ?>>Mademoiselle</option>
										</select>
										<div class="invalid-feedback" id="errCivilitéModif" style="display: none;"></div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group mt-3">
										<label class="form-label">Email</label>
										<input type="text" id="EmailModif" name="Email" class="form-control" value="<?=$Email?>" placeholder="Email">
										<div class="invalid-feedback" id="errEmailModif" style="display: none;"></div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group mt-3">
										<label class="form-label">Profil</label>
										<input type="text" id="ProfileModif" name="Profil" class="form-control" value="<?=$Profil?>" placeholder="Profil">
										<div class="invalid-feedback" id="errProfileModif" style="display: none;"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-end btn-page">
							<span><button class="btn btn-outline-secondary"  type="reset" onclick="annulerVérifModifCollab()">Annuler</button></span>
							<span><button class="btn btn-primary" name="modifCollab" type="submit">Enregistrer</button></span>
						</div>
					</form>
				</div>
				<div class="col">
					<form action="../controller.php" method="post" onsubmit="return vérifModifMdpsCollab()">
						<input type="hidden" value="<?=$_SESSION['membre']['IdMb']?>" id="IdMb" name="IdMb">
						<div class="card" id="cardModifMdsp" style="display: none;">
							<div class="card-body">
								<div class="row">
									<div class="col-12">
										<h5 style="display: inline;" class="gray">Modifier le mot de passe</h5>
										<a href="" id="linkInfoPerso" class="link-secondary" style="float: right;">Modifier les informations personnelles</a>
										<hr class="mb-4">
									</div>
									<div class="row-sm-6">
										<div class="form-group">
											<label class="form-label">Mot de passe actuelle</label>
											<input type="password" id="PasswordActuelle" class="form-control">
											<div class="invalid-feedback" id="errPasswordActuelle" style="display: none;"></div>
										</div>
									</div>
									<div class="row-sm-6">
										<div class="form-group mt-3">
											<label class="form-label">Nouveau mot de passe</label>
											<input type="password" name="Mdps" id="NewPassword" class="form-control">
											<div class="invalid-feedback" id="errNewPassword" style="display: none;"></div>
										</div>
									</div>
									<div class="row-sm-6">
										<div class="form-group mt-3">
											<label class="form-label">Confirmer nouveau mot de passe</label>
											<input type="password" id="ConfirmNewPassword" class="form-control">
											<div class="invalid-feedback" id="errConfirmNewPassword" style="display: none;"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-end btn-page">
								<span><button class="btn btn-outline-secondary"  type="reset" onclick="annulerVérifModifMdpsCollab()">Annuler</button></span>
								<span><button class="btn btn-primary" name="modifMdpsCollab" type="submit">Enregistrer</button></span>
							</div>
						</div>
					</form>
				</div>
			</div>
<!-- [ sample-page ] end -->
		</div>
<!-- [ Main Content ] end -->
	</div>
</div>
<?php 
include('./inc/footer.php');
?>