<?if(!check_bitrix_sessid())
	return;
?>

<h1>Шаг 2</h1>
<h2>Модуль удален</h2>
<form action="<?=$APPLICATION->GetCurPage()?>">
	<?=bitrix_sessid_post()?>	
	<input type="hidden" name="lang" value="<?echo LANG?>">	
	<input type="submit" name="" value="Перейти к списку модулей">
<form>