<?php
	namespace Tx\Post;
	
	use \Tx\Post\ListFunctionsTable;
	use \Tx\Post\ListWebSitesTable;
	use \Tx\Post\ListNewTable;
	
	/* Базовый класс для сайтов
	 */
	class BaseSite{
		var $website_id;
		var $type_id;
		var $module_id = 'tx.post';
		
		const STEP_DATE = '3600'; //шаг между подряд идущими постами
		
		function __construct($function_id) {
			$function =	$this->get_function_by_id($function_id);			
			$this->website_id = $function['WEBSITE_ID'];
			$this->type_id = $function['TYPE_ID'];
		}
		
		
		/*****************************************************************
							ОБЩИЕ ФУНКЦИИ ПО ВЫГРУЗКИ
		 *****************************************************************/
		/* Получить список ссылок превью фотографий у элементов
		 */
		public function getPreviewPicturesSrc($elements) {
			$arSrc = array();
			foreach($elements as $element) {
				$arSrc[$element['ID']] = $element['PREVIEW_PICTURE']['SRC'];
			}
			return $arSrc;
		}
		
		/* Получить новые элементы
		 */
		public function getNewElements() {
			$res = ListNewTable::getList(array(
				'order'=>array('SORT'=>'ASC'),
				'filter'=>array(
					'TYPE_ID'=>$this->type_id,
					'WEBSITE_ID'=>$this->website_id,
					'PUBLISHED'=>'N',
					'ACTIVE'=>'Y',
					'<=DATE_ACTIVE_FROM' => new \Bitrix\Main\Type\DateTime(),
				)
			));
			while($element = $res->fetch()) {
				$elements_id[] = $element['ELEMENT_ID'];
			}			
			
			/* Получить элементы
			 */
			if($elements_id) {
				$arSelect = array('ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'IBLOCK_ID');
				$arOrder = array("SORT"=>"DESC", 'ID'=>'DESC');
				$arFilter = array(
					"ID"=>$elements_id,
				);			
				$list_elements = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);		
				while($element = $list_elements->GetNext()) {
					//получить свойства
					$db_props  = \CIBlockElement::GetProperty(
						$element['IBLOCK_ID'], 
						$element['ID'],
						array(),
						array('CODE'=>'NAME_RU')
					);
					while($ar_props = $db_props->Fetch()) {
						if($ar_props['MULTIPLE'] == 'Y') {
							$element['PROPERTY'][$ar_props['CODE']][] = $ar_props;
						}else {
							$element['PROPERTY'][$ar_props['CODE']] = $ar_props;
						}
					}
					
					if($element['PREVIEW_PICTURE']) {
						$element['PREVIEW_PICTURE'] = \CFile::GetFileArray($element['PREVIEW_PICTURE']);
					}
					
					$elements[] = $element;
				}
				
				$element_sort = array();
				foreach($elements_id as $element_id) {
					foreach($elements as $element) {
						if($element['ID'] == $element_id) {
							$element_sort[] = $element;
							break;
						}
					}
				}
			}
			return $element_sort;
		}
		 
		/*****************************************************************
								ОБЩИЕ ФУНКЦИИ
		 *****************************************************************/	
		/* Отправка файлов
		 */
		function sendFile($url, $file) {			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 600);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
			curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false); 
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => '@'.$_SERVER["DOCUMENT_ROOT"].$file));
			
			$result = curl_exec($ch);
			$error = curl_error($ch);
			
			echo "<pre>"; print_r($result); echo "</pre>";
			curl_close($ch);
						
			return $result;
		}
		/* Отправка запроса
		 */
		function sendPostRequest($url, $data) {			
			$ch = curl_init(); // инициализируем сессию curl
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 600);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //не проверять сертефикат значение 0
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			
			//добавить значения
			/**/
			$data_str = '';
			foreach($data as $k => $v) {
				if($data_str != '') $data_str .= '&';
				$data_str .= urlencode($k).'='.urlencode($v);
			}
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
			$result = curl_exec($ch);
			curl_close($ch);
			
			return $result;
		}
		
		//получить id сайта по функции
		function get_function_by_id($func_id) {
			$db_res = ListFunctionsTable::getList(array(
				'filter'=>array('ID'=>$func_id)
				)
			);
			if($res = $db_res->fetch()) {
				return $res;				
			}
			
			return false;
		}		
	}
?>