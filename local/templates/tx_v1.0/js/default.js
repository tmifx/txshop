/*******************************************************
		ADS - РЕКЛАМА
*******************************************************/
$(function() {	
	$('.ads.center .timer').ready(function(){
		var back_obj;		
		var interval = setInterval(function(e) {
			var objects = $('.ads.center .timer');	
			var obj = objects[0];
			if(objects.length > 0  && obj != back_obj) {
				//timer
				var interval_2 = setInterval(function(e) {					
					var obj = $('.ads.center .timer')[0];
					if(back_obj == obj) {
						var value = parseInt($(obj).html()) - 1;						
						if(value >= 0) {
							$(obj).html(value);							
						}else {	
							$('.ads .title').addClass('hidden');
							$('.ads .button-close').removeClass('hidden');
						}						
					}else {						
						clearInterval(interval_2);
					}				
				}, 1000);
				
				$('.ads').removeClass('hidden');
				$('.ads .title').removeClass('hidden');
				$('.ads .button-close').addClass('hidden');
				
				$(obj).html($(obj).data('default'));
				back_obj = objects[0];
				
				
				$('.ads .button-close').on('off');
				$('.ads .button-close').on('click',function(){
					$('.ads iframe').contents().find('html').html('');
					$('.ads').addClass('hidden');
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
	var search_list = $('.search .search-list');
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
				search_list.html($(res).find('#content').html());
				search_list.removeClass('hidden');
			}
		});
		
		back_str = str;
	}, 250);
	
	
	$('body').on('keyup paste click','.search form input', function(e) {
		if(e.keyCode == 13) {
			return;
		}
		str = $(e.target).prop('value');
		if(str.length < min_lenght_q) {
			search_list.addClass('hidden');
		}else {
			search_list.removeClass('hidden');
		}
	});	
	$('body').on('focusin','.search form input', function(e) {						
		if(search_list.html().length > 1 && str.length > 1) {
			search_list.removeClass('hidden');
		}
	});
	
	$('body').on('click', '.search a',function(e) {	
		search_list.addClass('hidden');	
	});
	$('body').on('submit', '.wrapper .search form', function(e) {	
		search_list.addClass('hidden');	
	});
	$('body').on('click', function(e) {
		if($(e.target).closest(".search").length==0 ) {
			search_list.addClass('hidden');			
		}
	});
});

/*******************************************************
		READY GAME-UNITY3D - ГОТОВНОСТЬ UNITY3D ИГРЫ
*******************************************************/
$('#content .game-unity3d').ready(function(e) {
	var back_obj;		
	var interval = setInterval(function(e) {
		var objects = $('#content .game-unity3d');
		if(objects.length > 0  && objects[0] != back_obj) {
			var file_url = objects.data('file_url');
			var width = objects.data('width');
			var height = objects.data('height');
			var game_container = objects.data('game_container');
			var ads_container = objects.data('ads_container');
			
			unity3d_container(file_url, width, height, game_container, ads_container);
			sliderSizeGame();
			
			back_obj = objects[0];
		}	
	}, 100);
});

/*******************************************************
		READY GAME-FLASH - ГОТОВНОСТЬ FLASH ИГРЫ
*******************************************************/
$('#content .game-flash').ready(function(e) {
	var back_obj;		
	var interval = setInterval(function(e) {
		var objects = $('#content .game-flash');
		if(objects.length > 0  && objects[0] != back_obj && typeof(flash_container)=='function') {
			var file_url = objects.data('file_url');
			var width = objects.data('width');
			var height = objects.data('height');
			var game_container = objects.data('game_container');
			var ads_container = objects.data('ads_container');
			
			flash_container(file_url, width, height, game_container, ads_container);
			sliderSizeGame();
			
			back_obj = objects[0];
		}	
	}, 100);
});

/*******************************************************
		READY ELEMENT - ГОТОВНОСТЬ ЭЛЕМЕНТА
 ******************************************************/
$('#content>.element').ready(function(e) {
	var back_obj;		
	var interval = setInterval(function(e) {
		var objects = $('#content>.element');
		if(objects.length > 0  && objects[0] != back_obj) {
			$('#left-menu').addClass('hidden');
			$('#top').removeClass('fixed');
			$('#content').removeClass('col-xs-10');
			$('#content').removeClass('col-xs-offset-2');
			$('#content').addClass('col-xs-12');
		
			back_obj = objects[0];
		}	
	}, 100);
});

/*******************************************************
			SLIDER-SIZE-GAME - РАЗМЕР ИГРЫ
*******************************************************/
function sliderSizeGame() {
	var slider_size = $( "#slider-size" );	
	var container = $('#'+slider_size.data('container'));
	
	var height = slider_size.data('height');
	var width = slider_size.data('width');
	
	var max_width = width * 1.5;
	var min_width = width * 0.5;
		
	$( "#slider-size" ).slider({
	  value:min_width + (max_width - min_width) / 2,
	  min: min_width,
	  max: max_width,
	  step: (max_width - min_width) / 100,
	  slide: function( event, ui ) {
		var scale = ui.value / width;		
		container.css('width',width * scale);
		container.css('height',height * scale);
	  } 
	});	
}

/*******************************************************
		READY ELEMENTS - ГОТОВНОСТЬ СПИСКА ЭЛЕМЕНТОВ
 ******************************************************/
$('#content>.elements').ready(function(e) {
	var back_obj;		
	var interval = setInterval(function(e) {
		var objects = $('#content>.elements');
		if(objects.length > 0 && objects[0] != back_obj) {
			$('#top').addClass('fixed');
			$('#left-menu').addClass('fixed');
			$('#left-menu').removeClass('hidden');
			$('#top-panel-elements').addClass('fixed');
			$('#content').removeClass('col-xs-12');			
			$('#content').addClass('col-xs-10');
			$('#content').addClass('col-xs-offset-2');	
			reFixed();
			
			back_obj = objects[0];
		}	
	}, 100);
});

/*******************************************************
			LEFT MENU SELECTED - АКТИВНОЕ ЛЕВОЕ МЕНЮ
 ******************************************************/
function selected_menu() {	
	var url = window.location.href.split('?')[0];
	$('#left-menu a').each(function(key, value) {
		var item = $(value); 
		if(item.prop('href').split('#')[0] == url) {
			item.addClass('current')
		}else {
			item.removeClass('current');
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
	$('body').on('click', '.wrapper a:not(.ajax):not([href*="#"]):not(.no-history)', event_handler);	
	$('body').on('submit', '.wrapper form', event_handler);
	
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
		
		//get page, 
		var page = getPageHistory(url);	
		
		//save_content (Только для списка элементов)
		if($('.elements').length > 0) {
			addPageHistory();
		}
		
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
			
			//new elements
			if(!$('#left-menu').hasClass('hidden')) {
				elementList.recalculate(-1);	
			}else {
				var interval = setInterval(function() {			
					if(!$('#left-menu').hasClass('hidden')) {
						elementList.recalculate(-1);
						clearInterval(interval);
					}
				},100);	
			}
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
		beforeSend: function() {},
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
			if(!$('#left-menu').hasClass('hidden')) {
				elementList.recalculate(-1);	
			}else {
				var interval = setInterval(function() {			
					if(!$('#left-menu').hasClass('hidden')) {
						elementList.recalculate(-1);
						clearInterval(interval);
					}
				},100);	
			}					
		}
	});
}
function setPage(page) {
	$('#content').html($(page).find('#content').html());			
	$('title').html(page.split('<title>', 2)[1].split('</title>', 2)[0]);
}
/*******************************************************
			FIXED POSITION - ФИКСИРОВАННЫЕ ПОЗИЦИИ
*******************************************************/
$(window).on('scroll', function () {
	reFixed();
});
function reFixed() {
	var top = 0;
	
	if($('#bx-panel').length > 0) {
		top = $('#bx-panel').outerHeight() - $(window).scrollTop() - 1;
		if(top < -1) {		
			top = -1;
		}
	}	
	
	if($('#top.fixed').length > 0) {	
		if($('#top.fixed').position().top != top) {
			$('#top.fixed').css('top', top+'px');		
		}	
		if($('#left-menu.fixed').position().top != $('#top.fixed').position().top + $('#top.fixed').outerHeight()) {
			$('#left-menu.fixed').css('top', $('#top.fixed').position().top + $('#top.fixed').outerHeight()+'px');			
		}
		if($('#top-panel-elements').position().top != $('#top.fixed').position().top + $('#top.fixed').outerHeight()) {
			$('#top-panel-elements').css('top', $('#top.fixed').position().top + $('#top.fixed').outerHeight()+'px');
		}
	}	
}

/*******************************************************
			UNITY3D CONTAINER - Unity3D КОНТЭЙНЕР
*******************************************************/
function unity3d_container(file_url, width, height, unity3d_container,  container_ads) {
	var container = $('#'+unity3d_container).parent();
	container.css('width', width);
	container.css('height', height);
	container.addClass('hide');
	
	
	this.end_download = false;
	
	var u = new UnityObject2();
	 u.observeProgress(function (progress) {
		if(progress.pluginStatus == 'installed') {
			this.end_download = true;			
		}
	 });
	u.initPlugin($('#'+unity3d_container)[0], file_url);
	
	var interval = setInterval(function(e){	
		if($('.ads').hasClass('hidden')) {
			$('#'+unity3d_container).removeClass('hide');
			container.removeClass('hide');
			clearInterval(interval);
		}
	},100);	
}

/*******************************************************
			FLASH CONTAINER - ФЛЕШ КОНТЭЙНЕР
*******************************************************/
function flash_container(file_url, width, height, container_flash, container_ads) {		
	$('#'+container_ads).removeClass('hidden');
		
	var container = $('#'+container_flash);
	container.css('width', width);
	container.css('height', height);
	container.addClass('hide');
	
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
		//params.wmode = "opaque";
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
		if($('.ads').hasClass('hidden')) {
			container.find('object').removeClass('hide');
			container.removeClass('hide');
			clearInterval(interval);
		}
	},100);
	
}
/*******************************************************
			LOAD PAGE - ПОСЛЕ ЗАГРУЗКИ СТРАНИЦЫ
*******************************************************/
$(window, document).load(function(){
	elementList.sorting_all();
});
/*******************************************************
			RESIZE - ИЗМЕНЕНИЕ ЭКРАНА
*******************************************************/
$(window, document).resize(function() {
	elementList.sorting_all();
});
/*******************************************************
			EVENTS- СОБЫТИЯ
*******************************************************/
$(function() {
	/* COLORBOX	 
	$(function() {
		$("a.colorbox").colorbox({rel:'a.colorbox'});
		$.colorbox.settings.returnFocus = false;	
		$("#click").click(function(){ 
			$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("");
			return false;
		});
	});	
	*/
	/* AJAX
	 */
	$(function() {
		$('body').on('click', '.wrapper .ajax', function(e) {		
			e.preventDefault();		
						
			var button = $(this);
			
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
				beforeSend: function() {},
				success: function(res) {					
					if(button.parent().hasClass('paginator')) {						
						var container = button.parent().parent();						
						var last_index = $('#content .elements .element').length-1;
						var new_content = $(res).find('#content .elements').html();						
						container.append(new_content);					
												
						//new elements						
						elementList.recalculate(last_index);
						
						//anim - load
						$('#loading').removeClass('load');
						
						//remove old paginators
						removeOldPaginators()
					}
					if(button.parents('.add-favorite').length > 0) {
						res = JSON.parse(res);						
						button.html(res.text);
					}
				}
			});
			
		});
	});	
});
/*******************************************************
		REORDER ITEMS - ПЕРЕУПОРЯДОЧИТЬ ЭЛЕМЕНТЫ
*******************************************************/
var elementList = new function(){	
	this.ar_elements = $('.elements .element');
	this.elements = $('.elements');			
	this.width_container = $('.elements').outerWidth();
	this.elements_in_row = 4;						
	this.step = 20;
	this.step_left = 20;
	this.step_height = 20;
	this.max_width_element = (this.width_container+this.step_left) / this.elements_in_row - this.step;	
	this.index = -1;	
		
	this.setIndex = function(index) {
		this.index = index;
	};	

	this.recalculate = function(index) {		
		this.ar_elements = $('.elements .element');
		this.elements = $('.elements');
		
		this.width_container = $('.elements').outerWidth();
		this.max_width_element = (this.width_container+this.step_left) / this.elements_in_row - this.step;	
			
		if(index != null) {
			this.index = index;
		}
	};
	
	this.sorting = function(){
		if(this.ar_elements.length == 0) 
			return;
		
		if(++this.index > this.ar_elements.length-1) {
			this.index = 0;
			//paginator-start
			$('body').removeClass('paginator-stop');
		}
		this.element = $(this.ar_elements[this.index]);
		this.set_element_pos(this.element, this.index);		
	};
	
	this.sorting_all = function() {
		this.recalculate();
		this.ar_elements.each(function(index, element) {
			elementList.set_element_pos($(element), index);	
		});
	}
	
	this.set_element_pos = function(element, index) {		
		this.row = Math.floor(index / this.elements_in_row);
		this.left = (this.step_left + (this.max_width_element+this.step) * index) - this.row*this.elements_in_row*(this.max_width_element+this.step);
		this.element_top = $(this.ar_elements[index - this.elements_in_row]);	
		if(this.row > 0) {
			this.top = this.element_top.position().top + this.element_top.outerHeight() + this.step_height;								
		}
				
		element.css('left', this.left);
		if(this.row > 0) {			
			element.css('top', this.top+'px');			
		}	
		
		if(element.css('opacity') != 1) {
			element.css('opacity', 1);		
		}
		
		if(element.css('position') != 'absolute') {
			element.css('position', 'absolute');
		}
		
		if(element.css('width') != this.max_width_element) {
			element.css('width', this.max_width_element);		
		}
	}	
	
};
setInterval(function(){
	elementList.sorting();
}, 50);
/*******************************************************
			PAGINATOR - ПЕРЕКЛЮЧЕНИЕ СТРАНИЦ
*******************************************************/
setInterval(function(){
	if ($(window).scrollTop() >= $(document).height() - $(window).height()-200){
		if(!$('body').hasClass('paginator-stop') && $('.paginator:last-child a').length > 0) {
			$('#loading').addClass('load');
			$('body').addClass('paginator-stop');			
			$('.paginator:last-child a').click();			
		}
	}
}, 100);
function removeOldPaginators() {
	$('.paginator:not(:last-child)').remove();
}
/*******************************************************
			BACK-TOP - ПОДНЯТЬСЯ НАВЕРХ
*******************************************************/
$('#back-top').on('click mousedown mouseup', function() {	
	$('body,html').animate({
		scrollTop: 0
	}, 400);
});
$(function() {
	$(document).ready(function () {		
		$("#back-top").hide();
		$(window).on('scroll', function () {
			if ($(this).scrollTop() > $('#left-menu').position().top + $('#left-menu').outerHeight()) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});		
	});
});
