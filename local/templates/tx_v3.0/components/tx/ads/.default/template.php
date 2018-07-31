<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	//echo "<pre>"; print_r($arResult); echo "</pre>";
?>
<?if($arParams['CONTENT'] != 'Y'):?>
	<?/* Определить индекс рекламы
		 что бы его передать для загрузки
	   */?>
	<?$index = rand(0, count($arResult)-1)?>
	<?$ads = $arResult[$index]?>
	<div class='b-ads'>	
		<div class='b-ads__title'>РЕКЛАМА (<span class='b-ads__timer' data-default='<?=$ads['PROPERTY_TIME_VALUE']?>'><?=$ads['PROPERTY_TIME_VALUE']?></span> сек.)</div>
		<div class='b-ads__close ads__close_hidden'>Закрыть рекламу</div>
		<iframe class='b-ads__container' src="/ads/center/?index=<?=$index?>" width='<?=$ads['PROPERTY_WIDTH_VALUE']?>' height='<?=$ads['PROPERTY_HEIGHT_VALUE']?>' scrolling='no' frameborder='no' marginheight='0' marginwidth='0'></iframe>		
		<div class='b-ads__message'><?=$arParams['MESSAGE']?></div>
	</div>	
<?elseif($arParams['ELEMENT']):?>
	<?=$arParams['ELEMENT']['PREVIEW_TEXT']?>	
<?endif?>