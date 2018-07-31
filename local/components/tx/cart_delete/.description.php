<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Корзина - удалить товар",
	"DESCRIPTION" => "Корзина - удалить товар",
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "TX",
		"CHILD" => array(
			"ID" => "cart",
			"NAME" => 'Корзина'
		)
	),
);

?>