"use strict";function _classCallCheck(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}var _createClass=function(){function t(t,e){for(var a=0;a<e.length;a++){var s=e[a];s.enumerable=s.enumerable||!1,s.configurable=!0,"value"in s&&(s.writable=!0),Object.defineProperty(t,s.key,s)}}return function(e,a,s){return a&&t(e.prototype,a),s&&t(e,s),e}}(),Back=function(){function t(e){_classCallCheck(this,t),this.object=e?e:{},this.interval=null,this.initFn(this.object,this.constructor.setObject,this.addEl,this.constructor.setInputVal),this.categoryChilds(this.object,this.constructor.setInputVal),this.search(this.interval,this.object),this.sections(),this.sort(".item-menu",this.object,this.constructor.setInputVal),this.tabs("[data-tab]"),this.toggleBox()}return _createClass(t,[{key:"search",value:function(t,e){function a(t){jQuery.ajax({type:"POST",url:"/wp-content/plugins/jsplugin/scripts/back.search.php",data:{data:t.values,compare:e},beforeSend:function(){jQuery('<span class="js-spinner"></span>').appendTo(jQuery(t.el.closest(".bd")))},success:function(e){t.el.closest(".bd").css({"max-height":"1000px"}),jQuery(t.el.closest(".bd").find("#ajax-items")).html(e)},complete:function(){jQuery(".js-spinner").remove(),t={}}})}jQuery(document).on("keyup","[data-search]",function(e){var s=jQuery(this),n={el:s,values:{type:s.data("search"),val:s.val(),id:jQuery("#post_ID").val()}};try{if(s.val().length<3)throw"neni vetsi jak tri";if(8===e.keyCode)throw"to je back"}catch(t){return void console.log(t)}console.log(e.keyCode),clearTimeout(t),t=setTimeout(a,450,n)})}},{key:"addEl",value:function(t){jQuery.ajax({type:"POST",url:"/wp-content/plugins/jsplugin/scripts/tpl.page.php",data:{items:t},success:function(t){jQuery("#jsmenu").html(t)}})}},{key:"initFn",value:function(t,e,a,s){jQuery(document).on("click","[data-item]",function(){var n=jQuery(this),o=n.data("item"),i=n.prop("checked"),c=n.closest("[data-type]").data("type");n.closest("li").hasClass("active")?n.closest("li").removeClass("active"):n.closest("li").addClass("active"),e(t,n,c),a(t),s(t),jQuery('[data-item="'+o+'"]').attr("checked",i),n=null,o=null,i=null,c=null})}},{key:"categoryChilds",value:function(t,e){jQuery(document).on("change",'[name="childs"]',function(){var a=jQuery(this),s=a.closest("[data-type]").find("[data-item]").data("item");a.prop("checked")===!0?(t[s].childs=!0,a.closest(".section").removeClass("disabled"),a.closest("[data-type]").find("[data-posts]").text("only posts")):(delete t[s].childs,a.closest(".section").addClass("disabled"),a.closest(".section").find("select").val(""),a.closest("[data-type]").find("[data-posts]").text(""),a.closest("[data-type]").find("[data-count]").text(""),a.closest("[data-type]").find("[data-sort]").text("")),e(t)}),jQuery(document).on("change",".section select",function(){var a=jQuery(this),s=a.closest("[data-type]").find("[data-item]").data("item"),n=a.attr("name");a.val()?(t[s][n]=a.val(),a.closest("[data-type]").find("[data-"+n+"]").text(a.val())):(delete t[s][n],a.closest("[data-type]").find("[data-"+n+"]").text("")),console.log(n),console.log(t),e(t),n=null})}},{key:"sections",value:function(){jQuery(document).on("click","[data-section]",function(){var t=jQuery(this),e=t.closest(".jsbox"),a=e.find(".bd").outerHeight();e.toggleClass("open").find(".bd").animate({"max-height":a>0?0:e.find(".box-menu").outerHeight()},120),jQuery(".jsbox").each(function(){jQuery(this).index()!==e.index()&&jQuery(this).removeClass("open").find(".bd").animate({"max-height":"0"},120)})})}},{key:"toggleBox",value:function(){jQuery(document).on("click","[data-toggle]",function(t){t.preventDefault();var e=jQuery(this),a=e.closest("[data-parent]"),s=a.find(".bd");s.hasClass("hdn")===!0?(s.removeClass("hdn"),a.addClass("open")):(s.addClass("hdn"),a.removeClass("open"))})}},{key:"tabs",value:function(t){jQuery(document).on("click",t,function(t){t.preventDefault();var e=jQuery(this),a=jQuery('[data-tabs="'+e.data("tab")+'"]');console.log(e),e.closest(".box-menu").find("[data-tab]").removeClass("active"),e.addClass("active"),e.closest(".box-menu").find("[data-tabs]").addClass("hdn"),e.closest(".box-menu").find(a).removeClass("hdn")})}},{key:"sort",value:function(t,e,a){jQuery("#jsmenu").on("mouseenter",function(){jQuery(t).sortable({placeholder:"ui-state-highlight",stop:function(){var t={};jQuery(".item-menu").find(".item-box").each(function(){var a=jQuery(this).data("menu");t[a]=e[a]}),a(t)}})})}}],[{key:"setObject",value:function(t,e,a){t[e.data("item")]?(delete t[e.data("item")],jQuery('[data-item="'+e.data("item")+'"]').attr("checked",!1)):(t[e.data("item")]={id:e.data("item"),title:e.closest("li").text().trim(),type:a},e.data("posts")&&(t[e.data("item")].posts=e.data("posts")))}},{key:"setInputVal",value:function(t){jQuery("input#js_append").val(JSON.stringify(t))}}]),t}();jQuery(document).ready(function(){var t=jQuery("#js_append"),e=t.val()?JSON.parse(t.val()):{};new Back(e)});