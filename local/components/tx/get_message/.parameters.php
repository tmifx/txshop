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


//получить элемент
$arIBlockElement = Array();
$arOrder = array("SORT"=>"ASC");
$arFilter = Array(
	"IBLOCK_TYPE" => $arCurrentValues["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arCurrentValues["IBLOCK_ID"],
	);
$db_iblock_elements = CIBlockElement::GetList($arOrder, $arFilter);

while($arRes = $db_iblock_elements ->GetNext()) {	
	$arIBlockElement[$arRes["ID"]] = $arRes["NAME"];
}

$arComponentParameters = array(
   "GROUPS" => array(),
   "PARAMETERS" => array(
		"IBLOCK_TYPE" => Array(
			"PARENT" => "BASE",
			"NAME" => "Тип Инфоблока",
			"TYPE" => "LIST",
			"VALUES" => $arTypes,
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			),
		"IBLOCK_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => "Инфоблок",
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			),
		"ELEMENT_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => "Элемент",
			"TYPE" => "LIST",
			"VALUES" => $arIBlockElement,
			"REFRESH" => "N",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			),
		"CACHE_TIME"  =>  array(
			"DEFAULT"=>36000000
		),	
	)
);
?>