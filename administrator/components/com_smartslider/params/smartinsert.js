var SmartInsertConfigurator = {};
dojo.require("dojo.window");

dojo.declare("SmartInsertConfigurator", null, {
	constructor: function(args) {
	 dojo.mixin(this,args);
	 this.init();
  },
  
  init: function(){
    window.SmartInsertConfigurator = this;
  },
  
  showSelector: function(){
    pos = dojo.window.getBox();
    this.cont = dojo.create('div', { innerHTML: this.h });    
    return this.cont;
   }, 
   
   getselects: function() {
    this.select = dojo.byId('dbselect');
     dojo.connect(this.select, 'onchange', this, 'onshowfields');  
    this.fields = dojo.byId('fieldselect');
     dojo.connect(this.fields, 'onchange', this, 'getvalues');
    this.values = dojo.byId('values');
    dojo.connect(this.values, 'onchange', this, 'onshowbtns');
    this.length = dojo.byId('length');
    
    this.fldtr = dojo.query('.noshowfields');
    this.valtr = dojo.query('.noshowvalues');
    this.lentr = dojo.query('.noshowlength');
   },
    
  onshowfields: function() {
    if( this.select.value != "-Choose-" ) {
      window.InsertSelector.destroybuttons();

      dojo.empty(this.fields);
      dojo.empty(this.values);
           
      dojo.style(this.fldtr[0], 'display', 'block');
     // dojo.style(this.fldtr[0], 'display', 'table-row');
      dojo.style(this.valtr[0], 'display', 'none');
      dojo.style(this.lentr[0], 'display', 'none');
      
      dojo.create("option", {innerHTML: "-Choose-" }, this.fields);
      var arr = this.sqltables[this.select.value];
      dojo.forEach(arr, function(item, i) {
        if ( i == 0 || i == 1 ) return;
        dojo.create("option", { value: i, innerHTML: item }, this.fields);
      },this);
    } else {
      window.InsertSelector.destroybuttons();
      
      dojo.empty(this.fields);
      dojo.empty(this.values);
      
      dojo.style(this.fldtr[0], 'display', 'none');
      dojo.style(this.valtr[0], 'display', 'none');
      dojo.style(this.lentr[0], 'display', 'none');
    }
  },
  
  getvalues: function() {
    if( this.fields.value != "-Choose-" ) {
      window.InsertSelector.destroybuttons();
      dojo.style(this.valtr[0], 'display', 'none');
      dojo.style(this.lentr[0], 'display', 'none');
 
      dojo.xhrPost({
        url: this.url + "index.php?option=com_smartslider&controller=slide&task=ajax&tmpl=component&format=raw",
        handleAs: "json",
        content: {table: this.select.value, field: this.fields.value},
        load: dojo.hitch(this, 'load')
      });
    } else {
      window.InsertSelector.destroybuttons();
      dojo.empty(this.values);
      dojo.style(this.valtr[0], 'display', 'none');
      dojo.style(this.lentr[0], 'display', 'none');
    }
  },
  
  load: function(result) {
      dojo.empty(this.values);
      dojo.create("option", {innerHTML: "-Choose-" }, this.values);
      var labels;
      if ( this.sqltables[this.select.value][1] == this.sqltables[this.select.value][this.fields.value] ) {
        labels = this.sqltables[this.select.value][1];
      } else {
        labels = this.sqltables[this.select.value][1] + " " + this.sqltables[this.select.value][this.fields.value];
      }
      var tempEl = dojo.create("optgroup", { label: labels }, this.values);
      dojo.forEach(result, function(item, i) {        
        if ( item[1].length > 100 ) item[1] = item[1].substring(0, 100) + '...';
        item[1] = item[1].replace(/<.*?>/g, '');
        dojo.create("option", { value: item[0], innerHTML: item[0] + ' ' + item[1] }, tempEl);
      },tempEl);
     //dojo.style(this.valtr[0], 'display', 'table-row');
     dojo.style(this.valtr[0], 'display', 'block');

  },
    
  onshowbtns: function() {
    window.InsertSelector.destroybuttons();
    if( this.values.value != "-Choose-" ) {
      //dojo.style(this.lentr[0], 'display', 'table-row');
      dojo.style(this.lentr[0], 'display', 'block');
      window.InsertSelector.showinsertbuttons('smart');
    } else {
      dojo.style(this.lentr[0], 'display', 'none');
    }
  },
  
  inserttext: function() {
    var pk = this.sqltables[this.select.value][2];
    var lth = this.length.value;
    if( lth == 0 ) {
      lth = '';
    }
    return '{SMR ' + this.select.value + ' ' + this.sqltables[this.select.value][this.fields.value] + ' ' + pk +'=' + this.values.value + ' ' + lth + '}';
  }
  
});