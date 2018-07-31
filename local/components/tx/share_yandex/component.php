<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	//echo '<pre>';print_r($arParams); echo '</pre>';	
	//адрес сервера
	$arParams['SERVER_URL'] = ($_SERVER['SERVER_PORT']==80?'http://':'https://').$_SERVER['HTTP_HOST'];
	//проверить кэш	
	$obCache = new CPHPCache;
	if($arParams['CACHE_KEY'] = intval($arParams['ELEMENT_ID']))
	if($obCache->StartDataCache($arParams["CACHE_TIME"], $arParams['CACHE_KEY'], '/tx/share_yandex/')) {		
		//подключить модуль инфоблока
		if (! CModule::IncludeModule('iblock'))
			return;			
		
		//получить элементы
		$arSelect = array('ID', 'NAME', 'PREVIEW_TEXT', 
						  'PREVIEW_PICTURE', 'DETAIL_PAGE_URL',
						  'PROPERTY_NAME_RU');
		$arOrder = array("SORT"=>"DESC", 'ID'=>'DESC');
		$arFilter = array('ID'=>$arParams['ELEMENT_ID']);		
		$list_elements = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);		
		if($element = $list_elements->GetNext()) {
			if($element['PREVIEW_PICTURE']) {
				$file = CFile::GetFileArray($element['PREVIEW_PICTURE']);
				$element['PREVIEW_PICTURE'] = CFile::ResizeImageGet($file, array('width'=>200, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true, false, false, 75);
			}
			
			if($element['PROPERTY_NAME_RU_VALUE']) {
				$element['NAME'] = $element['PROPERTY_NAME_RU_VALUE'].' - ('.$element['NAME'].')';
			}
			
			$arResult = $element;
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
