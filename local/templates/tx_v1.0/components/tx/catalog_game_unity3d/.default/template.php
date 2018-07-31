<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$element=&$arResult?>
<?
	/* Распололожение файла
	 */
	if($element['PROPERTY']['FILE_LINK']['VALUE']) {
		$file_url = $element['PROPERTY']['FILE_LINK']['VALUE'];
	}elseif($element['PROPERTY']['FILE']['VALUE']) {
		$file_url = $element['PROPERTY']['FILE']['VALUE']['src'];
	}
	
	//Размер контэйнера
	$width = $element['PROPERTY']['WIDTH']['VALUE']?$element['PROPERTY']['WIDTH']['VALUE']:800;
	$height = $element['PROPERTY']['HEIGHT']['VALUE']?$element['PROPERTY']['HEIGHT']['VALUE']:600;
	
	//название		
?>
<div class='element game'>
	<div class='row panel'>
		<div class='back button col-xs-2'>
			<noindex>
				<a class='load-scroll-history' href='<?=strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])?$_SERVER['HTTP_REFERER']:'/'?>'>Назад</a>
			</noindex>
		</div>
		<div class='col-xs-4 col-xs-offset-2 padding-5-0'>		
			<?//if($element['PROPERTY']['RESIZABLE']['VALUE']):?>
					<div id="slider-size" data-container='game-container' data-width='<?=$width?>' data-height='<?=$height?>'></div>				
			<?//endif?>
		</div>
		<div class='add-favorite button col-xs-2 col-xs-offset-2 '>
			<noindex>
				<?if($arParams['AUTHORIZED']):?>
					<a href='/ajax/add_favorite/?id=<?=$element['ID']?>' class='ajax'><?=$arParams['IS_FAVORITES']?'Из избранного':'В избранное'?></a>
				<?else:?>
					<a>Для добавление игры себе в избранные, нужно войти</a>				
				<?endif?>
			</noindex>
		</div>
	</div>
	<div class='row name'>
		<div class='col-xs-12'>
			<?if($element['PROPERTY']['NAME_RU']['VALUE']):?>
				<?=$element['PROPERTY']['NAME_RU']['VALUE']?> - (<?=$element['NAME']?>)
			<?else:?>
				<?=$element['NAME']?>
			<?endif?>		
		</div>
	</div>
	<noindex>
		<div class='game-unity3d row'
									data-file_url='<?=$file_url?>'  
									data-width='<?=$width?>' 
									data-height='<?=$height?>'  
									data-game_container='unityPlayer'
									data-ads_container='ads-content'>
			<div>			
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
				<div id='game-container'>
					<div id="unityPlayer" style='width:100%;height:100%'>
						<div class="missing">
							<a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now!">
								<img alt="Unity Web Player. Install now!" src="http://webplayer.unity3d.com/installation/getunity.png" width="193" height="63" />
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</noindex>
	<div class='share row'>
		<div class='text'>Поделишься игрой с друзьями?</div>
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
	<?if($element['PROPERTY']['CONTROL'][0]['VALUE']):?>	
		<div class='control row'>
			<div>Управление</div>
			<div>
			<?foreach($element['PROPERTY']['CONTROL'] as $control):?>
				<div class='item <?=$control['VALUE']['UF_CSS_CLASS']?>'>
					<?if(!$control['VALUE']['UF_CSS_CLASS']):?>
						<?=$control['VALUE']['UF_NAME']?>
					<?endif?>
				</div>
			<?endforeach?>
			</div>
		</div>
	<?endif?>
	<?if($element['PROPERTY']['SECTIONS'][0]['VALUE']):?>
		<div class='section row'>
			<div>Категории</div>
			<div>
			<?foreach($element['PROPERTY']['SECTIONS'] as $section):?>
				<a href='<?=$section['VALUE']['SECTION_PAGE_URL']?>'>#<?=$section['VALUE']['NAME']?></a>
			<?endforeach?>
			</div>
		</div>
	<?endif?>
	<div class='info row'>
		<?if($element['VIEWS']):?>
			<div class='col-xs-6 text-center'>
				<div class='row'>
					<div class='col-xs-12 text-center'>
						Сыграли:
					</div>
					<div class='col-xs-12 text-center'>
						<?=$element['VIEWS']?>
					</div>
				</div>
			</div>
		<?endif?>
		<?if($element['FAVORITES']):?>
			<div class='col-xs-6 text-center'>
				<div class='row'>
					<div class='col-xs-12 text-center'>
						Добавили в избранные:
					</div>
					<div class='col-xs-12 text-center'>
						<?=$element['FAVORITES']?>
					</div>
				</div>
			</div>
		<?endif?>
	</div>
</div>
<?
	//echo "<pre>"; print_r($arResult); echo "</pre>";
?>