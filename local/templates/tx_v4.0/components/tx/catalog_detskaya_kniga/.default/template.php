<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$element=&$arResult['ELEMENT']?>
<div class='b-detail-element'>
	
	<?/* Панель с кнопками
	   */?>
	<div class='b-detail-element__panel'>
		<?/**/?>
		<div class='b-detail-element-panel'>
			<!--noindex-->
			<a class='b-detail-element-panel__b-back button button_green load-scroll-history' href='<?=strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])?$_SERVER['HTTP_REFERER']:'/'?>'>
				Назад
			</a>
			<!--noindex-->
			<?if($arParams['AUTHORIZED']):?>
				<a class='b-detail-element-panel__b-favorite button button_red ajax ajax_replace-content' href='/ajax/add_favorite/?id=<?=$element['ID']?>'>
					<?=$arResult['IS_FAVORITES']?'Из избранных':'В избранные'?>
				</a>
			<?else:?>
				<?/* Подгрузить сообщение с текстом об авторизации
				   */?>
				<a class='b-detail-element-panel__b-favorite button button_red ajax ajax_popup-content json' href='/ajax/messages/add_favorite_only_authorized/'>
					В избранные
				</a>
			<?endif?>
			<!--/noindex-->
			<div class='b-detail-element-panel__clear'></div>
		</div>			
	</div>
		
	<?/* Левая часть
	   */?>
	<div class='b-detail-element__left'>
		<div class='b-detail-element-img'>
			<?if($element['DETAIL_PICTURE']):?>
				<img class='b-detail-element-img__el' src="<?=$element['DETAIL_PICTURE']['src']?>" class="b-detail-element-img__el" />
			<?else:?>
				<div class='b-detail-element-img__el img-no-photo'></div>
			<?endif?>
		</div>
	</div>
	<?/* Правая часть
	   */?>
	<div class='b-detail-element__right'>
		<?/* Загаловок
			*/?>
		<H1 class='b-detail-element__title'>
			<?=$element['NAME']?>
		</H1>
		
		
		<?/* Покупка товара
			*/?>
		<div class='b-detail-element__buy b-detail-element-buy'>
			<div class="b-detail-element-buy__quantity">
				<div class="b_quantity">
					<span class="b_quantity__minus button button_orange"></span>
					<input class="b_quantity__num" value="1" name='quantity' />
					<span class="b_quantity__plus button button_orange"></span>					
					<div class="b_quantity__clear"></div>
				</div>
			</div>
			<div class="b-detail-element-buy__price-info">
				<div class="b-detail-element-buy__price-title">
					Цена:
				</div>
				<div class="b-detail-element-buy__price"><?=$element['PROPERTY']['PRICE']['VALUE']?> руб.</div>				
			</div>
			
			<div class="b-detail-element-buy__button">				
				<a href='/ajax/cart_add/' class='button button_blue ajax ajax_cart-add'>Добавить в корзину</a>
			</div>
			<div class="b-detail-element-buy__clear"></div>
		</div>
		
		<?/* Описание
			*/?>
		<?if($element['DETAIL_TEXT']):?>
			<div class="b-detail-element__description-title">
				Описание
			</div>
			<h2 class='b-detail-element__description'>
				<?=$element['DETAIL_TEXT']?>
			</h2>
		<?endif?>
		<?/* Характеристики
			*/?>
		<?if($element['PROPS']):?>
			<div class='b-detail-element__properties-title'>
				Характеристики
			</div>
			<ul class='b-detail-element__properties b-detail-element-properties'>
				<?foreach($element['PROPS'] as $prop):?>
					<li class='b-detail-element-properties__el'><h3><?=$prop['NAME']?>: <?=$prop['VALUE']?></h3></li>
				<?endforeach?>
			</ul>
		<?endif?>
		<?/* Акции
			*/?>
		<div class='b-detail-element__stock-title'>
			Акции
		</div>
		<ul class='b-detail-element__stock b-detail-element-stock'>
			<li class='b-detail-element-stock__el'><a href="#">Первая акция</a></li>
			<li class='b-detail-element-stock__el'><a href="#">Вторая акция</a></li>
			<li class='b-detail-element-stock__el'><a href="#">Третьея акция</a></li>
		</ul>
	</div>
	<?/*Поделиться
	   */?>
	<div class='b-detail-element__share'>
		<?/**/?>
		<div class='b-share'>
			<div class='b-share__text'>
				Поделиться
			</div>			
			<div class='b-share__elements'>
				<?$APPLICATION->IncludeComponent(
				"tx:share_yandex", 
				".default", 
				array(	
					"ELEMENT_ID" => $element['ID'],
					"CACHE_TYPE" => $arParams['CACHE_TYPE'],
					"CACHE_TIME" => $arParams['CACHE_TIME'],
				),
				false
			);?>
			</div>
		</div>
	</div>
	<?/*Разделы
	   */?>
	<?if($element['PROPERTY']['SECTIONS'][0]['VALUE']):?>
		<div class='b-detail-element__sections'>
			<div class='b-sections b-sections_center'>	
				<div class='b-sections__title'>Категории</div>
				<?foreach($element['PROPERTY']['SECTIONS'] as $section):?>
					<a class='b-sections__sec b-sections__sec_blue b-sections__sec_big' href='<?=$section['VALUE']['SECTION_PAGE_URL']?>'>
						#<?=$section['VALUE']['NAME']?>
					</a>
				<?endforeach?>
				<div class='b-sections__clear'></div>
			</div>
		</div>
	<?else:?>
		<div class='b-detail-element__sections'>
			<div class='b-sections b-sections_center'>	
				<div class='b-sections__title'>Категория</div>
				<?$section = $element['PROPERTY']['SECTIONS']?>
				<a class='b-sections__sec b-sections__sec_blue b-sections__sec_big' href='<?=$section['VALUE']['SECTION_PAGE_URL']?>'>
					#<?=$section['VALUE']['NAME']?>
				</a>
				<div class='b-sections__clear'></div>
			</div>
		</div>
	<?endif?>	
	<?if($element['PROPERTY']['PICTURES']):?>
		<div class='b-detail-element__pictures b-detail-element-pictures'>		
			<?foreach($element['PROPERTY']['PICTURES'] as $item):?>
				<?/*<div class='b-detail-element-pictures__el'>*/?>
					<img class='b-detail-element-pictures__el' src="<?=$item['src']?>" />		
				<?/*</div>*/?>					
			<?endforeach?>			
		</div>
	<?endif?>
	<div class='b-detail-element__footer'></div>
</div>
<?
	//echo "<pre>"; print_r($arResult); echo "</pre>";
?>