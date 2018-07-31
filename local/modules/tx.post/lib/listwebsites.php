<?php
	namespace Tx\Post;
	
	use \Bitrix\Main\Entity;
	
	class ListWebSitesTable extends Entity\DataManager
	{
		public static function getTableName() 		
		{
			return 'tx_post_list_websites';
		}		
		public static function getMap() 
		{
			return array(
				//ID
				new Entity\IntegerField('ID', array(
					'primary'=> true,
					'autocomplete'=>true,
				)),
				//DOMAIN
				new Entity\StringField('DOMAIN', array(					 
					'required'=>true, //обязательное для заполнения
				)),				
				//SORT
				new Entity\IntegerField('SORT', array(
					'required'=>true, //обязательное для заполнения
					'default_value'=>500, //значение по умолчанию
				)),
			);
		}
	}
?>