!function(e){var t={};function r(n){if(t[n])return t[n].exports;var o=t[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(n,o,function(t){return e[t]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="./../",r(r.s=5)}({5:function(e,t,r){e.exports=r(9)},9:function(e,t,r){"use strict";r.r(t);var n=()=>{const e=document.getElementById("working-overlay");document.body.removeChild(e)};var o=()=>{jQuery.ajax({type:"post",url:fp_account_settingsAdminAjax,data:{action:"all_user_transients"},success:e=>{e&&(console.log(e),n())},fail:e=>{console.error("There was an error: "+e)}})};var i=()=>{const e=document.createElement("div"),t=document.body;e.setAttribute("class","working-overlay"),e.setAttribute("id","working-overlay"),e.innerHTML='<div class="working-spinner"><div></div><div></div><div></div><div></div></div>',t.appendChild(e)};var a=()=>{const e=document.querySelector(".transient-user-all .ab-item");e&&e.addEventListener("click",()=>{i(),o()})};var u=()=>{jQuery.ajax({type:"post",url:fp_account_settingsAdminAjax,data:{action:"current_user_transients"},success:e=>{e&&(console.log(e),n())},fail:e=>{console.error("There was an error: "+e)}})};var c=()=>{const e=document.querySelector(".transient-user-current .ab-item");e&&e.addEventListener("click",()=>{i(),u()})};(()=>{a(),c()})()}});