"use strict";!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a(require("jquery")):a(jQuery||Zepto)}(function(a){var b=function(b,c,d){var e={invalid:[],getCaret:function(){try{var a,c=0,d=b.get(0),f=document.selection,g=d.selectionStart;return f&&navigator.appVersion.indexOf("MSIE 10")===-1?(a=f.createRange(),a.moveStart("character",-e.val().length),c=a.text.length):(g||"0"===g)&&(c=g),c}catch(a){}},setCaret:function(a){try{if(b.is(":focus")){var c,d=b.get(0);c=d.createTextRange(),c.collapse(!0),c.moveEnd("character",a),c.moveStart("character",a),c.select()}}catch(a){}},events:function(){b.on("keydown.mask",function(a){b.data("mask-keycode",a.keyCode||a.which)}).on(a.jMaskGlobals.useInput?"input.mask":"keyup.mask",e.behaviour).on("paste.mask drop.mask",function(){setTimeout(function(){b.keydown().keyup()},100)}).on("change.mask",function(){b.data("changed",!0)}).on("blur.mask",function(){g===e.val()||b.data("changed")||b.triggerHandler("change"),b.data("changed",!1)}).on("blur.mask",function(){g=e.val()}).on("focus.mask",function(b){d.selectOnFocus===!0&&a(b.target).select()}).on("focusout.mask",function(){d.clearIfNotMatch&&!h.test(e.val())&&e.val("")})},getRegexMask:function(){for(var b,d,e,g,h,i,a=[],j=0;j<c.length;j++)b=f.translation[c.charAt(j)],b?(d=b.pattern.toString().replace(/.{1}$|^.{1}/g,""),e=b.optional,g=b.recursive,g?(a.push(c.charAt(j)),h={digit:c.charAt(j),pattern:d}):a.push(e||g?d+"?":d)):a.push(c.charAt(j).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"));return i=a.join(""),h&&(i=i.replace(new RegExp("("+h.digit+"(.*"+h.digit+")?)"),"($1)?").replace(new RegExp(h.digit,"g"),h.pattern)),new RegExp(i)},destroyEvents:function(){b.off(["input","keydown","keyup","paste","drop","blur","focusout",""].join(".mask "))},val:function(a){var e,c=b.is("input"),d=c?"val":"text";return arguments.length>0?(b[d]()!==a&&b[d](a),e=b):e=b[d](),e},getMCharsBeforeCount:function(a,b){for(var d=0,e=0,g=c.length;e<g&&e<a;e++)f.translation[c.charAt(e)]||(a=b?a+1:a,d++);return d},caretPos:function(a,b,d,g){var h=f.translation[c.charAt(Math.min(a-1,c.length-1))];return h?Math.min(a+d-b-g,d):e.caretPos(a+1,b,d,g)},behaviour:function(c){c=c||window.event,e.invalid=[];var d=b.data("mask-keycode");if(a.inArray(d,f.byPassKeys)===-1){var g=e.getCaret(),h=e.val(),i=h.length,j=e.getMasked(),k=j.length,l=e.getMCharsBeforeCount(k-1)-e.getMCharsBeforeCount(i-1),m=g<i;return e.val(j),m&&(8!==d&&46!==d&&(g=e.caretPos(g,i,k,l)),e.setCaret(g)),e.callbacks(c)}},getMasked:function(a){var o,p,b=[],g=e.val(),h=0,i=c.length,j=0,k=g.length,l=1,m="push",n=-1;for(d.reverse?(m="unshift",l=-1,o=0,h=i-1,j=k-1,p=function(){return h>-1&&j>-1}):(o=i-1,p=function(){return h<i&&j<k});p();){var q=c.charAt(h),r=g.charAt(j),s=f.translation[q];s?(r.match(s.pattern)?(b[m](r),s.recursive&&(n===-1?n=h:h===o&&(h=n-l),o===n&&(h-=l)),h+=l):s.optional?(h+=l,j-=l):s.fallback?(b[m](s.fallback),h+=l,j-=l):e.invalid.push({p:j,v:r,e:s.pattern}),j+=l):(a||b[m](q),r===q&&(j+=l),h+=l)}var t=c.charAt(o);return i!==k+1||f.translation[t]||b.push(t),b.join("")},callbacks:function(a){var f=e.val(),h=f!==g,i=[f,a,b,d],j=function(a,b,c){"function"==typeof d[a]&&b&&d[a].apply(this,c)};j("onChange",h===!0,i),j("onKeyPress",h===!0,i),j("onComplete",f.length===c.length,i),j("onInvalid",e.invalid.length>0,[f,a,b,e.invalid,d])}};b=a(b);var h,f=this,g=e.val();c="function"==typeof c?c(e.val(),void 0,b,d):c,f.mask=c,f.options=d,f.remove=function(){var a=e.getCaret();return e.destroyEvents(),e.val(f.getCleanVal()),e.setCaret(a-e.getMCharsBeforeCount(a)),b},f.getCleanVal=function(){return e.getMasked(!0)},f.init=function(c){if(c=c||!1,d=d||{},f.clearIfNotMatch=a.jMaskGlobals.clearIfNotMatch,f.byPassKeys=a.jMaskGlobals.byPassKeys,f.translation=a.extend({},a.jMaskGlobals.translation,d.translation),f=a.extend(!0,{},f,d),h=e.getRegexMask(),c===!1){d.placeholder&&b.attr("placeholder",d.placeholder),b.data("mask")&&b.attr("autocomplete","off"),e.destroyEvents(),e.events();var g=e.getCaret();e.val(e.getMasked()),e.setCaret(g+e.getMCharsBeforeCount(g,!0))}else e.events(),e.val(e.getMasked())},f.init(!b.is("input"))};a.maskWatchers={};var c=function(){var c=a(this),e={},f="data-mask-",g=c.attr("data-mask");if(c.attr(f+"reverse")&&(e.reverse=!0),c.attr(f+"clearifnotmatch")&&(e.clearIfNotMatch=!0),"true"===c.attr(f+"selectonfocus")&&(e.selectOnFocus=!0),d(c,g,e))return c.data("mask",new b(this,g,e))},d=function(b,c,d){d=d||{};var e=a(b).data("mask"),f=JSON.stringify,g=a(b).val()||a(b).text();try{return"function"==typeof c&&(c=c(g)),"object"!=typeof e||f(e.options)!==f(d)||e.mask!==c}catch(a){}},e=function(a){var b=document.createElement("div");a="on"+a;var c=a in b;return c||(b.setAttribute(a,"return;"),c="function"==typeof b[a]),b=null,c};a.fn.mask=function(c,e){e=e||{};var f=this.selector,g=a.jMaskGlobals,h=a.jMaskGlobals.watchInterval,i=function(){if(d(this,c,e))return a(this).data("mask",new b(this,c,e))};return a(this).each(i),f&&""!==f&&g.watchInputs&&(clearInterval(a.maskWatchers[f]),a.maskWatchers[f]=setInterval(function(){a(document).find(f).each(i)},h)),this},a.fn.unmask=function(){return clearInterval(a.maskWatchers[this.selector]),delete a.maskWatchers[this.selector],this.each(function(){var b=a(this).data("mask");b&&b.remove().removeData("mask")})},a.fn.cleanVal=function(){return this.data("mask").getCleanVal()},a.applyDataMask=function(b){b=b||a.jMaskGlobals.maskElements;var d=b instanceof a?b:a(b);d.filter(a.jMaskGlobals.dataMaskAttr).each(c)};var f={maskElements:"input,td,span,div",dataMaskAttr:"*[data-mask]",dataMask:!0,watchInterval:300,watchInputs:!0,useInput:e("input"),watchDataMask:!1,byPassKeys:[9,16,17,18,36,37,38,39,40,91],translation:{0:{pattern:/\d/},9:{pattern:/\d/,optional:!0},"#":{pattern:/\d/,recursive:!0},A:{pattern:/[a-zA-Z0-9]/},S:{pattern:/[a-zA-Z]/}}};a.jMaskGlobals=a.jMaskGlobals||{},f=a.jMaskGlobals=a.extend(!0,{},f,a.jMaskGlobals),f.dataMask&&a.applyDataMask(),setInterval(function(){a.jMaskGlobals.watchDataMask&&a.applyDataMask()},f.watchInterval)});
