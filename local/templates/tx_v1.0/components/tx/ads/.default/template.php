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
	<div class='ads center'>	
		<div class='title'>РЕКЛАМА (<span class='timer' data-default='<?=$ads['PROPERTY_TIME_VALUE']?>'><?=$ads['PROPERTY_TIME_VALUE']?></span> сек.)</div>
		<div class='button-close hidden'>Закрыть рекламу</div>
		<div>
			<iframe id='ads-container'  src="/ads/center/?index=<?=$index?>" width='<?=$ads['PROPERTY_WIDTH_VALUE']?>px' height='<?=$ads['PROPERTY_HEIGHT_VALUE']?>px'  scrolling='no' frameborder='no' marginheight='0' marginwidth='0'></iframe>			
		</div>		
		<div class='message'><?=$arParams['MESSAGE']?></div>
	</div>	
<?elseif($arParams['ELEMENT']):?>
	<?=$arParams['ELEMENT']['PREVIEW_TEXT']?>	
<?endif?>