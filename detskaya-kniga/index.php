<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Товары из силикона");
?><?$APPLICATION->IncludeComponent(
	"tx:catalog_detskaya_kniga",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "products",
		"SEF_FOLDER" => "/detskaya-kniga/",
		"SEF_MODE" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>