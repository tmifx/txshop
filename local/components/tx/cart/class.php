<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	class Cart extends CBitrixComponent
	{		
		const TIME_COOKIE = 86400;
		
		
		/* сбросить карзину
		 */
		public function reset_cart() {
			global $APPLICATION;
			//переменные для куков
			$name_cookie = $this->get_user_id(); //id пользователя
			$value_cookie = json_encode(array());
			$time_cookie = time();
						
			//записать данные в куки
			$APPLICATION->set_cookie($name_cookie, $value_cookie, $time_cookie);
		}
		
		/* получить товары в корзине
		 */
		public function get_items_in_catr(&$arParams, &$items) {
			$result = array();
			
			if(! count($items)) {
				return $result;
			}
			//получить список всех товаров в корзине
			$arSelect = array('ID','NAME','PREVIEW_PICTURE','PROPERTY_PRICE','PROPERTY_NESTING','PROPERTY_DIMENSIONS','DETAIL_PAGE_URL');
			$arOrder = array("SORT"=>"ASC");
			$arFilter = array(
				"IBLOCK_TYPE"=>$arParams['IBLOCK_TYPE'],
				"IBLOCK_ID"=>$arParams['IBLOCK_ID'],
				'ID' => $this->get_items_id($items),
				);
			$list_elements = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
			
			while($element = $list_elements->GetNext()) {
				//количество товара
				$element['NUMBER'] = $this->get_number_item($items, $element['ID']);
				
				//получить кортинку если есть
				if($element['PREVIEW_PICTURE']) {
					$file = CFile::GetFileArray($element['PREVIEW_PICTURE']);
					$file = CFile::ResizeImageGet($file, array('width'=>150, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					
					$ext = explode('.',$file['FILE_NAME']);
					$ext = $ext[count($ext)-1];
					$file['EXT'] = $ext;
					$file['FILE_SIZE'] = CFile::FormatSize($file['FILE_SIZE']);
					
					$element['PREVIEW_PICTURE'] = $file;
				}
				$element['PROPERTY_PRICE_VALUE'] = $this->get_price_in_str($element['PROPERTY_PRICE_VALUE']);
				
				$result[] = $element; 
			}
			return $result;
		}
		/* получить цену в строке
		 */
		public function get_price_in_str($str) {			
			return floatval($str);
		}
		
		/* получить список id шников товаров
		 */
		public function get_items_id($items) {
			$items_id = array();
			foreach($items as $id=>$number) {
				$items_id[] = $id;
			}
			return $items_id;
		}
		/* получить количество товара
		 */
		public function get_number_item($items, $item_id) {
			$number = 0;			
			foreach($items as $id=>$number) {
				if($item_id == $id) {
					return $number;
					break;
				}
			}			
			return $number;
		}
		/* получить id пользователя
		 */
		public function get_user_id() {
			return $_COOKIE['BX_USER_ID'];
		}
		
		/* получить товары
		 */
		public function get_items($user_id) {
			global $APPLICATION;
			return json_decode($APPLICATION->get_cookie($user_id), true);
		}

		//посчитать общее количество товаров
		public function calc_number_items($items) {
			$sum = 0;
			foreach($items as $id=>$number) {
				$sum += $number;
			}
			return $sum;
		}
		
		//получить сумму денег всех товаров
		public function calc_sum_price_items($items) {
			$price = 0;
			$items_id = $this->get_items_id($items);
			
			if(!$items_id) {
				return $price;
			}
			
			//получить список всех товаров
			$arSelect = array('ID','PROPERTY_PRICE');
			$arOrder = array("SORT"=>"ASC");
			$arFilter = array(
				"IBLOCK_TYPE"=>$arParams['IBLOCK_TYPE'],
				"IBLOCK_ID"=>$arParams['IBLOCK_ID'],
				'ID' => $items_id
				);
			$list_elements = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
			while($element = $list_elements->GetNext()) {
				$number = $this->get_number_item($items, $element['ID']);				
				$price += $this->get_price_in_str($element['PROPERTY_PRICE_VALUE']) * $number;
			}
			return $price;
		}	
		
	}
?>
