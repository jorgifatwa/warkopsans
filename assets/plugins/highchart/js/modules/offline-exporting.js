/*
 Highcharts JS v6.0.6 (2018-02-05)
 Client side exporting module

 (c) 2015 Torstein Honsi / Oystein Moseng

 License: www.highcharts.com/license
*/
(function(n){"object"===typeof module&&module.exports?module.exports=n:n(Highcharts)})(function(n){(function(c){function n(a,f){var d=t.getElementsByTagName("head")[0],b=t.createElement("script");b.type="text/javascript";b.src=a;b.onload=f;b.onerror=function(){c.error("Error loading script "+a)};d.appendChild(b)}var C=c.merge,e=c.win,r=e.navigator,t=e.document,w=c.each,x=e.URL||e.webkitURL||e,A=/Edge\/|Trident\/|MSIE /.test(r.userAgent),D=/Edge\/\d+/.test(r.userAgent),E=A?150:0;c.CanVGRenderer={};
c.dataURLtoBlob=function(a){if(e.atob&&e.ArrayBuffer&&e.Uint8Array&&e.Blob&&x.createObjectURL){a=a.match(/data:([^;]*)(;base64)?,([0-9A-Za-z+/]+)/);for(var c=e.atob(a[3]),d=new e.ArrayBuffer(c.length),d=new e.Uint8Array(d),b=0;b<d.length;++b)d[b]=c.charCodeAt(b);a=new e.Blob([d],{type:a[1]});return x.createObjectURL(a)}};c.downloadURL=function(a,f){var d=t.createElement("a"),b;if("string"===typeof a||a instanceof String||!r.msSaveOrOpenBlob){if(D||2E6<a.length)if(a=c.dataURLtoBlob(a),!a)throw"Data URL length limit reached";
if(void 0!==d.download)d.href=a,d.download=f,t.body.appendChild(d),d.click(),t.body.removeChild(d);else try{if(b=e.open(a,"chart"),void 0===b||null===b)throw"Failed to open window";}catch(u){e.location.href=a}}else r.msSaveOrOpenBlob(a,f)};c.svgToDataUrl=function(a){var c=-1<r.userAgent.indexOf("WebKit")&&0>r.userAgent.indexOf("Chrome");try{if(!c&&0>r.userAgent.toLowerCase().indexOf("firefox"))return x.createObjectURL(new e.Blob([a],{type:"image/svg+xml;charset-utf-16"}))}catch(d){}return"data:image/svg+xml;charset\x3dUTF-8,"+
encodeURIComponent(a)};c.imageToDataUrl=function(a,c,d,b,u,l,k,m,p){var g=new e.Image,h,f=function(){setTimeout(function(){var e=t.createElement("canvas"),f=e.getContext&&e.getContext("2d"),y;try{if(f){e.height=g.height*b;e.width=g.width*b;f.drawImage(g,0,0,e.width,e.height);try{y=e.toDataURL(c),u(y,c,d,b)}catch(F){h(a,c,d,b)}}else k(a,c,d,b)}finally{p&&p(a,c,d,b)}},E)},q=function(){m(a,c,d,b);p&&p(a,c,d,b)};h=function(){g=new e.Image;h=l;g.crossOrigin="Anonymous";g.onload=f;g.onerror=q;g.src=a};
g.onload=f;g.onerror=q;g.src=a};c.downloadSVGLocal=function(a,f,d,b){function u(b,a){a=new e.jsPDF("l","pt",[b.width.baseVal.value+2*a,b.height.baseVal.value+2*a]);w(b.querySelectorAll('*[visibility\x3d"hidden"]'),function(b){b.parentNode.removeChild(b)});e.svg2pdf(b,a,{removeInvalid:!0});return a.output("datauristring")}function l(){z.innerHTML=a;var e=z.getElementsByTagName("text"),f;w(e,function(b){w(["font-family","font-size"],function(a){for(var c=b;c&&c!==z;){if(c.style[a]){b.style[a]=c.style[a];
break}c=c.parentNode}});b.style["font-family"]=b.style["font-family"]&&b.style["font-family"].split(" ").splice(-1);f=b.getElementsByTagName("title");w(f,function(a){b.removeChild(a)})});e=u(z.firstChild,0);try{c.downloadURL(e,v),b&&b()}catch(G){d()}}var k,m,p=!0,g,h=f.libURL||c.getOptions().exporting.libURL,z=t.createElement("div"),q=f.type||"image/png",v=(f.filename||"chart")+"."+("image/svg+xml"===q?"svg":q.split("/")[1]),B=f.scale||1,h="/"!==h.slice(-1)?h+"/":h;if("image/svg+xml"===q)try{r.msSaveOrOpenBlob?
(m=new MSBlobBuilder,m.append(a),k=m.getBlob("image/svg+xml")):k=c.svgToDataUrl(a),c.downloadURL(k,v),b&&b()}catch(y){d()}else"application/pdf"===q?e.jsPDF&&e.svg2pdf?l():(p=!0,n(h+"jspdf.js",function(){n(h+"svg2pdf.js",function(){l()})})):(k=c.svgToDataUrl(a),g=function(){try{x.revokeObjectURL(k)}catch(y){}},c.imageToDataUrl(k,q,{},B,function(a){try{c.downloadURL(a,v),b&&b()}catch(F){d()}},function(){var f=t.createElement("canvas"),u=f.getContext("2d"),l=a.match(/^<svg[^>]*width\s*=\s*\"?(\d+)\"?[^>]*>/)[1]*
B,k=a.match(/^<svg[^>]*height\s*=\s*\"?(\d+)\"?[^>]*>/)[1]*B,m=function(){u.drawSvg(a,0,0,l,k);try{c.downloadURL(r.msSaveOrOpenBlob?f.msToBlob():f.toDataURL(q),v),b&&b()}catch(H){d()}finally{g()}};f.width=l;f.height=k;e.canvg?m():(p=!0,n(h+"rgbcolor.js",function(){n(h+"canvg.js",function(){m()})}))},d,d,function(){p&&g()}))};c.Chart.prototype.getSVGForLocalExport=function(a,e,d,b){var f=this,l,k=0,m,p,g,h,n,q=function(a,c,d){++k;d.imageElement.setAttributeNS("http://www.w3.org/1999/xlink","href",
a);k===l.length&&b(f.sanitizeSVG(m.innerHTML,p))};c.wrap(c.Chart.prototype,"getChartHTML",function(b){var a=b.apply(this,Array.prototype.slice.call(arguments,1));p=this.options;m=this.container.cloneNode(!0);return a});f.getSVGForExport(a,e);l=m.getElementsByTagName("image");try{if(l.length)for(h=0,n=l.length;h<n;++h)g=l[h],c.imageToDataUrl(g.getAttributeNS("http://www.w3.org/1999/xlink","href"),"image/png",{imageElement:g},a.scale,q,d,d,d);else b(f.sanitizeSVG(m.innerHTML,p))}catch(v){d()}};c.Chart.prototype.exportChartLocal=
function(a,e){var d=this,b=c.merge(d.options.exporting,a),f=function(){if(!1===b.fallbackToExportServer)if(b.error)b.error(b);else throw"Fallback to export server disabled";else d.exportChart(b)};A&&(c.SVGRenderer.prototype.inlineWhitelist=[/^blockSize/,/^border/,/^caretColor/,/^color/,/^columnRule/,/^columnRuleColor/,/^cssFloat/,/^cursor/,/^fill$/,/^fillOpacity/,/^font/,/^inlineSize/,/^length/,/^lineHeight/,/^opacity/,/^outline/,/^parentRule/,/^rx$/,/^ry$/,/^stroke/,/^textAlign/,/^textAnchor/,/^textDecoration/,
/^transform/,/^vectorEffect/,/^visibility/,/^x$/,/^y$/]);A&&("application/pdf"===b.type||d.container.getElementsByTagName("image").length&&"image/svg+xml"!==b.type)||"application/pdf"===b.type&&d.container.getElementsByTagName("image").length?f():d.getSVGForLocalExport(b,e,f,function(a){-1<a.indexOf("\x3cforeignObject")&&"image/svg+xml"!==b.type?f():c.downloadSVGLocal(a,b,f)})};C(!0,c.getOptions().exporting,{libURL:"https://code.highcharts.com/6.0.6/lib/",menuItemDefinitions:{downloadPNG:{textKey:"downloadPNG",
onclick:function(){this.exportChartLocal()}},downloadJPEG:{textKey:"downloadJPEG",onclick:function(){this.exportChartLocal({type:"image/jpeg"})}},downloadSVG:{textKey:"downloadSVG",onclick:function(){this.exportChartLocal({type:"image/svg+xml"})}},downloadPDF:{textKey:"downloadPDF",onclick:function(){this.exportChartLocal({type:"application/pdf"})}}}})})(n)});
