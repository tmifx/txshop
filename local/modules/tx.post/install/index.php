<?
	use \Bitrix\Main\Loader;
	use \Bitrix\Main\Application;
	use \Bitrix\Main\Entity\Base;
	use \Bitrix\Main\Config\Option;
	use \Bitrix\Main\ModuleManager;
	use \Bitrix\Main\EventManager;
	use \Tx\Post\ListNewTable;
	
	Class tx_post extends CModule
	{	
		function __construct() {
			$arModuleVersion = array();
			include(__DIR__."/version.php");
			
			//id модуля
			$this->MODULE_ID = "tx.post";
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
			$this->MODULE_NAME = $arModuleVersion["MODULE_NAME"];
			$this->MODULE_DESCRIPTION = $arModuleVersion["MODULE_DESCRIPTION"];			
		}
		
	    function DoInstall()
	    {		
			//зарегестрировать модуль
			ModuleManager::registerModule($this->MODULE_ID);
			
			//скопировать административные скрипты
			CopyDirFiles(
				$_SERVER["DOCUMENT_ROOT"].'/local/modules/'.$this->MODULE_ID.'/install/admin/',
				$_SERVER["DOCUMENT_ROOT"].'/bitrix/admin/');			
			
			//установка баз данных
			$this->InstallDB();	
			
			//регистраниция событий
			$this->registerEvent();
			
			//добавить агента
			$this->addAgent();
		}	
		
		function DoUninstall()
	    {
			global $APPLICATION, $obModule, $removing_base;
			
			$context =  Bitrix\Main\Application::getInstance()->GetContext();
			$request = $context->getRequest();
			
			$obModule = $this; //объект модуля, можно использовать в шагах инсталяции
			
			if(!$request['step'] || $request['step'] == 1) {
				$APPLICATION->IncludeAdminFile('Удаление', 
				$_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/unstep1.php");
			}elseif($request['step'] == 2) {			
				//удалить административне скрипты
				DeleteDirFiles(
					$_SERVER["DOCUMENT_ROOT"].'/local/modules/'.$this->MODULE_ID.'/install/admin/', 
					$_SERVER["DOCUMENT_ROOT"].'/bitrix/admin/', 
					array("index.php"));
				
				//удалить базу, если есть галочка
				if($removing_base == 'Y') {
					$this->UnInstallDB();
				}
				
				//удалении зарегестрированных событий
				$this->unRegisterEvent();
				
				//удалить агента
				$this->deleteAgent();
				
				//удалить модуль
				ModuleManager::unRegisterModule($this->MODULE_ID); 
			}			
		}
		
		//добавить агента 
		function addAgent() {
			CAgent::AddAgent(
				"TX\Post\ScheduleTable::runFunctions();", 	// имя функции которая проверяет расписание и запускает нужные функции
				$this->MODULE_ID,                           // идентификатор модуля
				"N",                                  		// агент не критичен к кол-ву запусков
				60                                	  		// интервал запуска - каждую минуту				
			);
		}
		//удалить агента
		function deleteAgent() {
			CAgent::RemoveModuleAgents($this->MODULE_ID);
		}
		
		
		//регистрация событий
		function registerEvent() {
			EventManager::getInstance()->registerEventHandler(
				"iblock",
				"OnAfterIBlockElementAdd",
				$this->MODULE_ID,
				"\\Tx\\Post\\ListNewTable",
				"addNewElement"
			);
			EventManager::getInstance()->registerEventHandler(
				"iblock",
				"OnAfterIBlockElementUpdate",
				$this->MODULE_ID,
				"\\Tx\\Post\\ListNewTable",
				"updateNewElement"
			);
			EventManager::getInstance()->registerEventHandler(
				"iblock",
				"OnAfterIBlockElementDelete",
				$this->MODULE_ID,
				"\\Tx\\Post\\ListNewTable",
				"deleteNewElement"
			);
		}
		function unRegisterEvent() {			
			EventManager::getInstance()->unRegisterEventHandler(
				"iblock",
				"OnAfterIBlockElementAdd",
				$this->MODULE_ID,
				"\\Tx\\Post\\ListNewTable",
				"addNewElement"
			);
			EventManager::getInstance()->unRegisterEventHandler(
				"iblock",
				"OnAfterIBlockElementUpdate",
				$this->MODULE_ID,
				"\\Tx\\Post\\ListNewTable",
				"updateNewElement"
			);
			EventManager::getInstance()->unRegisterEventHandler(
				"iblock",
				"OnAfterIBlockElementDelete",
				$this->MODULE_ID,
				"\\Tx\\Post\\ListNewTable",
				"deleteNewElement"
			);
		}
		//установка базы данных
		function InstallDB() 
		{
			Loader::includeModule($this->MODULE_ID);			
			
			//расписание
			if(!Application::getConnection(\Tx\Post\ScheduleTable::getConnectionName())->
				isTableExists(Base::getInstance('\Tx\Post\ScheduleTable')->getDBTableName())
			)
			{
				Base::getInstance('\Tx\Post\ScheduleTable')->createDbTable();
			}
			
			//список новых публикаций
			if(!Application::getConnection(\Tx\Post\ListNewTable::getConnectionName())->
				isTableExists(Base::getInstance('\Tx\Post\ListNewTable')->getDBTableName())
			)
			{
				Base::getInstance('\Tx\Post\ListNewTable')->createDbTable();
			}
			
			//списко сайтов на которые будет публиковаться
			if(!Application::getConnection(\Tx\Post\ListWebSitesTable::getConnectionName())->
				isTableExists(Base::getInstance('\Tx\Post\ListWebSitesTable')->getDBTableName())
			)
			{
				Base::getInstance('\Tx\Post\ListWebSitesTable')->createDbTable();
			}
			
			//список функций
			if(!Application::getConnection(\Tx\Post\ListFunctionsTable::getConnectionName())->
				isTableExists(Base::getInstance('\Tx\Post\ListFunctionsTable')->getDBTableName())
			)
			{
				Base::getInstance('\Tx\Post\ListFunctionsTable')->createDbTable();
			}
			
			//список типов
			if(!Application::getConnection(\Tx\Post\ListTypesTable::getConnectionName())->
				isTableExists(Base::getInstance('\Tx\Post\ListTypesTable')->getDBTableName())
			)
			{
				Base::getInstance('\Tx\Post\ListTypesTable')->createDbTable();
			}			
		}
		
		//удаление базы
		function UnInstallDB()
		{
			Loader::includeModule($this->MODULE_ID);
			
			//расписание
			Application::getConnection(\Tx\Post\ScheduleTable::getConnectionName())->
				queryExecute('drop table if exists '.Base::getInstance('\Tx\Post\ScheduleTable')->getDBTableName()
			);
			
			//список новых публикаций
			Application::getConnection(\Tx\Post\ListNewTable::getConnectionName())->
				queryExecute('drop table if exists '.Base::getInstance('\Tx\Post\ListNewTable')->getDBTableName()
			);
			
			//списко сайтов на которые будет публиковаться
			Application::getConnection(\Tx\Post\ListWebSitesTable::getConnectionName())->
				queryExecute('drop table if exists '.Base::getInstance('\Tx\Post\ListWebSitesTable')->getDBTableName()
			);
			
			//список функций
			Application::getConnection(\Tx\Post\ListFunctionsTable::getConnectionName())->
				queryExecute('drop table if exists '.Base::getInstance('\Tx\Post\ListFunctionsTable')->getDBTableName()
			);
			
			//список типов
			Application::getConnection(\Tx\Post\ListTypesTable::getConnectionName())->
				queryExecute('drop table if exists '.Base::getInstance('\Tx\Post\ListTypesTable')->getDBTableName()
			);
			
			Option::delete($this->MODULE_ID);
		}	
	}
?>