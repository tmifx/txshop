<?php
	namespace Tx\Post;
	
	use \Bitrix\Main\Entity;
	
	class ListTypesTable extends Entity\DataManager
	{
		public static function getTableName() 		
		{
			return 'tx_post_list_types';
		}		
		public static function getMap() 
		{
			return array(
				//ID
				new Entity\IntegerField('ID', array(
					'primary'=> true,
					'autocomplete'=>true,
				)),	
				//TYPE
				new Entity\StringField('TYPE', array(
					'required'=>true, //обязательное для заполнения					
				)),	
				//IBLOCKS_ID
				new Entity\StringField('IBLOCKS_ID', array(
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