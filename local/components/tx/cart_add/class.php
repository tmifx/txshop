<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	/**/
	CBitrixComponent::includeComponentClass("ng:cart");
	
	class CartAdd extends Cart
	{
		/* добавить товар
		 */
		public function add_item($item_id, $quantity) {
			global $APPLICATION;
			$user_id = $this->get_user_id(); //id пользователя			
			$items = $this->get_items($user_id); //список товаров у пользователя
			
			//записать товар в массив
			if(array_key_exists($item_id, $items)) {
				if($quantity) {
					$items[$item_id] = $quantity;
				}else {
					$items[$item_id] += 1;
				}
			}else {
				if($quantity) {
					$items[$item_id] = $quantity;
				}else {
					$items[$item_id] = 1;
				}
			}
			//переменные для куков
			$name_cookie = $user_id; //id пользователя
			$value_cookie = json_encode($items);
			$time_cookie = time() + Cart::TIME_COOKIE; //время жизни кэша
						
			//записать данные в куки
			$APPLICATION->set_cookie($name_cookie, $value_cookie, $time_cookie);
			
			//вернуть результат
			return $items;
		}
		
	}
?>
