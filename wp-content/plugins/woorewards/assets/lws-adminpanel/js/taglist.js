(function(b){b.widget("custom.lws_taglist",{_create:function(){var d=this;this.container=this.element.parent();this.valueList=this.element.val().split(";");for(var a=0;a<this.valueList.length;a++){if(this.valueList[a]==""){this.valueList.splice(a,1)}}this._createStructure();this._fillList();this.container.on("click",".lws-tag-remove",this._bind(this._delTag,this));this.container.on("click",".lws-taglist-btadd",this._bind(this._addTags,this));this.container.on("keypress",".lws-taglist-input",function(c){if(c.which==13){d.container.find(".lws-taglist-btadd").trigger("click")}})},_bind:function(a,d){return function(){return a.apply(d,arguments)}},_createStructure:function(){b("<div>",{"class":"lws-taglist-wrapper"}).append(b("<div>",{"class":"lws-taglist-top"}).append(b("<input>",{type:"text","class":"lws-input lws-taglist-input"})).append(b("<a>",{"class":"lws-taglist-btadd",html:this.element.data("btlabel")}))).append(b("<div>",{"class":"lws-taglist-bottom"})).appendTo(this.container)},_fillList:function(){var f=this;var e=this.container.find(".lws-taglist-bottom");e.empty();for(var a=0;a<this.valueList.length;a++){b("<div>",{"class":"lws-tag-wrapper","data-index":a}).append(b("<div>",{"class":"lws-tag-text",html:this.valueList[a]})).append(b("<a>",{"class":"lws-tag-remove lws-icon-cross"})).appendTo(e)}},_delTag:function(d){var a=b(d.currentTarget).parent();this.valueList.splice(a.data("index"),1);a.remove();this._updateList()},_addTags:function(a){var d=this.valueList.concat(b(a.currentTarget).parent().find(".lws-taglist-input").val().split(","));this.valueList=d.filter(function(c,g,h){return g===h.indexOf(c)});b(a.currentTarget).parent().find(".lws-taglist-input").val("");this._fillList();this._updateList()},_updateList:function(){var d="";for(var a=0;a<this.valueList.length;a++){d+=this.valueList[a]+";"}d=d.slice(0,-1);this.element.val(d)}})})(jQuery);jQuery(function(b){b(".lws_taglist").lws_taglist()});