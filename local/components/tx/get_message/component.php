<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	//проверить кэш	
	$obCache = new CPHPCache;
	if($obCache->StartDataCache($arParams["CACHE_TIME"], $arParams['CODE'], '/tx/popup_messages/')) {
		//подключить модули
		if(!\Bitrix\Main\Loader::includeModule("highloadblock")) {
			return;
		}
		
		$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
			'filter'=>array('NAME'=>'Messages')
		));
		if($hlblock = $rsData->Fetch()) {	
			$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
			$entity_data_class = $entity->getDataClass();	
			
			$rsData = $entity_data_class::getList(array(
					"select" => array('*'),
					"filter"=>array(
						'UF_CODE' => $arParams['CODE']
						)));
			$rsData = new CDBResult($rsData);
			if($arRes = $rsData->Fetch()) {
				$arResult = $arRes;
			}
		}
		if($arResult) {
			$obCache->EndDataCache($arResult);
		}else {
			$obCache->AbortDataCache();
		}
	}else {
		$arResult = $obCache->GetVars();
	}
	//вернуть массив	
	echo json_encode(array(
		'name'=>$arResult['UF_NAME'],
		'content'=>$arResult['UF_TEXT']
	));
?>