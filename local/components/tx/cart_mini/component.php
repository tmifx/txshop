<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	//echo '<pre>';print_r($arParams); echo '</pre>';
	
	$user_id = $this->get_user_id(); //id пользователя		
	$items = $this->get_items($user_id); //список товаров у пользователя
	$arResult['NUMBER_ITEMS'] = $this->calc_number_items($items); //количество товаров
	if($arResult['NUMBER_ITEMS']) {
		$arResult['SUM_PRICE_ITEMS'] = $this->calc_sum_price_items($items); //обьщая сумма денег
	}else {
		$arResult['SUM_PRICE_ITEMS'] = 0;
	}
	$this->IncludeComponentTemplate();//вывести тему	
?>