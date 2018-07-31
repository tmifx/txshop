<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$element=&$arResult?>
<?
	/* Распололожение файла
	 */
	if($element['PROPERTY']['FILE_LINK']['VALUE']) {
		$file_url = $element['PROPERTY']['FILE_LINK']['VALUE'];
	}elseif($element['PROPERTY']['FILE']['VALUE']['src']) {
		$file_url = $element['PROPERTY']['FILE']['VALUE']['src'];
	}elseif($element['PROPERTY']['FILE']['VALUE']['SRC']) {
		$file_url = $element['PROPERTY']['FILE']['VALUE']['SRC'];
	}	
	
	//Размер контэйнера
	$width = $element['PROPERTY']['WIDTH']['VALUE']?$element['PROPERTY']['WIDTH']['VALUE']:800;
	$height = $element['PROPERTY']['HEIGHT']['VALUE']?$element['PROPERTY']['HEIGHT']['VALUE']:600;			
?>

<div class='b-detail-element'>
	
	<?/* Панель с кнопками
	   */?>
	<div class='b-detail-element__panel'>
		<?/**/?>
		<div class='b-game-panel'>
			<!--noindex-->
			<a class='b-game-panel__b-back button button_green load-scroll-history' href='<?=strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])?$_SERVER['HTTP_REFERER']:'/'?>'>
				Назад
			</a>
			<!--/noindex-->
			<div class='b-game-panel__size'>
				<?/**/?>
				<div class='b-game-panel-size' 
					data-game_container='b-detail-element__game-container'
					data-default_width='<?=$width?>'
					data-default_height='<?=$height?>'
					>
					<div class='b-game-panel-size__el b-game-panel-size__el_minus button button_blue img-minus'></div>
					<div class='b-game-panel-size__el b-game-panel-size__el_reset img-no-reset button button_blue'></div>
					<div class='b-game-panel-size__el b-game-panel-size__el_plus button button_blue img-plus'></div>
				</div>
			</div>
			<!--noindex-->
			<?if($arParams['AUTHORIZED']):?>
				<a class='b-game-panel__b-favorite button button_red ajax ajax_replace-content' href='/ajax/add_favorite/?id=<?=$element['ID']?>'>
					<?=$arParams['IS_FAVORITES']?'Из избранных':'В избранные'?>
				</a>
			<?else:?>
				<?/* Подгрузить сообщение с текстом об авторизации
				   */?>
				<a class='b-game-panel__b-favorite button button_red ajax ajax_popup-content json' href='/ajax/messages/add_favorite_only_authorized/'>
					В избранные
				</a>
			<?endif?>
			<!--/noindex-->
			<div class='b-game-panel__clear'></div>
		</div>			
	</div>
	
	<?/* Загаловок
	   */?>
	<H1 class='b-detail-element__title'>
		<?if($element['PROPERTY']['NAME_RU']['VALUE']):?>
			<?=$element['PROPERTY']['NAME_RU']['VALUE']?> - (<?=$element['NAME']?>)
		<?else:?>
			<?=$element['NAME']?>
		<?endif?>
	</H1>
	
	<?/* Реклама
	   */?>
	<div class='b-detail-element__ads'>
			<?/*ADS*/?>				
			<?$APPLICATION->IncludeComponent(
	"tx:ads", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"MESSAGE" => "Игра загружается",
		"IBLOCK_TYPE" => "ads",
		"IBLOCK_ID" => "4",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000"
	),
	false
);?>
	</div>
	
	<?/* Игра
	   */?>
	<!--noindex-->
	<div class='b-detail-element__game b-detail-element__game_flash' 
			data-file_url='<?=$file_url?>'
			data-width='<?=$width?>'
			data-height='<?=$height?>'
			data-game_container='b-detail-element__game-container'
			data-ads_container='b-ads__container'>		
	</div>
	<div class='b-detail-element__game-container'>
		<div></div>
	</div>
	<!--/noindex-->
	
	<?/*Поделиться
	   */?>
	<div class='b-detail-element__share'>
		<?/**/?>
		<div class='b-share'>
			<div class='b-share__text'>
				Поделишься игрой с друзьями?
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
	
	<?/*Управление
	   */?>
	<?if($element['PROPERTY']['CONTROL'][0]['VALUE']):?>				
		<div class='b-detail-element__control'>
			<div class='b-control b-control_center'>	
				<div class='b-control__title'>Управление</div>
				<?foreach($element['PROPERTY']['CONTROL'] as $control):?>
					<div class='b-control__el <?=$control['VALUE']['UF_CSS_CLASS']?>'>
						<?if(!$control['VALUE']['UF_CSS_CLASS']):?>
							<?=$control['VALUE']['UF_NAME']?>
						<?endif?>
					</div>
				<?endforeach?>
				<div class='b-control__clear'></div>
			</div>
		</div>
	<?endif?>
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
	<?endif?>
	
	<?if($element['VIEWS'] || $element['FAVORITES']):?>
		<div class='b-detail-element__info'>
			<div class='b-detail-info'>
				<?if($element['VIEWS']):?>
					<div class='b-detail-info__views'>
						<div class='b-detail-info-el'>							
							<div class='b-detail-info-el__title'>
								Сыграли:
							</div>
							<div class='b-detail-info-el__value'>
								<?/**/?>
								<div class='b-detail-info-el-value'>
									<div class='b-detail-info-el-value_el img-views'></div>
									<div class='b-detail-info-el-value_el'><?=$element['VIEWS']?></div>								
								</div>
							</div>
						</div>					
					</div>	
				<?endif?>
				<?if($element['FAVORITES']):?>	
					<div class='b-detail-info__favorites'>
						
						<div class='b-detail-info-el'>							
							<div class='b-detail-info-el__title'>
								Добавили в избранные:
							</div>
							<div class='b-detail-info-el__value'>
								<?/**/?>
								<div class='b-detail-info-el-value'>
									<div class='b-detail-info-el-value_el img-favorites-dark'></div>
									<div class='b-detail-info-el-value_el'><?=$element['FAVORITES']?></div>								
								</div>
							</div>
						</div>
						
					</div>					
				<?endif?>
				<div class='b-detail-info__clear'></div>
			</div>			
		</div>
	<?endif?>
	<div class='b-detail-element__footer'></div>
</div>
<?
	//echo "<pre>"; print_r($arResult); echo "</pre>";
?>