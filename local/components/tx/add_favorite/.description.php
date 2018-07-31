<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Добавление в Избранные",
	"DESCRIPTION" => "Добавление в Избранные",
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "TX",
		"CHILD" => array(
			"ID" => "favorites",
			"NAME" => 'Избранные'
		)
	),
);

?>