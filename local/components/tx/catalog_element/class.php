<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	CBitrixComponent::includeComponentClass("tx:catalog_elements");
	class CatalogElement extends CatalogElements
	{	
		public function executeComponent() {
			parent::executeComponent();
			//Ометить что из элемента нужно вернуться назад
			$GLOBALS['LOGO_LOAD_SCROLL_HISTORY'] = true;
		}
		
		//получить свойства
		public function getProps(&$element) {
			$props = array();
			foreach($element['PROPERTY'] as $code=>$prop) {				
				if(!strstr($code, 'PROP_')) 
					continue;
				
				
				$props[$code] = $prop;
				
				//удалить свойство из элемента
				unset($element[$code]);
			}
			return $props;
		}
		
		//получить элемент
		public function getElement($iblock_type, $iblock_id, $element_code, $sections) {
			$arSelect = array('ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PICTURE', 'DETAIL_TEXT');
			$arOrder = array();
			$arFilter = array(
				"IBLOCK_TYPE"=>$iblock_type,
				"IBLOCK_ID"=> $iblock_id,
				"CODE"=>$element_code);	
			$list_elements = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);	

			//разрещаемые свойства			
			if($element = $list_elements->Fetch()) {
				$db_props  = CIBlockElement::GetProperty(
					$element['IBLOCK_ID'], 
					$element['ID'],
					array(),
					array()
				);
				
				while($ar_props = $db_props->Fetch()) {
					//подставить разделы
					if($ar_props['CODE'] == 'SECTIONS') {
						$ar_props['VALUE'] = $sections[$ar_props['VALUE']];
					}
					
					//получить значение справочников из HL 
					if($ar_props['USER_TYPE_SETTINGS'] && $ar_props['USER_TYPE_SETTINGS']['TABLE_NAME']) {						
						$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
							'filter'=>array('TABLE_NAME'=>$ar_props['USER_TYPE_SETTINGS']['TABLE_NAME'])
						));						
						if($hlblock = $rsData->Fetch()) {	
							
							$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
							$entity_data_class = $entity->getDataClass();	
							
							$rsData = $entity_data_class::getList(array(
									"select" => array('*'),
									"filter"=>array(
										'ID' => $ar_props['VALUE']
										)));
							$rsData = new CDBResult($rsData);
							if($arRes = $rsData->Fetch()) {
								$ar_props['VALUE'] = $arRes['UF_NAME'];
							}
						}
					}
						
					
					
					//записать свойства
					if($ar_props['MULTIPLE'] == 'Y') {																	
						if($ar_props['VALUE'])
						$element['PROPERTY'][$ar_props['CODE']][] = array(
																		'NAME'=>$ar_props['NAME'],
																		'VALUE'=>$ar_props['VALUE']);
					}else {						
						if($ar_props['VALUE'])
						$element['PROPERTY'][$ar_props['CODE']] = array(
																		'NAME'=>$ar_props['NAME'],
																		'VALUE'=>$ar_props['VALUE']);
					}
				}			
				
				//получить детальную картинку
				if($element['DETAIL_PICTURE']) {
					$file = CFile::GetFileArray($element['DETAIL_PICTURE']);
					$element['DETAIL_PICTURE'] = CFile::ResizeImageGet($file, array('width'=>400, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false);
				}
				
				//получить картинки
				foreach($element['PROPERTY']['PICTURES'] as &$picture) {
					$file = CFile::GetFileArray($picture['VALUE']);
					$picture = CFile::ResizeImageGet($file, array('width'=>1280, 'height'=>2048), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, false, 50);
				}
				
				
				//записать id секций в элемент
				foreach($element['PROPERTY']['SECTIONS'] as $section) {					
					$element['SECTIONS_ID'][] = $section['VALUE'];
				}		
			}
			
			return $element;
		}
		
		//записать SEO
		public function set_seo($ar_suffix, $element) {
			global $APPLICATION;
			
			$name = $element['NAME'];
			
			$title = array();
			foreach($ar_suffix as $suffix) {
				$title[] = $suffix.' '.$name;			
			}
			$title = implode(' | ', $title);
			
			$APPLICATION->SetTitle($title);	
			
			//записать разделы  в описание
			$description = array();
			if($element['PROPERTY']['SECTIONS'][0]) {
				foreach($element['PROPERTY']['SECTIONS'] as $section) {
					$description[] = $section['VALUE']['NAME'];
				}
			}else {
				$description[] = $element['PROPERTY']['SECTIONS']['VALUE']['NAME'];
			}
			
			$description = implode(', ',$description);			
			$APPLICATION->SetPageProperty("description", $description);
			$APPLICATION->SetPageProperty("keywords", $description);
		}		
	}
?>
