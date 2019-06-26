<?php
$Host	= 'tbpinio.pw';
$User	= 'tbpikbfe_cpmDev1';
$Pass	= 'UxIn940OoxiPvt';
$DB		= 'tbpikbfe_cpmDev1';
$con_PRMSUB = mysqli_connect($Host,$User,$Pass);
if(!$con_PRMSUB){echo 'Server Not Connect!';exit;}

$Db_Con_PRMSUB = mysqli_select_db($con_PRMSUB,$DB);
if(!$Db_Con_PRMSUB){echo 'Database Not Connect!';exit;}
?>