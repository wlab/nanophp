//************************************************************************
// Author: Christopher Beck & Alex Cipriani
// Copyright: Wired IDS Ltd 2010
// Requirements:
//		- '/css/default.css'
//		- '/css/colorbox-default.css'
//		- '/js/jquery.min.js'
//		- '/js/jquery-ui.min.js'
//		- '/js/jquery.colorbox-min.js'
//************************************************************************
// Site JS Core
//************************************************************************
var NJSCORE = {};
//************************************************************************
// Site JS Core - Add Class Extension Function
//************************************************************************
NJSCORE['extend'] = function(namespace,obj){
	if(namespace!='extend'){
		var namespaces = namespace.split('.');
		var objPointer = NJSCORE;
		if(namespaces.length>1){
			for(var i=0;i<(namespaces.length-1);i++){
				var currentNamespace = namespaces[i];
				if(!objPointer[currentNamespace]){
					objPointer[currentNamespace] = {};
				}
				objPointer = objPointer[currentNamespace];
			}
			namespace = namespaces[namespaces.length-1];
		}
		objPointer[namespace] = new obj;
	}
}
//************************************************************************
// Site JS Core - Extend with Logging Bar Class
//************************************************************************
NJSCORE.extend('LoggingBar',function(){
	var isLoggingBarShown = true;
	var isLoggingBarDetailsShown = false;
	this.closeLoggingBar = function(){
		$('#core-logging-bar').hide();
	}
	this.toggleLoggingDetails = function(){
		if(isLoggingBarDetailsShown){
			$('#core-logging-bar-show-details-span').html('Show Details');
			$('#core-logging-bar-details').hide();
			isLoggingBarDetailsShown = false;
		} else {
			$('#core-logging-bar-show-details-span').html('Hide Details');
			$('#core-logging-bar-details').show();
			isLoggingBarDetailsShown = true;
		}
	}
	this.popUpDetails = function(){
		NJSCORE.LightBox.htmlLoad('<div style="font-size:0.8em">'+$('#core-logging-bar-details').html()+'</div>');
	}
});
//************************************************************************
// Site JS Core - Extend with Light Box Class
//************************************************************************
NJSCORE.extend('LightBox',function(){
	this.load = function(config){
		$.fn.colorbox(config);
	}
	this.htmlLoad = function(body,title,onContentLoad,onClose){
		if(title){
			this.load({title:title,html:body,onComplete:onContentLoad,onClose:onClose,opacity:0.75});
		} else {
			this.load({html:body,onComplete:onContentLoad,onClose:onClose,opacity:0.75});
		}
	}
	this.ajaxLoad = function(url,onContentLoad,onClose){
		this.load({href:url,onComplete:onContentLoad,onClose:onClose,opacity:0.75});
	}
	this.resize = function(){
		$.fn.colorbox.resize();
	}
	this.postAjaxLoad = function(url,formId,onSuccess){
		$.ajax({
			url: url,
			cache: false,
			global: false,
			type: 'POST',
			data: $('#'+formId).serialize(),
			dataType: 'html',
			beforeSend: function(){
				NJSCORE.LightBox.htmlLoad('<div id="cboxLoadingGraphic">&nbsp;</div>');
			},
			success: function(rtn){
				NJSCORE.LightBox.htmlLoad(rtn);
				if(onSuccess){
					onSuccess();
				}
			},
			error: function(){
				NJSCORE.LightBox.htmlLoad('Oooops, something has gone a little wrong here...');
			}
		});
	}
});
//************************************************************************
// Site JS Core - Extend with Ajax Class
//************************************************************************
NJSCORE.extend('Ajax',function(){
	this.getRequest = function(id,url,onSuccess){
		$.ajax({
			url: url,
			cache: false,
			global: false,
			type: 'GET',
			dataType: 'html',
			success: function(rtn){
				$('#'+id).html(rtn);
				if(onSuccess){
					onSuccess();
				}
			},
			error: function(){
				$('#'+id).html('Oooops, something has gone a little wrong here...');
			}
		});
	}
	this.postRequest = function(id,url,data,onSuccess){
		$.ajax({
			url: url,
			cache: false,
			global: false,
			type: 'POST',
			data: data,
			dataType: 'html',
			success: function(rtn){
				$('#'+id).html(rtn);
				if(onSuccess){
					onSuccess();
				}
			},
			error: function(){
				$('#'+id).html('Oooops, something has gone a little wrong here...');
			}
		});
	}
	this.formPostRequest = function(id,url,formId,onSuccess){
		$.ajax({
			url: url,
			cache: false,
			global: false,
			type: 'POST',
			data: $('#'+formId).serialize(),
			dataType: 'html',
			beforeSend: function(){
				$('#'+id).html('<div id="cboxLoadingGraphic">&nbsp;</div>');
			},
			success: function(rtn){
				$('#'+id).html(rtn);
				if(onSuccess){
					onSuccess();
				}
			},
			error: function(){
				$('#'+id).html('Oooops, something has gone a little wrong here...');
			}
		});
	}
});