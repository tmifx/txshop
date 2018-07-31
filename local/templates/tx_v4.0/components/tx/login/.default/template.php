<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	//echo '<pre>';print_r($arResult); echo '</pre>';
?>					
<div class='b-login'>
	<!--noindex-->
	<?if($arParams['AUTHORIZE']):?>
		<a rel="nofollow" class='button button_green no-history' href='/logout/'>Выход</a>
	<?else:?>
		<a rel="nofollow" class='button button_green' href='/login/'>Вход</a>
	<?endif?>
	<!--/noindex-->
</div>



