<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html lang="ru" xmlns="http://www.w3.org/1999/xhtml">	
<head>	
	<meta name="viewport" content="width=device-width">
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/image.css')?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/style.css')?>
	
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-2.2.3.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/swfobject.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/unity_object_2.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/yandex_metrika.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/google_analytics.js')?>
	
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/default.js')?>
	
	<?if($USER->IsAdmin()):?>
		<?$APPLICATION->ShowHead();?>
	<?else:?>
		<?$APPLICATION->ShowMeta("description")?>
		<?$APPLICATION->ShowMeta("keywords")?>
		<?$APPLICATION->ShowCSS()?>
		<?$APPLICATION->ShowHeadScripts()?>	
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

<div id="loading-top">
  <div class="sk-circle1 sk-circle"></div>
  <div class="sk-circle2 sk-circle"></div>
  <div class="sk-circle3 sk-circle"></div>
  <div class="sk-circle4 sk-circle"></div>
  <div class="sk-circle5 sk-circle"></div>
  <div class="sk-circle6 sk-circle"></div>
  <div class="sk-circle7 sk-circle"></div>
  <div class="sk-circle8 sk-circle"></div>
  <div class="sk-circle9 sk-circle"></div>
  <div class="sk-circle10 sk-circle"></div>
  <div class="sk-circle11 sk-circle"></div>
  <div class="sk-circle12 sk-circle"></div>
</div>
<div class='b-header'>	
	<div class='b-header__container'>
		<div class='b-header-container'>
			<div class='b-header-container__logo'>				
				<?/**/?>
				<div class='b-logo'>
					<a href='/' class='b-logo__img img-logo'></a>
				</div>								
			</div>
			<div class='b-header-container__search'>
				<?/**/?>
				<div class='b-search'>
					<form class='b-search__field' action='/' method='get'>
						<input autocomplete="off"  type='text' name='search' placeholder='Поиск' value='<?=$_REQUEST['search']?>'/>
					</form>
					<div class="b-search__list b-search__list_hidden"></div>					
				</div>
			</div>
			<div class='b-header-container__favorites'>
				<!--noindex-->
					<a rel="nofollow" class='button button_red no-cache' href='/?filter=favorites'>Мои избранные</a>
				<!--/noindex-->
			</div>			
			<div class='b-header-container__login'>				
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
			<div class='b-header-container__clear'></div>
		</div>
	</div>
</div>
<div class="b-page">
	<div class="b-page__container">
		<div class='b-page-container__left-column'>
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
		</div>
		<div class='b-page-container__content'>