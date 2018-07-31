<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	//echo '<pre>';print_r($arParams); echo '</pre>';
		
	//подключить модуль инфоблока
	if (! CModule::IncludeModule('iblock'))
		return;	
	
	//добавить товар и вернуть массив
	$items = $this->add_item($arParams['ITEM_ID'], $arParams['QUANTITY']);
	
	$arResult['NUMBER_ITEMS'] = $this->calc_number_items($items); //количество товаров
	$arResult['SUM_PRICE_ITEMS'] = $this->calc_sum_price_items($items); //обьщая сумма денег
	
	
	/* Найти элемент
	 */	 
	$result = array();
	$result['count'] = $arResult['NUMBER_ITEMS'];
	$result['total'] = $arResult['SUM_PRICE_ITEMS'];
	$result['price'] = $this->calc_sum_price_items(array($arParams['ITEM_ID']=>$items[$arParams['ITEM_ID']])).' руб.';
	if($result['price'] == 0) {
		$result['price'] = 'Договорная';
	}
	
	echo json_encode($result);
?>
