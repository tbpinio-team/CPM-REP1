<?php
$Host	= 'tbpinio.pw';
$User	= 'tbpikbfe_cpmDev1';
$Pass	= 'UxIn940OoxiPvt';
$DB		= 'tbpikbfe_cpmDev1';
$con_SCPR = mysqli_connect($Host,$User,$Pass);
if(!$con_SCPR){echo 'Server Not Connect!';exit;}

$Db_Con_SCPR = mysqli_select_db($con_SCPR,$DB);
if(!$Db_Con_SCPR){echo 'Database Not Connect!';exit;}
?>