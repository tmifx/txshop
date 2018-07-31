<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;
	
$arTypes = CIBlockParameters::GetIBlockTypes();
if(!$arCurrentValues["IBLOCK_TYPE"]) {
	foreach($arTypes as $iblock_type_id=>$iblock_type) {
		$arCurrentValues["IBLOCK_TYPE"] = $iblock_type_id;
		break;
	}
}

//получить инфоблок
$arIBlocks = Array();
$db_iblock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$_REQUEST["site"], "TYPE" => $arCurrentValues["IBLOCK_TYPE"]));	
while($arRes = $db_iblock->Fetch()) {
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];
	if(!$arCurrentValues["IBLOCK_ID"]) {
		$arCurrentValues["IBLOCK_ID"] = $arRes["ID"];
	}
}


$arTypesSections = CIBlockParameters::GetIBlockTypes();
if(!$arCurrentValues["IBLOCK_TYPE_SECTIONS"]) {
	foreach($arTypesSections as $iblock_type_id=>$iblock_type) {
		$arCurrentValues["IBLOCK_TYPE_SECTIONS"] = $iblock_type_id;
		break;
	}
}

//получить инфоблок
$arIBlocksSections = Array();
$db_iblock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$_REQUEST["site"], "TYPE" => $arCurrentValues["IBLOCK_TYPE_SECTIONS"]));	
while($arRes = $db_iblock->Fetch()) {
	$arIBlocksSections[$arRes["ID"]] = $arRes["NAME"];
	if(!$arCurrentValues["IBLOCK_ID_SECTIONS"]) {
		$arCurrentValues["IBLOCK_ID_SECTIONS"] = $arRes["ID"];
	}
}

$arComponentParameters = array(
   "GROUPS" => array(
		"PICTURES" => array(
			"NAME"=>"Картинки",
			"SORT"=>200,
		), 
   ),
   "PARAMETERS" => array(
		"IBLOCK_TYPE" => Array(
			"PARENT" => "BASE",
			"NAME" => "Тип Инфоблока",
			"TYPE" => "LIST",
			"VALUES" => $arTypes,
			"REFRESH" => "Y",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "N",
			),
		"IBLOCK_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => "Инфоблок",
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"REFRESH" => "Y",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "N",
			),
		"IBLOCK_TYPE_SECTIONS" => Array(
			"PARENT" => "BASE",
			"NAME" => "Тип Инфоблока секций",
			"TYPE" => "LIST",
			"VALUES" => $arTypesSections,
			"REFRESH" => "Y",
			),
		"IBLOCK_ID_SECTIONS" => Array(
			"PARENT" => "BASE",
			"NAME" => "Инфоблок секций",
			"TYPE" => "LIST",
			"VALUES" => $arIBlocksSections,
			"REFRESH" => "Y",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "N",
			),
		"PAGE_SIZE" => Array(
			"PARENT" => "BASE",
			"NAME" => "Количество элементов",
			"TYPE" => "STRING",
			"VALUES" => 32,
			"DEFAULT"=> 32
			),
		"SEF_MODE" => array(),
		"CACHE_TIME"  =>  array(
			"DEFAULT"=>36000000
		),
	)
);
?>