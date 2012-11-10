
dojo.copyTouch = function(sourceObj, targetObj){
    targetObj.screenX = sourceObj.screenX;
    targetObj.screenY = sourceObj.screenY;
    targetObj.identifier = sourceObj.identifier;
};

dojo.hasFlash = (navigator.mimeTypes && navigator.mimeTypes["application/x-shockwave-flash"]) ? 1 : 0;

dojo.declare("OfflajnSliderPhoto", null, {
	constructor: function(args) {
    this.maininterval = 400;
    this.transition = 1;
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
    this.pipe = dojo.query('.mainframepipe', this.node)[0];
    
    
    this.width = parseInt(dojo.position(this.slides[0]).w);

    this.n = this.slides.length;
    if(this.transition == 2){
      dojo.forEach(this.slides, function(el, i){
        if(i > 0)
          dojo.style(el, {opacity: 0, display: 'none'});
        dojo.style(el, 'position', 'absolute');
      });
    }

    var frames = dojo.query('.frame', this.node);
    var el = frames[0];
    this.rotate(el, 1.16);
    this.transformOrigin(el, '50%', '50%');
    el = frames[1];
    this.rotate(el, -1.02);
    this.transformOrigin(el, '50%', '50%');
    
    this.dots = dojo.query('.dot', this.node);
    dojo.forEach(this.slides, function(el, i){
      el.i = i;
      this.dots[i].i = i;
      dojo.connect(this.dots[i], "onclick", this, "onClick");
    }, this);
    
    this.opened = 0;
    
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
    var scroll = e[(!dojo.isMozilla ? "wheelDelta" : "detail")] * (!dojo.isMozilla ? 1 : -1);
    this.scroll(-1*scroll);
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
    dojo.stopEvent(e);
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
            (scroll > 0) ? this.prev() : this.next();
          }
        }
      }
    }
  },
  
  changeSlide: function(nextSlide){
    if(!this.stopAnim(this.anim) || nextSlide >= this.n || nextSlide < 0) return;
    
    dojo.forEach(dojo.query('embed',this.slides[this.opened]),function(v){ try{ v.stopVideo(); }catch(e){}; });
    
    dojo.removeClass(this.slides[this.opened], 'selected');
    dojo.removeClass(this.dots[this.opened], 'selected');
    
    if(this.transition == 1){
      this.anim = new dojo.Animation({
        duration: this.maininterval,
        onAnimate: dojo.hitch(this, 'onAnimateSliding'),
        onEnd: dojo.hitch(this, 'onEnd'),
        curve: [-this.width*this.opened, -this.width*nextSlide],
        easing: this.maineasing
      });
    } else if(this.transition == 2){
      this.anim = new dojo.Animation({
        duration: this.maininterval,
        onAnimate: dojo.hitch(this, 'onAnimateFading'),
        onEnd: dojo.hitch(this, 'onEnd'),
        curve: [0, 1],
        easing: this.maineasing
      });
      dojo.style(this.slides[nextSlide], 'display', 'block');
    }
    this.previous = this.opened;
    this.opened = nextSlide;
    
    dojo.addClass(this.slides[this.opened],'selected');
    dojo.addClass(this.dots[this.opened],'selected');
    
    this.anim.play();
  },
  
  onAnimateSliding: function(e){
    var px = parseInt(e);
    dojo.style(this.pipe, 'marginLeft', px+'px');
  },
  
  onAnimateFading: function(e){
    dojo.style(this.slides[this.opened], 'opacity', e);
    dojo.style(this.slides[this.previous], 'opacity', 1-e);
  },
  
  onEnd: function(){
    if(this.transition == 2){
      dojo.style(this.slides[this.previous], 'display', 'none');
    }
    if(this.slides[this.opened].caption)
      this.slides[this.opened].caption.slideShowed();
    if(this.slides[this.previous].caption)
      this.slides[this.previous].caption.reset(false);
  },
  
  stopAnim: function(anim){
    if(anim && anim.status() == "playing"){
      return false;
    }
    return true;
  },
  
  initRotate: function(){
    if(dojo.isIE < 9){
      this.rotate = function(el, deg){
        el.style.filter="progid:DXImageTransform.Microsoft.Matrix(sizingMethod='auto expand')";
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
          marginTop: (parseInt(dojo.style(el,'height'))-h)/2.8+'px',
          marginLeft: (parseInt(dojo.style(el,'width'))-w)/2.8+parseInt(dojo.style(el,'marginLeft'))+'px'
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