
dojo.copyTouch = function(sourceObj, targetObj){
    targetObj.screenX = sourceObj.screenX;
    targetObj.screenY = sourceObj.screenY;
    targetObj.identifier = sourceObj.identifier;
};

dojo.hasFlash = (navigator.mimeTypes && navigator.mimeTypes["application/x-shockwave-flash"]) ? 1 : 0;

dojo.declare("OfflajnSliderVertical", null, {
	constructor: function(args) {
    this.maininterval = 400;
    this.mode = 'onclick';
    dojo.mixin(this,args);
    if(dojo.isIE <= 6){
      new sliderIE6fix({node: this.node});
    }
    this.enabled = true;
    this.init();
    this.initCaptions();
    
    this.node.slider = this;
    
    this.id = parseInt(dojo.attr(this.node, 'id').replace('mod_smartslider_',''));
    window['slider'+this.id] = this;
    
    var hash = location.hash;
    var patt=new RegExp("slider([0-9]+)/([0-9]+)/([0-9]+)","g");
	  var go = patt.exec(hash);
	  if(go != null && 'mod_smartslider_'+go[1] == dojo.attr(this.node, 'id')){
      this.gotoSlide(go[2], go[3]);
    }else{
      this.initAutoplay();
    }
	},
	
	gotoSlide: function(slide, subslide){
    this.changeSlide(slide-1);
    var hor = this.dds[slide-1];
    if(hor && hor.horobj){
      hor.horobj.changeSlide(subslide-1);
    }
    return true;
  },
	
	init: function(){
    this.anims = new Array;
    this.anim = null;
	  this.opened = -1;
	  
    this.dts = dojo.query('dt.sslide', this.node);
	  this.dds = dojo.query('dd.sslide', this.node);
	  
    this.n = this.dts.length;
    
	  this.ddHeight = parseInt(dojo.style(this.dds[0], 'height'));
    this.dts.forEach(function(el, i){
      el.i = i;
      this.dds[i].i = i;
      if(dojo.style(this.dds[i], 'height') > 0){
        this.opened = i;
      }
      dojo.connect(el, this.mode, dojo.hitch(this,'onOpenOrClose'));
      
      this.dds[i].horizontal = dojo.query('li.subslide', this.dds[i]);
      if(this.dds[i].horizontal.length > 1){
        this.dds[i].horobj = new horizontal({
          nodes: this.dds[i].horizontal, 
          node: this.dds[i].horizontal[0].parentNode,
          dt: this.dts[i],
          interval: this.secondaryinterval,
          easing: this.secondaryeasing,
          showdots: this.showdots
        });
      }
    },this);
    if(this.mousescroll) dojo.connect(this.node, (!dojo.isMozilla ? "onmousewheel" : "DOMMouseScroll"), this, "onScroll");
    
    this.touch = {screenX: 0, screenY: 0, identifier: ''};
	  dojo.connect(this.node, "ontouchstart", this, "touchstart");
	  dojo.connect(this.node, "ontouchend", this, "touchend");
	  dojo.connect(this.node, "ontouchmove", dojo.stopEvent);
  },
  
  initCaptions: function(){
    this.captions = new Array;
    dojo.forEach(this.rawcaptions, function(caps, i){
      this.captions[i] = new Array;
      dojo.forEach(caps, function(c, j){
        if(c){
          this.captions[i][j] = eval('new slidercaption'+c.type+'()');
          c.node = dojo.query('.animated', this.dds[i].horizontal[j])[0];
          c.horizontal = this.dds[i].horobj;
          dojo.mixin(this.captions[i][j],c);
          this.captions[i][j].init();
          this.dds[i].horizontal[j].caption = this.captions[i][j];
        }else{
          this.captions[i][j] = null;
        }
      }, this);
    }, this);
    
    var horizontalObj = this.dds[this.opened].horobj;
    if(horizontalObj){ 
      if(horizontalObj.nodes[horizontalObj.current].caption)
        horizontalObj.nodes[horizontalObj.current].caption.slideShowed();
    }else{
      var horizontal = this.dds[this.opened].horizontal;
      if(horizontal[0].caption)
        horizontal[0].caption.slideShowed();
    }
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
    var horizontal = this.dds[this.opened].horobj;
    var moved = false;
    if(horizontal){
      if(horizontal.autoplayNextslide(1)){
        moved = 1;
      }
    }
    if(!moved){
      var next = this.opened + 1;
      if(next >= this.dts.length){
        next = 0;
      }
      var horizontal = this.dds[next].horobj;
      if(horizontal){
        horizontal.changeSlide(0);
      }
      this.changeSlide(next);
    }
    this.autoplayStart();
  },
  
  autoplayStop: function(){
    if(this.timer)
      clearTimeout(this.timer);
  },
  
  onOpenOrClose: function(e){
    this.changeSlide(e.currentTarget.i);
  },
  
  onScroll: function(e){
    var scroll = e[(!dojo.isMozilla ? "wheelDelta" : "detail")] * (!dojo.isMozilla ? 1 : -1);
    var next = this.opened;
    (scroll > 0) ? next-- : next++;
    
    if(next!=this.opened && next >=0 && next < this.dts.length){
      this.changeSlide(next);
    }
    dojo.stopEvent(e);
  },
  
  changeSlide: function(nextSlide){
    if(nextSlide >= this.n || nextSlide < 0) return;
    var h = dojo.style(this.dds[nextSlide], 'height');
    if(h == 0){
      if(!this.stopAnim(this.anim)) return;
      dojo.forEach(dojo.query('embed',this.dds[this.opened]),function(v){ try{ v.stopVideo(); }catch(e){}; });
      
      dojo.removeClass(this.dds[this.opened], 'selected');
      dojo.removeClass(this.dts[this.opened], 'selected');
      
      this.anim = new dojo.Animation({
        duration: this.maininterval,
        onAnimate: dojo.hitch(this, 'onAnimate'),
        onEnd: dojo.hitch(this, 'onEnd'),
        curve: [this.ddHeight, 0],
        easing: this.maineasing
      });
      this.previous = this.opened;
      this.opened = nextSlide;

      this.anim.play();
      dojo.addClass(this.dts[this.opened],'selected');
      dojo.addClass(this.dds[this.opened],'selected');
    }
  },
  
  onEnd: function(){
    var horizontalObj = this.dds[this.previous].horobj;
    if(horizontalObj){ 
      if(horizontalObj.nodes[horizontalObj.current].caption)
        horizontalObj.nodes[horizontalObj.current].caption.reset(false);
    }else{
      var horizontal = this.dds[this.previous].horizontal;
      if(horizontal[0].caption)
        horizontal[0].caption.reset(false);
    }
    horizontalObj = this.dds[this.opened].horobj;
    if(horizontalObj){ 
      if(horizontalObj.nodes[horizontalObj.current].caption)
        horizontalObj.nodes[horizontalObj.current].caption.slideShowed();
    }else{
      var horizontal = this.dds[this.opened].horizontal;
      if(horizontal[0].caption)
        horizontal[0].caption.slideShowed();
    }
  },

  onAnimate: function(e){
    var px = parseInt(e);
    dojo.style(this.dds[this.previous], 'height', px+'px');
    dojo.style(this.dds[this.opened], 'height', (this.ddHeight-px)+'px');
  },
  
  stopAnim: function(anim){
    if(anim && anim.status() == "playing"){
      return false;
    }
    return true;
  },
  
  touchstart: function(e){
    this.autoplayStop();
    dojo.copyTouch(e.changedTouches[0], this.touch);
  },
  
  touchend: function(e){
    if(this.touch.identifier == e.changedTouches[0].identifier){
      var dist = Math.sqrt(Math.pow(e.changedTouches[0].screenX-this.touch.screenX, 2) + Math.pow(e.changedTouches[0].screenY-this.touch.screenY, 2));
      if(dist > 100){
        var deg = Math.asin((e.changedTouches[0].screenX-this.touch.screenX)/dist)*180/Math.PI;
        if(deg < 45 && deg > -45){ //vertical
          var scroll = e.changedTouches[0].screenY-this.touch.screenY;
          if(scroll > 50 || scroll < -50){
            var next = this.opened;
            (scroll > 0) ? next-- : next++;
            if(next!=this.opened && next >=0 && next < this.dts.length){
              this.changeSlide(next);
            }
          }
        }else if(this.dds[this.opened].horobj){ //horizontal
          var scroll = e.changedTouches[0].screenX-this.touch.screenX;
          if(scroll > 50){
            this.dds[this.opened].horobj.up();
          }else if(scroll < -50){
            this.dds[this.opened].horobj.down();
          }
        }
      }
    }
  }
  
});

dojo.declare("horizontal", null, {
	constructor: function(args) {
    this.interval = 400;
    this.anim = null;
    dojo.mixin(this, args);
    this.init();
	},
	
	init: function(){
    this.current = 0;
    this.mouse = 0;
    dojo.connect(this.node.parentNode, "onmouseenter", this, "mouseenter");
    dojo.connect(this.node.parentNode, "onmouseleave", this, "mouseleave");
    
    this.width = parseInt(dojo.style(this.nodes[0], 'width'));
    
    var dots = dojo.create("div", { 'class': "dots" });
    this.dots = new Array;
  /*  dojo.forEach(this.nodes, function(node, j){
      var c = "dot";
      if(j == this.current){
        c+=" active";
      }
      this.dots[j] = dojo.create("div", { 'class': c }, dots, "first");
      dojo.connect(this.dots[j], 'onclick', dojo.hitch(this,'changeSlide', j));
    }, this);*/
    
     dojo.forEach(this.nodes, function(node, j){
      switch(this.showdots) {
        case 0:
        case 1: var c = "dot"; break;
        case 4: var c = "circle"; break;     
        }
      
      if((j == this.current) && ((this.showdots != 2) || (this.showdots != 3))){
        c+=" active";
      }
      
      switch(this.showdots) {
        case 0:
        case 1: this.dots[j] = dojo.create("div", { 'class': c }, dots, "first");
                dojo.connect(this.dots[j], 'onclick', dojo.hitch(this,'changeSlide', j)); break;           
        case 4: this.dots[j] = dojo.create("div", { 'class': c, 'innerHTML' : j + 1}, dots, "first"); 
                dojo.connect(this.dots[j], 'onclick', dojo.hitch(this,'changeSlide', j));break;  
      }
     }, this);
    
    if((this.showdots == 2) || (this.showdots == 3)) {
      this.aup = dojo.create("div", { 'class': 'arrowleft' }, dots);
       dojo.connect(this.aup, "onclick", this, "up");
       this.aupobj = new subslidearrow({node: this.aup});
       
       if ( this.showdots == 2 ) {
        var col = dojo.create("div", { 'class': 'col' }, dots);
       }
       
       if(this.showdots == 3) {
       // this.numbers =  dojo.create("div", { 'class': 'numbers', 'innerHTML': '1/' + this.nodes.length }, dots);
       this.numbers =  dojo.create("div", { 'class': 'numbers', 'innerHTML': '<div class="topline"></div><div class="nums">1/' + this.nodes.length + '</div><div class="bottomnline"></div>'}, dots);
       }
       
      this.adown = dojo.create("div", { 'class': 'arrowright', 'innerHTML': '<div class="bottomline"></div>' }, dots);
       dojo.connect(this.adown, "onclick", this, "down");
      this.adownobj = new subslidearrow({node: this.adown});
    }
  
    
    dojo.place(dots, this.dt);
    
    dojo.connect(this.node, (!dojo.isMozilla ? "onmousewheel" : "DOMMouseScroll"), this, "onScroll");
    
    this.arrowleft = dojo.query('.arrowleft', this.node.parentNode)[0];
    dojo.connect(this.arrowleft, "onclick", this, "up");
    this.arrowleftobj = new sliderinnerarrow({node: this.arrowleft});
    this.arrowright = dojo.query('.arrowright', this.node.parentNode)[0];
    dojo.connect(this.arrowright, "onclick", this, "down");
    this.arrowrightobj = new sliderinnerarrow({node: this.arrowright});
    this.displayArrows();
  },
  
  autoplayNextslide: function(){
    var next = this.current + 1;
    if(next >= this.nodes.length){
      return false;
    }
    this.changeSlide(next);
    return true;
  },
  
  onScroll: function(e){
    var scroll = e[(!dojo.isMozilla ? "wheelDelta" : "detail")] * (!dojo.isMozilla ? 1 : -1);
    var next = this.current;
    (scroll > 0) ? next-- : next++;
    
    if(next!=this.current && next >=0 && next < this.nodes.length){
      this.changeSlide(next);
      dojo.stopEvent(e);
    }else if(!this.stopAnim()){
      dojo.stopEvent(e);
    }
  },
  
  up: function(){
    this.click(-1);
  },
  
  down: function(){
    this.click(1);
  },
  
  click: function(scroll){
    var next = this.current;
    (scroll > 0) ? next++ : next--;
    if(next!=this.current && next >=0 && next < this.nodes.length){
      this.changeSlide(next);
    }
  },
  
  setActiveClass: function(next) {
    if((this.showdots == 1) ||(this.showdots == 4)) {
    dojo.removeClass(this.dots[this.current], 'active');
    dojo.addClass(this.dots[next], 'active');
    }    
    if(this.showdots == 3) {
    next++;
    this.numbers.innerHTML = '<div class="topline"></div><div class="nums">' + next + '/' + this.nodes.length + '</div><div class="bottomnline"></div>';    
    //this.numbers.innerHTML = next + 1 + '/' + this.nodes.length;
    }
  },
  
  changeSlide: function(nextSlide){
    if(!this.stopAnim() || nextSlide >= this.n || nextSlide < 0) return;

    dojo.forEach(dojo.query('embed',this.nodes[this.current]),function(v){ try{ v.stopVideo(); }catch(e){}; });
    
    this.setActiveClass(nextSlide);
    this.anim = dojo.animateProperty({
      node: this.node, 
      properties: {marginLeft: this.width*nextSlide*-1}, 
      duration: this.interval,
      onEnd: dojo.hitch(this, 'onAnimEnd', nextSlide),
      easing: this.easing
    });
    this.anim.play();
  },
  
  onAnimEnd: function(nextSlide){
    if(this.nodes[this.current].caption) 
      this.nodes[this.current].caption.reset();
    if(this.nodes[nextSlide].caption) 
      this.nodes[nextSlide].caption.slideShowed();
    this.current = nextSlide;
    this.displayArrows();
  },
  
  mouseenter: function(){
    this.mouse = 1;
    this.displayArrows();
  },
  
  mouseleave: function(){
    this.mouse = 0;
    this.displayArrows();
  },
  
  displayArrows: function(){
    var caption = this.nodes[this.current].caption ? this.nodes[this.current].caption.active : 0;

    (this.mouse && !caption && this.current != 0) ? this.arrowleftobj.show() : this.arrowleftobj.hide();
  
    (this.mouse && !caption && this.current < this.nodes.length - 1) ? this.arrowrightobj.show() : this.arrowrightobj.hide();
    
    if (this.showdots == 2 || this.showdots == 3) {
     ( this.current != 0 )  ? this.aupobj.show() : this.aupobj.hide();
     ( this.current < this.nodes.length - 1 ) ? this.adownobj.show() : this.adownobj.hide();
    }
  },
  
  stopAnim: function(){
    if(this.anim && this.anim.status() == "playing"){
      return false;
    }
    return true;
  }
});

dojo.declare("sliderinnerarrow", null, {
	constructor: function(args) {
    this.interval = 300;
    this.anim = null;
    dojo.mixin(this,args);
    this.init();
	},
	
	init: function(){
  
  },
  
  show: function(){
    this.stopAnim();
    dojo.style(this.node, 'display', 'block');
    this.anim = dojo.animateProperty({
      node: this.node, 
      properties: {opacity: 0.5}, 
      duration: this.interval,
      onEnd: function(el){
        dojo.addClass(el, 'show');
        dojo.removeAttr(el, 'style');
      }
    }).play();
  },
  
  hide: function(){
    this.stopAnim();
    this.anim = dojo.animateProperty({
      node: this.node, 
      properties: {opacity: 0}, 
      duration: this.interval,
      onEnd: function(el){
        dojo.removeClass(el, 'show');
        dojo.removeAttr(el, 'style');

      }
    }).play();
  },
  
  stopAnim: function(){
    if(this.anim && this.anim.status() == "playing"){
      this.anim.stop();
    }
  }
});
dojo.declare("subslidearrow", sliderinnerarrow, {
	constructor: function(args) {
    this.interval = 300;
    this.anim = null;
    dojo.mixin(this,args);
    this.init();
	},
	
	init: function(){
  
  },
  
  show: function(){
    dojo.style(this.node, 'visibility', 'visible');
    this.anim = dojo.animateProperty({
      node: this.node, 
      properties: {opacity: 1}, 
      duration: this.interval,
      onEnd: function(el){
        dojo.addClass(el, 'show');
      }
    }).play();
  },
  
  hide: function(){
    this.anim = dojo.animateProperty({
      node: this.node, 
      properties: {opacity: 0}, 
      duration: this.interval,
      onEnd: function(el){
        dojo.removeClass(el, 'show');
      }
    }).play();
  }
});