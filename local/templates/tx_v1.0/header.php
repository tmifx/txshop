<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">	
<head>	
	<meta name="viewport" content="width=1170">
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery-ui.css')?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/bootstrap.min.css')?>
	<?//$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/colorbox/colorbox.css')?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/image.css')?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/style.css')?>
	
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-2.2.3.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-ui.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/bootstrap.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/swfobject.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/unity_object_2.js')?>
	<?//APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.colorbox-min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/default.js')?>
	
	<?if($USER->IsAdmin()):?>
		<?$APPLICATION->ShowHead();?>
	<?else:?>
		<?$APPLICATION->ShowMeta("description")?>
		<?$APPLICATION->ShowMeta("keywords")?>
	<?endif?>
	<title><?$APPLICATION->ShowTitle()?></title>	
</head>
<body>
<?$APPLICATION->ShowPanel()?>
<div id='loading' class=''>
	<div class="sk-cube sk-cube1"></div>
	<div class="sk-cube sk-cube2"></div>
	<div class="sk-cube sk-cube3"></div>
	<div class="sk-cube sk-cube4"></div>
	<div class="sk-cube sk-cube5"></div>
	<div class="sk-cube sk-cube6"></div>
	<div class="sk-cube sk-cube7"></div>
	<div class="sk-cube sk-cube8"></div>
	<div class="sk-cube sk-cube9"></div>
</div>
<div class="wrapper">
	<div id='top'>
		<div class='container'>
			<div class='row'>
				<div class='logo'>
					<a class='img-logo' href='/'></a>
				</div>					
				<div class='search'>
					<form action='/' method='get'>
						<input autocomplete="off"  type='text' name='search' placeholder='Поиск' value='<?=$_REQUEST['search']?>'/>
					</form>
					<div class="search-list hidden"></div>
				</div>					
				<div class='favorites'>
					<noindex>
						<a rel="nofollow" class='button no-cache' href='/?filter=favorites'>Мои избранные</a>
					</noindex>
				</div>
				<?$APPLICATION->IncludeComponent(
	"tx:login", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "games",
		"IBLOCK_ID" => "1",
		"SECTION_ID" => "",
		"ELEMENT_ID" => "2",
		"SEF_MODE" => "N",
		"CACHE_TYPE" => "Y",
		"CACHE_TIME" => "36000000"
	),
	false
);?>
			</div>
		</div>
	</div>
	<div class='container'>
		<div class='row'>
			<div id='left-menu' class='col-xs-2'>
				<?$APPLICATION->IncludeComponent(
	"tx:left_menu", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "sections",
		"CACHE_TYPE" => "Y",
		"CACHE_TIME" => "36000000"
	),
	false
);?>	
			<div id='back-top' style='display:none'><span>Наверх</span></div>
			</div>		
			<div id='content' class='col-xs-10'>				