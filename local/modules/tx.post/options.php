<?
use Bitrix\Main\Config\Option;
$MODULE_ID = "tx.post";

if(!$USER->IsAdmin())
	return;


IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");

$aTabs = array(
	array("DIV" => "edit1", "TAB" =>'Настройки ВКонтакте', "TITLE" =>'Настройки ВКонтакте'),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);


if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid())
{
	if(strlen($RestoreDefaults)>0)
	{
		COption::RemoveOption($mid);
	}
	else
	{	
		$arDef = Option::getDefaults($MODULE_ID);
		foreach($arDef as $key=>$value) {
			if(($value == 'Y' || $value == 'N') && !$_POST[$key]) {
				$_POST[$key] = 'N';
			}
			if($_POST[$key]) {				
				Option::set($MODULE_ID, $key, $_POST[$key]);				
			}
		}
	}	
	LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid));		
}
$tabControl->Begin();
?>

<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
	<?$tabControl->BeginNextTab();?>
	<tr class="heading">
		<td colspan="2"><b>ACCESS_TOKEN</b></td>
	</tr>	
	<tr>		
		<td width="40%">
			Ссылка на получение ACCESS_TOKEN
		</td>
		<td width="60%">
			<input readonly type='text' value='<?=Option::get($MODULE_ID, 'vk_link_get_access_token')?>' name='vk_link_get_access_token' style='width:90%'/> 
		</td>
	</tr>
	<tr>		
		<td width="40%">
			ACCESS_TOKEN
		</td>
		<td width="60%">
			<input readonly type='text' value='<?=Option::get($MODULE_ID, 'vk_access_token')?>' name='vk_access_token' style='width:90%'/> 
		</td>
	</tr>
	<tr class="heading">
		<td colspan="2"><b>Настройки</b></td>
	</tr>
	<tr>		
		<td width="40%">
			ID группы
		</td>
		<td width="60%">
			<input readonly type='text' value='<?=Option::get($MODULE_ID, 'vk_group_id')?>' name='vk_group_id' /> 
		</td>
	</tr>
	<tr>		
		<td width="40%">
			ID паблика
		</td>
		<td width="60%">
			<input readonly type='text' value='<?=Option::get($MODULE_ID, 'vk_group_id_2')?>' name='vk_group_id_2' /> 
		</td>
	</tr>
	<tr>		
		<td width="40%">
			ID альбома для загрузки фотографий
		</td>
		<td width="60%">
			<input readonly type='text' value='<?=Option::get($MODULE_ID, 'vk_album_id')?>' name='vk_album_id' /> 
		</td>
	</tr>
	<tr>		
		<td width="40%">
			Тестовый режим (включен по умолчанию)
		</td>
		<td width="60%">
			<input type='checkbox' value='Y' <?=Option::get($MODULE_ID, 'vk_test_mode')=='Y'?'checked="checked"':''?> name='vk_test_mode' /> 
		</td>
	</tr>
	<tr class="heading">
		<td colspan="2"><b>Настройки тестового режима</b></td>
	</tr>
	<tr>		
		<td width="40%">
			ID группы (ДЛЯ ТЕСТИРОВАНИЯ)
		</td>
		<td width="60%">
			<input readonly type='text' value='<?=Option::get($MODULE_ID, 'vk_test_group_id')?>' name='vk_test_group_id' /> 
		</td>
	</tr>
	<tr>		
		<td width="40%">
			ID паблика (ДЛЯ ТЕСТИРОВАНИЯ)
		</td>
		<td width="60%">
			<input readonly type='text' value='<?=Option::get($MODULE_ID, 'vk_test_group_id_2')?>' name='vk_test_group_id_2' /> 
		</td>
	</tr>
	<tr>		
		<td width="40%">
			ID альбома для загрузки фотографий (ДЛЯ ТЕСТИРОВАНИЯ)
		</td>
		<td width="60%">
			<input readonly type='text' value='<?=Option::get($MODULE_ID, 'vk_test_album_id')?>' name='vk_test_album_id' /> 
		</td>
	</tr>
	<?$tabControl->Buttons();?>
	<input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">	
	<input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
	<?$tabControl->End();?>
</form>