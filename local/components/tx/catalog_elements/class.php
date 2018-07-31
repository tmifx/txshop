<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	CBitrixComponent::includeComponentClass("classes:stats");
	class CatalogElements extends Stats
	{	
		//виды сортировок
		const SORT_POPULAR = 'popular';
		const SORT_FAVORITES = 'favorites';
		
		//виды фильтров
		const FILTER_FAVORITES = 'favorites';
		
		
		//получить инфоблок по коду
		public function testIblockByCode($code) {
			$res = CIBlock::GetList(
				Array(), 
				Array("CODE"=>$code)
			);
			if($ar_res = $res->Fetch())
			{
				return true;
			}else {
				return false;	
			}
		}
				
		/* SEO
		 */
		public function getSEO($iblock_id, $section_id) {
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($iblock_id, $section_id);					
			return $ipropValues->getValues();
		}
		
		
		/* Проверить, была ли ранее добавлено в избранное
		 */
		public function isFavorites($user_id, $element_id) {
			$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
				'filter'=>array('NAME'=>'UsersFavorites')
			));
			if($hlblock = $rsData->Fetch()) {	
				$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
				$entity_data_class = $entity->getDataClass();	
				
				$rsData = $entity_data_class::getList(array(
						"select" => array('*'),
						"filter"=>array(
							'UF_USER_ID' => $user_id,
							'UF_ELEMENT_ID' => $element_id,
							)));
				$rsData = new CDBResult($rsData);
				
				if($arRes = $rsData->Fetch()) {		
					return true;
				}else {
					return false;
				}
			}
		}
		
		/* Получить списко всех разделов
		 */
		public function getSections() {
			$arSelect = array('ID','NAME','SECTION_PAGE_URL', 'CODE', 'DESCRIPTION', 'IBLOCK_ID');
			$arOrder = array("SORT"=>"ASC");
			$arFilter = array("IBLOCK_TYPE"=>'sections');
			$list_sections = CIBlockSection::GetList($arOrder, $arFilter,false,$arSelect);
			
			$arSections = array();
			while($section = $list_sections->GetNext()) {
				$arSections[$section['ID']] = $section;			
			}
			return $arSections;
		}
		/* Получить секцию
		 */
		public function getSection($sections, $code) {
			if(!$code) return false;
			
			foreach($sections as $section) {
				if($section['CODE'] == $code) {
					return $section;
				}
			}
			return false;
		}
		
		
		/* Получить элементы	
		 */
		public function getElements(&$arSections, &$arControls) {
			
			//получить элементы в зависимости от сортировки
			if($this->arParams['SORT']) {				
				switch($this->arParams['SORT']) {				
					case self::SORT_POPULAR:
						$elements_id = $this->get_sort_elements_id('UF_VIEWS');
						break;
					case self::SORT_FAVORITES:
						$elements_id = $this->get_sort_elements_id('UF_FAVORITES');
						break;					
					default:
						$this->arParams['SORT'] = false;
				}
			}	
			
			//получить элементы в зависимости от фильтра			
			if($this->arParams['FILTER']) {
				switch($this->arParams['FILTER']) {	
					case self::FILTER_FAVORITES:
						$elements_id = $this->get_user_favorites();
						break;				
					default:
						$this->arParams['FILTER'] = false;
				}
			}
			
			//получить элементы в зависимости от строки поиска			
			if($this->arParams['SEARCH']) {
				$elements_id = $this->get_search_elements_id();				
			}
			
			//получить id секций раздела по инфоблоку и коду
			$arSelect = array('ID');
			$arOrder = array("SORT"=>"ASC");
			$arFilter = array(
				"IBLOCK_TYPE"=>$this->arParams['IBLOCK_TYPE_SECTIONS'],
				"IBLOCK_ID"=>$this->arParams['IBLOCK_ID_SECTIONS'],				
				);
			if($this->arParams['IBLOCK_CODE_SECTIONS']) {
				$arFilter['IBLOCK_CODE'] = $this->arParams['IBLOCK_CODE_SECTIONS'];
			}
			if($this->arParams['SECTION_CODE']) {
				$arFilter['CODE'] = $this->arParams['SECTION_CODE'];
			}
			$list_sections = CIBlockSection::GetList($arOrder, $arFilter,false,$arSelect);
			while($section = $list_sections->GetNext()) {
				$sections_id[] = $section['ID'];
			}
						
			//получить элементы
			$arSelect = array(	'ID', 'NAME', 'ACTIVE', 'IBLOCK_ID',
								'DETAIL_PAGE_URL', 'PREVIEW_PICTURE');
			$arOrder = array("SORT"=>"DESC", 'ID'=>'DESC');
			$arFilter = array(
				"IBLOCK_TYPE"=>$this->arParams['IBLOCK_TYPE'],
				"IBLOCK_ID"=>$this->arParams['IBLOCK_ID'],	
				"ACTIVE_DATE"=>"Y", 
				"ACTIVE"=>"Y"				
			);
			if($sections_id) {
				$arFilter['PROPERTY_SECTIONS'] = $sections_id;
			}
			if($elements_id) {
				$arFilter['ID'] = $elements_id;
			}
			
			if(!$this->arParams['SORT'] && !$this->arParams['FILTER'] && !$this->arParams['SEARCH']) {
				$arNavStartParams = array(
					'nPageSize'=>$this->arParams['PAGE_SIZE'],
					'iNumPage'=>$this->arParams['NUM_PAGE'],
				);
				$list_elements = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect);			
				$this->arResult["NAV_STRING"] = $list_elements->GetPageNavStringEx();//Переключение страниц			
				$this->get_elements($list_elements, $arSections, $arControls);
			}else if($elements_id){
				$list_elements = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);	
				$this->get_elements($list_elements, $arSections, $arControls);
				$this->sort_elements_by_elements_id($elements_id);
			}
		}
		
		//получить элементы из строки поиска
		function get_search_elements_id() {			
			//обработка строки
			$str_search = $this->arParams['SEARCH'];
			$str_search = str_replace('-', ' ', $str_search);
			$str_search = str_replace(',', ' ', $str_search);
			$str_search = str_replace('.', ' ', $str_search);
						
			
			//получить элементы поиска
			$obSearch = new CSearch;
			
			//параметры поиска
			$arParamsSearch = array(
				"QUERY" => $str_search,
				"SITE_ID" => SITE_ID,
				"MODULE_ID" => "iblock",
			);
			
			//сортировка
			$arParamsSort = array(
				'TITLE_RANK'=>'DESC',
				'RANK'=>'DESC',
				'CUSTOM_RANK'=>'DESC',
			);
			
			
			//запросы на поиск
			$obSearch->SetOptions(array(
			   'ERROR_ON_EMPTY_STEM' => false,
			));			
			
			//без Морфологии			
			$obSearch->Search($arParamsSearch, $arParamsSort, array('STEMMING' => false));
			$obSearch->NavStart($this->arParams['PAGE_SIZE'], false, $this->arParams['NUM_PAGE']);
			
			if ($obSearch->errorno!=0) {} else {
				while($result = $obSearch->GetNext()) {					
					if(intval($result['ITEM_ID']) != $result['ITEM_ID']) 
						continue;
					
					if(!in_array($result['ITEM_ID'],$elements_id)) {						
						$elements_id[] = $result['ITEM_ID'];
					}
				}
			}
			
			//поиск с морфологией
			if(!$elements_id) {
				$obSearch->Search($arParamsSearch, $arParamsSort);
				if ($obSearch->errorno!=0) {} else {
					while($result = $obSearch->GetNext()) {					
						if(intval($result['ITEM_ID']) != $result['ITEM_ID']) 
							continue;
						
						if(!in_array($result['ITEM_ID'],$elements_id)) {
							$elements_id[] = $result['ITEM_ID'];
						}
					}
				}
			}
			
			$this->arResult["NAV_STRING"] = $obSearch->GetPageNavStringEx();//Переключение страниц
			
			return $elements_id;			
		}
		
		//получить избранные элементы пользователя
		function get_user_favorites() {	
			$elements_id = array();
			
			if(!$this->arParams['AUTHORIZED'] || !$this->arParams['USER_ID'])
				return $elements_id;		
			
			
			//получить элементы пользователя
			$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
				'filter'=>array('NAME'=>'UsersFavorites')
			));
			if($hlbl = $rsData->Fetch()) {
				$hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl['ID'])->fetch();
				$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
				$entity_data_class = $entity->getDataClass();	
				
				$rsData = $entity_data_class::getList(array(
						"select" => array('*'),
						"order"=>array('ID'=>'DESC'),
						"filter"=>array(
							'UF_USER_ID' => $this->arParams['USER_ID']
							)));
				$rsData = new CDBResult($rsData, $sTableID);
				$rsData->NavStart($this->arParams['PAGE_SIZE'], false, $this->arParams['NUM_PAGE']);
				$this->arResult["NAV_STRING"] = $rsData->GetPageNavStringEx();//Переключение страниц
				while($arRes = $rsData->Fetch()) {		
					$elements_id[] = $arRes['UF_ELEMENT_ID'];
				}
			}
			
			return $elements_id;
		}
		//отсортировать элементы по элемент id
		function sort_elements_by_elements_id($elements_id) {
			$new_elements = array();			
			foreach($elements_id as &$element_id) {
				foreach($this->arResult["ELEMENTS"] as &$element) {
					if($element['ID'] == $element_id) {
						$new_elements[] = $element;
						break;
					}
 				}	
			}			
			$this->arResult["ELEMENTS"] = $new_elements;
		}
		//получить id отсортированных элементов элементов
		function get_sort_elements_id($field) {
			
			//получить все элементы в секции
			if($this->arParams['SECTION_CODE']) {
				//получить элементы в данном разделе
				$arSelect = array('ID');
				$arOrder = array("SORT"=>"DESC");
				$arFilter = array(
					"IBLOCK_TYPE"=>$this->arParams['IBLOCK_TYPE'],
					"IBLOCK_ID"=>$this->arParams['IBLOCK_ID'],
					"SECTION_CODE"=>$this->arParams['SECTION_CODE'],			
				);
								
				$list_elements = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);		
				while($element = $list_elements->GetNext()) {
					$elements_in_section_id[] = $element['ID'];
				}				
			}
			
			$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
				'filter'=>array('NAME'=>'StatsElements')
			));
				
			if($hlbl = $rsData->Fetch()) {
				$hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl['ID'])->fetch();
				$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
				$entity_data_class = $entity->getDataClass();
				
				$params = array(
						"select" => array('UF_ELEMENT_ID'),
						"order" => array($field=>'DESC'),
						);
				if($this->arParams['SECTION_CODE']) {
					$params['filter'] = array('UF_ELEMENT_ID'=>$elements_in_section_id);
				}
				$rsData = $entity_data_class::getList($params);
				$rsData = new CDBResult($rsData, $sTableID);
				$rsData->NavStart($this->arParams['PAGE_SIZE'], false, $this->arParams['NUM_PAGE']);
				$this->arResult["NAV_STRING"] = $rsData->GetPageNavStringEx();//Переключение страниц	
				while($arRes = $rsData->Fetch()) {
					$elements_id[] = $arRes['UF_ELEMENT_ID'];
				}
			}			
			return $elements_id;
		}
		//получить элементы
		function get_elements($list_elements, &$arSections, &$arControls) {
			//разрещаемые свойства			
			while($element = $list_elements->GetNext()) {	
				$db_props  = CIBlockElement::GetProperty(
					$element['IBLOCK_ID'], 
					$element['ID'],
					array(),
					array()
				);			
				while($ar_props = $db_props->Fetch()) {
					
					//подставить разделы
					if($ar_props['CODE'] == 'SECTIONS') {
						$ar_props['VALUE'] = $arSections[$ar_props['VALUE']];
					}
					
					//подставить управление
					if($ar_props['CODE'] == 'CONTROL') {
						$ar_props['VALUE'] = $arControls[$ar_props['VALUE']];
					}
					
					if($ar_props['MULTIPLE'] == 'Y') {
						if($ar_props['VALUE'])
						$element['PROPERTY'][$ar_props['CODE']][] = array(
																	'NAME'=>$ar_props['NAME'],
																	'VALUE'=>$ar_props['VALUE'],
																);
					}else {
						if($ar_props['VALUE'])
						$element['PROPERTY'][$ar_props['CODE']] = array(
																	'NAME'=>$ar_props['NAME'],
																	'VALUE'=>$ar_props['VALUE'],
																);
					}
				}			
				//получить картинку
				$element['PREVIEW_PICTURE'] = $this->getPicture($element['PREVIEW_PICTURE'], $this->arParams['PICTURE_WIDTH'], $this->arParams['PICTURE_HEIGHT']);
				
				//список элементов
				$this->arResult["ELEMENTS"][] = $element;				
			}
		}
		
	}
?>
