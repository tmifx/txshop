<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	CBitrixComponent::includeComponentClass("classes:base");
	abstract class Session extends Base {
		//корень сессий
		const ROOT = 'TX';
		const TIME_ACTIVE = 3600; //время актуальности сессии
		
		//список констант
		const VISITED_SITE_USER = 'STATS_VISITED_SITE_USER'; //время посещения сайта
		const VIEW_ELEMENT = 'STATS_VIEW_ELEMENT'; //просмотр элемента
		const SHARE_ELEMENT = 'STATS_SHARE_ELEMENT'; //поделились элементом
		const VIEW_SECTION = 'STATS_VIEW_SECTION'; //просмотр секций
		const COMPLETED_TASKS = 'COMPLETED_TASKS'; //завершение заданий
		
		//записать в сессию
		public function setSession($key, $value, $value_as_key = false) {
			$time_start = time();
			if(!$value_as_key) {
				$_SESSION[self::ROOT][$key] = array(
						'value'=>$value,
						'time_start'=>$time_start,
					);
			}else {
				$_SESSION[self::ROOT][$key][$value] = array('time_start'=>$time_start, 'value'=>true);
			}
		}		
		
		//получить из сессии с учетом времени
		public function getSession($key, $value) {
			$time_end = time()-self::TIME_ACTIVE;
			if(!$value) {
				$res = $_SESSION[self::ROOT][$key];
				if($res['time_start'] > $time_end) {
					return $res['value'];
				}else {
					return false;
				}
			}else {
				$res = $_SESSION[self::ROOT][$key][$value];
				if($res['time_start'] > $time_end) {
					return $res['value'];
				}else {
					return false;
				}
				
			}
		}
		
		//очистить сессию
		public function clearSession($key) {
			if($key) {
				$_SESSION[self::ROOT][$key] = null;
			}else {
				$_SESSION[self::ROOT] = null;
			}
		}
	}
?>