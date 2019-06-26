<?php
$Host	= 'tbpinio.pw';
$User	= 'tbpikbfe_cpmDev1';
$Pass	= 'UxIn940OoxiPvt';
$DB		= 'tbpikbfe_cpmDev1';

$con_MAIN = mysqli_connect($Host,$User,$Pass);
if(!$con_MAIN){echo 'Server Not Connect!';exit;}

$Db_Con_Main = mysqli_select_db($con_MAIN,$DB);
if(!$Db_Con_Main){echo 'Database Not Connect!';exit;}
?>