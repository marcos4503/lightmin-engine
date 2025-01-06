<?php /* This JavaScript file contains commonly used methods and variables that are useful for many different JavaScript files and for the Website. */ ?>

<script>

class Windows{

    //Cache variables
    static existantWindowsInClientAndScreens = [];  //<- This references Window elements and contains information related to each Window in the Client and in Screens.
    static loadedPagesScriptSeparator = null;
    static loadingIndicatorBackground = null;
    static loadingIndicatorForeground = null;
    static loadingIndicatorTimer = null;

    //Public methods

    static isWindowExistent(windowIdentifier){
        //Store the result
        var exists = false;

        //Check if the window exists...
        if(Windows.existantWindowsInClientAndScreens[windowIdentifier] != null)
            exists = true;

        //Return the result
        return exists;
    }

    static isPageLoadedInSomeWindow(pageUri){
        //Prepare the result
        var isAlreadyLoaded = false;

        //Check in each window, if the page is loaded...
        for (var window in Windows.existantWindowsInClientAndScreens)
            if(Windows.existantWindowsInClientAndScreens[window].currentLoadedPageUri == pageUri)
                isAlreadyLoaded = true;

        //Return the result
        return isAlreadyLoaded;
    }

    static GetMainWindowIdentifier(){
        //Prepare the value
        var mainWindowIdentifier = "";
        
        //Search by the identifier of main window
        for (var window in Windows.existantWindowsInClientAndScreens)
            if(Windows.existantWindowsInClientAndScreens[window].windowType == "main")
                mainWindowIdentifier = window;

        //Return the value
        return mainWindowIdentifier;
    }

    static GetWindowIdentifierWhereThePageIsLoaded(pageUri){
        //Prepare the result
        var windowIdentifier = "";

        //Search by the page URI in all windows..
        for (var window in Windows.existantWindowsInClientAndScreens)
            if(Windows.existantWindowsInClientAndScreens[window].currentLoadedPageUri == pageUri)
                windowIdentifier = window;

        //Return the result
        return windowIdentifier;
    }

    static GetPageUriLoadedInMainWindow(){
        //Prepare to return the response
        var response = "";

        //Search by the identifier of main window, and get the loaded page URI
        for (var window in Windows.existantWindowsInClientAndScreens)
            if(Windows.existantWindowsInClientAndScreens[window].windowType == "main")
                response = Windows.existantWindowsInClientAndScreens[window].currentLoadedPageUri;


        //Return the response
        return response;
    }

    static LoadPage(windowIdentifier, pageUri, onDoneCallback){
        //If the page URI is empty, cancel
        if(pageUri == ""){
            Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The URI provided is empty."));
            return false;
        }
        //If the page extension is different from PHP, cancel
        if(pageUri.split("/").pop().split(".").pop().toLowerCase() != "php"){
            Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The URI entered must refer to a Page with a PHP extension."));
            return false;
        }
        //If the page URI starts with ".", "\" or "/", cancel
        if(pageUri.charAt(0) == "." || pageUri.charAt(0) == "/" || pageUri.charAt(0) == "\\"){
            Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The URI of the Page to be loaded cannot start with \".\", \"/\" or \"\\\". The path to the Page " +
                                 "takes into account that the \"pages\" directory is the root."));
            return false;
        }
        //If the requested Window don't exists, cancel
        if(Windows.isWindowExistent(windowIdentifier) == false){
            Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The Window \"" + windowIdentifier + "\" does not exist."));
            return false;
        }
        //If the requested page is already loaded in some window, cancel
        if(Windows.isPageLoadedInSomeWindow(pageUri) == true){
            Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. This Page is already loaded in a Window. A Page can only appear in one Window at a time."));
            return false;
        }





        //Prepare the function that reset the Window to not loading any page state
        var ResetWindowToNotLoadingPageState = function(wId){
            //Reset the timer of loading fade-in
            if(Windows.existantWindowsInClientAndScreens[wId].loadingFadeInTimer != null)
                window.clearTimeout(Windows.existantWindowsInClientAndScreens[wId].loadingFadeInTimer);
            Windows.existantWindowsInClientAndScreens[wId].loadingFadeInTimer = null;
            //Reset the timer of pre http request
            if(Windows.existantWindowsInClientAndScreens[wId].loadingHttpRequestTimer != null)
                window.clearTimeout(Windows.existantWindowsInClientAndScreens[wId].loadingHttpRequestTimer);
            Windows.existantWindowsInClientAndScreens[wId].loadingHttpRequestTimer = null;
            //Abort and reset a http request, if have
            if(Windows.existantWindowsInClientAndScreens[wId].httpRequestObj != null)
                if(Windows.existantWindowsInClientAndScreens[wId].httpRequestObj.isRequestInProgress() == true)
                    Windows.existantWindowsInClientAndScreens[wId].httpRequestObj.StopRequest();
            Windows.existantWindowsInClientAndScreens[wId].httpRequestObj = null;
            //Reset the timer of content fade-in
            if(Windows.existantWindowsInClientAndScreens[wId].contentFadeInTimer != null)
                window.clearTimeout(Windows.existantWindowsInClientAndScreens[wId].contentFadeInTimer);
            Windows.existantWindowsInClientAndScreens[wId].contentFadeInTimer = null;
            //Reset the timer of transition finish
            if(Windows.existantWindowsInClientAndScreens[wId].finishTransitionTimer != null)
                window.clearTimeout(Windows.existantWindowsInClientAndScreens[wId].finishTransitionTimer);
            Windows.existantWindowsInClientAndScreens[wId].finishTransitionTimer = null;
            //Inform that is not loading anymore
            Windows.existantWindowsInClientAndScreens[wId].isLoadingSomePage = false;

            //If this is a main window, inform progress in loading indicator
            if(Windows.existantWindowsInClientAndScreens[wId].windowType == "main")
                Windows.SetLoadingIndicatorProgress(100.0);
        }
        //Prepare the function to show the new content and do a Fade-in in the content
        var ShowContentAndDoFadeInAnimation = function(wId, newHtmlContent, newJsContent, isErrorContent){
            //Do a Fade-in in the new current content
            Windows.existantWindowsInClientAndScreens[wId].contentFadeInTimer = setTimeout(() => {
                //Do the fade-in in the new current content, and set window to default height config
                Windows.existantWindowsInClientAndScreens[wId].windowElementRef.style.minHeight = "256px";
                Windows.existantWindowsInClientAndScreens[wId].windowElementRef.style.minWidth = "";
                Windows.existantWindowsInClientAndScreens[wId].windowElementRef.innerHTML = newHtmlContent;
                Windows.existantWindowsInClientAndScreens[wId].windowElementRef.style.transition = "all 150ms";
                Windows.existantWindowsInClientAndScreens[wId].windowElementRef.style.opacity = "1.0";

                //Post-process the window content to instante all Pieces, if have...
                Pieces.ProcessPageHtmlCodeAndInstantiateAllPieces(wId, Windows.existantWindowsInClientAndScreens[wId].windowElementRef);

                //If is not an error, add the JavaScript of the Page to the Client...
                if(isErrorContent == false){
                    //Add the script node to the page
                    var newScriptTag = document.createElement("script");
                    newScriptTag.type = "text/javascript"
                    newScriptTag.setAttribute("window", wId);
                    var generatedCallbackRenamed = ("LE_OnPageLoad_" + Date.now());
                    newScriptTag.innerHTML = (newJsContent.replaceAll("LE_OnPageLoad", generatedCallbackRenamed));
                    Windows.loadedPagesScriptSeparator.parentNode.insertBefore(newScriptTag, Windows.loadedPagesScriptSeparator.nextSibling);

                    //Add the script node added to DOM, in cache
                    Windows.existantWindowsInClientAndScreens[wId].currentLoadedPageJsRef = newScriptTag;

                    //Try to call the Page callback
                    window.setTimeout(() => { try{ eval((generatedCallbackRenamed + "();")); } catch(e){  }; }, 50);
                }
                //If is an error content, add the onclick listener to the button...
                if(isErrorContent == true)
                    Windows.existantWindowsInClientAndScreens[wId].windowElementRef.querySelector((".le_window_" + wId + "_errorButton")).addEventListener("click", function(e){ Windows.LoadPage(wId, pageUri, onDoneCallback); });
                //If is an error content, inform to the window, that this is a error page...
                if(isErrorContent == true)
                    Windows.existantWindowsInClientAndScreens[wId].currentLoadedPageUri = ("errorPage-" + wId);

                //If this is a main window, inform progress in loading indicator
                if(Windows.existantWindowsInClientAndScreens[wId].windowType == "main")
                    Windows.SetLoadingIndicatorProgress(65.0);
            }, 150);

            //Finish the window to ready state, how the windows was before the load call
            Windows.existantWindowsInClientAndScreens[wId].finishTransitionTimer = setTimeout(() => {
                Windows.existantWindowsInClientAndScreens[wId].windowElementRef.style.transition = "";
                Windows.existantWindowsInClientAndScreens[wId].windowElementRef.style.opacity = "";

                //If this is a main window, inform progress in loading indicator
                if(Windows.existantWindowsInClientAndScreens[wId].windowType == "main")
                    Windows.SetLoadingIndicatorProgress(80.0);

                //Reset this window to state of not loading page state
                ResetWindowToNotLoadingPageState(wId);
            }, 300);
        }

        //If this window is already loading some page, cancel and reset this window, before start loading a page
        if(Windows.existantWindowsInClientAndScreens[windowIdentifier].isLoadingSomePage == true)
            ResetWindowToNotLoadingPageState(windowIdentifier);

        //Inform the current loaded page in this window
        Windows.existantWindowsInClientAndScreens[windowIdentifier].currentLoadedPageUri = pageUri;
        //Inform the current loaded page in the Browser URL, and add it to the Browser history, if is a main window
        if(Windows.existantWindowsInClientAndScreens[windowIdentifier].windowType == "main")
        {
            var queryParams = new URLSearchParams(window.location.search);
            queryParams.set("p", pageUri);
            history.pushState(null, null, "?" + queryParams.toString());
        }
        //Inform that is loading a page
        Windows.existantWindowsInClientAndScreens[windowIdentifier].isLoadingSomePage = true;

        //Remove old javascript node, if have
        if(Windows.existantWindowsInClientAndScreens[windowIdentifier].currentLoadedPageJsRef != null){
            Windows.existantWindowsInClientAndScreens[windowIdentifier].currentLoadedPageJsRef.remove();
            Windows.existantWindowsInClientAndScreens[windowIdentifier].currentLoadedPageJsRef = null;
        }
        //Run all possibles "OnDestroy" methods of all instantiated Pieces, inside this Window
        for (var piid in Windows.existantWindowsInClientAndScreens[windowIdentifier].instantiatedPiecesIdsAndRefs){
            var instantiatedPiece = Windows.existantWindowsInClientAndScreens[windowIdentifier].instantiatedPiecesIdsAndRefs[piid];
            if (instantiatedPiece !== null && instantiatedPiece !== undefined)
                try{ eval("Piece_" + instantiatedPiece.getAttribute("piece") + ".OnDestroy(\"" + windowIdentifier + "\", \"" + piid + "\");"); } catch(e){  };
        }
        //Reset the list of instantiated Pieces, inside this window
        Windows.existantWindowsInClientAndScreens[windowIdentifier].instantiatedPiecesIdsAndRefs = [];

        //If this is a main window, inform progress in loading indicator and scroll the Client to top, to see the new page
        if(Windows.existantWindowsInClientAndScreens[windowIdentifier].windowType == "main"){
            Windows.SetLoadingIndicatorProgress(10.0);
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        //Force to mantain the current height and width
        const windowRect = Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.getBoundingClientRect();
        Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.style.minHeight = (windowRect.height + "px");
        Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.style.minWidth = (windowRect.width + "px");
        //Do a Fade-out in the current content
        Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.style.transition = "all 150ms";
        Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.style.opacity = "0.0";
        //Do a Fade-in in the loading content
        Windows.existantWindowsInClientAndScreens[windowIdentifier].loadingFadeInTimer = setTimeout(() => {
            Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.innerHTML = Windows.GetLoadingPageHtmlCode();
            Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.style.transition = "all 150ms";
            Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.style.opacity = "1.0";

            //If this is a main window, inform progress in loading indicator
            if(Windows.existantWindowsInClientAndScreens[windowIdentifier].windowType == "main")
                Windows.SetLoadingIndicatorProgress(35.0);
        }, 150);

        //Wait animation (fade-out of content and fade-in of the loading) finish, before start a http request...
        Windows.existantWindowsInClientAndScreens[windowIdentifier].loadingHttpRequestTimer = setTimeout(() => {
            //Start the request
            Windows.existantWindowsInClientAndScreens[windowIdentifier].httpRequestObj = new HttpRequest("GET", ("pages/" + pageUri));
            Windows.existantWindowsInClientAndScreens[windowIdentifier].httpRequestObj.SetOnDoneCallback(function () {
                //On done the request, do the Fade-out in the loading content
                Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.style.transition = "all 150ms";
                Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.style.opacity = "0.0";
            });
            Windows.existantWindowsInClientAndScreens[windowIdentifier].httpRequestObj.SetOnErrorCallback(function () {
                //Render the error content inside the Window
                ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                //Send a Callback of error on load the Page (if have registered)
                if(onDoneCallback != null){ onDoneCallback(false); }
            });
            Windows.existantWindowsInClientAndScreens[windowIdentifier].httpRequestObj.SetOnSuccessCallback(function (textResponse, jsonResponse) {
                //Get the "<html>" root tag from the Page file...
                var parsedXmlRootNode = (new DOMParser()).parseFromString(textResponse, "text/xml").documentElement;

                //Check if have XML syntax errors in the file
                if(parsedXmlRootNode.getElementsByTagName("parsererror").length > 0){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The page contains XML syntax errors. Try to check if ALL opened tags are closed!"));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                //Check if the root tag name is correct
                if(parsedXmlRootNode.tagName.toLowerCase() != "html"){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The root tag \"html\" cannot be found."));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                //Check if the "head" tag exists
                if(parsedXmlRootNode.getElementsByTagName("head").length == 0){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The \"head\" tag cannot be found."));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                //Check if the number of tags child of "head" is 4
                var headTag = parsedXmlRootNode.getElementsByTagName("head")[0];
                if(headTag.getElementsByTagName("script").length != 1 || headTag.getElementsByTagName("title").length != 1 || headTag.getElementsByTagName("meta").length != 2){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. Have more tags than expected in the header. There can only be 1 \"script\", 1 \"title\" and 2 \"meta\"."));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                //Check if the "script" head tag is correct
                var headScriptTag = headTag.getElementsByTagName("script")[0];
                if(headScriptTag.getAttribute("src") != "" && headScriptTag.getAttribute("src").includes("page-to-client-redirector.js") == false){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The header \"script\" tag cannot reference a script other than \"page-to-client-redirector.js\"."));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                //Check if "meta" head tags is correct
                var headMetaTags = headTag.getElementsByTagName("meta");
                for(var i = 0; i < headMetaTags.length; i++)
                    //Check if have defined "name" and "content" attributes
                    if(headMetaTags[i].getAttribute("name") == null || headMetaTags[i].getAttribute("content") == null){
                        Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. Header \"meta\" tags must have \"name\" and \"content\" attributes with values."));
                        ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                        if(onDoneCallback != null){ onDoneCallback(false); }
                        return;
                    }
                //Check if have "meta" tags of "description" and "image"
                var haveDescriptionMetaTag = false;
                var haveImageMetaTag = false;
                for(var i = 0; i < headMetaTags.length; i++)
                    if(headMetaTags[i].getAttribute("name") == "description")
                        haveDescriptionMetaTag = true;
                for(var i = 0; i < headMetaTags.length; i++)
                    if(headMetaTags[i].getAttribute("name") == "image")
                        haveImageMetaTag = true;
                if(haveDescriptionMetaTag == false || haveImageMetaTag == false){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. There must be \"meta\" tags of \"description\" and \"image\" in the header."));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                //Check if have tags of "style" or "link"
                if(headTag.getElementsByTagName("style").length > 0 || headTag.getElementsByTagName("link").length > 0){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. There cannot be \"style\" or \"link\" tags in the header."));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                //Check if the "script" tag exists
                var rootChildNodes = parsedXmlRootNode.childNodes;
                var foundScripTag = false;
                var scriptTag = null;
                for(var i = 0; i < rootChildNodes.length; i++)
                    if(rootChildNodes[i].tagName !== undefined && rootChildNodes[i].tagName.toLowerCase() == "script"){
                        scriptTag = rootChildNodes[i];
                        foundScripTag = true;
                    }
                if(foundScripTag == false){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. There must be a \"script\" tag that is a child of the \"html\" tag, but not the \"head\" or \"body\" tags."));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                //Check if the "body" tag exists
                if(parsedXmlRootNode.getElementsByTagName("body").length == 0){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The \"body\" tag was not found."));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                //Check if exists "style" or "script" tags inside body
                var bodyTag = parsedXmlRootNode.getElementsByTagName("body")[0];
                if(bodyTag.getElementsByTagName("style").length > 0 || bodyTag.getElementsByTagName("script").length > 0){
                    Common.SendLog("E", ("There was a problem loading the Page \"" + pageUri + "\" in \"" + windowIdentifier + "\" Window. The \"body\" tag cannot contain \"style\" or \"script\" tags."));
                    ShowContentAndDoFadeInAnimation(windowIdentifier, Windows.GetErrorOnLoadHtmlCode(windowIdentifier), "", true);
                    if(onDoneCallback != null){ onDoneCallback(false); }
                    return;
                }
                


                //Change Client metadata for the current Page, if this is a Main Window
                if(Windows.existantWindowsInClientAndScreens[windowIdentifier].windowType == "main"){
                    Utils.ChangeClientMetadataTitle(headTag.getElementsByTagName("title")[0].innerHTML);
                    var description = "";
                    var image = "";
                    var metadataTags = headTag.getElementsByTagName("meta");
                    for(var i = 0; i < metadataTags.length; i++){
                        if(metadataTags[i].getAttribute("name") == "description")
                            description = metadataTags[i].getAttribute("content");
                        if(metadataTags[i].getAttribute("name") == "image")
                            image = metadataTags[i].getAttribute("content");
                    }
                    Utils.ChangeClientMetadataDescription(description);
                    Utils.ChangeClientMetadataUrl(window.location.href);
                    Utils.ChangeClientMetadataImage(image);
                }

                //Render the Page content inside the Window and compile the JavaScript
                ShowContentAndDoFadeInAnimation(windowIdentifier, bodyTag.innerHTML, Initializator.GetStringWithGtAndLtConverted(scriptTag.innerHTML), false);
                //Send a Callback of success on load the Page (if have registered)
                if(onDoneCallback != null){ onDoneCallback(true); }
            });
            Windows.existantWindowsInClientAndScreens[windowIdentifier].httpRequestObj.SetRequestCustomDelay(150);
            Windows.existantWindowsInClientAndScreens[windowIdentifier].httpRequestObj.StartRequest();

            //If this is a main window, inform progress in loading indicator
            if(Windows.existantWindowsInClientAndScreens[windowIdentifier].windowType == "main")
                Windows.SetLoadingIndicatorProgress(50.0);
        }, 300);

        //Send Callback informing that a Page is being loaded in this Window...
        try{ eval(("LE_OnLoadPageInSomeWindow(\"" + windowIdentifier + "\", \"" + pageUri + "\");")); } catch(e){  };

        //Inform that the call for this method was runned successfully
        return true;
    }

    //Auxiliar methods

    static PrepareWindowsCacheAndInternalFunctions(){
        //Start listening "go forward" and "go backward" click event
        window.addEventListener("popstate", () => {
            //Get the current "p" parameter of URL
            var url = new URL(window.location.href);
            var pParameter = url.searchParams.get("p");

            //If don't have "p" parameter, cancel
            if(pParameter == null)
                return;

            //If the page of the URI in the Browser URL, is not loaded in the main window, load it
            if(Windows.GetPageUriLoadedInMainWindow() != pParameter)
                Windows.LoadPage(Windows.GetMainWindowIdentifier(), pParameter, null);
        });

        //If don't have a cache for the "loadedPagesScriptSeparator", create it
        if(Windows.loadedPagesScriptSeparator == null)
            Windows.loadedPagesScriptSeparator = document.getElementById("le.loadedPagesJs.separator");
    }

    static GetNoPageLoadedHtmlCode(){
        //Return a HTML of no page loaded in the Window yet...
        return "<div style=\"height: 64px;\"></div><div style=\"text-align: center; opacity: 0.35; font-style: italic;\">" + Settings.Get("noPageLoadedInWindowMessage") + "</div>";
    }

    static GetLoadingPageHtmlCode(){
        //Prepare the loading html code
        var loadingHtmlCode = "";

        //Build the base...
        loadingHtmlCode += "<div style=\"height: 64px;\"></div><div style=\"display: flex; justify-content: center; align-items: center;\">";

        //If is desired to show the loading gif
        if(Settings.Get("showWindowLoadingGif") == true){
            loadingHtmlCode += "<div style=\"display: inline-block; ";
            loadingHtmlCode += "width: " + (Math.floor(Settings.Get("windowLoadingMessageFontSize") * 1.5)) + "px; ";
            loadingHtmlCode += "height: " + (Math.floor(Settings.Get("windowLoadingMessageFontSize") * 1.5)) + "px; ";
            loadingHtmlCode += "background-image: url(" + Settings.Get("windowLoadingGifUri") + "); ";
            loadingHtmlCode += "background-size: " + (Math.floor(Settings.Get("windowLoadingMessageFontSize") * 1.5)) + "px " + (Math.floor(Settings.Get("windowLoadingMessageFontSize") * 1.5)) + "px; ";
            loadingHtmlCode += "background-position: center; ";
            loadingHtmlCode += "margin-right: " + Settings.Get("windowLoadingGifSpacePx") + "px;";
            loadingHtmlCode += "\"></div>";
        }

        //Add the loading message
        loadingHtmlCode += "<div style=\"display: inline-block; width: auto; "
        loadingHtmlCode += "height: " + (Math.floor(Settings.Get("windowLoadingMessageFontSize") * 1.5)) + "px; "
        loadingHtmlCode += "font-size: " + Settings.Get("windowLoadingMessageFontSize") + "; "
        loadingHtmlCode += "font-weight: " + Settings.Get("windowLoadingMessageFontWeight") + ";"
        loadingHtmlCode += "\"><div style=\"display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;\">" + Settings.Get("windowLoadingMessage"); + "</div></div>";

        //Finish the code...
        loadingHtmlCode += "</div>";

        //Return the loading html code
        return loadingHtmlCode;
    }

    static GetErrorOnLoadHtmlCode(windowIdentifier){
        //Prepare the error html code
        var errorHtmlCode = "";

        //Build the base...
        errorHtmlCode += "<div style=\"height: 32px;\"></div>";

        //Construct the error code
        errorHtmlCode += "<style>"
        errorHtmlCode += ".le_windows_" + windowIdentifier + "_errorOnLoadPage_tryButton{"
        errorHtmlCode += "display: flex;\n"
        errorHtmlCode += "padding: 8px;\n"
        errorHtmlCode += "box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.0);\n"
        errorHtmlCode += "color: " + Settings.Get("windowErrorButtonTextColorHex") + ";\n"
        errorHtmlCode += "border-radius: 8px;\n"
        errorHtmlCode += "text-transform: uppercase;\n"
        errorHtmlCode += "align-items: center;\n"
        errorHtmlCode += "justify-content: center;\n"
        errorHtmlCode += "font-weight: bolder;\n"
        errorHtmlCode += "margin-right: auto;\n"
        errorHtmlCode += "margin-left: auto;\n"
        errorHtmlCode += "cursor: pointer;\n"
        errorHtmlCode += "background-color: " + Settings.Get("windowErrorButtonColorHex") + ";\n"
        errorHtmlCode += "transition: all 250ms;\n"
        errorHtmlCode += "}\n"
        errorHtmlCode += ".le_windows_" + windowIdentifier + "_errorOnLoadPage_tryButton:hover{"
        errorHtmlCode += "background-color: " + Settings.Get("windowErrorButtonHoverColorHex") + ";\n"
        errorHtmlCode += "box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.75);\n"
        errorHtmlCode += "}\n"
        errorHtmlCode += "</style>"
        errorHtmlCode += "<div style=\"display: flex; align-items: center; justify-content: center; margin-bottom: 16px;\">";
        errorHtmlCode += "<div style=\"width: 80px; height: 80px; opacity: 0.35; pointer-events: none;\"><img src=\"" + Settings.Get("windowErrorImageUri") + "\" style=\"width: 100%; height: 100%;\" /></div>";
        errorHtmlCode += "</div>";
        errorHtmlCode += "<div style=\"display: flex; align-items: center; justify-content: center; text-align: center; margin-bottom: 16px;\">";
        errorHtmlCode += Settings.Get("windowErrorMessage");
        errorHtmlCode += "</div>";
        errorHtmlCode += "<div style=\"display: flex; align-items: center; justify-content: center;\">";
        errorHtmlCode += "<div class=\"le_window_" + windowIdentifier + "_errorButton le_windows_" + windowIdentifier + "_errorOnLoadPage_tryButton\">" + Settings.Get("windowErrorButtonMessage") + "</div>";
        errorHtmlCode += "</div>";

        //Return the error html code
        return errorHtmlCode;
    }

    static SetLoadingIndicatorProgress(percentProgress){
        //If don't have reference for the background of indicator, get
        if(Windows.loadingIndicatorBackground == null)
            Windows.loadingIndicatorBackground = document.getElementById("le.loadingIndicator.bg");
        if(Windows.loadingIndicatorForeground == null)
            Windows.loadingIndicatorForeground = document.getElementById("le.loadingIndicator.fg");

        //If progress is full
        if(percentProgress == 0.0 || percentProgress == 100.0){
            //Show the progress
            Windows.loadingIndicatorForeground.style.width = (percentProgress + "%");

            //If already exists a timer, force reset it
            if(Windows.loadingIndicatorTimer != null)
               window.clearTimeout(Windows.loadingIndicatorTimer);
            Windows.loadingIndicatorTimer = null;

            //If the timer is free
            if(Windows.loadingIndicatorTimer == null){
                Windows.loadingIndicatorTimer = window.setTimeout(() => {
                    Windows.loadingIndicatorBackground.style.opacity = "0.0";
                    Windows.loadingIndicatorForeground.style.transition = "all 0ms";
                    Windows.loadingIndicatorForeground.style.width = "0%";

                    //Reset the timer...
                    Windows.loadingIndicatorTimer = null;
                }, 500);
            }
        }

        //If progress is more than zero
        if(percentProgress > 0.0 && percentProgress < 100.0){
            //If have a timer to hide the indicator, remove it
            if(Windows.loadingIndicatorTimer != null)
               window.clearTimeout(Windows.loadingIndicatorTimer);
            Windows.loadingIndicatorTimer = null;

            //Show the progress
            Windows.loadingIndicatorBackground.style.opacity = "1.0";
            Windows.loadingIndicatorForeground.style.transition = "all 500ms";
            Windows.loadingIndicatorForeground.style.width = (percentProgress + "%");
        }
    }
}

</script>