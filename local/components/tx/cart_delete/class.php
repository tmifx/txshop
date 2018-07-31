<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	/**/
	CBitrixComponent::includeComponentClass("ng:cart");
	
	class CartDelete extends Cart
	{	
		public function delete_item($item_id) {
			global $APPLICATION;
			$user_id = $this->get_user_id(); //id пользователя			
			$items = $this->get_items($user_id); //список товаров у пользователя
			
			//удалить товар из массива
			unset($items[$item_id]);
			
			//переменные для куков
			$name_cookie = $user_id; //id пользователя
			$value_cookie = json_encode($items);
			$time_cookie = time() + Cart::TIME_COOKIE; //время жизни кэша
						
			//записать данные в базу
			$APPLICATION->set_cookie($name_cookie, $value_cookie, $time_cookie);
			
			//вернуть результат
			return $items;
		}
	}
?>
