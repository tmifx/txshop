<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	class Base extends CBitrixComponent
	{			
		//получить время из числа
		public function getTimeFromInt($int) {
			if($int <= 0)
				return '00:00:00';
			
			$hours = floor($int/3600);
			$minutes = floor(($int - $hours * 3600) / 60);
			$seconds = $int - ($hours * 3600) - ($minutes * 60);
						
			if($hours < 10) $hours = '0'.$hours;
			if($minutes < 10) $minutes = '0'.$minutes;
			if($seconds < 10) $seconds = '0'.$seconds;
			
			return $hours.':'.$minutes.':'.$seconds;
		}
		
		public function error404() {
			global $APPLICATION;
			
			ShowError('К сожалению, запрошенная страница на сервере не найдена. =(');
			@define("ERROR_404", "Y");
			CHTTP::SetStatus("404 Not Found");			
			$APPLICATION->SetTitle("404 Not Found");
		}
		//получить картинку
		public function getPicture($picture_id, $max_height, $max_width) {
			if($picture_id) {			
				$file = CFile::GetFileArray($picture_id);
				if($file['HEIGHT'] == $max_height && $file['WIDTH'] == $max_width) {					-
					--$max_width;					
				}				
				return CFile::ResizeImageGet($file, array('width'=>$max_width, 'height'=>$max_height), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true, false, false, 75);
			}
			return;
		}
		/* Переместить к списку
		 */
		public function redirectToList($dir) {			
			if(!$this->arParams['SEF_FOLDER']) 
				return;
				
			global $APPLICATION;			
			if($APPLICATION->GetCurPage() == $this->arParams['SEF_FOLDER']) {
				LocalRedirect($dir, false, "301 Moved permanently");
				die;
			}
		}
		
		/* Получить символьный код по имени
		 */
		public function getCodeByName($name, $language) {
			$arTransParams = array(
			   "max_len" => 100,
			   "change_case" => 'L', // 'L' - toLower, 'U' - toUpper, false - do not change
			   "replace_space" => '-',
			   "replace_other" => '-',
			   "delete_repeat_replace" => true
			);			
			return CUtil::translit($name, $language, $arTransParams);
		}
	}
?>
