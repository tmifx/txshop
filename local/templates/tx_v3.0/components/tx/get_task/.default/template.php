<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	//echo '<pre>';print_r($arResult); echo '</pre>';
?>
<div class='b-task'>
	<div class='b-task__description'>
		<?=$arResult['UF_DESCRIPTION']?>		
	</div>
	<div class='b-task__table'>				
		<div class='b-task__level'>
			<div class='b-task__title b-task__title_left'>Сложность</div>
			<div class='b-task__value b-task__value_left'><?=$arResult['UF_LEVEL_DIFFICULTY']?></div>		
		</div>
		<div class='b-task__time'>
			<div class='b-task__title'>Время</div>
			<div class='b-task__value b-task__value_time'><?=$arResult['UF_TIMER']?></div>
		</div>
		<div class='b-task__payment'>
			<div class='b-task__title b-task__title_right'>Сокровищ</div>
			<div class='b-task__value b-task__value_right'><?=$arResult['UF_PAYMENT']?></div>
		</div>
	</div>
</div>
