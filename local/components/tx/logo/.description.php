<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Отложенная загрузка логотипа",
	"DESCRIPTION" => "Отложенная загрузка логотипа",
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "TX",
		"CHILD" => array(
			"ID" => "logo",
			"NAME" => 'Логотип'
		)
	),
);

?>