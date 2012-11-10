var Ordering = {};

dojo.declare("Ordering", null, {
	constructor: function(args) {
	 dojo.mixin(this,args);
	 this.node = dojo.byId('paramsordering');

	 this.getOptions();
	 
	 this.slide = this.options[this.node.selectedIndex];
	 this.prev = this.node.selectedIndex;
	 
	 dojo.connect(this.node, 'onchange', this, 'onChange');
  },
  
  getOptions: function(){
    this.options = this.node.options;
  },
  
  onChange: function(){
    var selected = this.options[this.node.selectedIndex];
    if(this.node.selectedIndex < this.prev){
      dojo.place(this.slide, selected, "before");
      this.node.selectedIndex = this.node.selectedIndex-1;
    }else{
      dojo.place(this.slide, selected, "after");
      this.node.selectedIndex = this.node.selectedIndex+1;
    }

    this.prev = this.node.selectedIndex;
    this.slide.value = this.prev;
    this.getOptions();
  }
  
});
