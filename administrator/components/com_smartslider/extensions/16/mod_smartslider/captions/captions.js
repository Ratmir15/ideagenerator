/*
Abstract class for captions
*/

dojo.declare("slidercaption", null, {
	constructor: function(args) {
    this.delay = 0;  
    this.interval = 400;
    this.hideEasing = dojo.fx.easing.linear;
    this.showEasing = dojo.fx.easing.linear;
    this.anim = null;
	},

	init: function(){
	  if(typeof(this.hideEasing)==='string') this.hideEasing = eval(this.hideEasing);
	  if(typeof(this.showEasing)==='string') this.showEasing = eval(this.showEasing);
    this.active = 0;
    var el = dojo.query('.opener', this.node)[0];
    dojo.connect(el, 'onclick', dojo.hitch(this,'onOpenOrClose'));
    dojo.connect(el, "ontouchstart", this, "touchstart");
    dojo.connect(el, "ontouchmove", this, "touchmove");
    dojo.connect(el, "ontouchend", dojo.stopEvent);
    this.touch = {screenX: 0, screenY: 0, identifier: ''};
    this.minval = parseInt(dojo.style(this.node, this.prop));
    this.init2();
  },
  
  init2: function(){}, // virtual
  
  slideShowed: function(){}, // virtual
	
  onOpenOrClose: function(e){
    this.stopAnim();
    var prop = parseInt(dojo.style(this.node, this.prop));
    
    if(prop > this.minval){
      this.anim = new dojo.Animation({
        duration: this.interval,
        onAnimate: dojo.hitch(this, 'onAnimate'),
        onEnd: dojo.hitch(this, 'enableArrow'),
        curve: [prop, this.minval],
        easing: this.hideEasing,
        delay: this.delay
      });
      dojo.removeClass(this.node, 'opened');
    }else{
      this.anim = new dojo.Animation({
        duration: this.interval,
        onAnimate: dojo.hitch(this, 'onAnimate'),
        onBegin: dojo.hitch(this, 'disableArrow'),
        curve: [prop, this.value],
        easing: this.showEasing,
        delay: this.delay
      });
      dojo.addClass(this.node, 'opened');
    }
    this.anim.play();
  },
  
  onAnimate: function(){}, // virtual
  
  reset: function(){
    this.active = 0;
    this.reset2();
  },
  
  enableArrow: function(){
    this.active = 0;
    if(this.vertical)
      this.vertical.displayArrows();
  },
  
  disableArrow: function(){
    this.active = 1;
    if(this.vertical)
      this.vertical.displayArrows();
  },
  
  stopAnim: function(){
    if(this.anim && this.anim.status() == "playing"){
      this.anim.stop();
    }
  },
  
  touchstart: function(e){
    dojo.copyTouch(e.changedTouches[0], this.touch);
  },
  
  touchmove: function(e){
    dojo.stopEvent(e);
    this.touchmoveAction(e);
    dojo.copyTouch(e.changedTouches[0], this.touch);
  },
  
  touchmoveAction: function(e){} // virtual
	
});

/*
From right class
*/

dojo.declare("slidercaptionfromright", slidercaption, {
	constructor: function(args) {
    this.prop = 'width';
    this.showcaption = 0;
	},
	
	init2: function(){
    dojo.style(this.node.parentNode, {'right':'0px', 'left':'auto', 'display':'block'});
    var el = dojo.query('.content', this.node)[0];
    dojo.style(el, this.prop, (this.value-dojo.position(el).w)+'px');
    this.reset2();
  },
  
  onAnimate: function(e){
    var px = parseInt(e);
    dojo.style(this.node, this.prop, px+'px');
  },
  
  reset2: function(){
    dojo.style(this.node, this.prop, this.minval+'px');
    dojo.removeClass(this.node, 'opened');
  },
  
  slideShowed: function(){
    if (this.showcaption==1) {
      this.onOpenOrClose();
    }
  },
  
  touchmoveAction: function(e){
    var v = parseInt(dojo.style(this.node, this.prop))-(e.changedTouches[0].screenX-this.touch.screenX);
    if(v < this.minval) v = this.minval;
    if(v > this.value) v = this.value;
    dojo.style(this.node, this.prop, v + 'px' );
  }
});

/*
From bottom class
*/

dojo.declare("slidercaptionfrombottom", slidercaption, {
	constructor: function(args) {
    this.prop = 'height';
    this.showcaption = 0;
  },
	
	init2: function(){
    dojo.style(this.node.parentNode, {'bottom':'0px', 'right':'0px', 'left':'auto', 'top':'auto', 'display':'block'});
    var el = dojo.query('.content', this.node)[0];
    dojo.style(el, this.prop, (this.value-dojo.position(el).h)+'px');
    this.reset2();
  },
  
  onAnimate: function(e){
    var px = parseInt(e);
    dojo.style(this.node, this.prop, px+'px');
  },
  
  reset2: function(){
    dojo.style(this.node, this.prop, this.minval+'px');
    dojo.removeClass(this.node, 'opened');
  },
  
  slideShowed: function(){
    if (this.showcaption==1) {
      this.onOpenOrClose();
    }
  },
  
  touchmoveAction: function(e){
    var v = parseInt(dojo.style(this.node, this.prop)) - (e.changedTouches[0].screenY-this.touch.screenY);
    if(v < this.minval) v = this.minval;
    if(v > this.value) v = this.value;
    dojo.style(this.node, this.prop, v + 'px' );
  }
});

/*
Simple class
*/

dojo.declare("slidercaptionsimple", slidercaption, {
	constructor: function(args) {
    this.interval = 400;
    this.easing = dojo.fx.easing.linear;
    this.anim = null;
    this.firstsuffix = 'px';
    this.secondsuffix = 'px';
    this.thirdsuffix = 'px';
	},

	init: function(){
	  if(typeof(this.tagEasing)==='string') this.tagEasing = eval(this.tagEasing);
	  if(typeof(this.titleEasing)==='string') this.titleEasing = eval(this.titleEasing);
    dojo.style(this.node.parentNode, 'display', 'block');
    this.active = 0;
    this.tag = dojo.query("h4", this.node)[0];
    this.title = dojo.query(".h3", this.node)[0];
    if(this.title)
      dojo.style(this.title, 'width', this.contentWidth+'px');
    
    if(this.firstProp == '') this.firstProp = '0';
    if(this.secondProp == '') this.secondProp = '0';  
    if(this.firstProp == 'opacity') this.firstsuffix = '';
    if(this.secondProp == 'opacity') this.secondsuffix = '';
    if(this.thirdProp == 'opacity') this.thirdsuffix = '';
    if(this.firstProp == 'opacity' && this.secondProp == 'opacity') this.secondProp = '0';
    var pos = dojo.position(this.node.parentNode.parentNode);
    if(this.firstProp == 'right'){
      var w = pos.w;
      this.tagStartX-=w;
      this.titleStartX-=w;
      this.tagTargetX-=w;
      this.titleTargetX-=w;
    }
    if(this.secondProp == 'bottom'){
      var h = pos.h;
      this.tagStartY-=h;
      this.titleStartY-=h;
      this.tagTargetY-=h;
      this.titleTargetY-=h;
    }
    this.reset(true);
  },
  
  slideShowed: function(){
    this.onOpenOrClose();
  },
	
  onOpenOrClose: function(e){
    this.tagDistX = 0;
    this.titleDistX = 0;
    if(this.firstProp != '0'){
      this.tagDistX = this.tagStartX-this.tagTargetX;
      this.titleDistX = (this.titleStartX-this.titleTargetX);
    }
    
    this.tagDistY = 0;
    this.titleDistY = 0;
    if(this.secondProp != '0'){
      this.tagDistY = this.tagStartY-this.tagTargetY;
      this.titleDistY = this.titleStartY-this.titleTargetY;
    }
    
    if(this.tag){
      this.animTag = new dojo.Animation({
        duration: this.tagInterval,
        onAnimate: dojo.hitch(this, 'onAnimateTag'),
        curve: [0, 100],
        easing: this.tagEasing,
        delay: this.tagDelay
      }).play();
    }
    if(this.title){
      this.animTitle = new dojo.Animation({
        duration: this.titleInterval,
        onAnimate: dojo.hitch(this, 'onAnimateTitle'),
        curve: [0, 100],
        easing: this.titleEasing,
        delay: this.titleDelay
      }).play();
    }
  },
  
  onAnimateTag: function(e){
    e = e/100;
    var obj = {};
    if(this.tagDistX != 0)
      eval("obj."+this.firstProp+" = '"+(this.tagStartX-this.tagDistX*e)+this.firstsuffix+"'");
    
    if(this.tagDistY != 0)
      eval("obj."+this.secondProp+" = '"+(this.tagStartY-this.tagDistY*e)+this.secondsuffix+"'");
    
    dojo.style(this.tag, obj);
  },
  
  onAnimateTitle: function(e){
    e = e/100;
    var obj = {};
    if(this.titleDistX != 0)
      eval("obj."+this.firstProp+" = '"+(this.titleStartX-this.titleDistX*e+this.firstsuffix)+"'");
    if(this.titleDistY != 0)
      eval("obj."+this.secondProp+" = '"+(this.titleStartY-this.titleDistY*e+this.secondsuffix)+"'");
    dojo.style(this.title, obj);
  },
  
  reset: function(starting){
    dojo.style(this.node, 'display', 'none');
    this.stopAnim();
    this.active = 0;
    if(this.tag){
      var obj = {};
      if(this.firstProp != 0)
        eval("obj."+this.firstProp+" = '"+(this.tagStartX+this.firstsuffix)+"'");
      
      if(this.secondProp != 0)
        eval("obj."+this.secondProp+" = '"+(this.tagStartY+this.secondsuffix)+"'");
      if(this.thirdProp != 0)
        eval("obj."+this.thirdProp+" = '"+(this.tagStartThird+this.thirdsuffix)+"'");
        
      dojo.style(this.tag, obj);
    }
    if(this.title){
      var obj = {};
      if(this.firstProp != 0)
        eval("obj."+this.firstProp+" = '"+(this.titleStartX+this.firstsuffix)+"'");
      if(this.secondProp != 0)
        eval("obj."+this.secondProp+" = '"+(this.titleStartY+this.secondsuffix)+"'");
      if(this.thirdProp != 0)
        eval("obj."+this.thirdProp+" = '"+(this.titleStartThird+this.thirdsuffix)+"'");
        
      dojo.style(this.title, obj);
    }
    dojo.style(this.node, 'display', 'block');
  },
  
  stopAnim: function(){
    if(this.animTag && this.animTag.status() != "stopped"){
      this.animTag.stop();
    }
    if(this.animTitle && this.animTitle.status() != "stopped"){
      this.animTitle.stop();
    }
  }
});


/*
Smart caption class
*/

dojo.declare("slidercaptionsmart", slidercaption, {
	constructor: function(args) {
    this.interval = 400;
    this.easing = dojo.fx.easing.linear;
	},

	init: function(){
	  if(typeof(this.easing)==='string') this.easing = eval(this.easing);
    this.top = parseInt(this.top);
    this.w = parseInt(this.w);
    dojo.style(this.node, 'visibility', 'hidden');
    dojo.style(this.node, 'display', 'block');
    dojo.style(this.node.parentNode, 'display', 'block');
    
    this.tag = dojo.query("h4", this.node)[0];
    this.title = dojo.query("h3", this.node)[0];
    if(!this.title) alert('Please fill out the title for this caption!');
    
    this.autostart = 0;
    this.initalized = 0;
    setTimeout(dojo.hitch(this, 'delayed'), 100);
  },
  
  delayed: function(){
    var canvas = this.node.parentNode.parentNode;
    this.canvaspos = dojo.position(this.node.parentNode.parentNode);
    if(this.canvaspos.w == 0){
      this.canvaspos.w = parseInt(dojo.style(canvas, 'width'));
      this.canvaspos.h = parseInt(dojo.style(canvas, 'height'));
    }
    

    this.tag ? this.tagpos = dojo.position(this.tag) : this.tagpos = {w:0,h:0};
    
    dojo.style(this.title, 'width', this.w+'px');
    this.titlepos = dojo.position(this.title);
    

    this.calcPosition();
    this.reset(true);
    if(this.autostart)
      this.onOpenOrClose();
    this.initalized = 1;
  },
  
  calcPosition: function(){}, // virtual
  
  slideShowed: function(){
    if(this.initalized)
      this.onOpenOrClose();
    else
      this.autostart = 1;
  },
	
  onOpenOrClose: function(e){
    this.anim = new dojo.Animation({
      duration: this.interval,
      onAnimate: dojo.hitch(this, 'onAnimate'),
      curve: [0, 1],
      easing: this.easing,
        delay: this.delay
    }).play();
  },
  
  onAnimate: function(e){
    var tagl = this.tagStart.l + this.tagdist * e;
    var titlel = this.titleStart.l + this.titledist * e;
    if(this.tag)
      dojo.style(this.tag, {left: tagl+'px', opacity: e});
    if(this.title)
      dojo.style(this.title, {left: titlel+'px', opacity: e});
  },
  
  reset: function(starting){
    this.stopAnim();
    this.active = 0;
    
    if(this.tag)
      dojo.style(this.tag, {opacity: 0, left: this.tagStart.l+'px', top: this.tagStart.t+'px'});
      
    if(this.title)
      dojo.style(this.title, {opacity: 0, left: this.titleStart.l+'px', top: this.titleStart.t+'px'});
    dojo.style(this.node, 'visibility', 'visible');
  },
  
  stopAnim: function(){
    if(this.anim && this.anim.status() != "stopped"){
      this.anim.stop();
    }
  }
});

dojo.declare("slidercaptiontaglefttitleright", slidercaptionsmart, {
  calcPosition: function(){
    this.tagStart = {
      l: this.canvaspos.w - this.tagpos.w*1.2,
      t: this.top
    }
    
    this.tagEnd = {
      l: this.canvaspos.w - this.titlepos.w
    }
    
    this.tagdist = this.tagEnd.l-this.tagStart.l;
    
    this.titleStart = {
      l: this.canvaspos.w - this.titlepos.w*1.5,
      t: parseInt(this.top)+this.tagpos.h
    }
    
    this.titleEnd = {
      l: this.canvaspos.w - this.titlepos.w
    }
    this.titledist = this.titleEnd.l-this.titleStart.l;

  }
});


dojo.declare("slidercaptiontagrighttitleleft", slidercaptionsmart, {
  calcPosition: function(){
    this.tagStart = {
      l: this.tagpos.w*0.8,
      t: this.top
    }
    
    this.tagEnd = {
      l: this.titlepos.w - this.tagpos.w
    }
    
    this.tagdist = this.tagEnd.l-this.tagStart.l;
    
    this.titleStart = {
      l: this.titlepos.w*0.5,
      t: parseInt(this.top)+this.tagpos.h
    }
    
    this.titleEnd = {
      l: 0
    }
    this.titledist = this.titleEnd.l-this.titleStart.l;

  }
});

dojo.declare("slidercaptiontagrighttitleright", slidercaptionsmart, {
  calcPosition: function(){
    this.tagStart = {
      l: this.canvaspos.w - this.titlepos.w*1.4,
      t: this.top
    }
    
    this.tagEnd = {
      l: this.canvaspos.w - this.titlepos.w
    }
    
    this.tagdist = this.tagEnd.l-this.tagStart.l;
    
    this.titleStart = {
      l: this.canvaspos.w - this.titlepos.w*1.8,
      t: parseInt(this.top)+this.tagpos.h
    }
    
    this.titleEnd = {
      l: this.canvaspos.w - this.titlepos.w
    }
    this.titledist = this.titleEnd.l-this.titleStart.l;

  }
});

dojo.declare("slidercaptiontaglefttitleleft", slidercaptionsmart, {
  calcPosition: function(){
    this.tagStart = {
      l: this.titlepos.w*1.4,
      t: this.top
    }
    
    this.tagEnd = {
      l: this.titlepos.w - this.tagpos.w
    }
    
    this.tagdist = this.tagEnd.l-this.tagStart.l;
    
    this.titleStart = {
      l: this.titlepos.w,
      t: parseInt(this.top)+this.tagpos.h
    }
    
    this.titleEnd = {
      l: 0
    }
    this.titledist = this.titleEnd.l-this.titleStart.l;

  }
});