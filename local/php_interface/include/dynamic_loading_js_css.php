<?
/* Динамическая загрузка css и js
 */
AddEventHandler("main", "OnEndBufferContent", Array('DynamicLoadingJsCss','loadingJsCss'));
class DynamicLoadingJsCss
{  
	function loadingJsCss(&$content)
	{	
		global $USER, $APPLICATION;
		if($USER->IsAdmin() || strstr($APPLICATION->GetCurPage(),'/bitrix/')){
			return;
		}
		
		$head = explode('<head>', $content);
		$head = explode('</head>', $head[1]);
		$head = $head[0];
		
		//получить ссылки для загрузки css или добавить содержание в самый низ			
		$ar_link = explode('<link', $head);								
		foreach($ar_link as $key=>$link) {
			if($key == 0)
				continue;
			
			$link = explode('>',$link, 2);
			$link = '<link'.$link[0].'>';
			
			$href = explode('href="',$link, 2);
								
			$href = explode('"',$href[1], 2);					
			$href = $href[0];
			
			//добавить в массив для динамической в
			$ar_hreff[] = $href;
			
			//удалить ссылки из содержания
			$content = str_replace($link, '', $content);						
		}
		
		//получить ссылки для загрузки js
		$ar_script = explode('<script', $head);								
		foreach($ar_script as $key=>$script) {
			if($key == 0)
				continue;
			
			$script = explode('</script>',$script, 2);
			$script = '<script'.$script[0].'</script>';
			
			$async = explode('>', $script, 2);
			$async = $async[0];
			
			$src = explode('src="',$script, 2);
			$src = explode('"',$src[1], 2);					
			$src = $src[0];
			
			//нечего не делать с асинхронными скриптами
			if(strstr($async, 'async')) {
				continue;
			}				
			
			if($src) {
				$ar_src[] = $src;
				//удалить скрипт из содержания
				$content = str_replace($script, '', $content);
			}
		}
		
		
		//добавить скрипт с динамической загрузкой		
		$script = '<script type="text/javascript">';					
		$script .= 'function dynamicLoadingJsCss() {';
		
		//динамическая загрузка css			
		foreach($ar_hreff as $id=>$href){			
			$script .= 'if (window.XMLHttpRequest) {';
			$script .= ' xhr = new XMLHttpRequest();';
			$script .= '} else {';
			$script .= '  xhr = new ActiveXObject("Microsoft.XMLHTTP");';
			$script .= '}';
			$script .= 'xhr.open("GET","'.$href.'",false);';
			$script .= 'xhr.send();';
			$script .= 'var lazyStyle = document.createElement("style");';
			$script .= 'lazyStyle.innerHTML = xhr.responseText;';
			$script .= 'document.head.appendChild(lazyStyle);';				
		}		
		
		//динамическая загрузка js			
		foreach($ar_src as $id=>$src){
			$script .= 'if (window.XMLHttpRequest) {';
			$script .= ' xhr = new XMLHttpRequest();';
			$script .= '} else {';
			$script .= '  xhr = new ActiveXObject("Microsoft.XMLHTTP");';
			$script .= '}';
			$script .= 'xhr.open("GET","'.$src.'",false);';
			$script .= 'xhr.send();';
			$script .= 'var lazyStyle = document.createElement("script");';
			$script .= 'lazyStyle.innerHTML = xhr.responseText;';
			$script .= 'document.head.appendChild(lazyStyle);';				
		}			
		
		$script .= '}';
		$script .= 'dynamicLoadingJsCss();';
		$script .= '</script>';			
		
		//добавить в конец содержимого
		$content = str_replace('</body>', $script.'</body>', $content);			
	}
}
?>