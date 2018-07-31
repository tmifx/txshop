<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class='row'>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<?$countIblock = count($arResult)?>
		<?foreach($arResult as $iblock_id => $iblock):?>
			<?if(!$iblock['SECTIONS']) continue?>
			<div class="panel">
				<div class="panel-heading" role="tab" id="heading_<?=$iblock_id?>">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?=$iblock_id?>" aria-expanded="true" aria-controls="collapse_<?=$iblock_id?>">
							<?=$iblock['NAME']?>
						</a>
					</h4>
				</div>
				<div id="collapse_<?=$iblock_id?>" class="iblock collapse <?=$countIblock==1 || $iblock['SELECTED']?'in':''?>" role="tabpanel" aria-labelledby="heading_<?=$iblock_id?>">
					<div class="panel-body">
						<?foreach($iblock['SECTIONS'] as $section):?>
							<div class='section'>
								<a class='<?=$section['SELECTED']?'current':''?>' href='<?=$section['SECTION_PAGE_URL']?>'><?=$section['NAME']?></a>
							</div>
						<?endforeach?>
						
					</div>
				</div>
			</div>
		<?endforeach?>
	</div>
</div>
<?//echo '<pre>';print_r($arResult); echo '</pre>';?>

