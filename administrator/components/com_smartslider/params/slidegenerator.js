var SlideGenerator = {};

dojo.declare("SlideGenerator", null, {
	constructor: function(args) {
	 dojo.mixin(this,args);
    this.generator = dojo.byId('paramsgenerator');
    dojo.connect(this.generator, 'onchange', this, 'onShowForm');
    this.generatorform = dojo.byId('generatorform');
    
    this.contents = dojo.byId('contents');
    this.contentm = dojo.byId('contentmanager'); //contenmanager div
    this.captions = dojo.byId('captions');
    this.captionm = dojo.byId('captionmanager'); //captionmanager div
    this.addfields = dojo.byId('additionalfields'); //additionalfields div
    this.addcaptions = dojo.byId('additionalcaptions'); //additional captions div
        
    this.contentaddbuttons = dojo.byId('contentaddbuttons');
	  this.captionaddbuttons = dojo.byId('captionaddbuttons');
    
   /*var els = dojo.query(".miniimage", this.generator.parentNode);
	 els.connect('onmouseenter', this, 'hover');
	 els.connect('onmouseleave', this, 'hoverend');
	 */
	 this.firstvalue = this.generator.options[this.generator.selectedIndex].value; //the value is equals of lang_choose variable value if it is a new slider
	 if(this.generator.options[this.generator.selectedIndex].value != this.lang_choose) {
    this.runs = 0; //for can count the selections of the slide generator type -> if 0 it means that no changes by user -> read the selected item from db
    this.onShowForm();
   } 
	}, 
	
	onShowForm: function() {
	 this.contentm.innerHTML = null;
	 this.captionm.innerHTML = null;
	 this.captions.innerHTML = null;
	 this.addedselect = 0;
	 this.addedcaption = 0;
	 this.positions = new Array();
	 this.captionpositions = new Array();
	 this.addfields.innerHTML = null;
	 this.addcaptions.innerHTML = null;
   this.contentaddbuttons.innerHTML = null;
	 this.captionaddbuttons.innerHTML = null;   
   
   if(this.generator.options[this.generator.selectedIndex].value.length) {
   //if(this.generator.options[this.generator.selectedIndex].value != this.lang_choose || this.generator.options[this.generator.selectedIndex].value != '') {
    this.generatorform.innerHTML = eval('this.data.'+this.generator.options[this.generator.selectedIndex].value+'.html');
    this.contents.innerHTML = eval('this.data.contents.html');
    this.contentselect = dojo.byId('paramsgeneratorcontents');
    
    this.imgs = '';
    
    this.getAddButtons('content');
    this.getAddButtons('caption');
    
    dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['allowedtype'], function(item, i) {
      dojo.create('option', { 'value': this.data[item]['lname'], 'innerHTML': this.data[item]['name'] }, this.contentselect);
      this.imgs += this.data[item]['img'];
    },this);
    
   this.contentc = dojo.connect(this.contentselect, 'onchange', this, 'onShowContent');
  //  this.contentc = dojo.connect(this.contentselect, 'onchange', this, 'onShowContent2');
    this.contentselect.callChange = dojo.hitch(this, "onChangeSelectValue");
    
    var cleardiv = dojo.create("div", {"style": "clear: both", "innerHTML": ""}, this.contentselect, "after");
    var imgdiv = dojo.create('div', {'innerHTML': this.imgs}, cleardiv, 'after');
    
      //if showcaption = 1 in the xml file show the captions
    if(this.data[this.generator.options[this.generator.selectedIndex].value]['showcaptions'] != 0){
      this.captions.innerHTML += eval('this.data.captions.html');
      this.captionselect = dojo.byId('paramsgeneratorcaptions');
      if (this.firstvalue == this.lang_choose) //make blank caption template for default
        this.captionselect.selectedIndex = 1;
      this.captionc = dojo.connect(this.captionselect, 'onchange', this, 'onShowCaption');
      this.captionselect.callChange = dojo.hitch(this, "onShowCaption");
      this.onShowCaption();
    }
    
   /* var els = dojo.query(".miniimage", this.contentselect.parentNode);
	  els.connect('onmouseenter', this, 'hover');
	  els.connect('onmouseleave', this, 'hoverend');
	  */
	     
 	  this.onShowContent();
	  
	  if(!this.firstvalue.length) {
	   if(this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcontent'] != null || this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcontent'] != undefined) {
	     this.getDefaultContents();
	   }
	 /*
	   if(this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcaption'] != null || this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcaption'] != undefined) {
	     this.getDefaultCaptions();
	   }*/
	  } 
	  
	 
	  
	 } else {
    this.generatorform.innerHTML = null;
    this.contents.innerHTML = null;
    dojo.disconnect(this.contentc);
    dojo.disconnect(this.captionc);
   }
  },
   
   //this function called when clicked on a content template type
  onChangeSelectValue: function(index) {
    for(i=0;i<this.contentselect.options.length;i++) {
      if(this.contentselect.options[i].value.toUpperCase() == this.data['contents']['types'][index].toUpperCase()) {
        this.contentselect.selectedIndex = i;
        break;
      }
    }
    if(this.addedselect>0) this.checkOptions();
    this.onShowContent();
   },
   /*
   onShowContent2: function() {
     if(this.addedselect>0) this.checkOptions();  
     this.onShowContent();
   },*/

  onShowContent: function() {
    dojo.empty(this.addfields);
    if(this.runs == 0) {
      dojo.forEach(this.contentselect.options, function(item, i) {
        if(item.value == this.data['selectedcontent']) {
          this.contentselect.selectedIndex = i;
          return;
        }
      },this);
      if(this.data['selectedcontent'] != null && this.data['selectedcontent'] != undefined && this.data['selectedcontent'] != '') {
        this.contentm.innerHTML = eval('this.data.'+this.data['selectedcontent']+'.html[1]');
      } else {
        this.contentm.innerHTML = eval('this.data.'+this.contentselect.options[this.contentselect.selectedIndex].value+'.html[1]');
      }
      //this.getDefaultButton();
      this.runs++;
    } else {
      this.contentm.innerHTML = eval('this.data.'+this.contentselect.options[this.contentselect.selectedIndex].value+'.html[1]');
      if(this.addedselect>0) this.checkOptions();
      if(this.addedcaption>0) this.checkCaptionOptions();
    }
    this.makeSelects();
    this.makeCaptions();
   this.getDefaultButton("content");
  },
  
  onShowCaption: function() {
      this.captionm.innerHTML = eval('this.data.'+this.captionselect.options[this.captionselect.selectedIndex].value+'.html[1]');
          if(this.addedcaption>0) this.checkCaptionOptions();
             if((dojo.query('.defaultbutton').length < 2 && this.runs > 0) && (this.captionselect.options[this.captionselect.selectedIndex].value != 'nocaption' && this.captionselect.options[this.captionselect.selectedIndex].value != 'blank') ) 
             //this.getDefaultButton("caption");
              this.getDefaultButton("");
    
  },
  
  getAddButtons: function(type) {
    var pos = '';
    if(type == 'content') {
      pos = dojo.byId('contentaddbuttons');
    } else if(type == 'caption') {
      pos = dojo.byId('captionaddbuttons');
    }
    if((this.data[this.generator.options[this.generator.selectedIndex].value][type + 'position'] != undefined) && (this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue'] != undefined) ) {
      var plusbuttons = dojo.create('div', {'class': 'plusbuttons'}, pos);
      //plusbutton
      var plusbutton = dojo.create('div', {'class': 'plusbutton_left'}, plusbuttons);
      var plusbutton_r = dojo.create('div', {'class': 'plusbutton_right'}, plusbutton);
      var plusbutton_c = dojo.create('div', {'class': 'plusbutton_center', 'innerHTML': this.lang_addposition}, plusbutton_r);
      
      //custom plusbutton
      var customplusbutton = dojo.create('div', {'class': 'plusbutton_left'}, plusbuttons);
      var customplusbutton_r = dojo.create('div', {'class': 'plusbutton_right'}, customplusbutton);
      var customplusbutton_c = dojo.create('div', {'class': 'plusbutton_center', 'innerHTML': this.lang_addcustom}, customplusbutton_r);
      
     if(type == 'content') { //place the add buttons to suitable positions
      dojo.connect(plusbutton, 'onclick', this, 'addNewItem');
      dojo.connect(customplusbutton, 'onclick', this, 'addNewCustomItem');
      } else if(type == 'caption') {
      dojo.connect(plusbutton, 'onclick', this, 'addNewCaption');
      dojo.connect(customplusbutton, 'onclick', this, 'addNewCustomCaption');
      }       
    }
  },
  
  addNewItem: function(pos, val, t, limit) {
    this.checkPositions();
    if (this.positions.length < this.data[this.contentselect.options[this.contentselect.selectedIndex].value]['fields'].length) {
      if((this.data[this.generator.options[this.generator.selectedIndex].value]['contentposition'] != undefined) && (this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue'] != undefined) ) {
        
        var selectdiv = dojo.create('div', {'class': 'selectdiv'}, this.addfields);
        var cleardiv = dojo.create('div', {'class': 'minusbutton'}, selectdiv);
        dojo.connect(cleardiv, 'onclick', this, 'clearSelect');
        
        if(t != 1) { //for custom textarea
          t = 0;
          var selectvalue = dojo.create('select', {'id': 'selectvalue'+this.addedselect}, selectdiv);
          dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue']['name'], function(item, i) {
           if (val != null && val == item) {
              dojo.create('option', {'value': item , 'innerHTML': this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue']['data'][i], 'selected': 'selected'}, selectvalue);
            } else {
              dojo.create('option', {'value': item, 'innerHTML': this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue']['data'][i]}, selectvalue);
            }
          },this);
    
        } else {
          if(val != null) {
            var selectvalue = dojo.create('textarea', {'id': 'selectvalue'+this.addedselect, 'value': val}, selectdiv);
          } else {
            var selectvalue = dojo.create('textarea', {'id': 'selectvalue'+this.addedselect}, selectdiv);
          }
        }
        
        var temp = '';
        var selectpos = dojo.create('select', {'id': 'selectpos'+this.addedselect, 'class': 'selectpos'}, selectdiv);
        dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['contentposition'], function(item, i){
         temp = item.replace(/\s/g, '').toLowerCase();
          //if(!this.in_array(item, this.positions) && ((this.in_array(item, this.data[this.contentselect.options[this.contentselect.selectedIndex].value]['fields'])) || (item == 'slidetitle') )) {
          if(!this.in_array(temp, this.positions) && ((this.in_array(temp, this.data[this.contentselect.options[this.contentselect.selectedIndex].value]['fields'])) || (temp == 'slidetitle') )) {
            if (pos != null && pos == temp) {
              //dojo.create('option', {'value': item, 'innerHTML': item, 'selected': 'selected'}, selectpos);
              dojo.create('option', {'value': temp, 'innerHTML': item.replace('Content', ''), 'selected': 'selected'}, selectpos);
            } else {
              //dojo.create('option', {'value': item, 'innerHTML': item}, selectpos);
              dojo.create('option', {'value': temp, 'innerHTML': item.replace('Content', '')}, selectpos);
            }
          }  
        },this);
         
        var limiter = '';
        if(limit == undefined || limit <= 0 ) {
         limiter = '';
        } else {
          limiter = limit;
        }
        if (t == 0) {
          var lbl = dojo.create('label', {'for': selectpos.options[selectpos.selectedIndex].value + 'lbl', 'innerHTML': this.lang_limit + ': ', 'class': 'limiterlbl'}, selectdiv);
          selectpos.lim = dojo.create('input', {'type': 'text', 'class': 'limiter', 'value': limiter, 'id': selectpos.options[selectpos.selectedIndex].value + 'lbl'}, selectdiv);
          selectpos.input = dojo.create('input', {'type': 'hidden', 'name': 'params[generator'+selectpos.options[selectpos.selectedIndex].value+']', 'id': 'paramsgenerator'+selectpos.options[selectpos.selectedIndex].value, 'value': '{' + selectvalue.options[selectvalue.selectedIndex].value + ',' + limiter + '}' }, selectdiv);
        } else {
          selectpos.input = dojo.create('input', {'type': 'hidden', 'name': 'params[generator'+selectpos.options[selectpos.selectedIndex].value+']', 'id': 'paramsgenerator'+selectpos.options[selectpos.selectedIndex].value, 'value': selectvalue.value }, selectdiv);
        }
          selectpos.val = selectvalue;
          selectpos.t = t;
                          
        dojo.connect(selectpos, 'onchange', this, 'checkOptions');
        dojo.connect(selectvalue, 'onchange', this, 'checkOptions');
        dojo.connect(selectpos.lim, 'onchange', this, 'checkOptions');
        if(this.addedselect>0) this.checkOptions();
        this.addedselect++;
      }
    }
  },
  
  //add a new caption position
  addNewCaptionItem: function(pos, val, t, limit) {
        this.checkCaptionPositions();
    if (this.captionpositions.length < this.data[this.captionselect.options[this.captionselect.selectedIndex].value]['fields'].length) {
      if((this.data[this.generator.options[this.generator.selectedIndex].value]['captionposition'] != undefined) && (this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue'] != undefined) ) {
        
        var selectdiv = dojo.create('div', {'class': 'selectdiv'}, this.addcaptions);
        var cleardiv = dojo.create('div', {'class': 'minusbutton'}, selectdiv);
        dojo.connect(cleardiv, 'onclick', this, 'clearCaption');
        
        if(t != 1) { //for custom textarea
          t = 0;
          var selectvalue = dojo.create('select', {'id': 'selectvalue'+this.addedcaption}, selectdiv);
          dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue']['name'], function(item, i) {
           if (val != null && val == item) {
              dojo.create('option', {'value': item , 'innerHTML': this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue']['data'][i], 'selected': 'selected'}, selectvalue);
            } else {
              dojo.create('option', {'value': item, 'innerHTML': this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue']['data'][i]}, selectvalue);
            }
          },this);
    
        } else {
          if(val != null) {
            var selectvalue = dojo.create('textarea', {'id': 'selectvalue'+this.addedcaption, 'value': val}, selectdiv);
          } else {
            var selectvalue = dojo.create('textarea', {'id': 'selectvalue'+this.addedcaption}, selectdiv);
          }
        }
        
        var temp = '';
        var selectpos = dojo.create('select', {'id': 'selectcap'+this.addedcaption, 'class': 'selectcap'}, selectdiv);
        dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['captionposition'], function(item, i){
        temp = item.replace(/\s/g, '').toLowerCase(); 
          //if(!this.in_array(item, this.captionpositions) && (this.in_array(item, this.data[this.captionselect.options[this.captionselect.selectedIndex].value]['fields']))) {
          if(!this.in_array(temp, this.captionpositions) && (this.in_array(temp, this.data[this.captionselect.options[this.captionselect.selectedIndex].value]['fields']))) {
            if (pos != null && pos == temp) {
              dojo.create('option', {'value': temp, 'innerHTML': item.replace('Caption', ''), 'selected': 'selected'}, selectpos);
            } else {
              dojo.create('option', {'value': temp, 'innerHTML': item.replace('Caption', '')}, selectpos);
            }
          }  
        },this);
         
        var limiter = '';
        var lim = ''; 
        if(limit == undefined || limit <= 0 ) {
          limiter = 0;
          lim = '';
        } else {
          limiter = limit;
          lim = limit;
        }
        if (t == 0) {
          var lbl = dojo.create('label', {'for': selectpos.options[selectpos.selectedIndex].value + 'lbl', 'innerHTML': this.lang_limit + ': ', 'class': 'limiterlbl'}, selectdiv);
          selectpos.lim = dojo.create('input', {'type': 'text', 'class': 'limiter', 'value': lim, 'id': selectpos.options[selectpos.selectedIndex].value + 'lbl'}, selectdiv);
          selectpos.input = dojo.create('input', {'type': 'hidden', 'name': 'params[generator'+selectpos.options[selectpos.selectedIndex].value+']', 'id': 'paramsgenerator'+selectpos.options[selectpos.selectedIndex].value, 'value': '{' + selectvalue.options[selectvalue.selectedIndex].value + ', ' + limiter + '}' }, selectdiv);
        } else {
          selectpos.input = dojo.create('input', {'type': 'hidden', 'name': 'params[generator'+selectpos.options[selectpos.selectedIndex].value+']', 'id': 'paramsgenerator'+selectpos.options[selectpos.selectedIndex].value, 'value': selectvalue.value }, selectdiv);
        }
          selectpos.val = selectvalue;
          selectpos.t = t;
                          
        dojo.connect(selectpos, 'onchange', this, 'checkCaptionOptions');
        dojo.connect(selectvalue, 'onchange', this, 'checkCaptionOptions');
        dojo.connect(selectpos.lim, 'onchange', this, 'checkCaptionOptions');
        if(this.addedcaption>0) this.checkCaptionOptions();
        this.addedcaption++;
      }
    }
  },
  
  
  addNewCustomItem: function() {
    this.addNewItem(null, null, 1);
  },
  
  addNewCaption: function() {
    this.addNewCaptionItem(null, null, 0);
  },
  
  addNewCustomCaption: function() {
    this.addNewCaptionItem(null, null, 1, 0, 'caption');
  },
  
  checkPositions: function() {
    var pos = dojo.query('.selectpos');
    //this.positions.splice(0,this.positions.length);
    this.positions.length = 0; //clear positions array
    dojo.forEach(pos, function(item, i) {
      this.positions[i] = item.options[item.selectedIndex].value;
    },this);
  },
  
  checkCaptionPositions: function() {
    var pos = dojo.query('.selectcap');
    this.captionpositions.splice(0,this.captionpositions.length); //clear positions array
    dojo.forEach(pos, function(item, i) {
      this.captionpositions[i] = item.value;
    },this);
  },
  
  checkCaptionOptions: function() {
    this.checkCaptionPositions();
    var pos = dojo.query('.selectcap');
    dojo.forEach(pos, function(item, i) {        
      if(this.in_array(item.options[item.selectedIndex].value, this.data[this.captionselect.options[this.captionselect.selectedIndex].value]['fields'])) {
        var selected = item.options[item.selectedIndex].value;
      } else {
        var selected = null;
      }
      dojo.empty(item);
      dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['captionposition'], function(option, j){
      temp = option.replace(/\s/g, '').toLowerCase(); 
          //if((!this.in_array(option, this.captionpositions) || selected == option) && (this.in_array(option, this.data[this.captionselect.options[this.captionselect.selectedIndex].value]['fields']))) {
          if((!this.in_array(temp, this.captionpositions) || selected == temp) && (this.in_array(temp, this.data[this.captionselect.options[this.captionselect.selectedIndex].value]['fields']))) {
            dojo.create('option', {'value': temp, 'innerHTML': option.replace('Caption', '')}, item);
          }      
      },this);
      for(i=0;i<item.options.length;i++) {
        if(item.options[i].value.toUpperCase() == selected.toUpperCase()) {
          item.selectedIndex = i;
          break;
        }
      }
      if(item.options.length < 1) {
        dojo.destroy(item.parentNode);
        this.checkCaptionPositions();
      }
      var limiter = 0;
      if (item.lim != undefined && item.lim.value > 0 && item.lim.value != undefined) {
          limiter = item.lim.value;
      } 
      
      if (item.t == 0) {
        item.input.value = '{' + item.val.options[item.val.selectedIndex].value + ', ' + limiter + '}';
      } else {
        item.input.value = item.val.value;
      }
      if(item.options.length > 0 ) {
        item.input.id = 'paramsgenerator' + item.options[item.selectedIndex].value;
        item.input.name = 'params[generator'+item.options[item.selectedIndex].value+']';
      }
    }, this);
  
  
  },

  checkOptions: function() {
    this.checkPositions();
    var pos = dojo.query('.selectpos');
    var selected = '';
    dojo.forEach(pos, function(item, i) {        
      if(this.in_array(item.options[item.selectedIndex].value, this.data[this.contentselect.options[this.contentselect.selectedIndex].value]['fields']) || item.options[item.selectedIndex].value == 'slidetitle') {
        var selected = item.options[item.selectedIndex].value;
      } else {
        var selected = null;
      }
      dojo.empty(item);
      dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['contentposition'], function(option, j){
        temp = option.replace(/\s/g, '').toLowerCase(); 
          //if((!this.in_array(option, this.positions) || selected == option) && ( (this.in_array(option, this.data[this.contentselect.options[this.contentselect.selectedIndex].value]['fields'])) || option == 'slidetitle' )) {
          if((!this.in_array(temp, this.positions) || selected == temp) && ( (this.in_array(temp, this.data[this.contentselect.options[this.contentselect.selectedIndex].value]['fields'])) || temp == 'slidetitle' )) {
            //dojo.create('option', {'value': option, 'innerHTML': option}, item);
            dojo.create('option', {'value': temp, 'innerHTML': option.replace('Content', '')}, item);
          }      
      },this);
      for(i=0;i<item.options.length;i++) { 
        if(selected != null && item.options[i].value.toUpperCase() == selected.toUpperCase()) {
          item.selectedIndex = i;
          break;
        }
      }
      if(item.options.length < 1) {
        dojo.destroy(item.parentNode);
        this.checkPositions();
      }
      var limiter = 0;
      if (item.lim != undefined && item.lim.value > 0 && item.lim.value != undefined) {
          limiter = item.lim.value;
      } 
      
      if (item.t == 0) {
        item.input.value = '{' + item.val.options[item.val.selectedIndex].value + ', ' + limiter + '}';
      } else {
        item.input.value = item.val.value;
      }
      if(item.options.length > 0 ) {
        item.input.id = 'paramsgenerator' + item.options[item.selectedIndex].value;
        item.input.name = 'params[generator'+item.options[item.selectedIndex].value+']';
      }
    }, this);
  },
  
  clearSelect: function(e) {
    dojo.destroy(e.currentTarget.parentNode);
    this.checkOptions();
  },
  
  clearCaption: function(e) {
    dojo.destroy(e.currentTarget.parentNode);
    this.checkCaptionOptions();
  },
  
  makeSelects: function() {
    dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['contentposition'], function(item, i) {
      item = item.replace(/\s/g, '').toLowerCase();
      if(this.data['sliderdatas']['generator'+item] != null) {
        var pattern = /\{([a-z_]*)(,\s([0-9]*))?\}/;
        var check = pattern.exec(this.data['sliderdatas']['generator'+item]);
        if((check != null) && (check[0] == this.data['sliderdatas']['generator'+item] && this.in_array(check[1], this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue']['name'])) ) {
          this.addNewItem(item, check[1], 0, check[3]);
        } else {
          this.addNewItem(item, this.data['sliderdatas']['generator'+item], 1)
        }
      }
    },this);
  },
  
  
  makeCaptions: function() {
    dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['captionposition'], function(item, i) {
      item = item.replace(/\s/g, '').toLowerCase();
      if(this.data['sliderdatas']['generator'+item] != null) {
        var pattern = /\{([a-z_]*)(,\s?([0-9]*))?\}/;
        var check = pattern.exec(this.data['sliderdatas']['generator'+item]);
        if((check != null) && (check[0] == this.data['sliderdatas']['generator'+item] && this.in_array(check[1], this.data[this.generator.options[this.generator.selectedIndex].value]['contentvalue']['name'])) ) { 
          this.addNewCaptionItem(item, check[1], 0, check[3]);
        } else {
          this.addNewCaptionItem(item, this.data['sliderdatas']['generator'+item], 1)
        }
      }
    },this);
  },

/*
  
  hover: function(e){
    e.currentTarget.src = e.currentTarget.src.replace("-selected."+this.ext, ".png").replace(".png", "-selected."+this.ext);
  },
  
  hoverend: function(e){
    if(!dojo.hasClass(e.currentTarget, 'selected') )
      e.currentTarget.src = e.currentTarget.src.replace("-selected."+this.ext, ".png");
  },
   */
  getDefaultButton: function(type) {
    var defb = dojo.query('.defaultbutton');
    dojo.forEach(defb, function(item, i) {
      dojo.destroy(item);
    }, this);
    if(type != "caption") {
      if(this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcontent'] != null || this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcontent'] != undefined) {
        var defbutton = dojo.create('div', {'class': 'defaultbutton'}, this.contentm);
        this.defaultbutton = dojo.create('div', {'class': 'plusbutton_left'}, defbutton);
        var defaultbutton_r = dojo.create('div', {'class': 'plusbutton_right'}, this.defaultbutton);
        var defaultbutton_c = dojo.create('div', {'class': 'plusbutton_center', 'innerHTML': this.lang_default}, defaultbutton_r);
        dojo.connect(this.defaultbutton, 'onclick', this, 'getDefaultContents');
      }
    } 
    if(type!= "content") {
      if(this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcaption'] != null || this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcaption'] != undefined) {
        var defcaptionbutton = dojo.create('div', {'class': 'defaultbutton'}, this.captionm);
        this.defaultcaptionbutton = dojo.create('div', {'class': 'plusbutton_left'}, defcaptionbutton);
        var defaultbutton_r = dojo.create('div', {'class': 'plusbutton_right'}, this.defaultcaptionbutton);
        var defaultbutton_c = dojo.create('div', {'class': 'plusbutton_center', 'innerHTML': this.lang_default}, defaultbutton_r);
        dojo.connect(this.defaultcaptionbutton, 'onclick', this, 'getDefaultCaptions');
      }
    }
  },
  
  getDefaultContents: function() {
      dojo.empty(this.addfields);
      dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcontent']['position'], function(item, i) {
        this.addNewItem(item, this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcontent']['data'][i]);
      }, this);
  },
  
  getDefaultCaptions: function() {
      dojo.empty(this.addcaptions);
      dojo.forEach(this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcaption']['position'], function(item, i) {
        this.addNewCaptionItem(item, this.data[this.generator.options[this.generator.selectedIndex].value]['defaultcaption']['data'][i]);
      }, this);    
  },
  
  in_array: function(needle, haystack, argStrict) {
    // Checks if the given value exists in the array  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/in_array    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: vlado houba
    // +   input by: Billy
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false
    var key = '',        strict = !! argStrict;
 
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {                return true;
            }
        }
    } else {
        for (key in haystack) {            if (haystack[key] == needle) {
                return true;
            }
        }
    } 
    return false;
  }
	 
	 });