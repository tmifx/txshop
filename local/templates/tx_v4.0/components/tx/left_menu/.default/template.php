<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//echo '<pre>';print_r($arResult); echo '</pre>';?>
<div class='b-menu'>
	<?foreach($arResult as $iblock_id => $iblock):?>
		<?if(!$iblock['SECTIONS']) continue?>
		<div class='b-menu__title'>
			<?=$iblock['NAME']?>
		</div>	
		<?foreach($iblock['SECTIONS'] as $section):?>		
			<a href='<?=$section['SECTION_PAGE_URL']?>' class='b-menu__section <?=$section['SELECTED']?'b-menu__section_current':''?>'>
				<?=$section['NAME']?>
			</a>
		<?endforeach?>
	<?endforeach?>	
</div>