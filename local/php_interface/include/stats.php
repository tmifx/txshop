<?
/* После загузки страницы
 */
$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler("main", "OnProlog",  array("OnPrologForStats", "onProlog"));
class OnPrologForStats
{
	function onProlog()
	{		
		if(defined("NO_KEEP_STATISTIC") || defined("NO_AGENT_STATISTIC") || defined("NOT_CHECK_PERMISSIONS")) {
			return;
		}
		//подключить модуль
		if(!\Bitrix\Main\Loader::includeModule("highloadblock")) {
			return;
		}
		
		//подключить класс статистики
		CBitrixComponent::includeComponentClass("classes:stats");
		
		//создать класс статистики
		$stats = new Stats();
		
		//добавить в статистику посещение сайта
		$stats->addVisitedSite();
	}
}
?>