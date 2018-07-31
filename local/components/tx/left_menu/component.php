<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	//echo '<pre>';print_r($arParams); echo '</pre>';	
	//проверить кэш	
	$obCache = new CPHPCache;
	if($obCache->StartDataCache($arParams["CACHE_TIME"], 'left_menu', '/tx/left_menu/')) {	
		//подключить модуль инфоблока
		if (! CModule::IncludeModule('iblock'))
			return;
		
		//получить инфоблоки
		$res = CIBlock::GetList(
			Array(), 
			Array(
				"ACTIVE"=>"Y",
				'TYPE'=>$arParams['IBLOCK_TYPE'],
			)
		);
		while($ar_res = $res->Fetch()) {
			$arResult[$ar_res['ID']] = array(
				'NAME'=>$ar_res['NAME'],
				'SECTIONS'=>array(),
				);			
			$iblocks_id[] = $ar_res['ID'];
		}
		
		//получить разделы в инфоблоках
		$arSelect = array('ID','NAME','SECTION_PAGE_URL', 'IBLOCK_ID');
		$arOrder = array("SORT"=>"ASC");
		$arFilter = array(
			"IBLOCK_TYPE"=>$arParams['IBLOCK_TYPE'],
			"IBLOCK_ID"=>$iblocks_id);
		$list_sections = CIBlockSection::GetList($arOrder, $arFilter,false,$arSelect);
		while($section = $list_sections->GetNext()) {
			$arResult[$section['IBLOCK_ID']]['SECTIONS'][] = $section;		
		}	
		
		if($arResult) {
			$obCache->EndDataCache($arResult);
		}else {
			$obCache->AbortDataCache();
		}
	}else {
		$arResult = $obCache->GetVars();
	}
		
	$this->IncludeComponentTemplate();//вывести тему	
?>