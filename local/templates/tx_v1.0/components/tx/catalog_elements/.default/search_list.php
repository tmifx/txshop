<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//echo '<pre>';print_r($arResult); echo '</pre>';?>
<?
	$num_elements = count($arResult['ELEMENTS']);
	$count = $num_elements>3?3:$num_elements;
?>
<?for($i=0; $i<$count; $i++):?>				
	<?$element = $arResult['ELEMENTS'][$i]?>
	<div class='sl-element'>	
		<div class='sl-name'>
			<a href='<?=$element['DETAIL_PAGE_URL']?>'>
				<?if($element['PROPERTY']['NAME_RU']['VALUE']):?>
					<?=$element['PROPERTY']['NAME_RU']['VALUE']?>
				<?else:?>
					<?=$element['NAME']?>
				<?endif?>
			</a>
		</div>			
		<?if($element['PREVIEW_PICTURE']):?>
			<div class='sl-picture'>
				<a href='<?=$element['DETAIL_PAGE_URL']?>'>
					<?if($element['PROPERTY']['NAME_RU']['VALUE']):?>
						<img alt='<?=$element['PROPERTY']['NAME_RU']['VALUE']?> (<?=$element['NAME']?>)' src='<?=$element['PREVIEW_PICTURE']['src']?>' width='<?=$element['PREVIEW_PICTURE']['width']?>' height='<?=$element['PREVIEW_PICTURE']['height']?>'>
					<?else:?>
						<img alt='<?=$element['NAME']?>' src='<?=$element['PREVIEW_PICTURE']['src']?>' width='<?=$element['PREVIEW_PICTURE']['width']?>' height='<?=$element['PREVIEW_PICTURE']['height']?>'>
					<?endif?>
				</a>
			</div>
		<?endif?>		
		<div class='sl-group'>
			<div class='sl-sections'>
				<?foreach($element['PROPERTY']['SECTIONS'] as $section):?>
					<div class='sl-section'>
						<a href='<?=$section['VALUE']['SECTION_PAGE_URL']?>'>
							#<?=$section['VALUE']['NAME']?>
						</a>
					</div>
				<?endforeach?>
			</div>
			
			<div class='sl-info'>
				<?if($element['FAVORITES']):?>	
					<div class='sl-views'>
						<span class='img-views'></span>
						<?=$element['VIEWS']?>
					</div>					
					<div class='sl-favorites'>
						<span class='img-favorites-dark'></span>
						<?=$element['FAVORITES']?>
					</div>					
				<?endif?>
			</div>
		</div>
		<div class='clear'></div>
	</div>
<?endfor?>
<?if($num_elements > $count):?>
	<div class='sl-show-more'>
		<a href='/?search=<?=urlencode($arParams['SEARCH'])?>'>Показать больше</a>
	</div>
<?endif?>
