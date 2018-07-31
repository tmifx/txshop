<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	//echo '<pre>';print_r($arResult); echo '</pre>';
?>
<!--noindex-->
<div class='b-tasks'>
	<div class='b-tasks__container'>					
			<div class='b-tasks__treasures'>				
				<a href='/ajax/messages/click_treasures/' class='b-treasures ajax ajax_popup-content json'>
					<div class='b-treasures__img img-treasures'></div>
					<div class='b-treasures__value'><?=$arResult['TREASURES']?></div>
				</a>
			</div>
			<?if($arResult['TASKS']):?>			
			<div class='b-tasks__left'></div>
			<div class='b-tasks__list'>
				<div class='b-tasks-list'>
					<div class='b-tasks-list__container'>
						<?foreach($arResult['TASKS'] as $key=>$task):?>
							<?if($arParams['AUTHORIZED']):?>
								<?if(!$task['COMPLETED']):?>
									<a href='/ajax/get_task/?id=<?=$task['ID']?>' class='b-tasks-list__el <?=!$key?'b-tasks-list__el_current':''?> ajax ajax_popup-content json'>
								<?else:?>
									<a href='/ajax/complete_task/?id=<?=$task['ID']?>' class='b-tasks-list__el <?=!$key?'b-tasks-list__el_current':''?> ajax ajax_popup-content json'>
								<?endif?>
							<?else:?>
								<a href='/ajax/messages/task_only_authorized/' class='b-tasks-list__el <?=!$key?'b-tasks-list__el_current':''?> ajax ajax_popup-content json'>						
							<?endif?>
								<div class='b-tasks-list-el'>
									<?if($task['COMPLETED']):?>
										<div class='b-tasks-list-el__completed'>
											<div class='img-attention anim-attention'></div>
										</div>
									<?endif?>
									<div class='b-tasks-list-el__name <?=$task['COMPLETED']?'b-tasks-list-el__name_center':''?>'><?=$task['UF_NAME']?></div>
									<?if($task['UF_TIMER']):?>
										<div class='b-tasks-list-el__time'><?=$task['UF_TIMER']?></div>							
									<?endif?>
									
								</div>
							</a>
						<?endforeach?>
					</div>					
				</div>			
			</div>
			<div class='b-tasks__right'></div>
		<?else:?>
			<div class='b-tasks__timer'>
				<div class='b-tasks-timer'>
					<div class='b-tasks-timer__el'>
						<span>До получения новых заданий осталось</span> <span><?=$arResult['TIMER_TASKS_UPDATE']?></span>
					</div>
				</div>
			</div>
		<?endif?>
		<div class='b-tasks__clear'></div>
	</div>
</div>
<!--/noindex-->