<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	//echo '<pre>';print_r($arParams); echo '</pre>';
	if (!$USER->IsAuthorized()) {
		echo 'Авторизуйтесь на сайте';
		return;
	}
	
	$element_id = $_REQUEST['id'];		
	$user_id = $USER->GetID();
	
	
	//выйти если нету одного из двух значений
	if(!$element_id || !$user_id) {
		echo('Неверное значение ID Element или User ID');
		return false;
	}
	
	//подключить модули
	if (! CModule::IncludeModule('iblock'))
		return false;
	if (! CModule::IncludeModule('highloadblock'))
		return;
		
	//проверить, существует ли элемент
	$res = CIBlockElement::GetByID($element_id);
	if(!$element = $res->GetNext()) {
		echo('Неверный ID Element');
		return false;
	}else {
		$db_props  = CIBlockElement::GetProperty(
			$element['IBLOCK_ID'], 
			$element['ID'],
			array(),
			array('CODE'=>'SECTIONS')
		);
		while($ar_props = $db_props->Fetch()) {
			$element['SECTIONS_ID'][] = $ar_props['VALUE'];			
		}
	}
	
	//добавить элемент в избранное
	$rsData = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
		'filter'=>array('NAME'=>'UsersFavorites')
	));
	if($hlbl = $rsData->Fetch()) {
		$hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl['ID'])->fetch();
		$entity =  Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
		$entity_data_class = $entity->getDataClass();	
		
		$rsData = $entity_data_class::getList(array(
				"select" => array('*'),
				"filter"=>array(
					'UF_ELEMENT_ID' => $element['ID'],
					'UF_USER_ID' => $user_id
					)));
		$rsData = new CDBResult($rsData);
		
		//данные
		$data = array(
			"UF_ELEMENT_ID"=>$element['ID'],
			"UF_USER_ID"=>$user_id,
		);
		
		if(!$arRes = $rsData->Fetch()) {
			$DB->StartTransaction();
			if($entity_data_class::add($data)) {
				$DB->Commit();	
			}else {
				$DB->Rollback();
			}
			
			//добавить в статистику 
			$this->addStatsFavoriteElement($element['ID'], $element['SECTIONS_ID']);
			
			echo json_encode(array(
				'text'=>'Из избранных'
			));
		}else {
			$DB->StartTransaction();
			if($entity_data_class::delete($arRes['ID'])) {
				$DB->Commit();	
			}else {
				$DB->Rollback();
			}
			
			//удалить из статистики
			$this->removeStatsFavoriteElement($element['ID'], $element['SECTIONS_ID']);
			
			echo json_encode(array(
				'text'=>'В избранные'
			));
		}
	}
	
	
?>
