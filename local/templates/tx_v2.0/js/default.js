/*******************************************************
		SHARE - Поделились
*******************************************************/
$(function() {
	var back_obj;		
	var interval = setInterval(function(e) {	
		var objects = $('.ya-share2__link');
		if(objects.length > 0  && objects[0] != back_obj) {			
			var obj = objects[0];
			
			$('.ya-share2__link').on('click', function() {
				var element_id = $('.ya-share2').data('element-id');
				
				//send - отправить
				$.ajax({
					type: "POST",
					url: '/ajax/add_share/',
					data: '&id='+element_id,
					beforeSend: function() {},
					success: function(res){}
				});
			});
			back_obj = objects[0];
		}		
	}, 100);
});

/*******************************************************
		POPUP - всплывающее окно
*******************************************************/
$('.b-popup__background').ready(function() {
	$('.b-popup__background').on('click', function() {
		$('.b-popup').addClass('b-popup_hidden');
	});
});
/*******************************************************
		ADS - РЕКЛАМА
*******************************************************/
$(function() { 
	$('.b-ads').ready(function(){
		var back_obj;		
		setInterval(function(e) {
			var objects = $('.b-ads__timer');	
			var obj = objects[0];
			if(objects.length > 0  && obj != back_obj) {
				//timer
				var interval = setInterval(function(e) {					
					var obj = $('.b-ads__timer')[0];
					if(back_obj == obj) {
						var value = parseInt($(obj).html()) - 1;						
						if(value >= 0) {
							$(obj).html(value);							
						}else {	
							$('.b-ads__title').addClass('b-ads__title_hidden');
							$('.b-ads__close').removeClass('b-ads__close_hidden');
						}						
					}else {						
						clearInterval(interval);
					}				
				}, 1000);
				
				$('.b-detail-element__ads').removeClass('b-detail-element__ads_hidden');
				$('.b-ads').removeClass('b-ads_hidden');
				$('.b-ads__title').removeClass('b-ads__title_hidden');
				$('.b-ads__close').addClass('b-ads__close_hidden');
				
				$(obj).html($(obj).data('default'));
				back_obj = objects[0];
				
				
				$('.b-ads__close').on('off');
				$('.b-ads__close').on('click',function(){
					$('.b-ads .b-ads__container').contents().find('html').html('');
					$('.b-ads').addClass('b-ads_hidden');
					$('.b-detail-element__ads').addClass('b-detail-element__ads_hidden');					
				});
			}			
		}, 100);
	});	
});
/*******************************************************
		SEARCH-LIST - ПОИСК
*******************************************************/
$(function() {	
	var str = '';
	var back_str = '';
	var search_list = $('.b-search__list');
	var min_lenght_q = 2;
		
	setInterval(function() {				
		if(str == back_str || str.length < min_lenght_q) 
			return;
		
		$.ajax({
			type: "POST",
			url: '/',
			data: 'search='+str+'&search_list=y',
			beforeSend: function() {},
			success: function(res){
				search_list.html($(res).find('.b-page-container__content').html());
				search_list.removeClass('b-search__list_hidden');
			}
		});
		
		back_str = str;
	}, 250);
	
	
	$('body').on('keyup paste click','.b-search form input', function(e) {
		if(e.keyCode == 13) {
			return;
		}
		str = $(e.target).prop('value');
		if(str.length < min_lenght_q) {
			search_list.addClass('b-search__list_hidden');
		}else {
			search_list.removeClass('b-search__list_hidden');
		}
	});	
	$('body').on('focusin','.b-search form input', function(e) {						
		if(search_list.html().length > 1 && str.length > 1) {
			search_list.removeClass('b-search__list_hidden');
		}
	});
	
	$('body').on('click', '.b-search a',function(e) {	
		search_list.addClass('b-search__list_hidden');	
	});
	$('body').on('submit', '.b-search form', function(e) {	
		search_list.addClass('b-search__list_hidden');	
	});
	$('body').on('click', function(e) {
		if($(e.target).closest(".b-search").length==0 ) {
			search_list.addClass('b-search__list_hidden');			
		}
	});
});
/*******************************************************
			SIZE-GAME - РАЗМЕР ИГРЫ
*******************************************************/
$('.b-game-panel-size').ready(function(e) {
	var back_obj;		
	var interval = setInterval(function(e) {
		var objects = $('.b-game-panel-size');
		if(objects.length > 0  && objects[0] != back_obj) {
			var obj = $(objects[0]);			
			var container = $('.'+obj.data('game_container'));
			var default_width = obj.data('default_width');
			var default_height = obj.data('default_height');
			var width = default_width;				
			var height = default_height;
			var step_width = default_width*0.1;
			var step_height = default_height*0.1;
			
			
			$('.b-game-panel-size__el_minus').on('click', function() {					
				container.css('width', width = width - step_width);
				container.css('height', height = height - step_height);
				
				img_reset();
			});
			
			$('.b-game-panel-size__el_plus').on('click', function() {			
				container.css('width', width = width + step_width);
				container.css('height', height = height + step_height);
								
				img_reset();
			});
			
			$('.b-game-panel-size__el_reset').on('click', function() {	
				container.css('width', width = default_width);
				container.css('height', height = default_height);
				
				img_reset();
			});
			
			function img_reset() {
				if(default_width != width || default_height != height) {
					$('.b-game-panel-size__el_reset').removeClass('img-no-reset');
					$('.b-game-panel-size__el_reset').addClass('img-reset');
				}else {
					$('.b-game-panel-size__el_reset').addClass('img-no-reset');
					$('.b-game-panel-size__el_reset').removeClass('img-reset');
				}
			}
			
			back_obj = objects[0];
		}	
	}, 100);
});

/*******************************************************
		READY GAME-UNITY3D - ГОТОВНОСТЬ UNITY3D ИГРЫ
*******************************************************/
$('.b-detail-element__game_unity3d').ready(function(e) {
	var back_obj;		
	var interval = setInterval(function(e) {
		var objects = $('.b-detail-element__game_unity3d');
		if(objects.length > 0  && objects[0] != back_obj) {
			var file_url = objects.data('file_url');
			var width = objects.data('width');
			var height = objects.data('height');
			var game_container = objects.data('game_container');
			var ads_container = objects.data('ads_container');
			
			unity3d_container(file_url, width, height, game_container, ads_container);
						
			back_obj = objects[0];
		}	
	}, 100);
});

/*******************************************************
		READY GAME-FLASH - ГОТОВНОСТЬ FLASH ИГРЫ
*******************************************************/
$('.b-detail-element__game_flash').ready(function(e) {
	var back_obj;		
	var interval = setInterval(function(e) {
		var objects = $('.b-detail-element__game_flash');
		if(objects.length > 0  && objects[0] != back_obj && typeof(flash_container)=='function') {
			var file_url = objects.data('file_url');
			var width = objects.data('width');
			var height = objects.data('height');
			var game_container = objects.data('game_container');
			var ads_container = objects.data('ads_container');
			
			flash_container(file_url, width, height, game_container, ads_container);
				
			back_obj = objects[0];
		}	
	}, 100);
});

/*******************************************************
		READY ELEMENT - ГОТОВНОСТЬ ЭЛЕМЕНТА
 ******************************************************/
$('.b-detail-element').ready(function(e) {
	var back_obj;		
	var interval = setInterval(function(e) {
		var objects = $('.b-detail-element');
		if(objects.length > 0  && objects[0] != back_obj) {
			$('.b-page-container__left-column').addClass('b-page-container__left-column_hidden');
			$('.b-page-container__content').addClass('b-page-container__content_full');
			$('.b-header').removeClass('b-header_fixed');
			
			back_obj = objects[0];
		}	
	}, 100);
});


/*******************************************************
		READY ELEMENTS - ГОТОВНОСТЬ СПИСКА ЭЛЕМЕНТОВ
 ******************************************************/
$('.b-elements').ready(function(e) {
	var back_obj;		
	var interval = setInterval(function(e) {
		var objects = $('.b-elements');
		if(objects.length > 0 && objects[0] != back_obj) {
			$('.b-header').addClass('b-header_fixed');
			$('.b-page-container__left-column').addClass('b-page-container__left-column_fixed');
			$('.b-page-container__left-column').removeClass('b-page-container__left-column_hidden');
			$('.b-page-container__content').removeClass('b-page-container__content_full');
			$('.b-sort').addClass('b-sort_fixed');
			$('.b-title').addClass('b-title_fixed');
			rePosition();
			
			back_obj = objects[0];
		}	
	}, 100);
});

/*******************************************************
			LEFT MENU SELECTED - АКТИВНОЕ ЛЕВОЕ МЕНЮ
 ******************************************************/
function selected_menu() {	
	var url = window.location.href.split('?')[0];
	$('.b-menu__section').each(function(key, value) {
		var item = $(value); 
		if(item.prop('href') == url) {
			item.addClass('b-menu__section_current')
		}else {
			item.removeClass('b-menu__section_current');
		}
	});
} 
setInterval(function() {
	selected_menu();
}, 100);

/*******************************************************
					API HISTORY
 ******************************************************/
//КЭШ СТРАНИЦ
var pages_cache = new Array();

//ADD PAGE
function addPageHistory(time_cache) {
	if(!time_cache) {
		time_cache = 3600;
	}
	var url = window.location.href;
	var content = $('html').html();
	var scroll = $(window).scrollTop();	
	var scroll = $(window).scrollTop();		
	var max_time = (new Date().getTime()) + time_cache*1000;	
	var test = true;
	
	$.each(pages_cache, function(key, page) {
		if(test == false) {
			return;
		}
		if(page.url != url) {
			return;
		}
		
		//update page
		page.url = url;
		page.content = content;
		page.scroll = scroll;
		
		test = false;
		
	});	
	if(test == true) {
		pages_cache.push({url:url, content:content, scroll:scroll, max_time:max_time});	
	}
}
function removePageHistory(url) {
	var i = pages_cache.length;
	
	while(--i >= 0) {
		var page = pages_cache[i];
		
		if(page.url == url) {
			pages_cache.splice(i,1);
			return;
		}
	}	
}
//SET URL
function setUrlHistory(url) {	
	$(document).ready(function () {		
		var maximum_num_cycles = 10;
		var num_cycles = 0;
		var interval = setInterval(function(){			
			var cur_url = window.location.href;				
			if(url != cur_url) {			
				window.history.pushState({url:url}, null, url);		
				clearInterval(interval);
			}else if(++num_cycles > maximum_num_cycles) {				
				clearInterval(interval);			
			}
		},10);
	});
}
//GET PAGE
function getPageHistory(url) {
	var content = '';	
	var cur_url = window.location.href;	
	var time = new Date().getTime();	
	
	var i = pages_cache.length;	
	while(--i >= 0) {
		var page = pages_cache[i];	
		if(page.url == url) {
			if(page.max_time < time) {
				removePageHistory(url);
				return content;
			}
			
			if(url != cur_url) {
				setUrlHistory(url);	
			}				
			
			return page.content;
		}		
	};
	
	return content;
}

//SET SCROLL
function setScrollFromHistory(url) {
	$(document).ready(function () {
		setTimeout(function(){
			$.each(pages_cache, function(key, page) {
				if(page.url == url) {
					if(page.scroll > 0) {
						$(window).scrollTop(page.scroll);	
					}else {
						$(window).scrollTop(0);
					}
				}
			});
		},200);
	});
}

//LOAD - PAGE
$(window).on('popstate', function(event) {
	if(history.state != null) {
		var url = history.state.url;
		var page = getPageHistory(url); 
		if(page != '') {
			//set page							
			setPage(page);
			
			setScrollFromHistory(url);
		}else {
			getAjaxPage(url, false);
		}
	}else {
		getAjaxPage(window.location.href, false);
	}
});

//AJAX FOR HISTORY
$(function() {
	$('body').on('click', '.b-header a:not(.ajax):not([href*="#"]):not(.no-history)', event_handler);	
	$('body').on('submit', '.b-header form', event_handler);
	$('body').on('click', '.b-page a:not(.ajax):not([href*="#"]):not(.no-history)', event_handler);	
	
	function event_handler(e) {				
		if(typeof(window.history.pushState) != 'function' || typeof(window.history) != 'object') {
			return;
		}
		e.preventDefault();		
				
		var obj = $(this);
		
		var url = null;			
		var time_cache = null;			
		
		if(obj.prop('href')) {
			url = obj.prop('href');			
		}else if(obj.prop('action')) {			
			url = obj.prop('action')+'?'+obj.serialize();
		}
				
		var load_scroll = false;
		if(obj.hasClass('load-scroll-history')) {
			load_scroll = true;
		}
		
		
		//url=null
		if(!url) {
			return;
		}
		
		//delete_cache
		if(obj.hasClass('no-cache')) {
			removePageHistory(url);
		}
		
		//save_content (Только для списка элементов)
		if($('.b-elements').length > 0) {
			addPageHistory();
		}
			
		//get page, 
		var page = getPageHistory(url);	
		
		if(page != '') {	
			//set scroll
			if(load_scroll == false) {
				$(window).scrollTop(0);
			}else {
				setScrollFromHistory(url);
			}
			
			//set page							
			setPage(page);
			
			//set url
			setUrlHistory(url);			
			
			//recalculate the position
			rePosition();
			
			elementList.recalculate();
		}else {		
			getAjaxPage(url, load_scroll);			
		}	
	}	
});
function getAjaxPage(url, load_scroll) {	
	$.ajax({				
		url: url,
		type: 'post',
		dataType: 'html',		
		beforeSend: function() {
			$('#loading-top').addClass('load');
			if($('.b-page-container__content_full').length > 0) {
				$('#loading-top').addClass('center');
			}
		},
		success: function(page) {			
			//set scroll
			if(load_scroll == false) {
				$(window).scrollTop(0);
			}else {
				setScrollFromHistory(url);
			}

			//set page							
			setPage(page);
						
			//set url
			setUrlHistory(url);
			
			//new elements
			elementList.init();
			
			//recalculate the position
			rePosition();			
		},
		complete: function() {
			$('#loading-top').removeClass('load');
			$('#loading-top').removeClass('center');
		}
	});
}
function setPage(page) {
	$('.b-page-container__content').html($(page).find('.b-page-container__content').html());			
	$('title').html(page.split('<title>', 2)[1].split('</title>', 2)[0]);
}
/*******************************************************
			FIXED POSITION - ФИКСИРОВАННЫЕ ПОЗИЦИИ
*******************************************************/
$(window).on('scroll', function () {
	rePosition();
});
$(window).resize(function() {
	rePosition();
});
$('#bx-panel').ready(function() {
	$('#bx-panel-hider, #bx-panel-expander').on('click', function() {
		rePosition();
	});
	$('#bx-panel').on('dblclick', function() {
		rePosition();
	});
});
function rePosition() {	
	if($('.b-header.b-header_fixed').length > 0) {
		//bx_panel
		var bx_panel = function() {
			return $('#bx-panel');
		}
		var bx_panel_pos = function() {
			var pos = bx_panel().outerHeight() - $(window).scrollTop();
			return pos>0?pos:0;
		}
		
		//offset				
		var offset = 0;		
		
		$(function(){
			var max_fixed_y = 0;
			var min_fixed_y = 999999999999;
			var window_scroll = $(window).scrollTop();
			var window_height = $(window).outerHeight();
			var panel_pos = bx_panel_pos();
			var panel = bx_panel();
			var panel_height = bx_panel().outerHeight();
			
			function min_fixed() {				
				$('[class*="fixed"]').each(function(){
					if($(this).css('display') == 'none')
						return;
					
					var y = $(this).position().top;											
					if(y < min_fixed_y) {
						min_fixed_y = y;
					}
					
				});			
			}
			min_fixed();
						
			function max_fixed() {
				$('[class*="fixed"]').each(function(){
					if($(this).css('display') == 'none')
						return;
					
					var y = $(this).position().top-min_fixed_y + $(this).outerHeight();
					if(y > max_fixed_y) {
						max_fixed_y = y;
					}
				});
			}
			max_fixed();
			
			if(window_height < max_fixed_y) {
				offset += max_fixed_y - window_height;			
			}		
			if(panel.length > 0) {		
				if(window_scroll + (panel_pos - panel_height) < offset) {		
					offset = window_scroll + (panel_pos - panel_height);				
				}
			}else if(window_scroll < offset) {				
				offset = window_scroll;				
			}
		});
		
		//header
		var header = function(){
			return $('.b-header');
		};
		var header_pos = function(){
			if(header().length > 0) {
				return header().position().top + header().outerHeight();
			}else {
				return 0;
			}
		}
		//left_column
		var left_column = function(){			
			return $('.b-page-container__left-column');
		}	
		var left_column_pos = function(){
			if(left_column().length > 0) {
				return left_column().position().top;
			}else {
				return 0;
			}
		}
		//sort
		var sort = function(){
			return $('.b-sort');
		}
		var sort_pos = function(){
			if(sort().length > 0) {
				return sort().position().top + sort().outerHeight();
			}else {
				return 0;
			}
		}
		//title
		var title = function(){
			return $('.b-title');
		}
		var title_pos = function(){
			if(title().length > 0) { 
				return title().position().top + title().outerHeight();
			}else {
				return 0;
			}
		}
		//message		
		var message = function(){
			return $('.b-message');
		}
		var message_pos = function() {
			if(message().length > 0) {
				return message().position().top + message().outerHeight();
			}else {
				return 0;
			}
		}
		//elements
		var elements = function() {
			return $('.b-elements');
		}
		var elements_pos = function() {
			if(elements().length > 0) {
				return elements().position().top + elements().outerHeight();
			}else {
				return 0;
			}
		}
		
		/* LOGIC		
		 */
		if(bx_panel().length > 0) {
			header().css('top', bx_panel_pos()-offset+'px');
		}else {
			header().css('top', -offset+'px');	
		}
						
		if(left_column_pos() != header_pos()) {			
			left_column().css('top', header_pos()+'px');			
		}
		
		if(sort_pos() != header_pos()) {
			sort().css('top', header_pos()+'px');
		}	
		
		
		if(title().hasClass('b-title_fixed')) {
			title().css('top', header_pos()+'px');
			title().css('margin-top', '0px');
		}else {
			title().css('margin-top', header().outerHeight()+'px');
			title().css('top', '0px');
		}		
		if(sort().hasClass('b-sort_fixed') || title().hasClass('b-title_fixed')) {
			elements().css('margin-top', header().outerHeight()+sort().outerHeight()+title().outerHeight()+'px');		
		}
		
	}	
}
/*******************************************************
			UNITY3D CONTAINER - Unity3D КОНТЭЙНЕР
*******************************************************/
function unity3d_container(file_url, width, height, container_game,  container_ads) {
	var container = $('.'+container_game);
	container.css('width', width);
	container.css('height', height);
	container.addClass(container_game+'_hidden');
	
	
	this.end_download = false;
	
	var u = new UnityObject2();
	 u.observeProgress(function (progress) {
		if(progress.pluginStatus == 'installed') {
			this.end_download = true;			
		}
	 });
	u.initPlugin($('.'+container_game)[0], file_url);
	
	var interval = setInterval(function(e){	
		if($('.b-ads').hasClass('b-ads_hidden')) {
			container.removeClass(container_game+'_hidden');
			clearInterval(interval);
		}
	},100);	
}

/*******************************************************
			FLASH CONTAINER - ФЛЕШ КОНТЭЙНЕР
*******************************************************/
function flash_container(file_url, width, height, container_game, container_ads) {		
	$('.'+container_ads).removeClass(container_ads+'_hidden');
		
	var container = $('.'+container_game);
	container.css('width', width);
	container.css('height', height);
	container.addClass(container_game+'_hidden');
	
	var swfVersionStr = "0";
	var xiSwfUrlStr = "";
	var flashvars = {};
	var id_container = new Date().getTime();
	var params = {};
		params.quality = "high";
		params.bgcolor = "#000000";
		params.play = "true";
		params.loop = "true";
		params.wmode = "direct";
		//params.scale = "noscale";
		params.menu = "false";
		params.devicefont = "false";
		params.salign = "";
		params.allowscriptaccess = "sameDomain";
		params.allowFullScreen = "true";
	var attributes = {};
		attributes.id = id_container;
		attributes.name = "game";
		attributes.align = "middle";
	var el = container.find('div')[0];
		
	swfobject.embedSWF(
		file_url, el,
		'100%', '100%',
		swfVersionStr, xiSwfUrlStr,
		flashvars, params, attributes);	
			
	var interval = setInterval(function(e){	
		if($('.b-ads').hasClass('b-ads_hidden')) {
			container.find('object').removeClass('hide');
			container.removeClass(container_game+'_hidden');
			clearInterval(interval);
		}
	},100);
	
}

/*******************************************************
			EVENTS- СОБЫТИЯ
*******************************************************/
$(function() {
	/* AJAX
	 */
	$(function() {
		$('body').on('click', '.b-page .ajax', function(e) {		
			e.preventDefault();		
						
			var button = $(this);
			if(button.hasClass('ajax_loading')){
				return false;
			}
			
			var data = null;
			var url = null;
			var type = null;			
			
			if(button.prop('href')) {
				url = button.prop('href');
			}else if(button.prop('type') == 'submit'){
				var form = button.closest('form');
				url = form.prop('action');
				data = form.serialize();				
			}		
			$.ajax({				
				url: url,
				type: !type || type=='post'?'post':'get',
				data: data,
				dataType: (button.hasClass('json') ? 'json':'html'),
				beforeSend: function() {
					button.addClass('ajax_loading');
				},
				success: function(res) {					
					if(button.parent().hasClass('b_paginator')) {						
						var container = button.parent().parent();
						var new_content = $(res).find('.b-elements').html();						
						container.append(new_content);					
						
						//new elements
						elementList.newElements();
						
						//animation - load
						$('#loading').removeClass('load');
						
						//remove old paginators
						removeOldPaginators();						
					}
					if(button.hasClass('ajax_replace-content')) {
						res = JSON.parse(res);						
						button.html(res.text);
					}
					if(button.hasClass('ajax_popup-content')) {
						$('.b-popup').removeClass('b-popup_hidden');
						$('.b-popup').find('.b-popup-container__title').html(res.name);
						$('.b-popup').find('.b-popup-container__content').html(res.content);
					}
				},
				complete: function() {
				  button.removeClass('ajax_loading');				 
				}				
			});
			
		});
	});	
});
/*******************************************************
		REORDER ITEMS - ПЕРЕУПОРЯДОЧИТЬ ЭЛЕМЕНТЫ
*******************************************************/
/* Инициализировать
 */
$('.b-elements').ready(function(){
	elementList.init();
});
/* Изменение экрана
 */
$(window).resize(function() {
	elementList.recalculate();
});

var elementList = new function(){
	this.elements_all = new Array(); //массив всех элементов
	this.col_index = 0; //глобальный индекс последней колонки в которую было добавление
	this.el_index = 0; //глобальный индекс последнего элемента
	
	
	//Тест списка
	this.test_element = function() {
		if($('.b-elements').length > 0) {
			return true;		
		}else {
			return false;
		}
	}
	
	// Инициализация	 
	this.init = function() {
		if(this.test_element() != true)
			return;
	
		//сбросить переменные
		this.col_index = 0;
		this.el_index = 0;
		
		//очистить массив с элементами
		this.elements_all = new Array();
		
		//удалить ранее созданные колонки
		this.remove_unnecessary_columns();
		
		//добавить элементы в массив
		this.addElements();
		
		//создать колонки
		this.createColumns();
		
		//переместить элементы в колонки
		this.moveElementsInColumns();
		
		//удалить не нужные элементы
		this.remove_unnecessary_elements();
	}
	
	// Новые элементы
	this.newElements = function() {
		if(this.test_element() != true)
			return;
		
		//добавить элементы в массив
		this.addElements();
		
		//переместить элементы в колонки
		this.moveElementsInColumns();
		
		//удалить не нужные элементы
		this.remove_unnecessary_elements();
		
		//удалить блокировку пагинации
		$('body').removeClass('b_paginator-stop');	
	}
	
	// Пересчитать элементы
	this.recalculate = function() {
		if(this.test_element() != true)
			return;
		
		if(this.getNumColumns() == $('.b-elements__col').length) {
			return;
		}		
		//сбросить переменные
		this.col_index = 0;
		this.el_index = 0;
		
		//запомнить положение scroll до изменений
		this.scroll = $(window).scrollTop();
				
		//очистить массив с элементами
		this.elements_all = new Array();
		
		//записать все элементы в массив
		this.addElementsAllInArray();
		
		//удалить ранее созданные колонки
		this.remove_unnecessary_columns();
		
		//создать колонки
		this.createColumns();
		
		//переместить элементы в колонки
		this.moveElementsInColumns();	
		
		//загрзить позицию скрола, после изменений
		this.scroll = $(window).scrollTop(this.scroll);
	}
			
	//Получить количество колонок, изменяеться в зависимости от размера экрана
	this.getNumColumns = function() {
		var header_width = $('.b-header__container').outerWidth();
		
		if(header_width >= 1100) {
			return 4;
		}else if(header_width >= 800) {
			return 3;
		}else if(header_width >= 500) {
			return 2;
		}else {
			return 1;
		}
	}
	
	//создать колонки 
	this.createColumns = function() {
		var columns = this.getNumColumns();		
		var b_elements = $('.b-elements');
		
		//создать колонки и блоки колонок
		while(columns-- > 0) {
			b_elements.prepend('<div class="b-elements__col"></div>');			
		}		
		$('.b-elements__col').each(function(){
			$(this).append('<div class="b-elements-col"></div>');
		});
	}
	
	//Добавить новые элементы в массив
	this.addElements = function() {
		var elements_all = this.elements_all;
		$('.b-elements .b-elements__el').each(function(){
			elements_all.push($(this).find('.b-element'));
		});
	}
	
	//Добавить все элементы в массив
	this.addElementsAllInArray = function() {		
		var elements_all = this.elements_all;
		var ar_columns = new Array();		
		var count_elements = $('.b-elements .b-element').length;
		var i = 0;
		
		$('.b-elements .b-elements__col').each(function(){
			var elements = new Array();
			$(this).find('.b-element').each(function(){
				elements.push($(this));
			});
			ar_columns.push(elements);
		});
		
		
		while(elements_all.length != count_elements) {
			elements_all.push(ar_columns[i].shift());		
			if(i < ar_columns.length-1) {
				i++;
			}else {
				i = 0;
			}
		}
	}
	
	//Переместить элменеты в колонки
	this.moveElementsInColumns = function() {
		var elements = this.elements_all;
		var count_elements = this.elements_all.length;				
		var columns = $('.b-elements .b-elements__col');
		var count_columns = columns.length;		
		var work = true;
		
		
		while(work == true) {					 
			while(this.col_index < count_columns) {		
				if(this.el_index < count_elements) {
					var column = $($(columns[this.col_index]).find('.b-elements-col'));
					
					//создать контэйнер
					column.append('<div class="b-elements-col__el"></div>');
					
					var column_el = column.find('.b-elements-col__el:last-child');
					
					//добавить блок элемента
					column_el.append(elements[this.el_index]);
					
					this.el_index++;//перейти к следующему элементу
				}else {
					//выйти из массива
					work = false;
				}			
				this.col_index++;//перейти к следующей колонке
			}
			this.col_index = 0;	
		}
	}
	
	
	//Удалить лишние элементы
	this.remove_unnecessary_elements = function() {
		$('.b-elements .b-elements__el').remove();
	}
	//Удалить лишние колонки
	this.remove_unnecessary_columns = function() {
		$('.b-elements .b-elements__col').remove();
	}
};
/*******************************************************
			PAGINATOR - ПЕРЕКЛЮЧЕНИЕ СТРАНИЦ
*******************************************************/
setInterval(function(){
	if ($(window).scrollTop() >= $(document).height() - $(window).height()-200){	
		if(!$('body').hasClass('b_paginator-stop') && $('.b_paginator:last-child a').length > 0) {			
			$('#loading').addClass('load');
			$('body').addClass('b_paginator-stop');			
			$('.b_paginator:last-child a').click();			
		}
	} 
}, 100);
function removeOldPaginators() {
	$('.b_paginator:not(:last-child)').remove();
}
/*******************************************************
			UP - ПОДНЯТЬСЯ НАВЕРХ
*******************************************************/

$(function() {
	$(document).ready(function () {		
		$(".b-up").hide();
		$('.b-up').on('click mousedown mouseup', function() {	
			$('body,html').animate({
				scrollTop: 0
			}, 400);
		});
		$(window).on('scroll', function () {
			var left_column = $('.b-page-container__left-column');
			if ($(this).scrollTop() > left_column.position().top + left_column.outerHeight()) {
				$('.b-up').fadeIn();
			} else {
				$('.b-up').fadeOut();
			}
		});
	});
});
