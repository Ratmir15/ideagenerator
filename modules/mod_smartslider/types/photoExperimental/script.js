
dojo.copyTouch = function(sourceObj, targetObj){
    targetObj.screenX = sourceObj.screenX;
    targetObj.screenY = sourceObj.screenY;
    targetObj.identifier = sourceObj.identifier;
};

dojo.hasFlash = (navigator.mimeTypes && navigator.mimeTypes["application/x-shockwave-flash"]) ? 1 : 0;

dojo.declare("OfflajnSliderPhotoExperimental", null, {
	constructor: function(args) {
    this.maininterval = 400;
    dojo.mixin(this,args);
    if(dojo.isIE <= 6){
      new sliderIE6fix({node: this.node});
    }
    this.enabled = true;
    this.slides = dojo.query('.sslide', this.node);
    
    this.initRotate();
    this.init();
    this.initCaptions();
    
    this.node.slider = this;
    
    this.id = parseInt(dojo.attr(this.node, 'id').replace('mod_smartslider_',''));
    window['slider'+this.id] = this;
    
    var hash = location.hash;
    var patt=new RegExp("slider([0-9]+)/([0-9]+)/([0-9]+)","g");
	  var go = patt.exec(hash);
	  if(go != null && 'mod_smartslider_'+go[1] == dojo.attr(this.node, 'id')){
	   this.gotoSlide(go[2], 0);
    }else{
      this.initAutoplay();
    }
	},
	
	gotoSlide: function(slide, subslide){
    this.changeSlide(slide-1);
    return true;
  },
	
	init: function(){
    this.dots = dojo.query('.dot', this.node);
    this.list = new LinkedList.Circular();
  
    this.n = this.slides.length;
    
    this.alter = null;
    
    dojo.forEach(this.slides, function(el, i){
      this.list.append(new LinkedList.Node(el));
      el.i = i;
      this.dots[i].i = i;
      dojo.connect(this.dots[i], "onclick", this, "onClick");
      dojo.connect(el, "onclick", this, "onClick");
      dojo.style(el, 'zIndex', this.n-i);
      if(i == 0){
        el.rot = 0;
        el.v1 = 50;
        el.v2 = 50;
        return;
      }
      el.rot = this.randomRotation();
      el.v1 = 50;
      el.v2 = 50;
      this.rotate(el, el.rot);
      this.transformOrigin(el, '50%', '50%');
    }, this);
    this.opened = 0;
    this.fwd = 1;
    
    if(this.mousescroll) dojo.connect(this.node, (!dojo.isMozilla ? "onmousewheel" : "DOMMouseScroll"), this, "onScroll");
    
    dojo.connect(dojo.query('.controllLeft', this.node)[0], "onclick", this, "prev");
    dojo.connect(dojo.query('.controllRight', this.node)[0], "onclick", this, "next");

    this.touch = {screenX: 0, screenY: 0, identifier: ''};
	  dojo.connect(this.node, "ontouchstart", this, "touchstart");
	  dojo.connect(this.node, "ontouchend", this, "touchend");
	  dojo.connect(this.node, "ontouchmove", dojo.stopEvent);
  },
  
  initCaptions: function(){
    this.captions = new Array;
    dojo.forEach(this.rawcaptions, function(c, i){
      if(c){
        this.captions[i] = eval('new slidercaption'+c.type+'()');
        c.node = dojo.query('.animated', this.slides[i])[0];
        dojo.mixin(this.captions[i],c);
        this.captions[i].init();
        this.slides[i].caption = this.captions[i];
      }else{
        this.captions[i] = null;
      }
    }, this);
    if(this.slides[this.opened].caption)
      this.slides[this.opened].caption.slideShowed();
  },
  
  initAutoplay: function(){
    if(this.autoplay){
      this.autoplayStart();
      if (this.restartautoplay!=2) {
	     dojo.connect(this.node, "onmouseenter", this, "autoplayStop");
      }
      if (this.restartautoplay==1) {
        dojo.connect(this.node, "onmouseleave", this, "autoplayStart");
      }
    }
  },
  
  autoplayStart: function(){
    this.timer = setTimeout(dojo.hitch(this,'autoplayNextslide'), this.autoplayinterval);
  },
  
  autoplayNextslide: function(){
    this.next();
    this.autoplayStart();
  },
  
  autoplayStop: function(){
    if(this.timer)
      clearTimeout(this.timer);
  },
  
  onScroll: function(e){
    if(this.alter == null){
      var scroll = e[(!dojo.isMozilla ? "wheelDelta" : "detail")] * (!dojo.isMozilla ? 1 : -1);
      this.scroll(-1*scroll);
    }else{
      this.scroll(0);
    }
    dojo.stopEvent(e);
  },
  
  next: function(e){
    this.scroll(1);
  },
  
  prev: function(e){
    this.scroll(-1);
  },
  
  scroll: function(scroll){
    var next = this.opened;
    if(scroll != 0){
      (scroll < 0) ? next-- : next++;
    }else if(this.alter){
      next = this.alter.tmpOpened;
    }
    if(next < 0) next = this.n-1;
    if(next >= this.n) next = 0;
    this.changeSlide(next);
  },
  
  onClick: function(e){
    this.changeSlide(e.currentTarget.i);
    //dojo.stopEvent(e);
  },
  
  touchstart: function(e){
    this.autoplayStop();
    dojo.copyTouch(e.changedTouches[0], this.touch);
  },
  
  touchend: function(e){
    if(this.touch.identifier == e.changedTouches[0].identifier){
      var dist = Math.sqrt(Math.pow(e.changedTouches[0].screenX-this.touch.screenX, 2) + Math.pow(e.changedTouches[0].screenY-this.touch.screenY, 2));
      if(dist > 100){
        var deg = Math.asin((e.changedTouches[0].screenY-this.touch.screenY)/dist)*180/Math.PI;
        if(deg < 45 && deg > -45){ //horizontal
          var scroll = e.changedTouches[0].screenX-this.touch.screenX;
          if(scroll > 50 || scroll < -50){
            (scroll > 0) ? this.next() : this.prev();
          }
        }else{ //vertical
          var scroll = e.changedTouches[0].screenY-this.touch.screenY;
          if(scroll > 50 || scroll < -50){
            (scroll > 0) ? this.prev() : this.next();
          }
        }
      }
    }
  },
  
  changeSlide: function(nextSlide){
    if(!this.stopAnim(this.anim) || nextSlide >= this.n || nextSlide < 0) return;
    if(this.alter != null){
      dojo.removeClass(this.slides[this.opened], 'selected');
      dojo.removeClass(this.dots[this.opened], 'selected');
      this.alter.nextSlide = nextSlide;
      this.list.remove(this.slides[this.opened].listItem);
      this.list.insertBefore(this.alter.tmpNext, this.slides[this.opened].listItem);
      this.previous = this.opened
      this.opened = this.alter.tmpOpened;
      this.startBWD1();
      dojo.addClass(this.slides[this.opened],'selected');
      dojo.addClass(this.dots[this.opened],'selected');
      return;
    }
    if(this.opened == nextSlide) return;
    
    dojo.forEach(dojo.query('embed',this.slides[this.opened]),function(v){ try{ v.stopVideo(); }catch(e){}; });
    
    dojo.removeClass(this.slides[this.opened], 'selected');
    dojo.removeClass(this.dots[this.opened], 'selected');
    

    if(nextSlide-this.opened == -1 || (this.opened == 0 && nextSlide==this.n-1)){ // hatso megy elore
      this.list.remove(this.slides[nextSlide].listItem);
      this.list.insertBefore(this.list.first, this.slides[nextSlide].listItem);
      this.previous = this.opened;
      this.opened = nextSlide;
      this.startFWD1();
    }else if(nextSlide-this.opened == 1 || (this.opened == this.n-1 && nextSlide==0)){ // elso megy hatra
      this.list.remove(this.slides[this.opened].listItem);
      this.list.append(this.slides[this.opened].listItem);
      this.previous = this.opened;
      this.opened = nextSlide;
      this.startBWD1();
    }else{
      this.alter = {
        tmpOpened: this.opened,
        tmpPrevious: this.previous,
        tmpNext: this.slides[nextSlide].listItem.next
      };
      this.list.remove(this.slides[nextSlide].listItem);
      this.list.insertBefore(this.list.first, this.slides[nextSlide].listItem);
      this.previous = this.opened;
      this.opened = nextSlide;
      this.startFWD1();
    }
    dojo.addClass(this.slides[this.opened],'selected');
    dojo.addClass(this.dots[this.opened],'selected');
  },
  
  startFWD1: function(){
    this.anim = new dojo.Animation({
      duration: this.maininterval,
      onAnimate: dojo.hitch(this, 'onFWDAnimate1'),
      onEnd: dojo.hitch(this, 'startFWD2'),
      curve: [0, 1],
      easing: this.maineasing
    }).play();
  },
  
  onFWDAnimate1: function(e){
    var el = this.slides[this.opened];
    dojo.style(el,{
      left: 50+(130-50)*e+'%',
      top: 50-(130-50)*e+'%'
    });
    this.rotate(el, el.rot-el.rot*e);
    if(dojo.isIE < 9) this.transformOrigin(el, el.v1+'%', el.v2+'%');
  },
  
  startFWD2: function(){
    this.reindexList();
    dojo.style(this.slides[this.opened], 'zIndex', '99999');
    this.anim = new dojo.Animation({
      duration: this.secondaryinterval,
      onAnimate: dojo.hitch(this, 'onFWDAnimate2'),
      onEnd: dojo.hitch(this, 'startFWD3'),
      curve: [1, 0],
      easing: this.secondaryeasing
    }).play();
  },
  
  onFWDAnimate2: function(e){
    var el = this.slides[this.opened];
      dojo.style(el,{
        left: 50+(130-50)*e+'%',
        top: 50-(130-50)*e+'%'
      });
  },
  
  startFWD3: function(){
    this.slides[this.previous].rot = this.randomRotation();
    this.anim = new dojo.Animation({
      duration: this.thirdinterval,
      onAnimate: dojo.hitch(this, 'onFWDAnimate3'),
      onEnd: dojo.hitch(this, 'endFWD'),
      curve: [0, 1],
      easing: this.thirdeasing
    }).play();
  },
  
  onFWDAnimate3: function(e){
    var el = this.slides[this.previous];
    this.rotate(el, el.rot*e);
    if(dojo.isIE < 9) this.transformOrigin(el, el.v1+'%', el.v2+'%');
  },
  
  endFWD: function(){
    if(this.slides[this.opened].caption)
      this.slides[this.opened].caption.slideShowed();
    if(this.slides[this.previous].caption)
      this.slides[this.previous].caption.reset(false);
  },
  
  startBWD1: function(){
    dojo.style(this.slides[this.previous], 'zIndex', '99999');
    this.anim = new dojo.Animation({
      duration: this.maininterval,
      onAnimate: dojo.hitch(this, 'onBWDAnimate1'),
      onEnd: dojo.hitch(this, 'startBWD2'),
      curve: [0, 1],
      easing: this.maineasing
    }).play();
  },
  
  onBWDAnimate1: function(e){
    var el = this.slides[this.previous];
    dojo.style(el,{
      left: 50+(130-50)*e+'%',
      top: 50-(130-50)*e+'%'
    });
  },
  
  startBWD2: function(){
    this.reindexList();
    this.slides[this.previous].rot = this.randomRotation();
    this.anim = new dojo.Animation({
      duration: this.secondaryinterval,
      onAnimate: dojo.hitch(this, 'onBWDAnimate2'),
      onEnd: dojo.hitch(this, 'startBWD3'),
      curve: [1, 0],
      easing: this.secondaryeasing
    }).play();
  },
  
  onBWDAnimate2: function(e){
    var el = this.slides[this.previous];
    dojo.style(el,{
      left: 50+(130-50)*e+'%',
      top: 50-(130-50)*e+'%'
    });
    this.rotate(el, el.rot-el.rot*e);
    if(dojo.isIE < 9) this.transformOrigin(el, el.v1+'%', el.v2+'%');
  },
  
  startBWD3: function(){
    this.anim = new dojo.Animation({
      duration: this.thirdinterval,
      onAnimate: dojo.hitch(this, 'onBWDAnimate3'),
      onEnd: dojo.hitch(this, 'endBWD'),
      curve: [0, 1],
      easing: this.thirdeasing
    }).play();
  },
  
  onBWDAnimate3: function(e){
    var el = this.slides[this.opened];
    this.rotate(el, el.rot - el.rot*e);
    if(dojo.isIE < 9) this.transformOrigin(el, el.v1+'%', el.v2+'%');
  },
  
  endBWD: function(){
    if(this.slides[this.opened].caption)
      this.slides[this.opened].caption.slideShowed();
    if(this.slides[this.previous].caption)
      this.slides[this.previous].caption.reset(false);
      
    if(this.alter != null){
      this.opened = this.alter.tmpOpened;
      this.previous = this.alter.tmpPrevious;
      var tmp = this.alter.nextSlide;
      this.alter = null;
      this.changeSlide(tmp);
    }
  },
  
  reindexList: function(){
    var el = this.list.first;
    var i = this.n;
    do{
      dojo.style(el.data, 'zIndex', i);
      el = el.next;
      --i;
    }while(el != this.list.first);
  },
  
  stopAnim: function(anim){
    if(anim && anim.status() == "playing"){
      return false;
    }
    return true;
  },
  
  randomRotation: function(){
    return Math.random()*8-4;
  },
  
  initRotate: function(){
    if(dojo.isIE < 9){
      dojo.forEach(this.slides, function(el){
        el.style.filter="progid:DXImageTransform.Microsoft.Matrix(sizingMethod='auto expand')";
      });
      this.rotate = function(el, deg){
        var deg2radians = Math.PI * 2 / 360;   
        rad = deg * deg2radians ;
        costheta = Math.cos(rad);
        sintheta = Math.sin(rad);
        el.filters.item(0).M11 = el.M11 = costheta;
        el.filters.item(0).M12 = el.M12  = -sintheta;
        el.filters.item(0).M21 = el.M11  = sintheta;
        el.filters.item(0).M22 = el.M11  = costheta;
      }
      this.transformOrigin = function(el, v1, v2){
        var pos = dojo.position(el);
        var w = pos.w;
        var h = pos.h;
        dojo.style(el,{
          marginTop: -h*(((parseFloat(v1)/100)-0.5)/10+0.5)+'px',
          marginLeft: -w*(((parseFloat(v2)/100)-0.5)/10+0.5)+'px'
        });
      }
    }else if(dojo.isIE){
      this.rotate = function(el, deg){
        el.style.msTransform='rotate('+deg+'deg)';
      }
      this.transformOrigin = function(el, v1, v2){
        el.style.msTransformOrigin=v1+" "+v2;
      }
    }else if(dojo.isFF){
      this.rotate = function(el, deg){
        el.style.MozTransform='rotate('+deg+'deg)';
      }
      this.transformOrigin = function(el, v1, v2){
        el.style.MozTransformOrigin=v1+" "+v2;
      }
    }else if(dojo.isWebKit){
      this.rotate = function(el, deg){
        el.style.WebkitTransform='rotate('+deg+'deg)';
      }
      this.transformOrigin = function(el, v1, v2){
        el.style.WebkitTransformOrigin=v1+" "+v2;
      }
    }else if(dojo.isOpera){
      this.rotate = function(el, deg){
        el.style.OTransform='rotate('+deg+'deg)';
      }
      this.transformOrigin = function(el, v1, v2){
        el.style.OTransformOrigin=v1+" "+v2;
      }
    }else{
      this.rotate = function(el, deg){
        el.style.transform='rotate('+deg+'deg)';
      }
      this.transformOrigin = function(el, v1, v2){
        el.style.transformOrigin=v1+" "+v2;
      }
    }
  }
  
});

function LinkedList() {}
LinkedList.prototype = {
  length: 0,
  first: null,
  last: null
};
LinkedList.Circular = function() {};
LinkedList.Circular.prototype = new LinkedList();
LinkedList.Circular.prototype.append = function(node) {
  if (this.first === null) {
    node.prev = node;
    node.next = node;
    this.first = node;
    this.last = node;
  } else {
    node.prev = this.last;
    node.next = this.first;
    this.first.prev = node;
    this.last.next = node;
    this.last = node;
  }
  this.length++;
};

LinkedList.Circular.prototype.insertAfter = function(node, newNode) {
  newNode.prev = node;
  newNode.next = node.next;
  node.next.prev = newNode;
  node.next = newNode;
  if (newNode.prev == this.last) { this.last = newNode; }
  this.length++;
};

LinkedList.Circular.prototype.insertBefore = function(node, newNode) {
  newNode.prev = node.prev;
  newNode.next = node;
  node.prev.next = newNode;
  node.prev = newNode;
  if (newNode.next == this.first) { this.first = newNode; }
  this.length++;
};

LinkedList.Circular.prototype.remove = function(node) {
  if (this.length > 1) {
    node.prev.next = node.next;
    node.next.prev = node.prev;
    if (node == this.first) { this.first = node.next; }
    if (node == this.last) { this.last = node.prev; }
  } else {
    this.first = null;
    this.last = null;
  }
  node.prev = null;
  node.next = null;
  this.length--;
};

LinkedList.Node = function(data) {
  this.prev = null; this.next = null;
  this.data = data;
  this.data.listItem = this;
};