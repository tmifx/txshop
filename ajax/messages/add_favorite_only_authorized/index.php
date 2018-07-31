<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
	$APPLICATION->IncludeComponent(
		"tx:get_message", ".default", 
		array(
			'CODE'=>'FAVORITE_ONLY_AUTHORIZED',			
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "86400"
		),
		false
	);
?>