<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?><?$APPLICATION->IncludeComponent(
	"tx:reg",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>