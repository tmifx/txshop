<?
	
	$module_id = "tx.post";//id модуля
	
	/* Иконки в зависимости от уровня вложености
	 */
	$icons = array('fileman_menu_icon','iblock_menu_icon_types','iblock_menu_icon_iblocks','menu_dd_blank_module_content');
	
	/* Идентификатор ветви меню
	 */
	$items_id = array($module_id.'_0',$module_id.'_1',$module_id.'_2',$module_id.'_3',$module_id.'_4',$module_id.'_5');
	
	/* Массивы
	 */
	$main_menu = array(); 	//главное меню
	$sites = array(); 		//сайты
	
	
	/* Структура меню модуля
	 */	
	$menu = array(
		'text'=>'Автоматическая публикация',
		'url'=>"",
		'icon'=>$icons[0],
		'parent_menu'=>'global_menu_content', 
		"more_url"=>array(),
		"items_id"=>$items_id[0],
		'items'=>&$main_menu
	);
	/* Меню первого уровня
	 */
	$main_menu[] = array('text'=>'Расписание','url'=>$module_id."_schedule.php",'icon'=>$icons[2],"items_id"=>$items_id[1], 'more_url'=>array(
		$module_id.'_schedule_edit.php',	
	));
	$main_menu[] = array('text'=>'Список новых публикаций','url'=>$module_id."_list_new.php",'icon'=>$icons[2],"items_id"=>$items_id[1], 'more_url'=>array(
		$module_id.'_list_new_edit.php',	
	));
	
	//Меню первого уровня + под меню
	$main_menu[] = array('text'=>'Справочники','url'=>'','icon'=>$icons[1],"items_id"=>$items_id[1], 'items'=>array(
		array('text'=>'Список сайтов','url'=>$module_id."_list_websites.php",'icon'=>$icons[2],"items_id"=>$items_id[2] , 'more_url'=>array(
			$module_id.'_list_websites_edit.php',	
		)),
		array('text'=>'Список типов','url'=>$module_id."_list_types.php",'icon'=>$icons[2],"items_id"=>$items_id[2], 'more_url'=>array(
			$module_id.'_list_types_edit.php',
		)),
		array('text'=>'Список функций','url'=>$module_id."_list_functions.php",'icon'=>$icons[2],"items_id"=>$items_id[2], 'more_url'=>array(
			$module_id.'_list_functions_edit.php',	
		)),
	));	
	
	$main_menu[] = array('text'=>'Настройки','url'=>"/bitrix/admin/settings.php?mid=$module_id",'icon'=>$icons[2],"items_id"=>$items_id[1], 'more_url'=>array());
	
	return $menu;
?>
