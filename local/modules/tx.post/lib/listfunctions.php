<?php
	namespace Tx\Post;
	
	use \Bitrix\Main\Entity;
	
	class ListFunctionsTable extends Entity\DataManager
	{
		public static function getTableName() 		
		{
			return 'tx_post_list_functions';
		}		
		public static function getMap() 
		{
			return array(
				//ID
				new Entity\IntegerField('ID', array(
					'primary'=> true,
					'autocomplete'=>true,
				)),	
				//CLASS_NAME
				new Entity\StringField('CLASS_NAME', array(					 
					'required'=>true, //обязательное для заполнения
				)),	
				//FUNCTION_NAME
				new Entity\StringField('FUNCTION_NAME', array(					 
					'required'=>true, //обязательное для заполнения
				)),								
				//DESCRIPTION
				new Entity\StringField('DESCRIPTION', array(
					'required'=>true, //обязательное для заполнения
				)),
				//WEBSITE_ID
				new Entity\IntegerField('WEBSITE_ID', array(
					'required'=>true, //обязательное для заполнения
				)),
				//WEBSITE
				new Entity\ReferenceField(
					'WEBSITE',
					'Tx\Post\ListWebSites',
					array('=this.WEBSITE_ID' => 'ref.ID')
				),
				//TYPE_ID
				new Entity\IntegerField('TYPE_ID', array(
					'required'=>true, //обязательное для заполнения
				)),
				//TYPE
				new Entity\ReferenceField(
					'TYPE',
					'Tx\Post\ListTypes',
					array('=this.TYPE_ID' => 'ref.ID')
				),
				//SORT
				new Entity\IntegerField('SORT', array(
					'required'=>true, //обязательное для заполнения
					'default_value'=>500, //значение по умолчанию
				)),	
			);
		}
	}
?>