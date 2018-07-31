<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
	$APPLICATION->IncludeComponent(
		"tx:add_favorite", ".default", 
		array(
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000"
		),
		false
	);
?>