var SpecialInsertConfigurator = {};
dojo.require("dojo.window");

dojo.declare("SpecialInsertConfigurator", null, {
	constructor: function(args) {
	 dojo.mixin(this,args);
	 this.init();
  },
  
  init: function(){
    window.SpecialInsertConfigurator = this;
  },
  
  showSelector: function(){
    this.cont = dojo.create('div', { innerHTML: this.h });    
    return this.cont;
  }, 

  getselect: function() {
    this.specialselect = dojo.byId('specialselect');
     dojo.connect(this.specialselect, 'onchange', this, 'showform');
  },
    
  showform: function() {
    window.InsertSelector.destroybuttons();
    this.form = dojo.query('.specialinsertform');
    dojo.empty(this.form[0]);
    if( this.specialselect.value != '-Choose-' ) {
      window.InsertSelector.showinsertbuttons('special');
      var formparams = dojo.create("div", {innerHTML: this.forms[this.specialselect.value]}, this.form[0]);    
    }
  },
    
  inserttext: function() {
    var inputs = dojo.query(".specialinsertform input[type=text], .specialinsertform select[]");
    this.conditions = '';
    dojo.forEach(inputs, function(item, i) {
      element = dojo.byId(item.id);
      if(element.value !='') {
        this.conditions += ' |' + element.id.replace('param', '') + '=' + element.value + '|';
      } else {
        this.conditions += ' |' + element.id.replace('param', '') + '=' + element.options[element.selectedIndex].value + '|';
      }
    },this);
    return '{SPR ' + this.specialselect.value + '' + this.conditions + '}';
  }
  
});