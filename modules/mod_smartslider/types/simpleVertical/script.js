
dojo.copyTouch = function(sourceObj, targetObj){
    targetObj.screenX = sourceObj.screenX;
    targetObj.screenY = sourceObj.screenY;
    targetObj.identifier = sourceObj.identifier;
};

dojo.hasFlash = (navigator.mimeTypes && navigator.mimeTypes["application/x-shockwave-flash"]) ? 1 : 0;

dojo.declare("OfflajnSliderSimpleVertical", null, {
	constructor: function(args) {
    this.maininterval = 400;
    this.transition = 1;
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
    this.anim = null;
	  this.opened = 0;
	  
    this.dots = dojo.query('.dot', this.node);
    dojo.addClass(this.dots[this.opened],'selected');
	  
    this.lis = dojo.query('li.sslide', this.node);
    this.n = this.lis.length;
    
    if(this.transition == 2){
      dojo.forEach(this.lis, function(el, i){
        if(i > 0)
          dojo.style(el, {opacity: 0, visibility: 'hidden'});
        dojo.style(el, 'position', 'absolute');
      });
    }
    
    this.ul = this.lis[0].parentNode;
    this.height = Math.round(dojo.position(this.lis[0]).h);
    
    this.up = dojo.query('.up', this.node);
    if(this.up[0])
      dojo.connect(this.up[0], 'onclick', dojo.hitch(this, 'onBtn', -1) );
    
    this.down = dojo.query('.down', this.node);
    if(this.down[0])
      dojo.connect(this.down[0], 'onclick', dojo.hitch(this, 'onBtn', 1) );
    
    this.lis.forEach(function(el, i){
      el.i = i;
      if(i != this.opened && dojo.hasClass(el, 'selected')){
        this.changeDot(this.opened, i);
        this.opened = i;
      }
      dojo.connect(this.dots[i], 'onclick', dojo.hitch(this, 'changeSlide', i));
    },this);
    
    if(this.mousescroll) dojo.connect(this.node, (!dojo.isMozilla ? "onmousewheel" : "DOMMouseScroll"), this, "onScroll");
    
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
        c.node = dojo.query('.animated', this.lis[i])[0];
        dojo.mixin(this.captions[i],c);
        this.captions[i].init();
        this.lis[i].caption = this.captions[i];
      }else{
        this.captions[i] = null;
      }
    }, this);
    if(this.lis[this.opened].caption)
      this.lis[this.opened].caption.slideShowed();
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
    var next = this.opened + 1;
    if(next >= this.lis.length){
      next = 0;
    }
    this.changeSlide(next);
    this.autoplayStart();
  },
  
  autoplayStop: function(){
    if(this.timer)
      clearTimeout(this.timer);
  },
  
  onBtn: function(scroll, e){
    var next = this.opened;
    (scroll < 0) ? next-- : next++;
    
    if(next!=this.opened){
      if(next < 0) next = this.lis.length-1;
      if(next >= this.lis.length) next = 0;
      
      this.changeSlide(next);
    }
    dojo.stopEvent(e);
  },
  
  onScroll: function(e){
    var scroll = e[(!dojo.isMozilla ? "wheelDelta" : "detail")] * (!dojo.isMozilla ? 1 : -1);
    var next = this.opened;
    (scroll > 0) ? next-- : next++;
    
    if(next!=this.opened){
      if(next < 0) next = this.lis.length-1;
      if(next >= this.lis.length) next = 0;
      this.changeSlide(next);
    }
    dojo.stopEvent(e);
  },
  
  changeSlide: function(nextSlide){
    if(!this.stopAnim(this.anim) || nextSlide >= this.n || nextSlide < 0) return;
    
    dojo.removeClass(this.lis[this.opened], 'selected');
    
    dojo.forEach(dojo.query('embed',this.lis[this.opened]),function(v){ try{ v.stopVideo(); }catch(e){}; });
    
    if(this.transition == 1){
      this.anim = new dojo.Animation({
        duration: this.maininterval,
        onAnimate: dojo.hitch(this, 'onAnimateSliding'),
        onEnd: dojo.hitch(this, 'onEnd'),
        curve: [-this.height*this.opened, -this.height*nextSlide],
        easing: this.maineasing
      });
    }else if(this.transition == 2){
      this.anim = new dojo.Animation({
        duration: this.maininterval,
        onAnimate: dojo.hitch(this, 'onAnimateFading'),
        onEnd: dojo.hitch(this, 'onEnd'),
        curve: [0, 1],
        easing: this.maineasing
      });
      dojo.style(this.lis[nextSlide], {display: 'block', visibility: 'visible'});
    }
    this.previous = this.opened;
    this.opened = nextSlide;

    this.anim.play();
    dojo.addClass(this.lis[this.opened],'selected');
    this.changeDot(this.previous, nextSlide);
  },
  
  onAnimateSliding: function(e){
    var px = parseInt(e);
    dojo.style(this.ul, 'marginTop', px+'px');
  },

  onAnimateFading: function(e){
    dojo.style(this.lis[this.opened], 'opacity', e);
    dojo.style(this.lis[this.previous], 'opacity', 1-e);
  },
  
  onEnd: function(){
    if(this.transition == 2){
      dojo.style(this.lis[this.previous], 'display', 'none');
    }
    if(this.lis[this.opened].caption)
      this.lis[this.opened].caption.slideShowed();
    if(this.lis[this.previous].caption)
      this.lis[this.previous].caption.reset(false);
  },
  
  changeDot: function(prev, next){
    dojo.removeClass(this.dots[prev],'selected');
    dojo.addClass(this.dots[next],'selected');
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
      if(dist > 50){
        var deg = Math.asin((e.changedTouches[0].screenY-this.touch.screenY)/dist)*180/Math.PI;
        if(deg < 45 && deg > -45){ //horizontal
        }else{
          var scroll = e.changedTouches[0].screenY-this.touch.screenY;
          if(scroll > 50 || scroll < -50){
            var next = this.opened;
            (scroll > 0) ? next-- : next++;
              if(next!=this.opened){
              if(next < 0) next = this.lis.length-1;
              if(next >= this.lis.length) next = 0;
              this.changeSlide(next);
            }
          }
        }
      }
    }
  }
  
});