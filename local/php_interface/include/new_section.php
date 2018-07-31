<?
/* Добавление разделы
 */
AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", Array("NewSection", "new_section"));

class NewSection
{    
	const STEP_SORT = 100; //шаг в сортировке
	
	/* Добавлять сортировку новым разделам
	 */
	function new_section(&$arFields) 
	{			
		$arSelect = array('SORT', 'IBLOCK_TYPE_ID');
		$arOrder = array("SORT"=>"DESC");
		$arFilter = array(
			"IBLOCK_ID"=>$arFields['IBLOCK_ID'],
			"SECTION_ID"=>$arFields['IBLOCK_SECTION_ID']);
		$list_sections = CIBlockSection::GetList($arOrder, $arFilter,false,$arSelect);
		$section = $list_sections->GetNext();			
		$arFields['SORT'] = $section['SORT']+self::STEP_SORT;
	}
}
?>