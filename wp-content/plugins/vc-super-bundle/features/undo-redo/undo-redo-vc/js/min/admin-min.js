jQuery(document).ready(function(t){"use strict";var e,n=function(t,e,n){var o;return function(){var c=this,r=arguments,a=n&&!o;clearTimeout(o),o=setTimeout(function(){o=null,n||t.apply(c,r)},e),a&&t.apply(c,r)}},o=-1!==navigator.appVersion.indexOf("Mac"),c=t("#post_ID").val(),r=[],a=[],i=null,s=!1,u=function(){t(".ur_undo").css("opacity",r.length?"1":"0.4"),t(".ur_redo").css("opacity",a.length?"1":"0.4"),localStorage.setItem("ur_stack_"+c,JSON.stringify(r))},d=function(){var t;r.length&&(t=r.pop(),a.push(i),a.length>50&&a.shift(),f(t),s=!0,y(),clearTimeout(e),e=setTimeout(function(){s=!1},10),u())},l=function(){var t;a.length&&(t=a.pop(),r.push(i),r.length>50&&r.shift(),f(t),s=!0,y(),clearTimeout(e),e=setTimeout(function(){s=!1},10),u())},p=n(function(){var t;s||(a=[],t=v(),null!==i?(r.push(i),i=t,u()):i=t)},10),v=function(){try{return tinyMCE.activeEditor.getContent()}catch(t){return jQuery(".wp-editor-area").val()}},f=function(t){t&&(i=t,tinyMCE.activeEditor.setContent(t))},y=function(){vc.shortcodes.fetch({reset:!0})},h=n(function(t){(t.metaKey||t.ctrlKey)&&t.shiftKey&&90===t.keyCode?l():(t.metaKey||t.ctrlKey)&&90===t.keyCode?d():o||!t.metaKey&&!t.ctrlKey||89!==t.keyCode||l()},10);if("undefined"!=typeof vc&&void 0!==vc.shortcodes){if(localStorage.getItem("ur_stack_"+c))try{r=JSON.parse(localStorage.getItem("ur_stack_"+c))}catch(t){}vc.shortcodes.bind("change",p),vc.shortcodes.bind("remove",p),t(document).keydown(h),t(".vc_templates-button").css("border-right","none"),t('<li class="ur_undo_wrapper"><a href="javascript:;" class="vc_icon-btn vc_templates-button ur_undo" title="Undo"><i class="vc-composer-icon dashicons-undo dashicons" style="font-family: \'dashicons\' !important; text-decoration: none;"></i></a></li>').appendTo(".vc_navbar-nav"),t('<li class="ur_redo_wrapper"><a href="javascript:;" class="vc_icon-btn vc_templates-button vc_navbar-border-right ur_redo" title="Redo"><i class="vc-composer-icon dashicons-redo dashicons" style="font-family: \'dashicons\' !important; text-decoration: none;"></i></a></li>').appendTo(".vc_navbar-nav"),t("body").append("<style>.vc_navgar-frontend .ur_undo_wrapper, .vc_navgar-frontend .ur_redo_wrapper { display: none; }</style>"),t("body").on("click",".ur_undo",function(){d()}),t("body").on("click",".ur_redo",function(){l()}),u()}});