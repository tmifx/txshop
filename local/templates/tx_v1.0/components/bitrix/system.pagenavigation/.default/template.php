<?//echo '<pre>';print_r($arResult); echo '</pre>';
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="paginator" style='visibility:hidden'>
	<?if($arResult['NavPageNomer'] != $arResult["nEndPage"]):?>
		<a class='ajax' href="<?=$arResult["sUrlPathParams"]?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"></a>
	<?endif?>
</div>