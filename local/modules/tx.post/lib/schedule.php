<?php
	namespace Tx\Post;
	
	use \Bitrix\Main\Entity;
	use \TX\Post\ListFunctionsTable;
	
	class ScheduleTable extends Entity\DataManager
	{
		public static function getTableName() 		
		{
			return 'tx_post_schedule';
		}		
		public static function getMap() 
		{
			return array(				
				//ID
				new Entity\IntegerField('ID', array(
					'primary'=> true,
					'autocomplete'=>true,
				)),
				//ACTIVE 
				new Entity\BooleanField('ACTIVE', array(
					'values' => array('N', 'Y'),
					"default_value" => 'Y'
				)),
				//SORT
				new Entity\IntegerField('SORT', array(
					'required'=>true, //обязательное для заполнения
					'default_value'=>500, //значение по умолчанию
				)),
				//DAY_WEEK
				new Entity\IntegerField('DAY_WEEK', array(					 
					'required'=>true, //обязательное для заполнения
					'default_value'=>1,
					'validation'=>function()
					{
						return array(
							function($value, $primary, $row, $field) {
								if($value >= 1 && $value <=7) {
									return true;
								}else {									
									return new Entity\FieldError(
										$field, 
										'Значение дня недени может быть только от 1 до 7'
									);
								}
							}
						);
					}					
				)),
				//HOUR
				new Entity\IntegerField('HOUR', array(					 
					'required'=>true, //обязательное для заполнения
					'default_value'=>7,
					'validation'=>function()
					{
						return array(
							function($value, $primary, $row, $field) {
								if($value >= 0 && $value <= 23) {
									return true;
								}else {									
									return new Entity\FieldError(
										$field, 
										'Значение значение часа должно быть от 0 до 23'
									);
								}
							}
						);
					}
				)),
				//MINUTES
				new Entity\IntegerField('MINUTES', array(					 
					'required'=>true, //обязательное для заполнения
					'default_value'=>0,
					'validation'=>function()
					{
						return array(
							function($value, $primary, $row, $field) {
								if($value >= 0  && $value <= 59) {
									return true;
								}else {									
									return new Entity\FieldError(
										$field, 
										'Значение минут должно быть от 0 до 59'
									);
								}
							}
						);
					}
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
				//FUNCTION_ID
				new Entity\IntegerField('FUNCTION_ID', array(
					'required'=>true, //обязательное для заполнения
				)),	
				//FUNCTION
				new Entity\ReferenceField(
					'FUNCTION',
					'Tx\Post\ListFunctions',
					array('=this.FUNCTION_ID' => 'ref.ID')
				),
				//LAST_RUN_TIME
				new Entity\DateTimeField('LAST_RUN_TIME', array()),
			);
		}
		//запуск функциий из расписания
		public static function runFunctions() {			
			//получить элементы расписания, функции которых нужно запустить
			$res = self::getList(array(				
				'order'=>array(
					'DAY_WEEK'=>'ASC',
					'HOUR'=>'ASC',
					'MINUTES'=>'ASC',
					'SORT'=>'ASC',
				),
				'filter'=>array(
					'ACTIVE'=>'Y',
					'DAY_WEEK'=>intval(date('w')>0?date('w'):7),					
					array(
						'LOGIC'=>'OR',						
						array(
							'HOUR'=>intval(date('G')),
							'<=MINUTES'=>intval(date('i'))
						),
						array(
							'<HOUR'=>intval(date('G'))
						)
					),
					'<=LAST_RUN_TIME' => new \Bitrix\Main\Type\DateTime(ConvertTimeStamp(time()-86400, 'FULL'))
				),
			));
			while($item = $res->fetch()) {
				//запустить функцию
				self::run_function_by_schedule_id($item['ID']);					
			}
			
			return 'TX\Post\ScheduleTable::runFunctions();';
		}
		
		
		//запуск функции по id расписания
		public static function run_function_by_schedule_id($schedule_id) {				
			//получить расписание
			$schedule = self::getRowById($schedule_id);
			
			//получить функцию
			if($schedule['FUNCTION_ID']) {				
				$function = ListFunctionsTable::getRowById($schedule['FUNCTION_ID']);				
				
				if($function) {
					$func_id = $function['ID'];
					$class_name = $function['CLASS_NAME'];
					$func_name = $function['FUNCTION_NAME'];
					
					//создать класс, с передачей id 
					$obj = new $class_name($func_id);
					
					//запустить функцию
					$obj->$func_name();
					
					//записать время запуска функции
					self::update($schedule_id, array(
						'LAST_RUN_TIME'=>new \Bitrix\Main\Type\DateTime,
					));
				}
			}
		}
	}
?>