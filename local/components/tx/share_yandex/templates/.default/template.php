<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$element = $arResult?>
<!--noindex-->
	<script async type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
	<script async type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
	<div class="ya-share2" data-element-id ='<?=$element['ID']?>' data-services="vkontakte,facebook,odnoklassniki,moimir,gplus" data-counter="" data-lang='ru' data-title='<?=$element['NAME']?>' <?=$element['PREVIEW_TEXT']?'data-description="'.htmlspecialcharsbx($element['PREVIEW_TEXT']).'"':''?> <?=$element['PREVIEW_PICTURE']?'data-image="'.$arParams['SERVER_URL'].$element['PREVIEW_PICTURE']['src'].'"':''?> ></div>	
<!--/noindex-->
<?
	//echo '<pre>';print_r($element); echo '</pre>';	
?>
