<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use \Bitrix\Main\Loader;
use \TX\Post\ScheduleTable as CurClass;

//name module
$MODULE_ID = 'tx.post';

//File Name Edit
$FILE_NAME_EDIT = 'schedule_edit';

//checking
$POST_RIGHT = $APPLICATION->GetGroupRight($MODULE_ID);
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

//load module
if(!Loader::includeModule($MODULE_ID)) {
	return;
}

/* Base Obj
 */
$sTableID = 'tbl_'.str_replace('.','_',$MODULE_ID).'_'.__CLASS__;
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);

// ******************************************************************** //
//                		Обработка AJAX Запросов        					//
// ******************************************************************** //
if($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST['ajax']=='Y' && $POST_RIGHT=="W") {
	/* Запуск функции из расписания
	 */
	if($_REQUEST['schedule_id']) {
		CurClass::run_function_by_schedule_id($_REQUEST['schedule_id']);		
		return;
	}
}

// ******************************************************************** //
//                		PROCESSING FORM              					//
// ******************************************************************** //
// update elements
if($lAdmin->EditAction() && $POST_RIGHT=="W")
{
	
	//list fields
	foreach($FIELDS as $id=>$arFields)
	{
		if(!$lAdmin->IsUpdated($id))
		  continue;
		
		//transaction
		$DB->StartTransaction();
		$id = IntVal($id);
		
		//save each element
		$rsData = CurClass::getByID($id);		
		if($arData = $rsData->Fetch()) {
			foreach($arFields as $key=>$value) {
				$arData[$key]=$value;
			}
			
			$result = CurClass::Update($id, $arData);
			if(!$result->isSuccess()) {
				$errors = $result->getErrorMessages();
				foreach($errors as $error) {
					$lAdmin->AddGroupError($error, $id);					
				}
				$DB->Rollback();
			}	
		} else {
			$lAdmin->AddGroupError('Element not found', $id);
			$DB->Rollback();
		}
		
		$DB->Commit();
	}
}
//group actions
if(($arID = $lAdmin->GroupAction()) && $POST_RIGHT=="W") {	
	//for all elements
	if($_REQUEST['action_target']=='selected') {		
		$rsData = CurClass::getList(array(
				'order'=>$order,
				'filter'=>$arFilter));
		while($arRes = $rsData->Fetch()) {
			$arID[] = $arRes['ID'];		
		}
	}
	//list elements
	foreach($arID as $ID) {
		if(strlen($ID)<=0)
			continue;
		
		$ID = IntVal($ID);
		
		//actions for each element
		switch($_REQUEST['action']) {			
			// delete
			case "delete":
				@set_time_limit(0);
				
				$DB->StartTransaction();
				$result = CurClass::delete($ID);
				if(!$result->isSuccess()) {
					$errors = $result->getErrorMessages();
					foreach($errors as $error) {
						$lAdmin->AddGroupError($error, $id);					
					}
					$DB->Rollback();
				}
				
				$DB->Commit();
			break;
		}	
	}
}

// ******************************************************************** //
//               			 GET LIST   		                        //
// ******************************************************************** //

// get list
$rsData = CurClass::getList(array(
	'select'=>array('*', 'WEBSITE.*', 'FUNCTION.*'),
	'order'=>array($by=>$order),
));

//convert list to CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

//pagination
$rsData->NavStart();

//show pagination
$lAdmin->NavText($rsData->GetNavPrint('Элементы'));

// ******************************************************************** //
//                PREPARATION OF A LIST TO THE CONCLUSION               //
// ******************************************************************** //
$lAdmin->AddHeaders(array(
	array(
		"id"    	=>"ID",
		"content"  	=>"ID",
		"sort"    	=>"ID",
		"align"    	=>"right",
		"default"  	=>true,
	),
	//ACTIVE
	array(
		"id"    	=>"ACTIVE",
		"content"  	=>'Активно',
		"sort"    	=>"ACTIVE",
		"default"  	=>true,
	),
	//SORT
	array(
		"id"    	=>"SORT",
		"content"  	=>'Сортировка',
		"sort"    	=>"SORT",
		"default"  	=>true,
	),
	//DAY_WEEK
	array(
		"id"    	=>"DAY_WEEK",
		"content"  	=>'День недели',
		"sort"    	=>"DAY_WEEK",
		"default"  	=>true,
	),
	//HOUR
	array(
		"id"    	=>"HOUR",
		"content"  	=>'В какой час',
		"sort"    	=>"HOUR",
		"default"  	=>true,
	),
	//MINUTES
	array(
		"id"    	=>"MINUTES",
		"content"  	=>'Во сколько минут',
		"sort"    	=>"MINUTES",
		"default"  	=>true,
	),	
	//Сайт
	array(
		"id"    	=>"WEBSITE_ID",
		"content"  	=>'Сайт',
		"sort"    	=>"WEBSITE_ID",
		"default"  	=>true,
	),
	//Функция
	array(
		"id"    	=>"FUNCTION_ID",
		"content"  	=>'Функция',
		"sort"    	=>"FUNCTION_ID",
		"default"  	=>true,
	),
	//Последний запуск
	array(
		"id"    	=>"LAST_RUN_TIME",
		"content"  	=>'Последний запуск',
		"sort"    	=>"LAST_RUN_TIME",
		"default"  	=>true,
	),
	//Запуск функции
	array(
		"id"    	=>"RUN_FUNCTION",
		"content"  	=>'Запуск функции',
		//"sort"    	=>"LAST_RUN_TIME",
		"default"  	=>true,
	),
));
while($arRes = $rsData->NavNext(true, "f_")) {
	$row =& $lAdmin->AddRow($f_ID, $arRes);
	$row->AddViewField("ID", '<a style="display: block;" href='.$MODULE_ID.'_'.$FILE_NAME_EDIT.'.php?ID='.$f_ID.'>'.htmlspecialchars_decode($f_ID).'</a>');
	switch($f_DAY_WEEK) {
		case 1:		
			$row->AddViewField("DAY_WEEK", 'Понедельник');
		break;
		case 2:		
			$row->AddViewField("DAY_WEEK", 'Вторник');
		break;
		case 3:		
			$row->AddViewField("DAY_WEEK", 'Среда');
		break;
		case 4:		
			$row->AddViewField("DAY_WEEK", 'Четверг');
		break;
		case 5:		
			$row->AddViewField("DAY_WEEK", 'Пятница');
		break;
		case 6:		
			$row->AddViewField("DAY_WEEK", 'Суббота');
		break;
		case 7:	
			$row->AddViewField("DAY_WEEK", 'Воскресенье');
		break;
	}
	
	if($f_HOUR < 9) {
		$row->AddViewField("HOUR", '0'.$f_HOUR);
	}
	
	if($f_MINUTES < 9) {
		$row->AddViewField("MINUTES", '0'.$f_MINUTES);
	}
	
	$row->AddViewField("WEBSITE_ID", $f_TX_POST_SCHEDULE_WEBSITE_DOMAIN);
	$row->AddViewField("FUNCTION_ID", $f_TX_POST_SCHEDULE_FUNCTION_DESCRIPTION);
	
	$row->AddInputField("NAME", array("size"=>20));
	$row->AddInputField("DAY_WEEK", array("size"=>20));
	$row->AddInputField("HOUR", array("size"=>20));
	$row->AddInputField("MINUTES", array("size"=>20));
	$row->AddInputField("SORT", array("size"=>20));
	
	$row->AddViewField("RUN_FUNCTION", '<a class="adm-btn" OnClick="tx_functions.run('.$f_FUNCTION_ID.','.$f_ID.');">Запустить</a>');
	
	
}

//footer table
$lAdmin->AddFooter(
  array(
    array("title"=>'Number elements', "value"=>$rsData->SelectedRowsCount()), // Number elements
    array("counter"=>true, "title"=>'Counter elements', "value"=>"0"), // Counter elements
  )
);

//group actions
$lAdmin->AddGroupActionTable(
	Array(
		"delete"=>'remove selected items',
	)	
);

//add buttons to menu
$aContext = array(
	array(
		"TEXT"=>"Добавить",
		"LINK"=>$MODULE_ID."_".$FILE_NAME_EDIT.".php",
		"TITLE"=>"Добавить",
		"ICON"=>"btn_new",
	),
);

//add menu in the content
$lAdmin->AddAdminContextMenu($aContext);

//ajax
$lAdmin->CheckListMode();

// ******************************************************************** //
//              			 TITLE							            //
// ******************************************************************** //
$APPLICATION->SetTitle("Расписание");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
//shoe table
$lAdmin->DisplayList();
?>

<script>
	/* Запуск функции расписания	
	 */	
	var tx_functions = new function () {
		//проверить, запущена ли фунеция уже
		this.tx_post_run_function_send = false;
		
		//Запуск функций
		this.run = function (function_id, schedule_id) {
			if(confirm('Запустить функцию расписания?')) {
				if(this.tx_post_run_function_send == false) {
					this.tx_post_run_function_send = true;
				}else {
					return;
				}
				BX.ajax.post(
					'<?=$APPLICATION->GetCurPage()?>?function_id='+function_id,
					'&ajax=Y&schedule_id='+schedule_id,
					function(result){
						tx_functions.tx_post_run_function_send = false;
						console.log(result);
					}
				);
			}
		}
	}
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>