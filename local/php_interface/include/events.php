<?
/* После загузки страницы
 */
$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler("main", "OnProlog",  array("OnPrologForEvents", "onProlog"));
class OnPrologForEvents
{
	function onProlog()
	{			
		if(defined("NO_KEEP_STATISTIC") || defined("NO_AGENT_STATISTIC") || defined("NOT_CHECK_PERMISSIONS")) {
			return;
		}
		//подключить класс событий
		CBitrixComponent::includeComponentClass("classes:events");
		
		$events = new Events();
		
		//посещение сайта, срабатывает раз в сутки
		$events->addEventListener(Events::VISIT_24, function(){
			global $APPLICATION;
			//проверка заданий
			$APPLICATION->IncludeComponent(
				"tx:tasks_checking", 
				".default", 
				array(
					'EVENT'=>Events::VISIT_24,
				),
				false
			);
		});
		
		//просмотр элемента
		$events->addEventListener(Events::VIEW_ELEMENT, function($element_id, $ar_sections_id){
			global $APPLICATION;
			//проверка заданий
			$APPLICATION->IncludeComponent(
				"tx:tasks_checking", 
				".default", 
				array(
					'EVENT'=>Events::VIEW_ELEMENT,
					'ELEMENT_ID'=>$element_id,
					'SECTIONS_ID'=>$ar_sections_id,
				),
				false
			);
		});
		
		//просмотр раздела
		$events->addEventListener(Events::VIEW_SECTION, function($section_id){
			global $APPLICATION;
			//проверка заданий
			$APPLICATION->IncludeComponent(
				"tx:tasks_checking", 
				".default", 
				array(
					'EVENT'=>Events::VIEW_SECTION,
					'SECTIONS_ID'=>$section_id,
				),
				false
			);
		});
		
		//добавление в избранные
		$events->addEventListener(Events::ADD_FAVORITE, function($element_id, $ar_sections_id){
			global $APPLICATION;
			//проверка заданий
			$APPLICATION->IncludeComponent(
				"tx:tasks_checking", 
				".default", 
				array(
					'EVENT'=>Events::ADD_FAVORITE,
					'ELEMENT_ID'=>$element_id,
					'SECTIONS_ID'=>$ar_sections_id,
				),
				false
			);
		});
		
		//удаление из избранных
		$events->addEventListener(Events::DEL_FAVORITE, function($element_id, $ar_sections_id){
			global $APPLICATION;
			//проверка заданий
			$APPLICATION->IncludeComponent(
				"tx:tasks_checking", 
				".default", 
				array(
					'EVENT'=>Events::DEL_FAVORITE,
					'ELEMENT_ID'=>$element_id,
					'SECTIONS_ID'=>$ar_sections_id,
				),
				false
			);
		});
		
		//когда пользователь поделился
		$events->addEventListener(Events::ADD_SHARE, function($element_id, $ar_sections_id){
			global $APPLICATION;
			//проверка заданий
			$APPLICATION->IncludeComponent(
				"tx:tasks_checking", 
				".default", 
				array(
					'EVENT'=>Events::ADD_SHARE,
					'ELEMENT_ID'=>$element_id,
					'SECTIONS_ID'=>$ar_sections_id,
				),
				false
			);
		});
	}
}

?>