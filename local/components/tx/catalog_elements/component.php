<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?		
	//количество элементов
	$arParams['PAGE_SIZE'] = $arParams['PAGE_SIZE']?$arParams['PAGE_SIZE']:32;
	
	//номер старницы
	$arParams['NUM_PAGE'] = $_REQUEST['PAGEN_1']?$_REQUEST['PAGEN_1']:1;
	
	//размер картинок
	$arParams['PICTURE_WIDTH'] = $arParams['PICTURE_WIDTH']?$arParams['PICTURE_WIDTH']:200;
	$arParams['PICTURE_HEIGHT'] = $arParams['PICTURE_HEIGHT']?$arParams['PICTURE_HEIGHT']:200;
	
	$arParams['CUR_PAGE'] = $APPLICATION->GetCurPage();
	$arParams['CUR_PAGE_PARAM'] = $APPLICATION->GetCurPageParam();
	
	//авторизация пользователя
	$arParams['AUTHORIZED'] = $USER->IsAuthorized()?true:false;
	
	//разбить ссылку на массив
	$arParams['AR_URL'] = explode('/', $arParams['CUR_PAGE']);

	//если существует больше 3 элементов, то выдать ошибку 404
	if($arParams['AR_URL'][3]) {
		$this->error404();	
		return;
	}
	
	//код инфоблока разделов
	$arParams['IBLOCK_CODE_SECTIONS'] = $arParams['AR_URL'][1]?$arParams['AR_URL'][1]:false;	
	
	//получить код секции
	$arParams['SECTION_CODE'] = $arParams['AR_URL'][2]?$arParams['AR_URL'][2]:false;	
	
	//сортировки
	if($_REQUEST['sort'] == CatalogElements::SORT_POPULAR) 
		$arParams['SORT'] = $_REQUEST['sort'];
	if($_REQUEST['sort'] == CatalogElements::SORT_FAVORITES) 
		$arParams['SORT'] = $_REQUEST['sort'];
		
	//фильтры для авторизованных
	if($_REQUEST['filter'] == CatalogElements::FILTER_FAVORITES) 
		$arParams['FILTER'] = $_REQUEST['filter'];
		
	//строка поиска
	$arParams['SEARCH'] = urldecode($_REQUEST['search']);
	
	//выгрузка списка
	$arParams['SEARCH_LIST'] = $_REQUEST['search_list']=='y'?true:false;
	
	//id пользователя
	$arParams['USER_ID'] = $arParams['AUTHORIZED']?$USER->GetID():false;
	
	//модуль HL
	if (! CModule::IncludeModule('highloadblock'))
		return;
			
	//проверить кэш	
	$obCache = new CPHPCache;
	$cachePath = '/tx/catalog_elements/';
	
	//собрать ключи кэш, при котором будет сбрасываться
	$arParams['CACHE_KEY'] = array();	
	foreach($arParams['IBLOCK_TYPE'] as $item) {
		$arParams['CACHE_KEY'][] = $item;
	}
	foreach($arParams['IBLOCK_ID'] as $item) {
		$arParams['CACHE_KEY'][] = $item;
	}
	foreach($arParams['IBLOCK_TYPE_SECTIONS'] as $item) {
		$arParams['CACHE_KEY'][] = $item;
	}
	foreach($arParams['IBLOCK_ID_SECTIONS'] as $item) {
		$arParams['CACHE_KEY'][] = $item;
	}	
	$arParams['CACHE_KEY'][] = $arParams['IBLOCK_CODE_SECTIONS'];
	$arParams['CACHE_KEY'][] = $arParams['SECTION_CODE'];
	$arParams['CACHE_KEY'][] = $arParams['NUM_PAGE'];
	$arParams['CACHE_KEY'][] = $arParams['SORT'];
	$arParams['CACHE_KEY'][] = $arParams['SEARCH'];
	$arParams['CACHE_KEY'][] = $arParams['FILTER'];
	$arParams['CACHE_KEY'] = implode(',', $arParams['CACHE_KEY']);
	
	if($obCache->StartDataCache($arParams["CACHE_TIME"], $arParams['CACHE_KEY'], $cachePath)) {
		//тэг кэширования
		global $CACHE_MANAGER;
		$CACHE_MANAGER->StartTagCache($cachePath);
		foreach($arParams['IBLOCK_ID'] as $iblock_id) {
			$CACHE_MANAGER->RegisterTag("iblock_id_".$iblock_id);
		}
		$CACHE_MANAGER->EndTagCache();
		
		//подключить модули
		if (! CModule::IncludeModule('iblock'))
			return;	
		
		//подключить модуль поиска, если есть строка поиска
		if($arParams['SEARCH']) {
			if (! CModule::IncludeModule('search'))
				return;	
		}
		
		//проверить существует ли инфоблок, иначе выдать ошибку 404
		if($arParams['IBLOCK_CODE_SECTIONS']) {
			if(!$this->testIblockByCode($arParams['IBLOCK_CODE_SECTIONS'])) {
				$this->error404();				
				$obCache->AbortDataCache();
				return;
			}
		}
		
		//Получить разделы		 
		$arSections = $this->getSections();		
				
		//получить элементы
		$this->getElements($arSections, $arControls);
		
		//получить секцию
		$arResult['SECTION'] = $this->getSection($arSections , $arParams['SECTION_CODE']);	
		
		//ошибка 404, если раздел не найден
		if($arParams['SECTION_CODE'] && !$arResult['SECTION']) {
			$this->error404();				
			$obCache->AbortDataCache();
			return;
		}
		
		
		//получить SEO
		$arResult['SEO'] = $this->getSEO($arResult['SECTION']['IBLOCK_ID'], $arResult['SECTION']['ID']);
		
		
		//если пользователь авторизовон и есть фильтр по избранным, то не кэшировать
		if($arParams['FILTER'] == CatalogElements::FILTER_FAVORITES) {
			$obCache->AbortDataCache();
		}else {		
			if($arResult["ELEMENTS"]) {
				$obCache->EndDataCache($arResult);
			}else {
				$obCache->AbortDataCache();
			}
		}
	}else {
		$arResult = $obCache->GetVars();
	}
	
	/***********************************************************************
						ОБРАБОТКА ДАННЫХ
	***********************************************************************/	
	//добавить в статистику просмотры секции
	if($arParams['SECTION_CODE'] && $arResult['SECTION']) {
		$this->addStatsViewSection($arResult['SECTION']['ID']);		
	}
		
	//записать SEO
	if($arResult['SECTION']) {
		//получить заголовок
		if(!$arResult['SEO']['SECTION_META_TITLE']) {
			$title = $arResult['SECTION']['NAME'];
		}elseif($arResult['SEO']['SECTION_META_TITLE']) {
			
			$arTitle = explode(chr(13),$arResult['SEO']['SECTION_META_TITLE']);
			$title = $arTitle[0];
			if($_REQUEST['sort'] == CatalogElements::SORT_POPULAR) {
				$title = $arTitle[1];
			}
			if($_REQUEST['sort'] == CatalogElements::SORT_FAVORITES) {
				$title = $arTitle[2];
			}
		}
		
		//получить описание
		if($arResult['SEO']['SECTION_META_DESCRIPTION']) {
			$description = $arResult['SEO']['SECTION_META_DESCRIPTION'];
		}
		
		//получить ключевые слова
		if($arResult['SEO']['SECTION_META_KEYWORDS']) {
			$keywords = $arResult['SEO']['SECTION_META_KEYWORDS'];
		}
		
		$APPLICATION->SetTitle($title); //заголовок
		$APPLICATION->SetPageProperty("description", $description); //описание
		$APPLICATION->SetPageProperty("keywords", $keywords); //ключевые слова
	}
	
	//темы	
	if($arParams['SEARCH_LIST']) {
		$this->IncludeComponentTemplate('search_list');
	}else {
		$this->IncludeComponentTemplate();//вывести тему	
	}
?>