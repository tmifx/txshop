<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	$this->redirectToList('/knigi-dlya-detey/'); //перенаправить на список
	
	$arParams['CUR_PAGE'] = $APPLICATION->GetCurPage();
	$arParams['ELEMENT_CODE'] = explode('/', $arParams['CUR_PAGE']);
	$arParams['ELEMENT_CODE'] = $arParams['ELEMENT_CODE'][2];	
	if($arParams['AUTHORIZED'] = $USER->IsAuthorized()) {
		$arParams['USER_ID'] = $USER->GetID();
	}
	
	//модуль HL
	if (! CModule::IncludeModule('highloadblock'))
		return;
			
	//проверить кэш	
	$obCache = new CPHPCache;
	$cachePath = '/tx/knigi-dlya-detey/';
	
	$arParams['CACHE_KEY'][] = $arParams['IBLOCK_TYPE'];
	$arParams['CACHE_KEY'][] = $arParams['IBLOCK_ID'];
	$arParams['CACHE_KEY'][] = $arParams['ELEMENT_CODE'];
	$arParams['CACHE_KEY'] = implode(',', $arParams['CACHE_KEY']);
	
	if($obCache->StartDataCache($arParams["CACHE_TIME"], $arParams['CACHE_KEY'], $cachePath)) {
		//тэг кэширования
		global $CACHE_MANAGER;
		$CACHE_MANAGER->StartTagCache($cachePath);
		$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams['IBLOCK_ID']);
		$CACHE_MANAGER->EndTagCache();
		
		
		//подключить модули
		if (! CModule::IncludeModule('iblock'))
			return;
			
		$arResult['ELEMENT'] = $this->getElement(
			$arParams['IBLOCK_TYPE'], 
			$arParams['IBLOCK_ID'], 
			$arParams['ELEMENT_CODE'], 
			$this->getSections() //разделы
		);
		
		//получить свойства
		$arResult['ELEMENT']['PROPS'] = $this->getProps($arResult['ELEMENT']);
		
		if($arResult['ELEMENT']) {
			$obCache->EndDataCache($arResult);
		}else {
			$obCache->AbortDataCache();
		}
	}else {
		$arResult = $obCache->GetVars();
	}
	
	//добавить в статистику просмотров
	$this->addStatsViewElement($arResult['ELEMENT']['ID'], $arResult['ELEMENT']['SECTIONS_ID']);
		
	//SEO
	$this->set_seo(array('Книга'), $arResult['ELEMENT']);
		
	//проверить, добавлял ли ранее пользователь в избранное данный элемент
	if($arParams['AUTHORIZED'] && $arParams['USER_ID']) {
		$arResult['IS_FAVORITES'] = $this->isFavorites($arParams['USER_ID'], $arResult['ELEMENT']['ID']);
	}
		
	$this->IncludeComponentTemplate();//вывести тему	
?>
