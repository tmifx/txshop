<?if(!check_bitrix_sessid())
	return;
	global $obModule;
?>

<h1>Шаг 1</h1>
<h2>Мастер удаления</h2>
<form action="<?=$APPLICATION->GetCurPage()?>">
	<?=bitrix_sessid_post()?>	
	<input type="hidden" name="lang" value="<?echo LANG?>">
	<input type="hidden" name="step" value="2">	
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="id" value="<?=$obModule->MODULE_ID?>">	
	<p>
		<label for="news"><?='Удалить базы'?></label>
		<input type="checkbox" name="removing_base" value='Y'>
	</p>	
	<input type="submit" name="uninst" value="Удалит модуль">
<form>