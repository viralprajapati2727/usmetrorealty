/**
 * ExtraWatch - A real-time ajax monitor and live stats
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) by CodeGravity.com - All rights reserved!
 * @website http://www.codegravity.com
 */

var ew_Utf8Encoder = { //added ex_ to avoid conflicts
    /** Credits: http://www.webtoolkit.info/javascript-url-decode-encode.html **/

// URL utf-8 encoding  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
    encode : function (str) {
        return escape(ew_Utf8Encoder._utf8_encode(str));
    },

// UTF-8 encoding
    _utf8_encode : function (str) {
        str = str.replace(/\r\n/g,"\n");
        var encoded_str = "";

        for (var n = 0; n < str.length; n++) {
            var c = str.charCodeAt(n);
            if (c < 128) {
                encoded_str += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                encoded_str += String.fromCharCode((c >> 6) | 192);
                encoded_str += String.fromCharCode((c & 63) | 128);
            } else {
                encoded_str += String.fromCharCode((c >> 12) | 224);
                encoded_str += String.fromCharCode(((c >> 6) & 63) | 128);
                encoded_str += String.fromCharCode((c & 63) | 128);
            }
        }
        return encoded_str;
    },

    /** prevent restricted %2F in paths */
    ew_encodeURIComponent : function (str) {
        str = str.replace("/","_SLASH_");
        str = encodeURIComponent(str);
        return str;
    }

}

var ew_Helper = {   /* helper functions which can be used anywhere*/
    replaceString: function (_str, _search, _replace) {
        var re = new RegExp(_search, 'g')
        return _str.replace(re, _replace);
    },

    getQueryVariable: function (variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
    },
    endsWith: function (str, suffix) {
		if (str == null || suffix == null) {
			return false;
		}
        return str.indexOf(suffix, str.length - suffix.length) !== -1;
    },
    startsWith : function(str, prefix) {
		if (str == null || prefix == null) {
			return false;
		}
		return str.indexOf(prefix) === 0;
    }
}


function extrawatch_createRequestObject() {
    var ro;
    if(window.ActiveXObject){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}



var ew_Heatmap = {

    extraWatch_click : function(evt, frontendTokenToPass, uri2titleId) {

        var positionFromEvent = ew_Heatmap.getPositionFromEvent(evt);
        var xpath = ew_Heatmap.createXPathFromElement(evt.target);

        var scrollx = window.pageXOffset == undefined ? clickDoc.scrollLeft : window.pageXOffset;
        var scrolly = window.pageYOffset == undefined ? clickDoc.scrollTop : window.pageYOffset;

        /* Is the click in the viewing area? Not on scrollbars. The problem still exists for FF on the horizontal scrollbar */
        var url = urlBase + "&params=" + encodeURIComponent("&action=click&uri2titleId=" + uri2titleId + "&x=" + positionFromEvent[0] + "&y=" + positionFromEvent[1] + "&w=" + w + "&h=" + h + "&xpath=" + encodeURIComponent(encodeURIComponent(xpath)));


        if(ew_Heatmap.checkIfDoSynchronousClick(evt))	  {
            downloadUrl(url, function (e) {}, true, false);	//download it synchronously
        } else {
			setTimeout(function() {
				downloadUrl(url, function (e) {}, true);
			}, 100);	//we had to add some delay so that this call is last one. If it was called first, it was causing some problems with some shopping cart ajax calls
        }


		},
    keyListener: function (evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var key = (evt) ? evt.which : event.keyCode;

        if (String.fromCharCode(key) == "p") {
            ew_Heatmap.changeDayInUrl(-1);
        } else if (String.fromCharCode(key) == "n") {
            ew_Heatmap.changeDayInUrl(1);
        } else if (String.fromCharCode(key) == "t") {
            ew_Heatmap.toggleHeatmap();
        }
    },

    changeDayInUrl: function (_increment) {
        var dayVariable = "extraWatchDay";	//todo - move to constants
        var location = document.location.href;
        var extraWatchDay = ew_Helper.getQueryVariable(dayVariable);
        var prevDay = parseInt(extraWatchDay) + _increment;
        var newLocationString = ew_Helper.replaceString(location, extraWatchDay, prevDay);
        document.location.href = newLocationString;
    },

    toggleHeatmap: function () {
        xx.toggleDisplay();
    },


    /**
     source and thanks to: http://nickthecoder.wordpress.com/2013/02/26/offsetx-and-offsety-in-firefox/
     */
    ew_getOffset: function (evt) {
        if (evt.offsetX != undefined)
            return {x: evt.offsetX, y: evt.offsetY};

        var el = evt.target;
        var offset = {x: 0, y: 0};

        while (el.offsetParent) {
            offset.x += el.offsetLeft;
            offset.y += el.offsetTop;
            el = el.offsetParent;
        }

        offset.x = evt.pageX - offset.x;
        offset.y = evt.pageY - offset.y;

        return offset;
    },

    /* attach click event listener on onclick event */
    attachExtraWatchClickListener: function (frontendTokenToPass, uri2titleId) {

		if(false) {	//bugfix: binding via jquery was taking too long, using rather native js addEventListener //registering click listener via jQuery if it's loaded
			jQuery(document).bind("click", function (event) {
                ew_Heatmap.extraWatch_click(event, frontendTokenToPass, uri2titleId);
				});
		} else {
			window.document.onclick = function (evt) {
				if (window.addEventListener) {
					window.addEventListener("onclick", ew_Heatmap.extraWatch_click(evt, frontendTokenToPass, uri2titleId), false);
				} else if (window.attachEvent) {
					window.attachEvent("onclick", ew_Heatmap.extraWatch_click(evt, frontendTokenToPass, uri2titleId));
				} else {
					window.addEventListener("onclick", ew_Heatmap.extraWatch_click(evt, frontendTokenToPass, uri2titleId), false);
				}
			}
		}
    },


    extraWatch_decorateLinksWithCustomHandler: function (extraWatchLinkElementsList, frontendTokenToPass, uri2titleId) {
		for (i = 0; i < extraWatchLinkElementsList.length; i++) {
            try {
                if (extraWatchLinkElementsList[i].onclick != null) {    /* go through all onclick methods */
                    var replaceOnClick= ["javascript:",""];
					window.ewTemp_frontendTokenToPass = frontendTokenToPass;
					window.ewTemp_uri2titleId = uri2titleId;
                    replaceOnClick.forEach(function(entry) {    /* for each search pattern */
                            var searchMask = entry + "(.*)";
                            var regEx = new RegExp(searchMask, "i");
                            if (extraWatchLinkElementsList[i].outerHTML.match(regEx) != null) {  /* if outer HTML matches pattern */
                                var replaceMask = entry + "ew_Heatmap.extraWatch_click\(event, window.ewTemp_frontendTokenToPass, window.ewTemp_uri2titleId\);$1; return false;";  /* inserting click method before original onclick method content */
								var originalOnClick = extraWatchLinkElementsList[i].getAttribute('onclick');
								var decoratedOnClick = originalOnClick.replace(regEx, replaceMask);
								extraWatchLinkElementsList[i].setAttribute('onclick', decoratedOnClick);
                                return true;
                            }
                    });
                }
            } catch (e) {
            }
        }
    },

    findPos: function (obj) {
        var curtop = 0;
        if (obj.offsetParent) {
            do {
                curtop += obj.offsetTop;
            } while (obj = obj.offsetParent);
            return [curtop];
        }
    },

    createXPathFromElement: function (elm) {
        var allNodes = document.getElementsByTagName('*');
        for (segs = []; elm && elm.nodeType == 1; elm = elm.parentNode) {
            if (elm.hasAttribute('id')) {
                var uniqueIdCount = 0;
                for (var n = 0; n < allNodes.length; n++) {
                    if (allNodes[n].hasAttribute('id') && allNodes[n].id == elm.id) uniqueIdCount++;
                    if (uniqueIdCount > 1) break;
                }
                ;
                if (uniqueIdCount == 1) {
                    //segs.unshift('id("' + elm.getAttribute('id') + '")');
                    segs.unshift('//*[@id="' + elm.getAttribute('id') + '"]');
                    return segs.join('/');
                } else {
                    segs.unshift(elm.localName.toLowerCase() + '[@id="' + elm.getAttribute('id') + '"]');
                }
            } else if (elm.hasAttribute('class')) {
                segs.unshift(elm.localName.toLowerCase() + '[@class="' + elm.getAttribute('class') + '"]');
            } else {
                for (i = 1, sib = elm.previousSibling; sib; sib = sib.previousSibling) {
                    if (sib.localName == elm.localName)  i++;
                }
                ;
                segs.unshift(elm.localName.toLowerCase() + '[' + i + ']');
            }
            ;
        }
        ;
        return segs.length ? '/' + segs.join('/') : null;
    },

    isTargetElementNavigatingAway: function (target) {

        switch (target.localName) {
            case "a": {
                /* if there's A element and contains # or javascript: in href="..." we are assuming that we are not navigating away */
                if (target.href == "" || ew_Helper.endsWith(target.href,"#") || ew_Helper.startsWith(target.href, 'javascript:')) {
                    return false;
                }
                return true;
                break;
            }
            case "input": {
				 switch (target.type) {
						 
					 case "button": {
		                return false;	//we assume that all buttons are not navigating away, most of them are on site
					 }
					
					 default : {	 
                		if (target.type == "submit") {
                    		return true;
                		} else {
                    		return false;
                		}
						break;
					 }
				}	 
						 
            }
            case "submit": {
                /* in case of submit and form buttons we'll assume this is navigating away */
                return true;
            }
            default: {
                return false;
            }
        }


    },

    checkIfElementContainsHrefWithHttp: function (targetElement, domain) {
        while(targetElement != null) {  /* go through all parent elements */
            if (targetElement.href != null && ew_Helper.startsWith(targetElement.href,"http") || ew_Helper.startsWith(targetElement.href,"https") /* && !(targetElement.href.indexOf(domain) > 0) */) {
                return true;
            }
            targetElement = targetElement.parentElement;
        };
        return false;
    },

   checkIfElementContainsHash: function (targetElement, domain) {
        while(targetElement != null) {  /* go through all parent elements */
            if (targetElement.href != null && targetElement.href.indexOf("#") != -1) {
                return true;
            }
            targetElement = targetElement.parentElement;
        };
        return false;
    },


    checkIfDoSynchronousClick: function (evt) {
		var currentDomain = null;
		if (window.location && window.location.href && window.location.href.split('/') && (window.location.href.split('/').length > 0)) {
			currentDomain = window.location.href.split('/')[2];
		}
		if (evt.target != null && evt.target.type != null && evt.target.type == "submit") {	/* shopping cart submit buttons etc. */
			return true;
		}
		if (evt.target != null && ew_Heatmap.checkIfElementContainsHash(evt.target, currentDomain)) {	//only for links which have http://
			return false;
		}

		if (evt.target != null && ew_Heatmap.checkIfElementContainsHrefWithHttp(evt.target, currentDomain)) {	//only for links which have http://
			return true;
		}
		
        return false;    /* do synchronous click if navigating away */
    },


    getPositionFromEvent : function (evt) {
        var pos = window.h337.util.mousePosition(evt); //relative position to element
        if (evt.offsetX == null || evt.offsetY == null) {
            var calculatedOffset = ew_Heatmap.ew_getOffset(evt);
            var x = calculatedOffset.x;
            var y = calculatedOffset.y;
        } else {
            var x = evt.offsetX; // offset relative within the element
            var y = evt.offsetY;
        }
        return [x,y];
    },

    highlightElementsByXPath: function(xpathElements, document){
        var xpath = null;
        var length = xpathElements.length;

        for (var i = 0; i < length; i++) {
            xpath = xpathElements[i];
            try {
                var elementFound = document.evaluate(xpath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null);
                var element = elementFound.singleNodeValue;
                if (element != null) {
                    element.style.border="2px solid " + xpathElementColors[i];
                    element.style.backgroundColor=xpathElementColors[i];
                }
            } catch (e) {
                //suppress if no element was found
            }
        }
    },


    renderHeatmap : function (randHash, data) {
		try {
			var ip = ew_Helper.getQueryVariable('ip');
			var obj = eval( data );
			xx.displayLoading
			xx.store.setDataSet(obj);
		} catch (e) {
			alert("There was problem loading ExtraWatch Heatmap. This is not fault of ExtraWatch but fact that you might have some other javascript errors on your website.\n\nError:\n" + e.message + "\nStack:\n" + e.stack);
		}
    }



}

