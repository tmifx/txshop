<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

use \Bitrix\Main\Loader;
use \TX\Post\ListWebSitesTable as CurClass;

//name module
$MODULE_ID = 'tx.post';

//File Name List
$FILE_NAME_LIST = 'list_websites';

//checking
$POST_RIGHT = $APPLICATION->GetGroupRight($MODULE_ID);
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

//add js lib
$APPLICATION->AddHeadScript('/bitrix/js/iblock/iblock_edit.js');

//load module
if(!Loader::includeModule($MODULE_ID)) {
	return;
}

//add tabs
$aTabs = array(
	array("DIV" => "edit1", "TAB" => 'Сайт', "ICON"=>"main_user_edit", "TITLE"=>'Сайт'),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

// ******************************************************************** //
//                			PROCESSING FORM                            	//
// ******************************************************************** //
if(
    $REQUEST_METHOD == "POST" // Checking method    
    &&
    $POST_RIGHT=="W"          // Check write permissions
    &&
    check_bitrix_sessid()     // Check session
)
{
	/* Add Or Update
	 */
	if(!$_REQUEST['ID']) {
		$result = CurClass::add(array(
			'DOMAIN'=>$_REQUEST['DOMAIN'],
			'SORT'=>$_REQUEST['SORT'],
		));
		if(!$result->isSuccess()) {
			$errors = $result->getErrorMessages();
		}else {
			$ID = $result->getId();
		}
	}else{
		$result = CurClass::update($_REQUEST['ID'], array(
			'DOMAIN'=>$_REQUEST['DOMAIN'],
			'SORT'=>$_REQUEST['SORT'],
		));
		if(!$result->isSuccess()) {
			$errors = $result->getErrorMessages();
		}
	}
}
// ******************************************************************** //
//                SELECTION AND PREPARATION OF DATA FORMS               //
// ******************************************************************** //
//default values
$str_DOMAIN = '';
$str_SORT = '100';

//max sort
$db_res = CurClass::getList(array(
	'order'=>array('SORT'=>'DESC'),
	'select'=>array('SORT'),
));
if($res = $db_res->fetch()) {
	$str_SORT = $res['SORT'] + 100;
}

//load values
if($ID>0) {	
	$result = CurClass::GetById($ID);
	$fields = $result->fetch();
	foreach($fields as $key=>$value) {		
		$key = "str_".$key;
		$$key = $value;
	}	
}

/**********************************************************************
							SET TITLE
**********************************************************************/
$APPLICATION->SetTitle('Редактирование сайта');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
// ******************************************************************** //
//              		 SHOW FORM                                      //
// ******************************************************************** //
$aMenu = array(
  array(
    "TEXT"=>'Назад',
    "TITLE"=>'Назад',
    "LINK"=>$MODULE_ID."_".$FILE_NAME_LIST.'.php',
    "ICON"=>"btn_list",
  ),
);

// create menu
$context = new CAdminContextMenu($aMenu);

// show the administrative menu
$context->Show();

?>
<form method="POST" Action="<?=$APPLICATION->GetCurPage()?>?ID=<?=$ID?>" ENCTYPE="multipart/form-data" name="post_form">
<?=bitrix_sessid_post()?>
<?
	//
	if($errors) {
		CAdminMessage::ShowMessage(implode(chr(13),$errors));
	}
?>
<?	
	$tabControl->Begin();

	$tabControl->BeginNextTab();
?>
<tr>
	<?if($ID):?>
		<td width="40%" class='adm-detail-content-cell-l'>
			<span class='adm-required-field'>ID:</span>
		</td>
		<td width="60%" class='adm-detail-content-cell-r'>			
			<?=$ID?>
		</td>
	<?endif?>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>Доменное имя</span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<input type='text' name='DOMAIN' value='<?=$str_DOMAIN?>'>
	</td>
</tr>
<tr>
	<td width="40%" class='adm-detail-content-cell-l'>
		<span class='adm-required-field'>Сортировка</span>
	</td>
	<td width="60%" class='adm-detail-content-cell-r'>
		<input type='text' name='SORT' value='<?=$str_SORT?>'>
	</td>
</tr>
<?
	$tabControl->Buttons();
?>
	<input class="adm-btn-save" type="submit" value="Сохранить" />
<?
	$tabControl->End();
?>
<?
	// show warnings
	$tabControl->ShowWarnings("post_form", $message);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>