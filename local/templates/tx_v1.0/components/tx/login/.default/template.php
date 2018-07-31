<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
	//echo '<pre>';print_r($arResult); echo '</pre>';
?>					
<div class='login'>
	<noindex>
	<?if($arParams['AUTHORIZE']):?>
		<a rel="nofollow" class='button no-history' href='/logout/'>Выход</a>
	<?else:?>
		<a rel="nofollow" class='button no-history' href='/login/'>Вход</a>
	<?endif?>
	</noindex>
</div>



