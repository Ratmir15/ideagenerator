function jInsertEditorText( text, editor ) {
  var el = dojo.byId(editor.replace(/\[|\]/g, ''));
  if(el){
    el.value = document.joomlabase+text.match(/src=".*?"/)[0].replace(/(src=)|"/g,'');
    if (document.createEvent){
      event = document.createEvent("HTMLEvents");
      event.initEvent("change", false, true);
      el.dispatchEvent(event);
     }else{
      el.fireEvent("onchange");
     } 
  }
}

function $m(theVar){
	return document.getElementById(theVar)
}
function remove2(theVar){
	var theParent = theVar.parentNode;
	theParent.removeChild(theVar);
}
function addEvent2(obj, evType, fn){
	if(obj.addEventListener)
	    obj.addEventListener(evType, fn, true)
	if(obj.attachEvent)
	    obj.attachEvent("on"+evType, fn)
}
function removeEvent2(obj, type, fn){
	if(obj.detachEvent){
		obj.detachEvent('on'+type, fn);
	}else{
		obj.removeEventListener(type, fn, false);
	}
}
function isWebKit(){
	return RegExp(" AppleWebKit/").test(navigator.userAgent);
}
function ajaxUpload(form,url_action,id_element,html_show_loading,html_error_http){
	var detectWebKit = isWebKit();
	form = typeof(form)=="string"?$m(form):form;
	var erro="";
	if(form==null || typeof(form)=="undefined"){
		erro += "The form of 1st parameter does not exists.\n";
	}else if(form.nodeName.toLowerCase()!="form"){
		erro += "The form of 1st parameter its not a form.\n";
	}
	if($m(id_element)==null){
		erro += "The element of 3rd parameter does not exists.\n";
	}
	if(erro.length>0){
		alert("Error in call ajaxUpload:\n" + erro);
		return;
	}
	var iframe = document.createElement("iframe");
	iframe.setAttribute("id","ajax-temp");
	iframe.setAttribute("name","ajax-temp");
	iframe.setAttribute("width","0");
	iframe.setAttribute("height","0");
	iframe.setAttribute("border","0");
	iframe.setAttribute("style","width: 0; height: 0; border: none;");
	form.parentNode.appendChild(iframe);
	window.frames['ajax-temp'].name="ajax-temp";
	var doUpload = function(){
		removeEvent2($m('ajax-temp'),"load", doUpload);
		var cross = "javascript: ";
		cross += "window.parent.$m('"+id_element+"').innerHTML = document.body.innerHTML; window.parent.$m('"+id_element+"').obj.callback(); void(0);";
		$m(id_element).innerHTML = html_error_http;
		$m('ajax-temp').src = cross;
	  if(detectWebKit){
      remove2($m('ajax-temp'));
    }else{
      setTimeout(function(){ remove2($m('ajax-temp'))}, 250);
    }
  }
	dojo.connect($m('ajax-temp'), "onload", doUpload);
	form.setAttribute("target","ajax-temp");
	form.setAttribute("action",url_action);
	form.setAttribute("method","post");
	form.setAttribute("enctype","multipart/form-data");
	form.setAttribute("encoding","multipart/form-data");
	if(html_show_loading.length > 0){
		$m(id_element).innerHTML = html_show_loading;
	}
	form.submit();
}

var ImgUpload = {};

dojo.declare("ImgUpload", null, {
	constructor: function(args) {
	 dojo.mixin(this,args);
	 if(this.v16 == 1){
	   this.group = this.node.parentNode;
   }else{
	   this.group = this.node.parentNode.parentNode.parentNode.parentNode.parentNode;
	 }
	 this.id = dojo.attr(this.node, 'id');
	 this.pos = dojo.position(this.node, true);
	 this.pos2 = dojo.position(this.group, true);
	 var html = "\
      <form style='display: none;' action='' method='post' enctype='multipart/form-data'>\
        <input type='hidden' name='maxSize' value='9999999999' />\
        <input type='hidden' name='filename' value='filename' />\
        <input type='file' style='margin-top:-3px;' name='filename' onchange=\"ajaxUpload(this.form,'index.php?option=com_smartslider&controller=slide&task=upload&format=raw','"+this.id+"_msg','&lt;img style=\\\'vertical-align:middle;\\\' src=\\\'components/com_smartslider/params/images/load.gif\\\' border=\\\'0\\\' /&gt;','Error in upload! Please try again.'); return false;\" />\
      </form>\
      <span class='' id='"+this.id+"_msg'></span>\
      ";
      
   var margtop = -8;
   if(this.v16) margtop = -13;
	 //this.form = dojo.create('div', {innerHTML: this.html, style: 'display:block; position: absolute; top:'+(this.pos.y-this.pos2.y+margtop)+'px; left: '+(this.pos.x-this.pos2.x+this.pos.w+20)+'px;'}, this.group);

	 this.form = dojo.create('div', { innerHTML: this.html }, this.node, 'after');

	 //this.msg = dojo.byId(this.id+"_msg");
   //this.msg.obj = this;
  },
  
  callback: function(){
    var msg = this.msg.innerHTML;
    var match = msg.match(/{(.*)}/);
    this.msg.innerHTML = msg.replace(match[0], '');
    this.node.value = match[1];
    this.node.reparse[2]();
  }
  
});
