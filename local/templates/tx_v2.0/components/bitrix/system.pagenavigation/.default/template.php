<?//echo '<pre>';print_r($arResult); echo '</pre>';
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="b_paginator">
	<?if($arResult['NavPageNomer'] != $arResult["nEndPage"]):?>
		<a class='b_paginator__el ajax' href="<?=$arResult["sUrlPathParams"]?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"></a>
	<?endif?>
</div>