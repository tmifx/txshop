/*******************************************************
					API HISTORY
 ******************************************************/
var pages = new Array();

/* ADD PAGE
 */
function addPage(url) {	
	var content = $('#content').html();
	window.history.pushState({url:url}, null, url);			
	pages.push({url:url, content:content});	
}
/* GET PAGE
 */
function getPage(url) {
	var content = '';	
	var cur_url = location.href;		
	
	$.each(pages, function(key, obj) {
		if(obj.url == url) {
			if(url != cur_url) {
				window.history.pushState({url:url}, null, url);	
			}
			content = obj.content;			
		}
	});
	
	return content;
}
/* LOAD - PAGE
 */
$(window).on('popstate', function(event) {	
	var content = getPage(history.state.url); 
	if(content != '') {
		$('#content').html(content);
	}	
	reorderItems();
});
/* FIRST PAGE IN THE HISTORY
 */
$(window).ready(function(){
	addPage(location.href);
	reorderItems();
});
/*******************************************************
					EVENTS
*******************************************************/
$(function() {
	/* COLORBOX
	 */
	$(function() {
		$("a.colorbox").colorbox({rel:'a.colorbox'});
		$.colorbox.settings.returnFocus = false;	
		$("#click").click(function(){ 
			$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("");
			return false;
		});
	});	
	
	/* AJAX
	 */
	$(function() {	
		$('body').off('click', '.wrapper a, .wrapper button, .wrapper input[type="submit"]');
		$('body').on('click', '.wrapper a, .wrapper button, .wrapper input[type="submit"]', function(e) {
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
			if(button.hasClass('send')) {
				return;
			}			
			
			//отправить запрос			
			var content = getPage(url);
			
			//save position scroll
			//savePageScroll();
						
			if(content != '') {
				$('#content').html(content);
				reorderItems();
			}else {							
				$.ajax({				
					url: url,
					type: !type || type=='post'?'post':'get',
					data: data,
					dataType: (button.hasClass('json') ? 'json':'html'),
					beforeSend: function() {
						button.addClass('send');					
					},
					success: function(res) {
						button.removeClass('send');
						
						var new_content = $(res).find('#content').html();									
						$('#content').html(new_content);
						addPage(url);					
						reorderItems();
						
						/*
						if(button.parent().hasClass('paginator')) {							
							//content + new_content
							var content = $('#content').html();						
							$('#content').html(content + new_content);
							
							reorderItems();
							
							if(button.parent().hasClass('paginator')) {	
								//remove paginator
								$('.paginator')[0].remove();
							}
							addPage(url, false);
						}else {
							addPage(url);
							$('#content').html(new_content);
							reorderItems();
						}
						*/
					}
				});
				
			}			
		});
	});	
});
/*******************************************************
		REORDER ITEMS - ПЕРЕУПОРЯДОЧИТЬ ЭЛЕМЕНТЫ
*******************************************************/
$(reorderItems);
function reorderItems() {
	//events
	$(document).ready(elements_pos);
	$(window).resize(elements_pos);
	
	function elements_pos() {		
		var elements = $('.elements');		
		var ar_elements = $('.elements .element');		
		var width_container = $('.elements').innerWidth();
		var max_width_element = 0;
		$('.elements .element').each(function(key, value) {
			var width_element = $(value).innerWidth();			
			if(width_element > max_width_element) {
				max_width_element = width_element;
			}
		});			
		var max_elements_in_row = Math.floor(width_container / max_width_element);
		var step = Math.floor((width_container-(max_elements_in_row * max_width_element)) / max_elements_in_row);
		var step_height = 15;
		
		//set pos elements
		ar_elements.each(function(key, element){			
			element = $(element);
			
			var row = Math.floor(key / max_elements_in_row);
			var left = (step + (max_width_element+step) * key) - row*max_elements_in_row*(max_width_element+step);
			var element_top = $(ar_elements[key - max_elements_in_row]);	
				
			if(element_top.length > 0) {
				var top = parseInt(element_top.position().top) + parseInt(element_top.innerHeight()) + step_height;				
			}
			
			element.css('left', left);
			if(top > 0) {				
				element.css('top', top+'px');			
			}
		});
		
		paginator_pos();
	}
}
/*******************************************************
			PAGINATOR - Переключение страниц
*******************************************************/

function paginator_pos() {
	var max_top = 0;
	$('.elements .element').each(function(key, element) {
		element = $(element);
		var element_top = element.position().top;
		var element_height = element.innerHeight()
		var max = element_top + element_height;
		if(max_top < max) {
			max_top = max;
		}
	});		
	$('.paginator').css('top', max_top+25+'px');
}
/*
var countScrollTop = 0;
$(window).scroll(function (e) {	
	if(countScrollTop++ == 0) {
		$(window).scrollTop(0);
	}else {
		if ($(window).scrollTop() >= $(document).height() - $(window).height() - 100){
			$('.paginator a').click();
		}		
	}
});*/
/*******************************************************
			
*******************************************************/