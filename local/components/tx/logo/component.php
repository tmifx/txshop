<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	$arResult = $APPLICATION->AddBufferContent('showLogo');	

	function showLogo()
	{		
		global $APPLICATION;		
		if ($GLOBALS['LOGO_LOAD_SCROLL_HISTORY'] === true && strstr($_SERVER['HTTP_REFERER'],str_replace(array(':80',':443'),'',$_SERVER['HTTP_HOST']) )) {			
			return '<div class="b-logo">
						<a href="'.$_SERVER['HTTP_REFERER'].'" class="b-logo__img img-logo load-scroll-history"></a>
					</div>';
		}else {
			return '<div class="b-logo">
						<a href="/" class="b-logo__img img-logo"></a>
					</div>';
		}
	}	
?>
