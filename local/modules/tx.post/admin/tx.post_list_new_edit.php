<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use \Bitrix\Main\Loader;
use \Bitrix\Main\Type;
use \TX\Post\ListNewTable as CurClass;

//name module
$MODULE_ID = 'tx.post';

//Class File Name List
$CLASS_FILE_NAME_LIST = 'list_new';

//checking
$POST_RIGHT = $APPLICATION->GetGroupRight($MODULE_ID);
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

//add js lib
$APPLICATION->AddHeadScript('/bitrix/js/iblock/iblock_edit.js');

//load module
if(!Loader::includeModule($MODULE_ID)) {
	return;
}
if(!Loader::includeModule("iblock")) {
	return;
}

//add tabs
$aTabs = array(
	array("DIV" => "edit1", "TAB" => 'Элемент', "ICON"=>"main_user_edit", "TITLE"=>'Элемент'),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

// ******************************************************************** //
//                			PROCESSING FORM                            	//
// ******************************************************************** //
if(
    $REQUEST_METHOD == "POST" // Checking method    
    &&
    $POST_RIGHT=="W"          // Check write permissions
    &&
    check_bitrix_sessid()     // Check session
)
{
	/* Подготовить переменные
	 */
	$_REQUEST['ELEMENT_ID'] = intval($_REQUEST['ELEMENT_ID']);
	$_REQUEST['WEBSITE_ID'] = intval($_REQUEST['WEBSITE_ID']);
	
	//получить элепент по id
	if($_REQUEST['ELEMENT_ID']) {			
		$res = CIBlockElement::GetByID($_REQUEST['ELEMENT_ID']);		
		$element = $res->GetNext();		
	}
	/* Add Or Update
	 */
	if(!$_REQUEST['ID']) {				
		$result = CurClass::add(array(
			'ELEMENT_ID'=>$_REQUEST['ELEMENT_ID']?$_REQUEST['ELEMENT_ID']:NULL,
			'SORT'=>$element['SORT']?$element['SORT']:$_REQUEST['SORT'],
			'WEBSITE_ID'=>$_REQUEST['WEBSITE_ID'],
			'TYPE_ID'=>$_REQUEST['TYPE_ID'],
			'PUBLISHED'=>$_REQUEST['PUBLISHED']=='Y'?'Y':'N',
			'ACTIVE'=>$_REQUEST['ACTIVE']=='Y'?'Y':'N',
			'DATE_ACTIVE_FROM'=>new \Bitrix\Main\Type\DateTime($_REQUEST['DATE_ACTIVE_FROM']),			
		));
		if(!$result->isSuccess()) {
			$errors = $result->getErrorMessages();
		}else {
			$ID = $result->getId();			
		}		
	}else{
		$result = CurClass::update($_REQUEST['ID'], array(
			'ELEMENT_ID'=>$_REQUEST['ELEMENT_ID']?$_REQUEST['ELEMENT_ID']:NULL,
			'SORT'=>$element['SORT']?$element['SORT']:$_REQUEST['SORT'],
			'WEBSITE_ID'=>$_REQUEST['WEBSITE_ID'],
			'PUBLISHED'=>$_REQUEST['PUBLISHED']=='Y'?'Y':'N',
			'ACTIVE'=>$_REQUEST['ACTIVE']=='Y'?'Y':'N',
			'DATE_ACTIVE_FROM'=>new \Bitrix\Main\Type\DateTime($_REQUEST['DATE_ACTIVE_FROM']),
		));
		if(!$result->isSuccess()) {
			$errors = $result->getErrorMessages();
		}
	}
}

// ******************************************************************** //
//                SELECTION AND PREPARATION OF DATA FORMS               //
// ******************************************************************** //
//default values
$str_ELEMENT_ID = '';
$str_SORT = '100';
$str_WEBSITE_ID = '';
$str_TYPE_ID_ID = '';
$str_PUBLISHED = '';
$str_DATE_ACTIVE_FROM = '';

//max sort
$db_res = CurClass::getList(array(
	'order'=>array('SORT'=>'DESC'),
	'select'=>array('SORT'),
));
if($res = $db_res->fetch()) {
	$str_SORT = $res['SORT'] + 100;
}

//load values
if($ID>0) {	
	$result = CurClass::GetById($ID);
	$fields = $result->fetch();
	foreach($fields as $key=>$value) {		
		$key = "str_".$key;
		$$key = $value;
	}	
}

//список сайтов
$result = TX\Post\ListWebSitesTable::getList();
while($element = $result->fetch()) {
	$arResult['SITES'][] = $element;	
}

//список типов данных
$result = TX\Post\ListTypesTable::getList();
while($element = $result->fetch()) {
	$arResult['TYPES'][] = $element;	
}

/**********************************************************************
							SET TITLE
**********************************************************************/
$APPLICATION->SetTitle('Редактировать');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
// ******************************************************************** //
//              		 SHOW FORM                                      //
// ******************************************************************** //
$aMenu = array(
  array(
    "TEXT"=>'Назад',
    "TITLE"=>'Назад',
    "LINK"=>$MODULE_ID."_".$CLASS_FILE_NAME_LIST.'.php',
    "ICON"=>"btn_list",
  ),
);

// create menu
$context = new CAdminContextMenu($aMenu);

// show the administrative menu
$context->Show();

?>
<form method="POST" Action="<?=$APPLICATION->GetCurPage()?>?ID=<?=$ID?>" ENCTYPE="multipart/form-data" name="post_form">
<?=bitrix_sessid_post()?>
<?
	//
	if($errors) {
		CAdminMessage::ShowMessage(implode(chr(13),$errors));
	}
?>
<?	
	$tabControl->Begin();

	$tabControl->BeginNextTab();
?>
<tr>
	<?if($ID):?>
		<td width="40%" class='adm-detail-content-cell-l'>
			<span class='adm-required-field'>ID:</span>
		</td>
		<td width="60%" class='adm-detail-content-cell-r'>			
			<?=$ID?>
		</td>
	<?endif?>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>ID элемента</span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<input type='text' name='ELEMENT_ID' value='<?=$str_ELEMENT_ID?>' style='width: 250px'>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>Сортировка<span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<input readonly type='text' name='SORT' value='<?=$str_SORT?>' style='width: 250px'>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>Сайт</span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<select name='WEBSITE_ID' style='width: 250px'>
			<?foreach($arResult['SITES'] as $element):?>
				<option value = '<?=$element['ID']?>'><?=$element['DOMAIN']?></option>
			<?endforeach?>
		</select>		
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>Типы</span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<select name='TYPE_ID' style='width: 250px'>
			<?foreach($arResult['TYPES'] as $element):?>
				<option value = '<?=$element['ID']?>'><?=$element['TYPE']?></option>
			<?endforeach?>
		</select>		
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		Опубликовано
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<input type='checkbox' name='PUBLISHED' value='Y' <?=$str_PUBLISHED=='Y'?'checked':''?>>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		Активность элемента
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<input type='checkbox' name='ACTIVE' value='Y' <?=$str_ACTIVE=='Y'?'checked':''?>>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		Дата и Время начала активности
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>		
		<input type="text" value="<?=$str_DATE_ACTIVE_FROM?>" name="DATE_ACTIVE_FROM" onclick="BX.calendar({node: this, field: this, bTime: true});">
	</td>
</tr>
<?
	$tabControl->Buttons();
?>
	<input class="adm-btn-save" type="submit" value="Сохранить" />
<?
	$tabControl->End();
?>
<?
	// show warnings
	$tabControl->ShowWarnings("post_form", $message);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>