<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use \Bitrix\Main\Loader;
use \TX\Post\ListTypesTable as CurClass;

//name module
$MODULE_ID = 'tx.post';

//File Name Edit
$FILE_NAME_EDIT = 'list_types_edit';

//checking
$POST_RIGHT = $APPLICATION->GetGroupRight($MODULE_ID);
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

//load module
if(!Loader::includeModule($MODULE_ID)) {
	return;
}
if(!\Bitrix\Main\Loader::includeModule("iblock")) {
	return;
}

// ******************************************************************** //
//                			ПОЛУЧИТЬ ДАННЫЕ                         	//
// ******************************************************************** //

//Получить список всех инфоблоков
$list_elements = \Bitrix\Iblock\IblockTable::getList();			
while($element = $list_elements->fetch()) {
	$arResult['IBLOCKS'][$element['ID']] = $element;
}

/* Base Obj
 */
$sTableID = 'tbl_'.str_replace('.','_',$MODULE_ID).'_'.__CLASS__;
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);

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
$rsData = CurClass::getList(array('order'=>array($by=>$order)));

//convert list to CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

//pagination
$rsData->NavStart();

//show pagination
$lAdmin->NavText($rsData->GetNavPrint('Elements'));

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
	array(
		"id"    	=>"TYPE",
		"content"  	=>'Тип',
		"sort"    	=>"TYPE",
		"default"  	=>true,
	),
	array(
		"id"    	=>"IBLOCKS_ID",
		"content"  	=>'Инфоблоки',
		"sort"    	=>"IBLOCKS_ID",
		"default"  	=>true,
	),
	array(
		"id"    	=>"SORT",
		"content"  	=>'Сортировка',
		"sort"    	=>"SORT",
		"default"  	=>true,
	),
));
while($arRes = $rsData->NavNext(true, "f_")) {
	$row =& $lAdmin->AddRow($f_ID, $arRes);
	
	$row->AddViewField("TYPE", '<a href='.$MODULE_ID.'_'.$FILE_NAME_EDIT.'.php?ID='.$f_ID.'>'.htmlspecialchars_decode($f_TYPE).'</a>');
	$row->AddInputField("TYPE", array("size"=>20));
	
	//подготовить имена для выгрузки
	foreach(explode(',', $f_IBLOCKS_ID) as $iblock_id) {
		$iblocks_name[] = $arResult['IBLOCKS'][$iblock_id]['NAME'];
	}	
	$row->AddViewField("IBLOCKS_ID", implode(', ', $iblocks_name));
	$row->AddInputField("SORT", array("size"=>20));
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
$APPLICATION->SetTitle("List Elements");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
//shoe table
$lAdmin->DisplayList();
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>