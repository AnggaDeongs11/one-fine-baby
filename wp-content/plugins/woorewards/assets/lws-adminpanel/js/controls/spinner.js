(function(b){b.widget("lws.lws_spinner",{options:{step:1},_create:function(){this.actionInterval="";this._getDataOptions();this._createStructure();this._manageEvents()},_bind:function(a,d){return function(){return a.apply(d,arguments)}},_getDataOptions:function(){if(this.element.data("step")!=undefined){this.options.step=this.element.data("step")}},_createStructure:function(){this.container=b("<div>",{"class":"lws-spinner-wrapper"}).append(b("<div>",{"class":"lws-spinner-bwrapper"}).append(b("<div>",{"class":"lws-spinner-bplus lws-icon-caret-up"})).append(b("<div>",{"class":"lws-spinner-bminus lws-icon-caret-down"}))).insertAfter(this.element);this.element.detach().prependTo(this.container).addClass("lws-spinner-input")},_manageEvents:function(){if(this.element.prop("disabled")){this.textInput.prop("disabled",true);this.container.find(".lws-spinner-bplus").addClass("lws-disabled");this.container.find(".lws-spinner-bminus").addClass("lws-disabled")}else{this.container.on("mousedown",".lws-spinner-bplus",this._bind(this._btPlus,this));this.container.on("mousedown",".lws-spinner-bminus",this._bind(this._btMinus,this));this.container.on("mouseup",".lws-spinner-bplus",this._bind(this._btRelease,this));this.container.on("mouseup",".lws-spinner-bminus",this._bind(this._btRelease,this));this.container.on("mouseout",".lws-spinner-bplus",this._bind(this._btRelease,this));this.container.on("mouseout",".lws-spinner-bminus",this._bind(this._btRelease,this))}},_changeValue:function(e,f){var a=0;if(f=="plus"){a=parseInt(this.element.val())+e}if(f=="minus"){a=parseInt(this.element.val())-e}this.element.val(a)},_btPlus:function(){var a=this;this.actionInterval=setInterval(function(){a._changeValue(a.options.step,"plus")},70)},_btMinus:function(){var a=this;this.actionInterval=setInterval(function(){a._changeValue(a.options.step,"minus")},70)},_btRelease:function(){clearInterval(this.actionInterval);this.element.trigger("change")}})})(jQuery);jQuery(function(b){b(".lws_spinner").lws_spinner()});