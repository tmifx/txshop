<?
/* Добавление элемента
 */
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("NewElement", "new_element"));

class NewElement
{    
	const STEP_SORT = 100; //шаг в сортировке
	
	//Добавлять сортировку новым элементам
	function new_element(&$arFields) {			
		//получить секцию	
		$arSelect = array('SORT');
		$arOrder = array("SORT"=>"DESC");
		$arFilter = array();
		$arNavStartParams  = array('nTopCount'=>1);
		$list_elements = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect);
		$element = $list_elements->GetNext();
		$arFields['SORT'] = $element['SORT']+self::STEP_SORT;
	}
}
?>