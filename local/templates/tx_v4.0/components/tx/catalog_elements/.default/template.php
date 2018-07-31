<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//echo '<pre>';print_r($arResult); echo '</pre>';?>
<?if(!$arParams['FILTER'] && !$arParams['SEARCH']):?>
	<div class='b-sort'>	
		<?if($arParams['SEF_FOLDER']):?>
			<?if($arParams['CUR_PAGE']==$arParams['CUR_PAGE_PARAM']):?>
				<a class="b-sort__el b-sort__el_current" href='<?=$arParams['CUR_PAGE']?>'><h1>Новые<?=$arResult['SECTION']['NAME']?' "'.$arResult['SECTION']['NAME'].'"':''?></h1></a>
			<?else:?>
				<a class="b-sort__el" href='<?=$arParams['CUR_PAGE']?>'>Новые<?=$arResult['SECTION']['NAME']?' "'.$arResult['SECTION']['NAME'].'"':''?></a>
			<?endif?>
		<?endif?>	
		<a class="b-sort__el <?=$_REQUEST['sort']=='popular'?'b-sort__el_current':''?>"  href='<?=$arParams['CUR_PAGE']?>?sort=popular'>Популярные<?=$arResult['SECTION']['NAME']?' "'.$arResult['SECTION']['NAME'].'"':''?></a>
		<a class="b-sort__el <?=$_REQUEST['sort']=='favorites'?'b-sort__el_current':''?>"  href='<?=$arParams['CUR_PAGE']?>?sort=favorites'>Избранные<?=$arResult['SECTION']['NAME']?' "'.$arResult['SECTION']['NAME'].'"':''?></a>				
		<div class='b-sort__cler'></div>	
	</div>
<?endif?>

<?if($arParams['FILTER'] == CatalogElements::FILTER_FAVORITES ||
	$arParams['SEARCH']):?>
	<div class='b-title'>
		<?if($arParams['FILTER'] == CatalogElements::FILTER_FAVORITES):?>
			<div class='b-title__el'>
				Мои избранные
			</div>
		<?endif?>
		
		<?if($arParams['SEARCH']):?>
			<div class='b-title__el'>
				Поиск: "<?=$arParams['SEARCH']?>"
			</div>
		<?endif?>
	</div>
<?endif?>
<?/* СООБЩЕНИЯ
   */?>
<?if(!$arParams['AUTHORIZED'] && $arParams['FILTER'] == CatalogElements::FILTER_FAVORITES ||
	$arParams['AUTHORIZED'] && !$arResult['ELEMENTS'] && $arParams['FILTER'] == CatalogElements::FILTER_FAVORITES):?>
	<div class='b-message'>
		<?if(!$arParams['AUTHORIZED'] && $arParams['FILTER'] == CatalogElements::FILTER_FAVORITES):?>
			<div class='b-message_el'>
				Раздел избранные, доступен после входа.
			</div>
		<?endif?>
		<?if($arParams['AUTHORIZED'] && !$arResult['ELEMENTS'] && $arParams['FILTER'] == CatalogElements::FILTER_FAVORITES):?>
			<div class='b-message_el'>
				Вы не добавили ничего в избранные.
			</div>
		<?endif?>
	</div>
<?endif?>
<?if($arResult['ELEMENTS']):?>
	<div class='b-elements'>
		<?foreach($arResult['ELEMENTS'] as $element):?>		
			<div class='b-elements__el'>			
				<div class='b-element'>
					<a class='b-element__name' href='<?=$element['DETAIL_PAGE_URL']?>'>
						<?=$element['NAME']?>
					</a>
					<?if($element['PREVIEW_PICTURE']):?>
						<a class='b-element__picture' href='<?=$element['DETAIL_PAGE_URL']?>'>
							<div class='b-element-picture'>
								<?if($element['PROPERTY']['NAME_RU']['VALUE']):?>
									<img class='b-element-picture__el' alt='<?=$element['PROPERTY']['NAME_RU']['VALUE']?> (<?=$element['NAME']?>)' src='<?=$element['PREVIEW_PICTURE']['src']?>' width='<?=$element['PREVIEW_PICTURE']['width']?>' height='<?=$element['PREVIEW_PICTURE']['height']?>'>
								<?else:?>							
									<img class='b-element-picture__el' alt='<?=$element['NAME']?>' src='<?=$element['PREVIEW_PICTURE']['src']?>' width='<?=$element['PREVIEW_PICTURE']['width']?>' height='<?=$element['PREVIEW_PICTURE']['height']?>'>
								<?endif?>
							</div>
						</a>
					<?endif?>
					<?if($element['PROPERTY']['SECTIONS']):?>
						<div class='b-element__sections'>
							<div class='b-sections'>
								<?if($element['PROPERTY']['SECTIONS'][0]):?>
									<?foreach($element['PROPERTY']['SECTIONS'] as $section):?>
										<a class='b-sections__sec' href='<?=$section['VALUE']['SECTION_PAGE_URL']?>'>
											#<?=$section['VALUE']['NAME']?>
										</a>
									<?endforeach?>
								<?else:?>
									<?$section = $element['PROPERTY']['SECTIONS']?>
									<a class='b-sections__sec' href='<?=$section['VALUE']['SECTION_PAGE_URL']?>'>
										#<?=$section['VALUE']['NAME']?>
									</a>
								<?endif?>
								<div class='b-sections__clear'></div>
							</div>
						</div>
					<?endif?>
					<?if($arParams['FILTER'] == CatalogElements::FILTER_FAVORITES):?>
						<div class='b-element__add-favorite'>
							<a href='/ajax/add_favorite/?id=<?=$element['ID']?>' class='ajax ajax_replace-content'>Из избранного</a>
						</div>
					<?endif?>
				</div>
			</div>
		<?endforeach?>
		<?=$arResult["NAV_STRING"]?>
	</div>
<?endif?>