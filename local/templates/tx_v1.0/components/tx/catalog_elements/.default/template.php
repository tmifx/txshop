<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//echo '<pre>';print_r($arResult); echo '</pre>';?>

<div id='top-panel-elements'>		
	<?if(!$arParams['FILTER'] && !$arParams['SEARCH']):?>
		<?if($arParams['SEF_FOLDER']):?>
			<?if($arParams['CUR_PAGE']==$arParams['CUR_PAGE_PARAM']):?>
				<a class="current" href='<?=$arParams['CUR_PAGE']?>'><h1>Новые<?=$arResult['SECTION']['NAME']?' "'.$arResult['SECTION']['NAME'].'"':''?></h1></a>
			<?else:?>
				<a href='<?=$arParams['CUR_PAGE']?>'>Новые<?=$arResult['SECTION']['NAME']?' "'.$arResult['SECTION']['NAME'].'"':''?></a>
			<?endif?>
		<?endif?>
		
		
			<a <?=$_REQUEST['sort']=='popular'?'class="current"':''?> href='<?=$arParams['CUR_PAGE']?>?sort=popular'>Популярные<?=$arResult['SECTION']['NAME']?' "'.$arResult['SECTION']['NAME'].'"':''?></a>
			<a <?=$_REQUEST['sort']=='favorites'?'class="current"':''?> href='<?=$arParams['CUR_PAGE']?>?sort=favorites'>Избранные<?=$arResult['SECTION']['NAME']?' "'.$arResult['SECTION']['NAME'].'"':''?></a>		
	<?endif?>
	
	<?if($arParams['FILTER'] == CatalogElements::FILTER_FAVORITES):?>
		<div class='title'>
			Мои избранные
		</div>
	<?endif?>
	
	<?if($arParams['SEARCH']):?>
		<div class='title'>
			Поиск: "<?=$arParams['SEARCH']?>"
		</div>
	<?endif?>
</div>
<div class='elements'>
	<?/* СООБЩЕНИЯ
	   */?>
	<?if(!$arParams['AUTHORIZED'] && $arParams['FILTER'] == CatalogElements::FILTER_FAVORITES):?>
		<div class='message'>
			Раздел избранные, доступен после входа.
		</div>
	<?endif?>
	<?if($arParams['AUTHORIZED'] && !$arResult['ELEMENTS'] && $arParams['FILTER'] == CatalogElements::FILTER_FAVORITES):?>
		<div class='message'>
			Вы не добавили ничего в избранные.
		</div>
	<?endif?>
	<?foreach($arResult['ELEMENTS'] as $element):?>				
		<div style='opacity: 0' class='element'>	
			<div class='name'>
				<a href='<?=$element['DETAIL_PAGE_URL']?>'>
					<?if($element['PROPERTY']['NAME_RU']['VALUE']):?>
						<?=$element['PROPERTY']['NAME_RU']['VALUE']?>
					<?else:?>
						<?=$element['NAME']?>
					<?endif?>
				</a>
			</div>			
			<?if($element['PREVIEW_PICTURE']):?>
				<div class='picture'>
					<a href='<?=$element['DETAIL_PAGE_URL']?>'>
						<?if($element['PROPERTY']['NAME_RU']['VALUE']):?>
							<img alt='<?=$element['PROPERTY']['NAME_RU']['VALUE']?> (<?=$element['NAME']?>)' src='<?=$element['PREVIEW_PICTURE']['src']?>' width='<?=$element['PREVIEW_PICTURE']['width']?>px' height='<?=$element['PREVIEW_PICTURE']['height']?>px'>
						<?else:?>							
							<img alt='<?=$element['NAME']?>' src='<?=$element['PREVIEW_PICTURE']['src']?>' width='<?=$element['PREVIEW_PICTURE']['width']?>px' height='<?=$element['PREVIEW_PICTURE']['height']?>px'>
						<?endif?>
					</a>
				</div>
			<?endif?>
			
			<?if($element['PROPERTY']['SECTIONS'][0]['VALUE']):?>
				<div class='sections'>
					<?foreach($element['PROPERTY']['SECTIONS'] as $section):?>
						<div class='section'>
							<a href='<?=$section['VALUE']['SECTION_PAGE_URL']?>'>
								#<?=$section['VALUE']['NAME']?>
							</a>
						</div>
					<?endforeach?>
				</div>
			<?endif?>
			
			<?if($element['PROPERTY']['CONTROL'][0]['VALUE']):?>				
				<div class='control'>
					<?foreach($element['PROPERTY']['CONTROL'] as $control):?>
						<div class='item <?=$control['VALUE']['UF_CSS_CLASS']?>'>
							<?if(!$control['VALUE']['UF_CSS_CLASS']):?>
								<?=$control['VALUE']['UF_NAME']?>
							<?endif?>
						</div>
					<?endforeach?>
				</div>
			<?endif?>
			
			<div class='info'>
				<?if($element['VIEWS']):?>
					<div class='views'>
						<span class='img-views'></span>
						<?=$element['VIEWS']?>
					</div>	
				<?endif?>
				<?if($element['FAVORITES']):?>	
					<div class='favorites'>
						<span class='img-favorites-dark'></span>
						<?=$element['FAVORITES']?>
					</div>					
				<?endif?>
			</div>
			
			<?if($arParams['FILTER'] == CatalogElements::FILTER_FAVORITES):?>
				<div class='add-favorite'>
					<a href='/ajax/add_favorite/?id=<?=$element['ID']?>' class='ajax'>Из избранного</a>
				</div>
			<?endif?>
		</div>
	<?endforeach?>
	<?=$arResult["NAV_STRING"]?>
</div>