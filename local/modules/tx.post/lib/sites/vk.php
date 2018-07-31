<?php
	namespace Tx\Post\Sites;
	
	use Tx\Post\ListNewTable; 
	use \Bitrix\Main\Config\Option;
	
	class Vk extends \Tx\Post\BaseSite 
	{		
		//переменные
		var $ACCESS_TOKEN;
		var $GROUP_ID;
		var $GROUP_ID_2;
		var $ALBUM_ID;
		
		//ссылки на апи вк
		const URL_UPLOAD_SERVE = 'https://api.vk.com/method/photos.getUploadServer';
		const URL_PHOTOS_SAVE = 'https://api.vk.com/method/photos.save';
		const URL_WALL_POST = 'https://api.vk.com/method/wall.post';
		const URL_POLLS_CREATE = 'https://api.vk.com/method/polls.create';
		
		function __construct($function_id) {
			parent::__construct($function_id);
			
			//задать переменные
			$this->ACCESS_TOKEN = Option::get($this->module_id, 'vk_access_token');
			$this->TEST_MODE = Option::get($this->module_id, 'vk_test_mode');		
			
			if($this->TEST_MODE != 'Y') {
				$this->GROUP_ID = Option::get($this->module_id, 'vk_group_id');
				$this->GROUP_ID_2 = Option::get($this->module_id, 'vk_group_id_2');
				$this->ALBUM_ID = Option::get($this->module_id, 'vk_album_id');
			}else {
				$this->GROUP_ID = Option::get($this->module_id, 'vk_test_group_id');
				$this->GROUP_ID_2 = Option::get($this->module_id, 'vk_test_group_id_2');
				$this->ALBUM_ID = Option::get($this->module_id, 'vk_test_album_id');
			}
		}
		//Пост новой игры
		function postNewGame() {			
			//подключить модуль инфоблоков
			if(!\Bitrix\Main\Loader::includeModule("iblock")) {
				return;
			}
			
			//получить список новых игр
			$elements = $this->getNewElements();
			if(!$elements) {
				return;
			}
			
			$elements = array($elements[0]); //выбрать только самую первую
			//получить массив ссылок на фотографии
			$ar_photos_src = $this->getPreviewPicturesSrc($elements);
			
			
			//получить сервер для загрузки фотографий
			$upload_url = $this->getUploadServerForPhoto($this->ALBUM_ID);
			if(!$upload_url) {
				echo "Ошибка при получении сервера";
				return false;
			}
			//загрузить фотографии на сервер
			$photos = $this->uploadPhotos($upload_url, $elements, $ar_photos_src);
			
			//сделать пост			
			foreach($photos as $photo) {				
				//создать опрос
				$question = 'Голосование';
				$answers = array('Мне нравится', 'Мне не нравится', 'Ещё не играл(а)');		
				$poll = self::createPolls($question, $answers);
								
				//создать пост новой игры
				if($photo['ELEMENT']['PROPERTY']['NAME_RU']['VALUE']) {
					$message = 'НОВАЯ ИГРА: '.$photo['ELEMENT']['PROPERTY']['NAME_RU']['VALUE'].' - ('.$photo['ELEMENT']['NAME'].')'."\n".\COption::GetOptionString('main','server_name').$photo['ELEMENT']['DETAIL_PAGE_URL']; 				
				}else {
					$message = 'НОВАЯ ИГРА: '.$photo['ELEMENT']['NAME']."\n".\COption::GetOptionString('main','server_name').$photo['ELEMENT']['DETAIL_PAGE_URL']; 				
				}
				
				//в группу
				$post = self::createPost($this->GROUP_ID, $message, $photo, $poll);
							
				//в публик
				$post = self::createPost($this->GROUP_ID_2, $message, $photo, $poll);					
			}

			//отметить элемент как опубликованный
			ListNewTable::published($elements, $this->website_id, $this->type_id);
		}
		/*****************************************************************
								ФУНКЦИИ СЕРВЕРНЫЕ ДЛЯ ВК
		 *****************************************************************/
		/* создать пост в вк
		 */
		function createPost($group_id, $message, $photos, $poll = null, $date = 0) {
			$attachments = array();
			//добавить фотографии			
			foreach($photos['response'] as $photo) {
				$attachments[] = 'photo'.$photo['owner_id'].'_'.$photo['pid'];
			}		
			
			//добавить опрос	
			if($poll != null) {
				$attachments[] = 'poll'.$poll['owner_id'].'_'.$poll['poll_id'];
			}
			
			//собрать строчку
			$attachments = implode(',', $attachments);
			
			//сделать пост в группу
			$data = array(	'owner_id' => '-'.$group_id,
							'friends_only' => 0, 
							'from_group' => 1, 
							'message' => $message,
							'attachments' => $attachments,
							'publish_date' => $date, //время публикации
							'access_token' =>$this->ACCESS_TOKEN);
			
			$server_post = $this->sendPostRequest(self::URL_WALL_POST, $data);						
			$server_post = json_decode($server_post);
			
			return true;
		}
		/* создать голосование в вк
		 */
		function createPolls($question, $answers) {			 
			$answers = '["'.implode('","', $answers).'"]';
			$data = array(	'question' => $question,
							'is_anonymous' => 0, 
							'owner_id' => '-'.$this->GROUP_ID,
							'add_answers' => $answers, 
							'access_token' =>$this->ACCESS_TOKEN);
			
			$server_poll = $this->sendPostRequest(self::URL_POLLS_CREATE, $data);
			$server_poll = json_decode($server_poll, true);
			
			return $server_poll['response'];
		}
		/* загрузить файл на сервер
		 */
		function savePhoto($upload_file, $caption) {
			
			//фото лист
			$photos_list = $upload_file['photos_list'];
			
			//защитный ключ
			$hash = $upload_file['hash'];
			
			//адрес сервера
			$server = $upload_file['server'];
			
			//сохранить в альбом
			$data = array(	'server' => $server,
							'photos_list' => $photos_list,
							'album_id' => $this->ALBUM_ID, 
							'hash' => $hash, 
							'access_token' => $this->ACCESS_TOKEN, 
							'caption' => $caption);
			
			$server_save = $this->sendPostRequest(self::URL_PHOTOS_SAVE, $data);
			$server_save = json_decode($server_save, true);
			return $server_save;
		}		 
		/* загрузить файл на сервер
		 */
		function uploadServerForPhoto($upload_url, $photo_src) {
			$upload_file = $this->sendFile($upload_url, $photo_src);
			if($upload_file === false) return false;
			$upload_file = json_decode($upload_file, true);
			
			$upload_file['photos_list'] = stripslashes($upload_file['photos_list']);
						
			return $upload_file;
		}
		/* Отправить фотографии на сервер
		 */
		function uploadPhotos($upload_url, $elements, $ar_photos_src) {
			$photos = array();
			foreach($elements as $element) {
				$photo_src = $ar_photos_src[$element['ID']];
				
				$upload_file = $this->uploadServerForPhoto($upload_url, $photo_src);
				
				//сохранить фотографии на сервере
				if($element['PROPERTY']['NAME_RU']['VALUE']) {
					$caption = $element['PROPERTY']['NAME_RU']['VALUE'].' - ('.$element['NAME'].')'."\n".\COption::GetOptionString('main','server_name').$element['DETAIL_PAGE_URL'];
				}else {
					$caption = $element['NAME']."\n".\COption::GetOptionString('main','server_name').$element['DETAIL_PAGE_URL'];
				}
				$photo = self::savePhoto($upload_file, $caption);				
				$photo['ELEMENT'] = $element;
				
				$photos[] = $photo;
			}
			return $photos;
		}
		/* Получить сервер для фотографий
		 */
		function getUploadServerForPhoto($album_id) {
			
			//получить сервер для загрузки фотографий			
			$data = array( 'access_token' => $this->ACCESS_TOKEN, 'album_id' => $album_id);
			$server_upload = $this->sendPostRequest(self::URL_UPLOAD_SERVE, $data);
			$server_upload = json_decode($server_upload);
			$upload_url = null;
			
			//получит адрес для загрузки
			foreach($server_upload as $k=>$v) {
				if($k == 'error' ) {
					return false;
				}
				foreach($v as $k2=>$v2) {
					$upload_url= $v2;
					break;
				}
			}
			if($upload_url == null) {
				return false;
			}
			
			return $upload_url;
		}
	}
?>