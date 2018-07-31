<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
	//echo '<pre>';print_r($arResult); echo '</pre>';
?>
<div class='info'>	
	<div class='views'>
		<span class='img-views'></span>
		<?=$arResult['VIEWS']?$arResult['VIEWS']:0?>
	</div>					
	<div class='favorites'>
		<span class='img-favorites-dark'></span>
		<?=$arResult['FAVORITES']?$arResult['FAVORITES']:0?>
	</div>	
</div>