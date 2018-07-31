<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use \Bitrix\Main\Loader;
use \TX\Post\ScheduleTable as CurClass;

//name module
$MODULE_ID = 'tx.post';

//Class File Name List
$CLASS_FILE_NAME_LIST = 'schedule';

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


/* Получить данные
 */
$db_res = \TX\Post\ListWebSitesTable::getList(
	array(
		'order'=>array('SORT'=>'ASC'),
	)
);
while($el = $db_res->fetch()) {
	$arResult['SITES'][] = $el;
}

$db_res = \TX\Post\ListFunctionsTable::getList(
	array(
		'order'=>array('SORT'=>'DESC'),		
	)
);
while($el = $db_res->fetch()) {
	$arResult['FUNCTIONS'][] = $el;
}


//add tabs
$aTabs = array(
	array("DIV" => "edit1", "TAB" => 'Расписание', "ICON"=>"main_user_edit", "TITLE"=>'Расписание'),
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
	/* Add Or Update
	 */
	if(!$_REQUEST['ID']) {
		$result = CurClass::add(array(
			'ACTIVE'=>$_REQUEST['ACTIVE']?'Y':'N',
			'SORT'=>$_REQUEST['SORT'],
			'DAY_WEEK'=>$_REQUEST['DAY_WEEK'],
			'HOUR'=>$_REQUEST['HOUR'],
			'MINUTES'=>$_REQUEST['MINUTES'],
			'WEBSITE_ID'=>$_REQUEST['WEBSITE_ID'],
			'FUNCTION_ID'=>$_REQUEST['FUNCTION_ID'],
		));
		if(!$result->isSuccess()) {
			$errors = $result->getErrorMessages();
		}else {
			$ID = $result->getId();
		}
	}else{
		$fields = array(
			'ACTIVE'=>$_REQUEST['ACTIVE']?'Y':'N',
			'SORT'=>$_REQUEST['SORT'],
			'DAY_WEEK'=>$_REQUEST['DAY_WEEK'],
			'HOUR'=>$_REQUEST['HOUR'],
			'MINUTES'=>$_REQUEST['MINUTES'],
			'WEBSITE_ID'=>$_REQUEST['WEBSITE_ID'],
			'FUNCTION_ID'=>$_REQUEST['FUNCTION_ID'],
		);
		if($_REQUEST['LAST_RUN_TIME_NULL']) {			
			$fields['LAST_RUN_TIME'] = new \Bitrix\Main\Type\DateTime('00.00.0000 00:00:00');
		}
		$result = CurClass::update($_REQUEST['ID'], $fields);
		if(!$result->isSuccess()) {
			$errors = $result->getErrorMessages();
		}
	}
}

// ******************************************************************** //
//                SELECTION AND PREPARATION OF DATA FORMS               //
// ******************************************************************** //
//default values
$str_SORT = '100';
$str_DAY_WEEK = '';
$str_HOUR = '';
$str_MINUTES = '';
$str_WEBSITE_ID = '';
$str_FUNCTION_ID = '';
$str_LAST_RUN_TIME = '';

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

/**********************************************************************
							SET TITLE
**********************************************************************/
if($ID) {
	$APPLICATION->SetTitle('Редактировать расписание');
}else {
	$APPLICATION->SetTitle('Добавить расписание');
}


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
		Активность
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<input type='checkbox' name='ACTIVE' value='Y' <?=$str_ACTIVE=='Y' || !$str_ACTIVE?'checked':''?>>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>Сортировка:<span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<input type='text' name='SORT' value='<?=$str_SORT?>'>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>День недели:<span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<select name = 'DAY_WEEK' >
			<option <?=$str_DAY_WEEK == '1'?'selected':''?> value='1'>Понедельник</option>			
			<option <?=$str_DAY_WEEK == '2'?'selected':''?> value='2'>Вторник</option>			
			<option <?=$str_DAY_WEEK == '3'?'selected':''?> value='3'>Среда</option>			
			<option <?=$str_DAY_WEEK == '4'?'selected':''?> value='4'>Четверг</option>			
			<option <?=$str_DAY_WEEK == '5'?'selected':''?> value='5'>Пятница</option>			
			<option <?=$str_DAY_WEEK == '6'?'selected':''?> value='6'>Суббота</option>			
			<option <?=$str_DAY_WEEK == '7'?'selected':''?> value='7'>Воскресенье</option>			
		</select>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>В какой час:<span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<select name = 'HOUR'>
			<?$start_time = 7?>
			<?for($i=$start_time; $i<24; $i++):?>				
				<option <?=($str_HOUR == $i) || ($str_HOUR == NULL && $i==$start_time)?'selected':''?>><?=$i>9?$i:'0'.$i?></option>
			<?endfor?>
			<?for($i=0; $i<$start_time; $i++):?>
				<option <?=$str_HOUR == $i && $str_HOUR != NULL?'selected':''?>>0<?=$i?></option>
			<?endfor?>
		</select>		
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>Во сколько минут:<span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<select name = 'MINUTES'>
			<?for($i=0; $i<60; $i++):?>
				<option <?=$str_MINUTES == $i?'selected':''?>><?=$i>9?$i:'0'.$i?></option>
			<?endfor?>
		</select>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>Сайт:<span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<select name = 'WEBSITE_ID' id = 'WEBSITE_ID' onchange='change_site()'>
			<?foreach($arResult['SITES'] as $element):?>
				<option <?=$str_WEBSITE_ID == $element['ID']?'selected':''?> value='<?=$element['ID']?>'><?=$element['DOMAIN']?></option>
			<?endforeach?>			
		</select>		
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>Функция:<span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>		
		<select name = 'FUNCTION_ID' id='FUNCTION_ID'>
			<?foreach($arResult['SITES'] as $site):?>
				<?foreach($arResult['FUNCTIONS'] as $element):?>
					<option <?=$element['WEBSITE_ID']!=$site['ID']?'disabled':''?> data-website_id='<?=$element['WEBSITE_ID']?>' <?=$str_FUNCTION_ID == $element['ID']?'selected':''?> value='<?=$element['ID']?>'><?=$element['DESCRIPTION']?></option>
				<?endforeach?>
				<?break?>
			<?endforeach?>			
		</select>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		Последнее время запуска:
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<?=$str_LAST_RUN_TIME?$str_LAST_RUN_TIME:''?>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		Сбросить время последнего запуска:
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<input type='checkbox' name='LAST_RUN_TIME_NULL' value='Y'>
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
<script>
	function change_site() {
		for(var i = 0; i < post_form.WEBSITE_ID.options.length; i++) {
			var option = post_form.WEBSITE_ID.options[i];
			if(option.selected == true) {
				var site_id = option.value;				
			}
		}
		//включить выключить
		for(var i = 0; i < post_form.FUNCTION_ID.options.length; i++) {
			var option = post_form.FUNCTION_ID.options[i];
			if(option.dataset.website_id == site_id) {
				option.disabled = false;
			}else {
				option.disabled = true;
			}			
		}
		//сделать выделенной первую функцию
		for(var i = 0; i < post_form.FUNCTION_ID.options.length; i++) {
			var option = post_form.FUNCTION_ID.options[i];
			if(option.dataset.website_id == site_id) {
				option.selected = true;
				break;
			}			
		}
	}
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>