<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//echo '<pre>';print_r($arResult); echo '</pre>';?>
<?
	$num_elements = count($arResult['ELEMENTS']);
	$count = $num_elements>3?3:$num_elements;
?>
<div class='b-search-list'>
	<?for($i=0; $i<$count; $i++):?>				
		<?$element = $arResult['ELEMENTS'][$i]?>
		<div class='b-search-list__el'>
			<div class='b-search-element'>
				<a class='b-search-element__name' href='<?=$element['DETAIL_PAGE_URL']?>'>					
					<?if($element['PROPERTY']['NAME_RU']['VALUE']):?>
						<?=$element['PROPERTY']['NAME_RU']['VALUE']?>
					<?else:?>
						<?=$element['NAME']?>
					<?endif?>
				</a>
				<a class='b-search-element__img' href='<?=$element['DETAIL_PAGE_URL']?>'>
					<?if($element['PROPERTY']['NAME_RU']['VALUE']):?>
						<img alt='<?=$element['PROPERTY']['NAME_RU']['VALUE']?> (<?=$element['NAME']?>)' src='<?=$element['PREVIEW_PICTURE']['src']?>' width='<?=$element['PREVIEW_PICTURE']['width']?>' height='<?=$element['PREVIEW_PICTURE']['height']?>'>
					<?else:?>
						<img alt='<?=$element['NAME']?>' src='<?=$element['PREVIEW_PICTURE']['src']?>' width='<?=$element['PREVIEW_PICTURE']['width']?>' height='<?=$element['PREVIEW_PICTURE']['height']?>'>
					<?endif?>
				</a>
				<div class='b-search-element__info'>
					<div class='b-search-element-info'>
						<div class='b-search-element-info__category'>
							<div class='b-search-element-info-category'>
								<?foreach($element['PROPERTY']['SECTIONS'] as $section):?>
									<a class='b-search-element-info-category__el' href='<?=$section['VALUE']['SECTION_PAGE_URL']?>'>
										#<?=$section['VALUE']['NAME']?>
									</a>
								<?endforeach?>
							</div>
						</div>
						<div class='b-search-element-info__views'>
							<span class='img-views'></span>
							<?=$element['VIEWS']?>
						</div>
						<div class='b-search-element-info__favorites'>
							<span class='img-favorites-dark'></span>
							<?=$element['FAVORITES']?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?endfor?>
	<?if($num_elements > $count):?>
		<div class='b-search-list__more'>
			<a href='/?search=<?=urlencode($arParams['SEARCH'])?>'>Показать больше</a>
		</div>
	<?endif?>
</div>
