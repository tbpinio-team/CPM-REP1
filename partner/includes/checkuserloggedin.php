<?php
session_start();
if(isset($_SESSION)){
	if($_SESSION['user_id'] != ''){
		if(strtolower($_SESSION['user_type']) == 'partner'){
			header('Location: dashboard.php');
		}
		else{
			header('Location: ../login.php');
		}
	}else{
		header('Location: onboard_login.php');
	}
}
?>