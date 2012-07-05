String.prototype.trim = function(str) {
	str = str || '';
	return this.replace(/^\s+|\s+$/g, str);
}

function convert_url(str){
	var string = str.toLowerCase().replace(/[\'"]/g, '').trim();
    string = string.replace(/[^a-zA-Z0-9]+/g, '-');
    return string;
}

var current_url = '';

// HTML5 placeholder plugin version 1.01
// Copyright (c) 2010-The End of Time, Mike Taylor, http://miketaylr.com
// MIT Licensed: http://www.opensource.org/licenses/mit-license.php
//
// Enables cross-browser HTML5 placeholder for inputs, by first testing
// for a native implementation before building one.
//
//
// USAGE: 
//$('input[placeholder]').placeholder();

// <input type="text" placeholder="username">
(function($){
  //feature detection
  var hasPlaceholder = 'placeholder' in document.createElement('input');
  
  //sniffy sniff sniff -- just to give extra left padding for the older
  //graphics for type=email and type=url
  var isOldOpera = $.browser.opera && $.browser.version < 10.5;

  $.fn.placeholder = function(options) {
    //merge in passed in options, if any
    var options = $.extend({}, $.fn.placeholder.defaults, options),
    //cache the original 'left' value, for use by Opera later
    o_left = options.placeholderCSS.left;
  
    //first test for native placeholder support before continuing
    //feature detection inspired by ye olde jquery 1.4 hawtness, with paul irish
    return (hasPlaceholder) ? this : this.each(function() {
  	  //TODO: if this element already has a placeholder, exit
    
      //local vars
      var $this = $(this),
          inputVal = $.trim($this.val()),
          inputWidth = $this.width(),
          inputHeight = $this.height(),

          //grab the inputs id for the <label @for>, or make a new one from the Date
          inputId = (this.id) ? this.id : 'placeholder' + (Math.floor(Math.random() * 1123456789)),
          placeholderText = $this.attr('placeholder'),
          placeholder = $('<label for='+ inputId +'>'+ placeholderText + '</label>');
        
      //stuff in some calculated values into the placeholderCSS object
      options.placeholderCSS['width'] = inputWidth;
      options.placeholderCSS['height'] = inputHeight;
      options.placeholderCSS['color'] = options.color;

      // adjust position of placeholder 
      options.placeholderCSS.left = (isOldOpera && (this.type == 'email' || this.type == 'url')) ?
         '11%' : o_left;
      placeholder.css(options.placeholderCSS);
    
      //place the placeholder
      $this.wrap(options.inputWrapper);
      $this.attr('id', inputId).after(placeholder);
      
      //if the input isn't empty
      if (inputVal){
        placeholder.hide();
      };
    
      //hide placeholder on focus
      $this.focus(function(){
        if (!$.trim($this.val())){
          placeholder.hide();
        };
      });
    
      //show placeholder if the input is empty
      $this.blur(function(){
        if (!$.trim($this.val())){
          placeholder.show();
        };
      });
    });
  };
  
  //expose defaults
  $.fn.placeholder.defaults = {
    //you can pass in a custom wrapper
    inputWrapper: '<span style="position:relative; display:block;"></span>',
  
    //more or less just emulating what webkit does here
    //tweak to your hearts content
    placeholderCSS: {
      'font':'100% sans-serif', 
      'color':'#bababa', 
      'position': 'absolute', 
      'left':'5px',
      'top':'3px', 
      'overflow-x': 'hidden',
			'display': 'block'
    }
  };
})(jQuery);

$(document).ready(function(){

	// initializing the plugins
	$('.dropdown-toggle').dropdown();
	$(".collapse").collapse();

	current_url = $('#url').val();

	$('input[placeholder]').placeholder();

	$('#content').redactor({ 
		fixed: true,
		imageUpload: '../../../includes/libs/imgupload.php',
		keyupCallback: function(obj, event) {
			if($('#meta_desc').val() == ''){
				var text = $.trim(obj.$editor.text());

				text = text.replace('/\n/', " ");
				text = text.replace('/\r/', " ");
				text = text.replace('/\t/', " ");
				text = text.replace('/ +/', " ");

				text = (text.length > 160) ? text.substr(0, 156) + ' ...' : text;
				$('#descseo').text(text);
			}
		},
		imageUploadCallback: function(obj, json){
			if(json.error){
				if(json.error == 'memory'){
					alert("Allowed memory size exhausted. \r\n" + json.errortxt);
				} else {
					alert("The image is bigger than the upload size limit. \r\n" + json.errortxt);
				}
			}
		},
		imageGetJson: '../../../includes/libs/uploaded.php'
	});

	$('.sortable').sortable({
		stop: function(){
			// Create the order to send
			var order = '';
			var type = $(this).data('type');
			$('.sortable > tr').each(function(index){
				if(order === ''){
					order = $(this).data('id');
				} else {
					order += ','+$(this).data('id');
				}
			});

			$.post('../includes/libs/callback.php', {'sort': type, 'order': order}, function(r){
				if(r.error){
					alert('ERROR');
				}
			});
		}
	});

	$('.row-actions').parents('tr').mouseenter(function(){
		$(this).find('.row-actions').css('visibility', 'visible');
	});
	$('.row-actions').parents('tr').mouseleave(function(){
		$(this).find('.row-actions').css('visibility', 'hidden');
	});

	$('#title').change(function(){
		if($('#url').val() == ''){
			$('#url').val(convert_url($(this).val())).trigger('change');
		}
	});
	$('#title').keyup(function(){
		var $this = $(this);
		var title = $.trim($this.val());
		if($('#meta_title').val() == ''){
			var titleseo = (title.length > 70) ? title.substr(0, 66) + ' ...' : title;
			$('#titleseo').html(titleseo);
		}
	});

	$('#url').change(function(){

		$this = $(this);
		$parent = $this.parents('.control-group');
		$hint = $parent.find('.help-inline');

		if($this.val() == current_url){
			$parent.removeClass('success').removeClass('error');
			$hint.text('');
			return false;
		}

		// Convert to a valid permalink
		var permalink = convert_url($this.val());
		$this.val(permalink);

		$.post('../includes/libs/callback.php', {'permalink': permalink}, function(r){
			if(r == 0){
				$parent.addClass('success').removeClass('error');
				$hint.text('');
				var aUrl = document.URL.split('/');
				var length = aUrl.length-2;
				var site_url = '';
				for(var i = 0; i < length; i++){
					site_url += aUrl[i] + '/';
				}
				$('#linkseo').html(site_url+$this.val()+'/');
			} else {
				$parent.addClass('error').removeClass('success');
				$hint.text('That permalink url is not available');
			}
		});
	});
	$('#meta_title').keyup(function(){
		$('#titleseo').html($.trim($(this).val()));
	});
	$('#meta_desc').keyup(function(){
		$('#descseo').html($.trim($(this).val()));
	});

	$('.trash').click(function(e){
		if(!confirm("You really want to delete \r\n« "+$(this).parents('td').children('.row-title').text()+' »')){
			e.preventDefault();
		}
	});

	$('.withchildren').mouseenter(function(e){
		var $children = $(this).children('.child');
		if(!$children.is(':visible')){
			$children.slideDown('fast');
		}
	});
	$('.withchildren').mouseleave(function(e){
		var $children = $(this).children('.child');
		if($children.is(':visible')){
			$children.slideUp('fast');
		}
	});
});