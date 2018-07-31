<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	//echo '<pre>';print_r($arParams); echo '</pre>';
	
	$arParams['AUTHORIZE'] = $USER->IsAuthorized();
	$this->IncludeComponentTemplate();//вывести тему	
?>
