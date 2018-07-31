<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	class Reg extends CBitrixComponent
	{	
		public function addUser($login, $ulogin) {		
			$pass = randString(12, array(
						  "abcdefghijklnmopqrstuvwxyz",
						  "ABCDEFGHIJKLNMOPQRSTUVWX­YZ",
						  "0123456789",
						  "!@#\$%^&*()",
						));
			$arFields = Array(
			  "LOGIN"             => $login,
			  "LID"               => SITE_ID,
			  "ACTIVE"            => "Y",
			  "GROUP_ID"          => array(5),
			  "PASSWORD"          => $pass,
			  "CONFIRM_PASSWORD"  => $pass,
			);
			
			
			//дополнительные данные
			if($ulogin['first_name']) $arFields['NAME'] = $ulogin['first_name'];
			if($ulogin['last_name']) $arFields['LAST_NAME'] = $ulogin['last_name'];
			if($ulogin['email']) $arFields['EMAIL'] = $ulogin['email'];
			if($ulogin['identity']) $arFields['PERSONAL_WWW'] = $ulogin['identity'];
			
			//создать пользователя
			$user = new CUser;
			if($user_id = $user->Add($arFields)) {
				return $user_id;
			}else {
				return false;
			}
		}
		
		//получить id пользователя по логину
		public function getUserIdByLogin($login) {
			$rsUser = CUser::GetByLogin($login);
			if($arUser = $rsUser->Fetch()) {
				return $arUser['ID'];
			}else {
				return false;
			}			
		}
	}
?>
