<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Легенды Flash, legendsofflash, flash, game, games, игры, игра, легенды флеш, легенды флэш, легенды");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Легенды Flash");
?><?$APPLICATION->IncludeComponent(
	"tx:catalog_elements", 
	".default", 
	array(
		"CACHE_TIME" => "3600000",
		"CACHE_TYPE" => "A",
		"IBLOCK_ID" => array(
			0 => "6",
		),
		"IBLOCK_ID_SECTIONS" => array(
			0 => "5",
		),
		"IBLOCK_TYPE" => array(
			0 => "products",
		),
		"IBLOCK_TYPE_SECTIONS" => "sections",
		"PAGE_SIZE" => "32",
		"SEF_FOLDER" => "/",
		"SEF_MODE" => "Y",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>