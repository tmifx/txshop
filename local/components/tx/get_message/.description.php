<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Получить сообщение из базы",
	"DESCRIPTION" => "Получить сообщение из базы",
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "TX",
		"CHILD" => array(
			"ID" => "messages",
			"NAME" => 'Сообщения'
		)
	),
);

?>