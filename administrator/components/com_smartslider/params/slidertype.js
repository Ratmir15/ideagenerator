var ThemeConfigurator = {};

dojo.declare("ThemeConfigurator", null, {
	constructor: function(args) {
	 dojo.mixin(this,args);
	 
	 this.paramstype = dojo.byId('paramstype');
	 dojo.connect(this.paramstype, 'onchange', this, 'changeType');

   this.changeType();
  },
  
  changeType: function(e){
    if(this.chooserconnect)
      dojo.disconnect(this.chooserconnect);
      
    this.type = this.paramstype.options[this.paramstype.selectedIndex].value;
    
    dojo.byId('typesettings').innerHTML = eval('this.data.'+this.type+'.html');
    eval(eval('this.data.'+this.type+'.script'));
    
    var themechooser = dojo.byId('themechooser');
    themechooser.innerHTML = eval('this.data.'+this.type+'.chooser.html');
    this.chooserconnect = dojo.connect(themechooser, 'onchange', this, 'changeTheme');
    this.changeTheme();
  },
  
  changeTheme: function(e){
    var paramstheme = dojo.byId('paramstheme');
    this.theme = paramstheme.options[paramstheme.selectedIndex].value;
   
    var themechooser = dojo.byId('thememanager');
    themechooser.innerHTML = eval('this.data.'+this.type+'.themes.'+this.theme+'.html');
    eval(eval('this.data.'+this.type+'.themes.'+this.theme+'.script'));
    
  }
  
});
