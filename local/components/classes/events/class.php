<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	CBitrixComponent::includeComponentClass("classes:session");
	class Events extends Session {
		/* Массив всех событий
		 */
		public static $list_events = array();
		
		/* Константы событий, которые делает пользователь
		 */		
		const VISIT_24 			= 'VISIT_24'; 		//событие посещения		
		const VIEW_ELEMENT 		= 'VIEW_ELEMENT'; 	//событие просмотра элемента
		const VIEW_SECTION 		= 'VIEW_SECTION'; 	//событие просмотра раздела
		const ADD_FAVORITE 		= 'ADD_FAVORITE'; 	//событие добавление в избранные
		const DEL_FAVORITE 		= 'DEL_FAVORITE'; 	//событие удаление из избранных
		const ADD_SHARE 		= 'ADD_SHARE';		//событие срабатывает когда пользователь поделился элементом
		const TASK_DONE 		= 'TASK_DONE';		//событие срабатывает когда выполняеться задание
		const ADD_TREASURES 	= 'ADD_TREASURES';	//событие срабатывает когда добавляються сокровища
		
		
		//Добавить прослушивателя событий
		public function addEventListener($event_key, $function) {
			self::$list_events[$event_key][] = $function;
		}
		
		/* Регистрация события
		 */
		public function regEvent($event_key, $value, $value_2) {
			if(self::$list_events[$event_key]) {
				//получить список функций
				$list_function = self::$list_events[$event_key];
				foreach($list_function as $function) {
					//запустить функцию
					$function($value, $value_2);
				}
			}
		}		
	}
?>