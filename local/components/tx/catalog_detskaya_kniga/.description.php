<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Книги для детей",
	"DESCRIPTION" => "Книги для детей",
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "TX",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => 'Каталог'
		)
	),
);

?>