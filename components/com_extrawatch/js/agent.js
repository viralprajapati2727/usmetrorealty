

/* Detects if user has "Tell websites that I don't want to be tracked" browser setting switched on and skips tracking of such user */
var extraWatchDoNotTrackSettingEnabled = navigator.doNotTrack == "yes" || navigator.doNotTrack == "1" || navigator.msDoNotTrack == "1";

if (!extraWatchDoNotTrackSettingEnabled) {

    var extraWatchAjaxLinkDecoded = decodeURIComponent(extraWatchAjaxLink);
    var extraWatchLiveSiteDecoded = decodeURIComponent(extraWatchLiveSite);

    (function() {

        var ew_img = document.createElement('img');

        ew_img.src = extraWatchAjaxLinkDecoded + "img&rand="+ Math.random() +"&env=" + extraWatchEnv + "&ref="+ew_Utf8Encoder.ew_encodeURIComponent(ew_Utf8Encoder.ew_encodeURIComponent(document.referrer))+"&title="+ew_Utf8Encoder.encode(document.title)+"&uri="+ew_Utf8Encoder.ew_encodeURIComponent(window.location.pathname)+"&referringQuery="+ew_Utf8Encoder.ew_encodeURIComponent("")+"&getParams="+ew_Utf8Encoder.ew_encodeURIComponent(location.search);

    })();


    (function() {

        var ewHeatmapIncludeJS = document.createElement('script');
        ewHeatmapIncludeJS.type = 'text/javascript'; ewHeatmapIncludeJS.async = true;

        ewHeatmapIncludeJS.src = extraWatchAjaxLinkDecoded + "heatmap.include.js&env=" + extraWatchEnv + "&amp;id=0&amp;extraWatchHeatmap=&amp;extraWatchDay=&amp;params="+encodeURIComponent("getParams=" + encodeURIComponent(location.search)) + "&title=" + document.title + "&uri=" + location.pathname;

        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ewHeatmapIncludeJS, s);

    })();

	 (function() {

        var ewDownloadUrlJS = document.createElement('script');
        ewDownloadUrlJS.type = 'text/javascript'; ewDownloadUrlJS.async = true;

        ewDownloadUrlJS.src =  extraWatchLiveSiteDecoded + "components/com_extrawatch/js/jdownloadurl.js";

        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ewDownloadUrlJS, s);

    })();

    

    (function() {	//onload ajax request which deactivates user
        window.addEventListener('beforeunload', function() {
            downloadUrl(extraWatchAjaxLinkDecoded + "unloaded", function (e) {}, true, true);
        })
    })();

}