<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	//echo '<pre>';print_r($arParams); echo '</pre>';
	
	$APPLICATION->AddChainItem('Корзина');
	
	//подключить модуль инфоблока
	if (! CModule::IncludeModule('iblock'))
		return;	
	
	//получить список элементов
	$user_id = $this->get_user_id();
	//список элементова 
	$items = $this->get_items($user_id); 
	
	//если нету элементов, вывести пустую корзину
	if(count($items) == 0) {
		$arParams['STAGE'] = 1;
		$this->IncludeComponentTemplate();//вывести тему
		return;
	}	
	
	//получить заказы
	$arResult['ELEMENTS'] = $this->get_items_in_catr($arParams, $items);
	
	//сумма денег
	$arResult['TOTAL_PRICE'] = $this->calc_sum_price_items($items); //обьщая сумма денег
		
	$this->IncludeComponentTemplate();//вывести тему	
?>
