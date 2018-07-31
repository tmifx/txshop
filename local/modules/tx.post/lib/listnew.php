<?php
	namespace Tx\Post;
	
	use \Bitrix\Main\Entity;
	
	class ListNewTable extends Entity\DataManager
	{
		public static function getTableName() 		
		{
			return 'tx_post_list_new';
		}		
		public static function getMap() 
		{
			return array(
				//ID
				new Entity\IntegerField('ID', array(
					'primary'=> true,
					'autocomplete'=>true,
				)),				
				//ELEMENT_ID
				new Entity\IntegerField('ELEMENT_ID', array(					 
					'required'=>true, //обязательное для заполнения
					'validation' => function() {
						return array(
							function($element_id, $primary, $row, $field) {
								//проверка на число
								$element_id = intval($element_id);
								if($element_id > 0) {} else {
									return new Entity\FieldError(
										$field, 
										'Значение поля должно быть числовым'
									);
								}
								
								//подключить инфоблок
								if(!\Bitrix\Main\Loader::includeModule("iblock")) {
									return;
								}
								//проверить, существует ли элемент в базе
								if(!\Bitrix\Iblock\ElementTable::getRowById($element_id)) {
									return new Entity\FieldError(
										$field,
										'Данного элемента нету в базе Инфоблоков'
									);
								}
								
								//Если существует первычный ключь, то 
								if(!$primary) {									
									$res = self::getList(array(
										'filter'=>array(
											'ELEMENT_ID'=>$row['ELEMENT_ID']
										),
									));
									if($res->fetch()) {
										return new Entity\FieldError(
											$field,
											'Данный элемент уже добавлен в базу'
										);
									}
									
								}
								
								return true;
							}
						);
					}
				)),
				//SORT
				new Entity\IntegerField('SORT', array(					 
					'required'=>true, //обязательное для заполнения
					'default_value'=>500, //значение по умолчанию
				)),				
				//WEBSITE_ID
				new Entity\IntegerField('WEBSITE_ID', array(
					'required'=>true, //обязательное для заполнения
				)),
				//WEBSITE
				new Entity\ReferenceField(
					'WEBSITE',
					'Tx\Post\ListWebSites',
					array('=this.WEBSITE_ID' => 'ref.ID')
				),
				//TYPE_ID
				new Entity\IntegerField('TYPE_ID', array(
					'required'=>true, //обязательное для заполнения
				)),
				//TYPE
				new Entity\ReferenceField(
					'TYPE',
					'Tx\Post\ListTypes',
					array('=this.TYPE_ID' => 'ref.ID')
				),				
				//PUBLISHED 
				new Entity\BooleanField('PUBLISHED', array(
					'values' => array('N', 'Y'),
					"default_value" => 'N'
				)),
				//ACTIVE 
				new Entity\BooleanField('ACTIVE', array(
					'values' => array('N', 'Y'),
					"default_value" => 'N'
				)),
				//DATE_ACTIVE_FROM				
				new Entity\DateTimeField('DATE_ACTIVE_FROM', array(
					'required'=>true, //обязательное для заполнения
					"default_value" => new \Bitrix\Main\Type\DateTime
				)),				
			);
		}
		/****************************************************
						ДОПОЛНИТЕЛЬНЫЕ ФУНКЦИИ
		 ***************************************************/
		//отметить элемент как опубликованный
		public static function published($elements, $site_id, $type_id) {
			foreach($elements as $element) {
				$element_id = $element['ID'];
				$res = self::getList(array(
					'select'=>array('ID'),
					'filter'=>array(
						'ELEMENT_ID'=>$element_id,
						'WEBSITE_ID'=>$site_id,
						'TYPE_ID'=>$type_id,
					),
				));
				while($item = $res->fetch()) {
					//отметить элемен как опубликованный
					self::update($item['ID'], array(
						'PUBLISHED'=>'Y'
					));					
				}
			}
		}
		 
		//получить список сайтов
		public static function get_websites_id() {
			$res = \Tx\Post\ListWebSitesTable::getList(array('order'=>array('SORT'=>'ASC')));
			while($element = $res->fetch()) {
				$elements_id[] = $element['ID'];
			}
			return $elements_id;
		}
		
		//получить максимальную сортировку
		public static function get_max_sort() {
			$sort = 100;
			//max sort
			$db_res = self::getList(array(
				'order'=>array('SORT'=>'DESC'),
				'select'=>array('SORT'),
			));
			if($res = $db_res->fetch()) {
				$sort = $res['SORT'] + 100;
			}
			return $sort;
		}
		//получить тип записи по инфоблоку
		public static function get_types_id_by_iblock_id($iblock_id) {
			$res = \Tx\Post\ListTypesTable::getList(array(
				'filter'=>array('IBLOCKS_ID'=>'%'.$iblock_id.'%')
			));			
			while($element = $res->fetch()) {
				$elements_id[] = $element['ID'];
			}
			return $elements_id;
		}
		
		//при добавление элемента
		public static function addNewElement($result) {
			
			$element_id = $result['ID'];
			$iblock_id = $result['IBLOCK_ID'];
			$active = $result['ACTIVE'];
			$active_from = $result['ACTIVE_FROM'];			
			$sort = $result['SORT'];
			
			//получить сайты для публикации
			$websites_id = self::get_websites_id();			
			//получить типы
			$types_id = self::get_types_id_by_iblock_id($iblock_id);	
			
			foreach($websites_id as $website_id) {
				foreach($types_id as $type_id) {									
					self::add(array(
						'ELEMENT_ID'=>$element_id,
						'SORT'=>$sort,
						'WEBSITE_ID'=>$website_id,
						'TYPE_ID'=>$type_id,
						'ACTIVE'=>$active,
						'DATE_ACTIVE_FROM'=>new \Bitrix\Main\Type\DateTime($active_from),
					));
				}
			}
		}
		//при изменение элемента
		public static function updateNewElement($result) {						
			$element_id = $result['ID'];
			$active = $result['ACTIVE'];
			$active_from = $result['ACTIVE_FROM'];			
			
			$fields = array();
			$fields['ACTIVE'] = $active;	
			$fields['SORT'] = $result['SORT'];	
			if($active_from) {
				$fields['DATE_ACTIVE_FROM'] = new \Bitrix\Main\Type\DateTime($active_from);
			}
			
			//отметить изменения активности и времени активности у элемента
			$res = self::getList(array(
				'filter'=>array('ELEMENT_ID'=>$element_id),
			));
			while($element = $res->fetch()) {
				self::update($element['ID'], $fields);
			}			
		}
		//при удалении элемента
		public static function deleteNewElement($result) {			
			$element_id = $result['ID'];
			
			$res = self::getList(array(
				'filter'=>array('ELEMENT_ID'=>$element_id),
			));
			while($element = $res->fetch()) {
				self::delete($element['ID']);
			}			
		}
	}
?>