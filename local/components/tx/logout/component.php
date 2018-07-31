<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	//echo '<pre>';print_r($_REQUEST); echo '</pre>';	
	global $USER;

	//выйти пользователю
	$USER->Logout();        
	$redirect = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:'/';	
	LocalRedirect($redirect);	
?>