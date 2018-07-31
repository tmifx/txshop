<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	CBitrixComponent::includeComponentClass("classes:events");
	class Stats extends Events
	{			
		/*********************************************************************
							ФУНКУЦИИ ПОЛУЧЕНИЯ СТАТИСТИКИ
		**********************************************************************/
		public function getStats($elements) {
			
			//если у элеметов есть ключь ID, значить нужно добавить элемент в массив
			if($elements['ID']) {
				$elements = array($elements);
			}
			
			foreach($elements as $element) {
				$elements_id[] = $element['ID'];
			}
			
			$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
				'filter'=>array('NAME'=>'StatsElements')
			));
			$result = array();
			if($hlblock = $rsData->Fetch()) {
				$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
				$entity_data_class = $entity->getDataClass();	
				$rsData = $entity_data_class::getList(array(
						"select" => array('*'),
						"filter"=>array(
							'UF_ELEMENT_ID'=>$elements_id
						)));
				$rsData = new CDBResult($rsData);			
				while($arRes = $rsData->Fetch()) {					
					$result[$arRes['UF_ELEMENT_ID']]['FAVORITES'] = $arRes['UF_FAVORITES'];
					$result[$arRes['UF_ELEMENT_ID']]['VIEWS'] = $arRes['UF_VIEWS'];								
				}	
			}
			
			return $result;
		}
		/*********************************************************************
								ФУНКЦИИ ДОБАВЛЕНИЯ В СТАТИСТИКУ
		**********************************************************************/
		//посещение сайта
		public function addVisitedSite() {			
			global $USER;
			
			$time = time();
			$time_update_data_user = 3600;  //время проверки визита, раз в час
			$time_update_data_visit = 86400; //время обновления посещения, раз в сутки
			
			//добавить посещение пользователю
			if($USER->IsAuthorized()) {			
				if($time - $this->getSession(Session::VISITED_SITE_USER) > $time_update_data_user) {
					//получить id пользователя
					$user_id = $USER->GetID();
					
					$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
						'filter'=>array('NAME'=>'StatsUsers')
					));
					if($hlblock = $rsData->Fetch()) {	
						$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
						$entity_data_class = $entity->getDataClass();	
						
						$rsData = $entity_data_class::getList(array(
								"select" => array('*'),
								"filter"=>array(
									'UF_USER_ID' => $user_id,
									)));
						$rsData = new CDBResult($rsData);
						
						$data = array(
							"UF_USER_ID"=>$user_id,
							"UF_TIME_VISIT_24"=>ConvertTimeStamp($time, 'FULL'),
							"UF_NUM_VISITS"=>1,
						);
						if(!$arRes = $rsData->Fetch()) {		
							$entity_data_class::add($data);	

							$this->regEvent(Events::VISIT_24); //регистрация события
						}else {
							//если прошло больше суток, то прибавить визит							
							if(!$arRes['UF_TIME_VISIT_24'] || ($time - MakeTimeStamp($arRes['UF_TIME_VISIT_24']->toString()) > $time_update_data_visit)) {
								$data['UF_NUM_VISITS'] = $arRes['UF_NUM_VISITS'] + 1;								
								$entity_data_class::update($arRes['ID'], $data);	
								
								$this->regEvent(Events::VISIT_24); //регистрация события
							}
						}										
					}					
					$this->setSession(Session::VISITED_SITE_USER, $time);
				}
			}			
		}
		
		//добавить в статистику просмотр элемента
		public function addStatsViewElement($element_id, $ar_sections_id) {			
			global $DB, $USER;
			
			//если нету элемента то выйти
			if(!$element_id || !$ar_sections_id) 
				return;
			
			//Проверить сессию, если ранее элемент просматривался, то не прибавлять счетчик просмотров
			if(!$this->getSession(Session::VIEW_ELEMENT, $element_id)) {			
				//статистика элемента
				$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
					'filter'=>array('NAME'=>'StatsElements')
				));			
				if($hlblock = $rsData->Fetch()) {				
					$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
					$entity_data_class = $entity->getDataClass();	
													
					//найти элемент
					$rsData = $entity_data_class::getList(array(
							"select" => array('*'),
							"filter"=>array(
								'UF_ELEMENT_ID' => $element_id
								)));
					$rsData = new CDBResult($rsData);				
					if($arRes = $rsData->Fetch()) {
						$DB->StartTransaction();
						if($entity_data_class::update($arRes['ID'], array("UF_VIEWS"=>$arRes['UF_VIEWS'] + 1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}else {
						$DB->StartTransaction();
						if($entity_data_class::add(array('UF_ELEMENT_ID'=>$element_id, "UF_VIEWS"=>1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}
				}
				$this->setSession(Session::VIEW_ELEMENT, $element_id, true);
			}
			
			
			//добавить в статистику пользователя просмотр элемента
			if($USER->IsAuthorized()) {
				//получить id пользователя
				$user_id = $USER->GetID();
				
				$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
					'filter'=>array('NAME'=>'StatsUsers')
				));			
				if($hlblock = $rsData->Fetch()) {				
					$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
					$entity_data_class = $entity->getDataClass();	
													
					//найти элемент
					$rsData = $entity_data_class::getList(array(
							"select" => array('*'),
							"filter"=>array(
								'UF_USER_ID' => $user_id
								)));
					$rsData = new CDBResult($rsData);				
					if($arRes = $rsData->Fetch()) {
						$DB->StartTransaction();
						if($entity_data_class::update($arRes['ID'], array("UF_NUM_VIEW_ELEMENTS"=>$arRes['UF_NUM_VIEW_ELEMENTS'] + 1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}else {
						$DB->StartTransaction();
						if($entity_data_class::add(array('UF_USER_ID'=>$user_id, "UF_NUM_VIEW_ELEMENTS"=>1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}
				}			
				
				$this->regEvent(Events::VIEW_ELEMENT, $element_id, $ar_sections_id); //регистрация события
			}			
		}
		//добавить в статистику просмотр раздела
		public function addStatsViewSection($section_id) {
			global $DB, $USER;
				
			if(!$section_id) 
				return;
						
			//Проверить сессию, если ранее секция просматривалась, то не прибавлять счетчик просмотров
			if(!$this->getSession(Session::VIEW_SECTION, $section_id)) {		
				//Добавить в статистику секции
				$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
					'filter'=>array('NAME'=>'StatsSections')
				));		
				if($hlblock = $rsData->Fetch()) {				
					$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
					$entity_data_class = $entity->getDataClass();	
													
					//найти раздел
					$rsData = $entity_data_class::getList(array(
							"select" => array('*'),
							"filter"=>array(
								'UF_SECTION_ID' => $section_id
								)));
					$rsData = new CDBResult($rsData);				
					
					if($arRes = $rsData->Fetch()) {
						$DB->StartTransaction();
						if($entity_data_class::update($arRes['ID'], array("UF_VIEWS"=>$arRes['UF_VIEWS'] + 1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}else {
						$DB->StartTransaction();
						if($entity_data_class::add(array('UF_SECTION_ID'=>$section_id, "UF_VIEWS"=>1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}
				}
				$this->setSession(Session::VIEW_SECTION, $section_id, true);
			}

			//добавить в статистику пользователя просмотр раздела
			if($USER->IsAuthorized()) {
				//получить id пользователя
				$user_id = $USER->GetID();
				
				$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
					'filter'=>array('NAME'=>'StatsUsers')
				));			
				if($hlblock = $rsData->Fetch()) {				
					$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
					$entity_data_class = $entity->getDataClass();	
													
					//найти элемент
					$rsData = $entity_data_class::getList(array(
							"select" => array('*'),
							"filter"=>array(
								'UF_USER_ID' => $user_id
								)));
					$rsData = new CDBResult($rsData);				
					if($arRes = $rsData->Fetch()) {
						$DB->StartTransaction();
						if($entity_data_class::update($arRes['ID'], array("UF_NUM_VIEW_SECTIONS"=>$arRes['UF_NUM_VIEW_SECTIONS'] + 1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}else {
						$DB->StartTransaction();
						if($entity_data_class::add(array('UF_USER_ID'=>$user_id, "UF_NUM_VIEW_SECTIONS"=>1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}
				}
				
				//регистрация события
				$this->regEvent(Events::VIEW_SECTION, $section_id); 
			}			
		}
		//добавить в статистику избранное +1
		public function addStatsFavoriteElement($element_id, $ar_sections_id) {
			global $DB, $USER;
				
			if(!$USER->IsAuthorized() || !$element_id || !$ar_sections_id) {
				return;
			}			
			//получить id пользователя
			$user_id = $USER->GetID();
			
			//добавить в статистику элементу
			$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
				'filter'=>array('NAME'=>'StatsElements')
			));			
			if($hlblock = $rsData->Fetch()) {
				$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
				$entity_data_class = $entity->getDataClass();	
				
				//найти элемент
				$rsData = $entity_data_class::getList(array(
						"select" => array('*'),
						"filter"=>array(
							'UF_ELEMENT_ID' => $element_id
							)));
				$rsData = new CDBResult($rsData);				
						
				if($arRes = $rsData->Fetch()) {
					$DB->StartTransaction();
					if($entity_data_class::update($arRes['ID'], array("UF_FAVORITES"=>$arRes['UF_FAVORITES'] + 1))) {
						$DB->Commit();	
					}else {
						$DB->Rollback();
					}
				}else {
					$DB->StartTransaction();
					if($entity_data_class::add(array('UF_ELEMENT_ID'=>$element_id, "UF_FAVORITES"=>1))) {
						$DB->Commit();	
					}else {
						$DB->Rollback();
					}
				}
			}


			//добавить в статистику пользователя добавление в избранные			
			$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
				'filter'=>array('NAME'=>'StatsUsers')
			));			
			if($hlblock = $rsData->Fetch()) {				
				$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
				$entity_data_class = $entity->getDataClass();	
												
				//найти элемент
				$rsData = $entity_data_class::getList(array(
						"select" => array('*'),
						"filter"=>array(
							'UF_USER_ID' => $user_id
							)));
				$rsData = new CDBResult($rsData);				
				if($arRes = $rsData->Fetch()) {
					$DB->StartTransaction();
					if($entity_data_class::update($arRes['ID'], array("UF_NUM_ADD_FAVORITE"=>$arRes['UF_NUM_ADD_FAVORITE'] + 1))) {
						$DB->Commit();	
					}else {
						$DB->Rollback();
					}
				}else {
					$DB->StartTransaction();
					if($entity_data_class::add(array('UF_USER_ID'=>$user_id, "UF_NUM_ADD_FAVORITE"=>1))) {
						$DB->Commit();	
					}else {
						$DB->Rollback();
					}
				}
			}
			
			//регистрация события
			$this->regEvent(Events::ADD_FAVORITE, $element_id, $ar_sections_id); 
		}
		
		//добавить в статистику избранное -1
		public function removeStatsFavoriteElement($element_id, $ar_sections_id) {
			global $DB, $USER;
			
			if(!$USER->IsAuthorized() || !$element_id || !$ar_sections_id) {
				return;
			}	
			//получить id пользователя
			$user_id = $USER->GetID();
			
			//убрать из статистики элемента
			$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
				'filter'=>array('NAME'=>'StatsElements')
			));
			
			if($hlblock = $rsData->Fetch()) {
				$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
				$entity_data_class = $entity->getDataClass();	
				
				//найти элемент
				$rsData = $entity_data_class::getList(array(
						"select" => array('*'),
						"filter"=>array(
							'UF_ELEMENT_ID' => $element_id
							)));
				$rsData = new CDBResult($rsData);				
						
				if($arRes = $rsData->Fetch()) {
					$DB->StartTransaction();
					if($entity_data_class::update($arRes['ID'], array("UF_FAVORITES"=>--$arRes['UF_FAVORITES']))) {
						$DB->Commit();	
					}else {
						$DB->Rollback();
					}
				}
			}

			//добавить в статистику пользователя удаление из избранных				
			$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
				'filter'=>array('NAME'=>'StatsUsers')
			));			
			if($hlblock = $rsData->Fetch()) {				
				$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
				$entity_data_class = $entity->getDataClass();	
												
				//найти элемент
				$rsData = $entity_data_class::getList(array(
						"select" => array('*'),
						"filter"=>array(
							'UF_USER_ID' => $user_id
							)));
				$rsData = new CDBResult($rsData);				
				if($arRes = $rsData->Fetch()) {
					$DB->StartTransaction();
					if($entity_data_class::update($arRes['ID'], array("UF_NUM_DEL_FAVORITE"=>$arRes['UF_NUM_DEL_FAVORITE'] + 1))) {
						$DB->Commit();	
					}else {
						$DB->Rollback();
					}
				}else {
					$DB->StartTransaction();
					if($entity_data_class::add(array('UF_USER_ID'=>$user_id, "UF_NUM_DEL_FAVORITE"=>1))) {
						$DB->Commit();	
					}else {
						$DB->Rollback();
					}
				}
			}
			
			//регистрация события
			$this->regEvent(Events::DEL_FAVORITE, $element_id, $ar_sections_id); 
		}		
		
		//добавить в статистику когда поделились
		public function addStatsShare($element_id, $ar_sections_id) {
			global $DB, $USER;
			
			if(!$element_id || !$ar_sections_id)
				return;
			
			//записать в статистику элемента, когда ей поделились
			if(!$this->getSession(Session::SHARE_ELEMENT, $element_id)) {
				$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
					'filter'=>array('NAME'=>'StatsElements')
				));			
				if($hlblock = $rsData->Fetch()) {
					$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
					$entity_data_class = $entity->getDataClass();	
					
					//найти элемент
					$rsData = $entity_data_class::getList(array(
							"select" => array('*'),
							"filter"=>array(
								'UF_ELEMENT_ID' => $element_id
								)));
					$rsData = new CDBResult($rsData);				
							
					if($arRes = $rsData->Fetch()) {
						$DB->StartTransaction();
						if($entity_data_class::update($arRes['ID'], array("UF_SHARED"=>$arRes['UF_SHARED'] + 1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}else {
						$DB->StartTransaction();
						if($entity_data_class::add(array('UF_ELEMENT_ID'=>$element_id, "UF_SHARED"=>1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}
				}
				
				//добавить в сессию элемент которым уже поделились
				$this->setSession(Session::SHARE_ELEMENT, $element_id, true);				
			}
			
			//количество раз когда пользователь поделился
			if($USER->IsAuthorized()) {	
				//получить id пользователя
				$user_id = $USER->GetID();
				
				$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
					'filter'=>array('NAME'=>'StatsUsers')
				));			
				if($hlblock = $rsData->Fetch()) {				
					$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
					$entity_data_class = $entity->getDataClass();	
													
					//найти элемент
					$rsData = $entity_data_class::getList(array(
							"select" => array('*'),
							"filter"=>array(
								'UF_USER_ID' => $user_id
								)));
					$rsData = new CDBResult($rsData);				
					if($arRes = $rsData->Fetch()) {
						$DB->StartTransaction();
						if($entity_data_class::update($arRes['ID'], array("UF_NUM_SHARED"=>$arRes['UF_NUM_SHARED'] + 1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}else {
						$DB->StartTransaction();
						if($entity_data_class::add(array('UF_USER_ID'=>$user_id, "UF_NUM_SHARED"=>1))) {
							$DB->Commit();	
						}else {
							$DB->Rollback();
						}
					}
				}
				
				//регистрация события
				$this->regEvent(Events::ADD_SHARE, $element_id, $ar_sections_id); 
			}
		}		
	}
?>