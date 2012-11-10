var SlideLive = {};
dojo.require("dojo.cookie");

dojo.declare("SlideLive", null, {
	constructor: function(args) {
	 dojo.mixin(this,args);
	 document.liveObj = this;
	   var cclass = ' disabled';
	  if(dojo.cookie("livepreview") == 0){
      this.enabled = 1;
      
    }else{
      this.enabled = 0;
      cclass = '';
    }
    this.liveprevbg = dojo.create('div', {'class': 'livepreview'});
    this.liveleft = dojo.create('div', {'class': 'liveleft'}, this.liveprevbg);
    this.liveright = dojo.create('div', {'class': 'liveright'}, this.liveleft);
	  this.controll = dojo.create('div', {'class': 'livecontroll'}, this.liveright);

    this.enable = dojo.create('div', {'class': 'enable'+cclass}, this.controll);
    this.refresh = dojo.create('div', {'class': 'refresh'}, this.controll);
    this.insert = dojo.create('div', {'class': 'insert'}, this.controll);
    this.footertext = dojo.create('div', {'class': 'footertext', 'innerHTML': 'The preview only shows the actually edited slide!'}, this.controll);
    this.canvas = dojo.create('div', {'class': 'size', innerHTML: '<b>Canvas size: </b><br />width: '+document.canvaswidth+' px<br />height: '+document.canvasheight+' px'}, this.controll);
    
    
    
    dojo.place(this.liveprevbg, dojo.body());
    
    //for the ajax refresh switch
    this.ajaxRefresh = 1;
    
	  //this.preview = dojo.create('div', {'class': 'sliderpreview', style: 'width:'+this.width+'px;height:'+this.height+'px;'}, dojo.body());
    this.preview = dojo.create('div', {'class': 'sliderpreview'}, dojo.body());
    
    this.title = dojo.byId('paramstitle');
    dojo.connect(this.title, 'onchange', this, 'show');
    
    
    dojo.connect(this.enable, 'onclick', this, 'onoff');
    
    dojo.connect(this.refresh, 'onclick', this, 'show');
      
    new InsertSelector({
      live : this
    });
      
    setTimeout(dojo.hitch(this, 'onoff'), 500);
  },
  
  onoff: function(){
    if(this.enabled){
      dojo.addClass(this.enable, 'disabled');
      this.enabled = 0;
      dojo.style(this.preview, 'display', 'none');
      dojo.cookie("livepreview", "0", {
          expires: 5
      });
    }else{
      dojo.removeClass(this.enable, 'disabled');
      this.enabled = 1;
      this.show();
      dojo.style(this.preview, 'display', 'block');
      dojo.cookie("livepreview", "1", {
          expires: 5
      });
    }
  },
  
  show: function(){
    if(this.enabled){
      this.content = dojo.byId('paramparamscontenthtml');
      this.captionfield = dojo.byId('paramparamscaptionhtml');
      dojo.style(this.preview, 'visibility', 'hidden');
      this.replaceslide = this.parseLive();
      if ( this.ajaxRefresh ) {
        dojo.xhrPost({
          url: window.SmartInsertConfigurator.url + "index.php?option=com_smartslider&controller=slide&task=replaceSlideContent&tmpl=component&format=raw",
          handleAs: "json",
          content: { slider: this.replaceslide },
          load: dojo.hitch(this, 'load')
        });
      } else {
        this.load(this.replaceslide);
      }
    }
  },
  
  load: function(result) {
    this.preview.innerHTML = result;
    setTimeout(dojo.hitch(this, 'delayed'), 100);
    dojo.style(this.preview, 'width', this.width + 'px');
  },
  
  delayed: function(){
    this.createCaption(this.captionfield.value);
    dojo.style(this.preview, 'visibility', 'visible');
    if(this.caption)
      this.caption.slideShowed();
  },
  
  parseLive: function(){
    var live = document.live;
    live = live.replace('{title}', this.title.value);
    live = live.replace('{content}', this.content.value);
    
    live = live.replace('{caption}', this.captionfield.value);
    return live;
  },
  
  createCaption: function(innerHTML){
    var caption = null;  
    var div = dojo.create('div', {innerHTML: innerHTML});  
    var x = div.getElementsByTagName("script");   
    for(var i=0;i<x.length;i++){
       eval(x[i].text);
    }
    if(!caption) return;
    var c = caption;
    this.caption = eval('new slidercaption'+c.type+'()');
    c.node = dojo.query('.animated', this.preview)[0];
    c.vertical = this;
    dojo.mixin(this.caption,c);
    this.caption.init();
  },
  
  displayArrows: function(){}
  
});

var InsertSelector = {};

dojo.declare("InsertSelector", null, {
	constructor: function(args) {
	 dojo.mixin(this,args);
	 this.init();
  },
  
  init: function() { 
    this.openinsert = dojo.connect(this.live.insert, 'onclick', this, 'showInsertSelector');
    window.InsertSelector = this;
    this.btns = 0;
  },
  
    showInsertSelector: function(){
    dojo.addClass(this.live.insert, 'opened');
    dojo.disconnect(this.openinsert);
    this.openinsert = dojo.connect(this.live.insert, 'onclick', this, 'closeselector');
    
    this.selector = dojo.query('.insertselector');
    this.selectorfieldset = dojo.query('.selector');
    dojo.empty(this.selectorfieldset[0]);
    
    dojo.style(this.selector[0], 'display', 'block');
    dojo.style(this.selector[0], 'left', dojo.cookie("insertselectorx"));
    dojo.style(this.selector[0], 'top', dojo.cookie("insertselectory"));
    
    this.cancelbutton = dojo.query('.cancelbutton_left');
    
    dojo.connect(this.cancelbutton[0], 'onclick', this, 'closeselector');
    
    this.draginsert = dojo.query('.draginsert');
    dojo.connect(this.draginsert[0], 'onmousedown', this, 'dragdown');
    dojo.connect(this.draginsert[0], 'onmouseup', this, 'dragup');
    
    this.closeinsert = dojo.query('.closeinsert');
    dojo.connect(this.closeinsert[0], 'onclick', this, 'closeselector');
    
    this.smartinsert = dojo.query('.smartinsert');
    this.specialinsert = dojo.query('.specialinsert');
    dojo.connect(this.smartinsert[0], 'onclick', this, 'showsmartinsert');
    dojo.connect(this.specialinsert[0], 'onclick', this, 'showspecialinsert');
  },
  
  closeselector: function() {
    dojo.style(this.selector[0], 'display', 'none');
    this.destroybuttons();
    dojo.removeClass(this.live.insert, 'opened');
    dojo.disconnect(this.openinsert);
    this.openinsert = dojo.connect(this.live.insert, 'onclick', this, 'showInsertSelector');
    dojo.removeClass(this.smartinsert[0], 'opened');
    dojo.removeClass(this.specialinsert[0], 'opened');
  },
  
  showsmartinsert: function() {
    dojo.empty(this.selectorfieldset[0]);
    dojo.place(window.SmartInsertConfigurator.showSelector(), this.selectorfieldset[0]);
    window.SmartInsertConfigurator.getselects();
    this.destroybuttons();
    dojo.removeClass(this.specialinsert[0], 'opened');
    dojo.addClass(this.smartinsert[0], 'opened');
  },
  
  showspecialinsert: function() {
    dojo.empty(this.selectorfieldset[0]);
    dojo.place(window.SpecialInsertConfigurator.showSelector(), this.selectorfieldset[0]);
    window.SpecialInsertConfigurator.getselect();
    this.destroybuttons();
    dojo.removeClass(this.smartinsert[0], 'opened');
    dojo.addClass(this.specialinsert[0], 'opened');
  }, 
 
  dragdown: function() {
   dragDrop.initElement(this.selector[0]);
  },

  dragup: function() {
   dragDrop.clearElement(this.selector[0]);
  },
  
  showinsertbuttons: function(type) {
  this.type = type;
     var inputs = dojo.query("#paramstitle, #contenteditorparams input[type=text],  #contenteditorparams textarea, #captioneditorparams input[type=text], #captioneditorparams textarea");   
    dojo.forEach(inputs, function(item, i) {
      if( dojo.style(item, "display") == "none" ) return;
      //var ibuttonleft = dojo.create('div', {'class': 'insertbutton_left', 'style': 'margin-top:' + dojo.style(item, 'marginTop') + 'px;'}, item, 'after');
     // var ibuttonleft = dojo.create('div', {'class': 'insertbutton_left', 'style': 'margin-top:' + dojo.style(item, 'marginTop') + 'px;'}, item.parentNode.lastChild, 'after');
      var ibuttonleft = dojo.create('div', {'class': 'insertbutton_left', 'style': 'margin-top:' + dojo.style(item, 'marginTop') + 'px;'}, item, 'after');
      var ibuttonright = dojo.create('div', {'class': 'insertbutton_right'}, ibuttonleft);
      var ibutton = dojo.create('div', {'class': 'insertbutton', 'innerHTML': this.live.lang_insert}, ibuttonright);
      ibuttonleft.input = item;
      item.ibutton = ibuttonleft;
      dojo.connect(ibuttonleft, 'onclick', this, 'insertBTN');
      
      var rbuttonleft = dojo.create('div', {'class': 'insertbutton_left', 'style': 'margin-top:' + dojo.style(item, 'marginTop') + 'px;'}, ibuttonleft, 'after');
      var rbuttonright = dojo.create('div', {'class': 'insertbutton_right'}, rbuttonleft);
      var rbutton = dojo.create('div', {'class': 'insertbutton', 'innerHTML': this.live.lang_replace}, rbuttonright);
      rbuttonleft.input = item;
      item.rbutton = rbuttonleft;
      dojo.connect(rbuttonleft, 'onclick', this, 'replaceBTN');
     
    },this);
    this.btns = 1;
    this.showhelptext('show');
  },
  
  destroybuttons: function() {
    var buttons = dojo.query(".insertbutton_left");
    dojo.forEach(buttons, function(item, i) {
      dojo.destroy(item); 
    });
    this.btns = 0;
    this.showhelptext('hide');
    //unset inputs' insertbutton
    var inputs = dojo.query("#contenteditorparams input[type=text],  #contenteditorparams textarea, #captioneditorparams input[type=text], #captioneditorparams textarea");   
    dojo.forEach(inputs, function(item, i) {
      item.ibutton = null;
      item.rbutton = null;
    },this);
  },
  
  insertBTN: function(e) {
    var input = e.currentTarget.input;
    if (this.type == 'smart') {
      input.value += window.SmartInsertConfigurator.inserttext();
    } else if (this.type == 'special') {
      input.value += window.SpecialInsertConfigurator.inserttext();
    }
    input.changeFieldinTemplate();
  },
  
  replaceBTN: function(e) {
   var input = e.currentTarget.input;
    if (this.type == 'smart') {
      input.value = window.SmartInsertConfigurator.inserttext();
    } else if (this.type == 'special') {
      input.value = window.SpecialInsertConfigurator.inserttext();
    }
    input.changeFieldinTemplate();
  },
  
  showhelptext: function(f) {
    var helptext = dojo.query('.inserttext');
    if(f == 'show')
      dojo.style(helptext[0], 'display', 'block');
    else if(f == 'hide')
      dojo.style(helptext[0], 'display', 'none');
  }

});

//dragDrop class from this site: http://www.quirksmode.org/js/dragdrop.html
//moving functions by cursor keys were removed 

dragDrop = {
	initialMouseX: undefined,
	initialMouseY: undefined,
	startX: undefined,
	startY: undefined,
	draggedObject: undefined,
	
	initElement: function (element) {
		if (typeof element == 'string')
			element = document.getElementById(element);
		element.onmousedown = dragDrop.startDragMouse;
	},
	
	startDragMouse: function (e) {
		dragDrop.startDrag(this);
		var evt = e || window.event;
		dragDrop.initialMouseX = evt.clientX;
		dragDrop.initialMouseY = evt.clientY;
		dragDrop.addEventSimple(document,'mousemove',dragDrop.dragMouse);
		dragDrop.addEventSimple(document,'mouseup',dragDrop.releaseElement);
		return false;
	},
	
	startDrag: function (obj) {
		if (dragDrop.draggedObject)
			dragDrop.releaseElement();
		dragDrop.startX = obj.offsetLeft;
		dragDrop.startY = obj.offsetTop;
		dragDrop.draggedObject = obj;
		obj.className += ' dragged';
	},
	
	dragMouse: function (e) {
		var evt = e || window.event;
		var dX = evt.clientX - dragDrop.initialMouseX;
		var dY = evt.clientY - dragDrop.initialMouseY;
		dragDrop.setPosition(dX,dY);
		return false;
	},
	
	setPosition: function (dx,dy) {
		dragDrop.draggedObject.style.left = dragDrop.startX + dx + 'px';
		dragDrop.draggedObject.style.top = dragDrop.startY + dy + 'px'; 
    //save the insertselector current position into cookie 
    dojo.cookie("insertselectorx", dragDrop.draggedObject.style.left, {
          expires: 5
    });
    dojo.cookie("insertselectory", dragDrop.draggedObject.style.top, {
          expires: 5
    });  
	},
	
	releaseElement: function() {
		dragDrop.removeEventSimple(document,'mousemove',dragDrop.dragMouse);
		dragDrop.removeEventSimple(document,'mouseup',dragDrop.releaseElement);
		dragDrop.draggedObject.className = dragDrop.draggedObject.className.replace(/dragged/,'');
		dragDrop.draggedObject = null;
	},
	
	addEventSimple: function(obj,evt,fn) {
	 if (obj.addEventListener)
		obj.addEventListener(evt,fn,false);
	 else if (obj.attachEvent)
		obj.attachEvent('on'+evt,fn);
  },
  
  removeEventSimple: function(obj,evt,fn) {
	 if (obj.removeEventListener)
		obj.removeEventListener(evt,fn,false);
	 else if (obj.detachEvent)
		obj.detachEvent('on'+evt,fn);
  },
  
  //clearElement funtion added
  clearElement: function(element) {
    element.onmousedown = null;
  }
}
