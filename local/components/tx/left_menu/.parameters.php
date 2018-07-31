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
		"CACHE_TIME"  =>  array(
			"DEFAULT"=>36000000
		),	
	)
);
?>