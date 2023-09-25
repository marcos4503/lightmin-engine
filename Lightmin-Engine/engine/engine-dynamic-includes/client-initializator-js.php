<?php /* This file contains the base JS of the Lightmin Engine, responsible for initial loading and initializing the Engine and Website. */ ?>

<script>

//Install the onload callback
window.onload = (event) => { Initializator.StartWebsiteFirstLoad(); };

class Initializator {

    //Cache variables
    static currentStepOfInit = 0;  //0=not started, 1=google/aws tags removed, 2=settings loaded, 3=settings applied, 4=manifest loaded, 5=css libs loaded, 6=js libs loaded, 7=pieces loaded, 8=screens loaded,  9=client checked, 10=finished!
    static loadingBoxesTransitionTimeOut = null;
    static cssLibsList = [];
    static cssLibsLoadedAtHere = 0;
    static jsLibsList = [];
    static jsLibsLoadedAtHere = 0;
    static piecesList = [];
    static piecesLoadedAtHere = 0;
    static screensList = [];
    static screensLoadedAtHere = 0;
    static loadingProgressCurrent = 0;
    static loadingProgressMax = 0;
    
    //Cache for loaded items
    static lastStyleNodeAdded = null;
    static lastScriptNodeAdded = null;
    static lastPieceNodeAdded = null;
    
    //Loading screen components
    static loadingScreenFront = null;
    static loadingScreenLoadBox = null;
    static loadingScreenLoadBoxLogo = null;
    static loadingScreenLoadBoxLogoImg = null;
    static loadingScreenLoadBoxText = null;
    static loadingScreenLoadBoxBarBg = null;
    static loadingScreenLoadBoxBarFg = null;
    static loadingScreenErrorBox = null;
    static loadingScreenErrorBoxText = null;
    static loadingScreenErrorBoxButton = null;
    static loadingScreenBack = null;
    static loadingScreenCredits = null;
    static loadedCssLibsSeparator = null;
    static loadedJsLibsSeparator = null;
    static loadedPiecesSeparator = null;
    static screensAreaSandbox = null;
    static bodyAllWebsiteContent = null;
    static clientErrorWarningPopUp = null;

    //Core methods

    static StartWebsiteFirstLoad(){
        //Get loading screen components...
        Initializator.GetReferencesForAllLoadingScreenComponents();

        //Wait a time before start the load proccess
        window.setTimeout(() => {
            //Enable the loading screen
            Initializator.loadingScreenLoadBox.style.display = "block";
            window.setTimeout(() => {
                Initializator.loadingScreenLoadBox.style.opacity = "1.0";
            }, 50);

            //Show the progress bar
            window.setTimeout(() => {
                Initializator.loadingScreenLoadBoxBarBg.style.width = "100%";
            }, 500);

            //Finally, start the load process
            window.setTimeout(() => {
                Initializator.RemoveAwsAndGoogleAutomaticScripts();
            }, 500);
        }, 25);        
    }

    static RemoveAwsAndGoogleAutomaticScripts(){
        //Search by tags
        var headElement = document.head;
        var scriptTags = headElement.getElementsByTagName("script");

        //Remove any tag that matches with AWS or Google automatic scripts
        for(var i = 0; i < scriptTags.length; i++)
            if(scriptTags[i].getAttribute("src") != null){
                //Get TYPE and SRC of current tag...
                var tagSrcAtt = scriptTags[i].getAttribute("src");

                //If have script of Google or AWS, remove it
                if(tagSrcAtt.includes("google-analytics.com") == true || tagSrcAtt.includes("s3.scriptcdn.net") == true || tagSrcAtt.includes("pagespeed-mod.com") == true || tagSrcAtt.includes("googletagmanager.com") == true)
                    scriptTags[i].remove();
            }

        //Increase current step of init and go to next step
        Initializator.currentStepOfInit = 1;
        Initializator.LoadWebsiteSettings();
    }

    static LoadWebsiteSettings(){
        //Start the request for the settings.php
        var httpRequest = new HttpRequest("GET", "settings.php");
        httpRequest.SetOnErrorCallback(function () {
            //Change to error box
            Initializator.ChangeToErrorBox();
            //Set function to retry button
            Initializator.loadingScreenErrorBoxButton.addEventListener("click", function(event){
                //Retry this step...
                Initializator.LoadWebsiteSettings();

                //Change to loading box
                Initializator.ChangeToLoadingBox();

                //Reset the button
                Utils.RemoveAllListeners(Initializator.loadingScreenErrorBoxButton);
                Initializator.loadingScreenErrorBoxButton = document.getElementById("le.loadingScreen.errorBox.button");
            });
        });
        httpRequest.SetOnSuccessCallback(function (textResponse, jsonResponse) {
            //Get the parsed JSON response if the ResponseHeader is the expected and run OnError callback automatically, if is not the expected ResponseHeader...
            var jsonReply = httpRequest.GetParsedJsonIfApiUsingBackendResponseBuilderHaveReturnedExpectedResponseHeaderAndRunOnErrorCallbackIfNotReturned(textResponse, "success");

            //If have a JSON, continue...
            if(jsonReply != null){
                //Get and store the settings
                Settings.loadedWebsiteSettings = jsonReply;

                //Increase current step of init and go to next step
                Initializator.currentStepOfInit = 2;
                Initializator.ApplyAllSettings();
            }
        });
        httpRequest.StartRequest();
    }

    static ApplyAllSettings(){
        //Update the progress bar
        Initializator.loadingScreenLoadBoxBarFg.style.width = "25%";

        //Apply client settings
        Utils.ChangeFavicon(Settings.Get("websiteFaviconNoNotificationsUri"));
        document.documentElement.setAttribute("lang", Settings.Get("websiteLang"));
        document.getElementById("le.websiteCharset").setAttribute("charset", Settings.Get("websiteCharSet"));
        document.body.style.fontFamily = Settings.Get("websitePrimaryFontFamily");
        document.body.style.fontSize = (Settings.Get("websiteFontSizePx") + "px");
        document.getElementById("le.websiteBrowserColor").setAttribute("content", Settings.Get("browserColor"));
        Initializator.loadingScreenLoadBoxLogoImg.src = Settings.Get("loadScreenLogoUri");
        Initializator.loadingScreenLoadBoxLogoImg.style.display = "block";
        Initializator.loadingScreenLoadBoxLogoImg.style.animation = "le_loadingScreen_loadingBox_logo_img_enable 0.15s linear normal";
        Initializator.loadingScreenLoadBoxText.innerHTML = Settings.Get("loadScreenMessage");
        Initializator.loadingScreenLoadBoxText.style.opacity = "1.0";
        Initializator.loadingScreenFront.style.background = ("radial-gradient(circle at 10%, " + Settings.Get("loadScreenBackgroundColorHexGradientStart") + ", " + Settings.Get("loadScreenBackgroundColorHexGradientEnd") + ")");
        Initializator.loadingScreenLoadBoxBarBg.style.backgroundColor = Settings.Get("loadScreenBackgroundColorHex");
        Initializator.loadingScreenLoadBoxBarFg.style.backgroundColor = Settings.Get("loadScreenForegroundColorHex");
        if(Settings.Get("showTextSelectionHighlight") == false) { document.body.style.userSelect = "none"; }
        Initializator.loadingScreenErrorBoxText.innerHTML = Settings.Get("loadScreenErrorMessage");
        Initializator.loadingScreenErrorBoxButton.innerHTML = Settings.Get("loadScreenErrorButtonMessage");

        //Increase current step of init and go to next step
        Initializator.currentStepOfInit = 3;
        Initializator.LoadManifest();
    }

    static LoadManifest(){
        //Start the request for the manifest.json
        var httpRequest = new HttpRequest("GET", "manifest.json?time=" + ((new Date).getMilliseconds()));
        httpRequest.SetOnErrorCallback(function () {
            //Change to error box
            Initializator.ChangeToErrorBox();
            //Set function to retry button
            Initializator.loadingScreenErrorBoxButton.addEventListener("click", function(event){
                //Retry this step...
                Initializator.LoadManifest();

                //Change to loading box
                Initializator.ChangeToLoadingBox();

                //Reset the button
                Utils.RemoveAllListeners(Initializator.loadingScreenErrorBoxButton);
                Initializator.loadingScreenErrorBoxButton = document.getElementById("le.loadingScreen.errorBox.button");
            });
        });
        httpRequest.SetOnSuccessCallback(function (textResponse, jsonResponse) {
            //If don't have a json object, force the run of "OnError" and stop here...
            if(jsonResponse == null){
                var tmp = httpRequest.GetParsedJsonIfApiUsingBackendResponseBuilderHaveReturnedExpectedResponseHeaderAndRunOnErrorCallbackIfNotReturned("error<br/>{}", "success");
                return;
            }

            //Store the base title and default page of the website in cache of Settings
            Settings.loadedBaseTitle = jsonResponse.baseTitle;
            Settings.loadedDefaultPage = jsonResponse.defaultPage;

            //Get URI for all css libraries
            for(var i = 0; i < jsonResponse.cssLibraries.length; i++)
                Initializator.cssLibsList.push(jsonResponse.cssLibraries[i]);

            //Get URI for all js libraries
            for(var i = 0; i < jsonResponse.javascriptLibraries.length; i++)
                Initializator.jsLibsList.push(jsonResponse.javascriptLibraries[i]);

            //Get URI for all pieces
            for(var i = 0; i < jsonResponse.websitePieces.length; i++)
                Initializator.piecesList.push(jsonResponse.websitePieces[i]);

            //Get URI for all screens
            for(var i = 0; i < jsonResponse.websiteScreens.length; i++)
                Initializator.screensList.push(jsonResponse.websiteScreens[i]);

            //Increase the progress bar value
            Initializator.loadingScreenLoadBoxBarFg.style.width = "50%";
            Initializator.loadingProgressCurrent = (Initializator.cssLibsList.length + Initializator.jsLibsList.length + Initializator.piecesList.length + Initializator.screensList.length);
            Initializator.loadingProgressMax = ((Initializator.cssLibsList.length + Initializator.jsLibsList.length + Initializator.piecesList.length + Initializator.screensList.length) * 2);
            //Increase current step of init and go to next step
            Initializator.currentStepOfInit = 4;
            Initializator.LoadCssLibraries();
        });
        httpRequest.SetRequestCustomDelay(50);
        httpRequest.StartRequest();
    }

    static LoadCssLibraries(){
        //If don't have css libraries to load, skip this step
        if(Initializator.cssLibsList.length == 0){
            Initializator.cssLibsLoadedAtHere = Initializator.cssLibsList.length;
            Initializator.currentStepOfInit = 5;
            Initializator.LoadJsLibraries();
            return;
        }

        //Made a request for every CSS to be loaded...
        var httpRequest = new HttpRequest("GET", Initializator.cssLibsList[Initializator.cssLibsLoadedAtHere]);
        httpRequest.SetOnErrorCallback(function () {
            //Change to error box
            Initializator.ChangeToErrorBox();
            //Set function to retry button
            Initializator.loadingScreenErrorBoxButton.addEventListener("click", function(event){
                //Retry this step...
                Initializator.LoadCssLibraries();

                //Change to loading box
                Initializator.ChangeToLoadingBox();

                //Reset the button
                Utils.RemoveAllListeners(Initializator.loadingScreenErrorBoxButton);
                Initializator.loadingScreenErrorBoxButton = document.getElementById("le.loadingScreen.errorBox.button");
            });
        });
        httpRequest.SetOnSuccessCallback(function (textResponse, jsonResponse) {
            //Process the CSS library...
            Initializator.ProcessLoadedCssLibrary(Initializator.cssLibsList[Initializator.cssLibsLoadedAtHere], textResponse);

            //Increase the progress bar value
            Initializator.cssLibsLoadedAtHere += 1;
            Initializator.loadingProgressCurrent += 1;
            Initializator.loadingScreenLoadBoxBarFg.style.width = (((Initializator.loadingProgressCurrent / Initializator.loadingProgressMax) * 100.0) + "%");

            //If this is the last librarie loaded, skip to next step
            if(Initializator.cssLibsLoadedAtHere == Initializator.cssLibsList.length){
                Initializator.currentStepOfInit = 5;
                Initializator.LoadJsLibraries();
            }
            //If still need to load more css libraries, then repeat this step
            if(Initializator.cssLibsLoadedAtHere < Initializator.cssLibsList.length)
                Initializator.LoadCssLibraries();
        });
        httpRequest.SetRequestCustomDelay(0);
        httpRequest.StartRequest();
    }

    static LoadJsLibraries(){
        //If don't have js libraries to load, skip this step
        if(Initializator.jsLibsList.length == 0){
            Initializator.jsLibsLoadedAtHere = Initializator.jsLibsList.length;
            Initializator.currentStepOfInit = 6;
            Initializator.LoadWebsitePieces();
            return;
        }

        //Made a request for every JS to be loaded...
        var httpRequest = new HttpRequest("GET", Initializator.jsLibsList[Initializator.jsLibsLoadedAtHere]);
        httpRequest.SetOnErrorCallback(function () {
            //Change to error box
            Initializator.ChangeToErrorBox();
            //Set function to retry button
            Initializator.loadingScreenErrorBoxButton.addEventListener("click", function(event){
                //Retry this step...
                Initializator.LoadJsLibraries();

                //Change to loading box
                Initializator.ChangeToLoadingBox();

                //Reset the button
                Utils.RemoveAllListeners(Initializator.loadingScreenErrorBoxButton);
                Initializator.loadingScreenErrorBoxButton = document.getElementById("le.loadingScreen.errorBox.button");
            });
        });
        httpRequest.SetOnSuccessCallback(function (textResponse, jsonResponse) {
            //Process the JS library...
            Initializator.ProcessLoadedJsLibrary(Initializator.jsLibsList[Initializator.jsLibsLoadedAtHere], textResponse);

            //Increase the progress bar value
            Initializator.jsLibsLoadedAtHere += 1;
            Initializator.loadingProgressCurrent += 1;
            Initializator.loadingScreenLoadBoxBarFg.style.width = (((Initializator.loadingProgressCurrent / Initializator.loadingProgressMax) * 100.0) + "%");

            //If this is the last librarie loaded, skip to next step
            if(Initializator.jsLibsLoadedAtHere == Initializator.jsLibsList.length){
                Initializator.currentStepOfInit = 6;
                Initializator.LoadWebsitePieces();
            }
            //If still need to load more js libraries, then repeat this step
            if(Initializator.jsLibsLoadedAtHere < Initializator.jsLibsList.length)
                Initializator.LoadJsLibraries();
        });
        httpRequest.SetRequestCustomDelay(0);
        httpRequest.StartRequest();
    }

    static LoadWebsitePieces(){
        //If don't have pieces to load, skip this step
        if(Initializator.piecesList.length == 0){
            Initializator.piecesLoadedAtHere = Initializator.piecesList.length;
            Initializator.currentStepOfInit = 7;
            Initializator.LoadWebsiteScreens();
            return;
        }

        //Made a request for every Piece to be loaded...
        var httpRequest = new HttpRequest("GET", Initializator.piecesList[Initializator.piecesLoadedAtHere]);
        httpRequest.SetOnErrorCallback(function () {
            //Change to error box
            Initializator.ChangeToErrorBox();
            //Set function to retry button
            Initializator.loadingScreenErrorBoxButton.addEventListener("click", function(event){
                //Retry this step...
                Initializator.LoadWebsitePieces();

                //Change to loading box
                Initializator.ChangeToLoadingBox();

                //Reset the button
                Utils.RemoveAllListeners(Initializator.loadingScreenErrorBoxButton);
                Initializator.loadingScreenErrorBoxButton = document.getElementById("le.loadingScreen.errorBox.button");
            });
        });
        httpRequest.SetOnSuccessCallback(function (textResponse, jsonResponse) {
            //Process the Piece...
            Initializator.ProcessLoadedWebsitePiece(Initializator.piecesList[Initializator.piecesLoadedAtHere], textResponse);

            //Increase the progress bar value
            Initializator.piecesLoadedAtHere += 1;
            Initializator.loadingProgressCurrent += 1;
            Initializator.loadingScreenLoadBoxBarFg.style.width = (((Initializator.loadingProgressCurrent / Initializator.loadingProgressMax) * 100.0) + "%");

            //If this is the last librarie loaded, skip to next step
            if(Initializator.piecesLoadedAtHere == Initializator.piecesList.length){
                Initializator.currentStepOfInit = 7;
                Initializator.LoadWebsiteScreens();
            }
            //If still need to load more Pieces, then repeat this step
            if(Initializator.piecesLoadedAtHere < Initializator.piecesList.length)
                Initializator.LoadWebsitePieces();
        });
        httpRequest.SetRequestCustomDelay(0);
        httpRequest.StartRequest();
    }

    static LoadWebsiteScreens(){
        //If don't have Screens to load, skip this step
        if(Initializator.screensList.length == 0){
            Initializator.screensLoadedAtHere = Initializator.screensList.length;
            Initializator.currentStepOfInit = 8;
            Initializator.CheckClientValidity();
            return;
        }

        //Made a request for every Screen to be loaded...
        var httpRequest = new HttpRequest("GET", Initializator.screensList[Initializator.screensLoadedAtHere]);
        httpRequest.SetOnErrorCallback(function () {
            //Change to error box
            Initializator.ChangeToErrorBox();
            //Set function to retry button
            Initializator.loadingScreenErrorBoxButton.addEventListener("click", function(event){
                //Retry this step...
                Initializator.LoadWebsiteScreens();

                //Change to loading box
                Initializator.ChangeToLoadingBox();

                //Reset the button
                Utils.RemoveAllListeners(Initializator.loadingScreenErrorBoxButton);
                Initializator.loadingScreenErrorBoxButton = document.getElementById("le.loadingScreen.errorBox.button");
            });
        });
        httpRequest.SetOnSuccessCallback(function (textResponse, jsonResponse) {
            //Process the Screen...
            Initializator.ProcessLoadedWebsiteScreen(Initializator.screensList[Initializator.screensLoadedAtHere], textResponse);

            //Increase the progress bar value
            Initializator.screensLoadedAtHere += 1;
            Initializator.loadingProgressCurrent += 1;
            Initializator.loadingScreenLoadBoxBarFg.style.width = (((Initializator.loadingProgressCurrent / Initializator.loadingProgressMax) * 100.0) + "%");

            //If this is the last librarie loaded, skip to next step
            if(Initializator.screensLoadedAtHere == Initializator.screensList.length){
                Initializator.currentStepOfInit = 8;
                Initializator.CheckClientValidity();
            }
            //If still need to load more Screens, then repeat this step
            if(Initializator.screensLoadedAtHere < Initializator.screensList.length)
                Initializator.LoadWebsiteScreens();
        });
        httpRequest.SetRequestCustomDelay(0);
        httpRequest.StartRequest();
    }

    static CheckClientValidity(){
        //Prepare the is valid client variable
        var isClientValid = true;

        //Check if the Client code has some "style" attribute in some tag
        if((/style\s*?=\s*?"/).test(Initializator.bodyAllWebsiteContent.innerHTML) == true){
            Common.SendLog("E", ("There was a problem loading the \"Client.php\" of website. The HTML code cannot contain \"style\" attributes in any tag. Please place your styles in a library in the \"libraries/css\" path, for further optimization."));
            isClientValid = false;
        }
        //Check if have a Script tag in the Client
        var scriptsTagFound = Initializator.bodyAllWebsiteContent.getElementsByTagName("script");
        if(scriptsTagFound != null)
            if(scriptsTagFound.length > 0){
                Common.SendLog("E", ("There was a problem loading the \"Client.php\" of website. HTML code cannot contain any \"script\" tags. Please place your scripts in a library in the \"libraries/js\" path, for further optimization."));
                isClientValid = false;
            }
        //Check if have a Style tag in the Client
        var stylesTagFound = Initializator.bodyAllWebsiteContent.getElementsByTagName("style");
        if(stylesTagFound != null)
            if(stylesTagFound.length > 0){
                Common.SendLog("E", ("There was a problem loading the \"Client.php\" of website. HTML code cannot contain any \"style\" tags. Please place your styles in a library in the \"libraries/css\" path, for further optimization."));
                isClientValid = false;
            }

        //If the client is not valid, show the undismissable warning popup
        if(isClientValid == false)
            Initializator.clientErrorWarningPopUp.style.display = "flex";

        //If the client is valid, enable the Client layout and HTML code
        if(isClientValid == true)
            Initializator.bodyAllWebsiteContent.style.display = "block";

        //Increase current step of init and go to next step
        Initializator.currentStepOfInit = 9;
        Initializator.FinishLoading();
    }

    static FinishLoading(){
        //Wait time before close the loading screen
        window.setTimeout(() => {
            //Move the loading screen to out of screen
            Initializator.loadingScreenFront.style.top = "-100%";
            window.setTimeout(() => { Initializator.loadingScreenBack.style.top = "-100%"; }, 100);
            window.setTimeout(() => { Initializator.loadingScreenBack.style.boxShadow = "0px 0px 0px 0px rgba(0,0,0,0.15)"; }, 150);
            Initializator.loadingScreenCredits.style.bottom = "-50%";
            window.setTimeout(() => { Initializator.loadingScreenFront.style.display = "none" }, 1000);
            window.setTimeout(() => { Initializator.loadingScreenBack.style.display = "none" }, 1000);
            window.setTimeout(() => { Initializator.loadingScreenCredits.style.display = "none" }, 5000);

            //Increase current step of init and go to next step
            Initializator.currentStepOfInit = 10;

            //Try to run a possible "OnLoadEngineFinished" method registered in any possible JS library...
            try{ eval("LE_OnLoadEngineFinished();"); } catch(e){  };
        }, 500);
    }

    //Auxiliar methods

    static GetReferencesForAllLoadingScreenComponents(){
        //Get reference for all loading screen components
        Initializator.loadingScreenFront = document.getElementById("le.loadingScreenFront");
        Initializator.loadingScreenLoadBox = document.getElementById("le.loadingScreen.loadBox");
        Initializator.loadingScreenLoadBoxLogo = document.getElementById("le.loadingScreen.loadBox.logo");
        Initializator.loadingScreenLoadBoxLogoImg = document.getElementById("le.loadingScreen.loadBox.logo.img");
        Initializator.loadingScreenLoadBoxText = document.getElementById("le.loadingScreen.loadBox.text");
        Initializator.loadingScreenLoadBoxBarBg = document.getElementById("le.loadingScreen.loadBox.bar.bg");
        Initializator.loadingScreenLoadBoxBarFg = document.getElementById("le.loadingScreen.loadBox.bar.fg");
        Initializator.loadingScreenErrorBox = document.getElementById("le.loadingScreen.errorBox");
        Initializator.loadingScreenErrorBoxText = document.getElementById("le.loadingScreen.errorBox.text");
        Initializator.loadingScreenErrorBoxButton = document.getElementById("le.loadingScreen.errorBox.button");
        Initializator.loadingScreenBack = document.getElementById("le.loadingScreenBack");
        Initializator.loadingScreenCredits = document.getElementById("le.loadinScreenCredits");
        Initializator.loadedCssLibsSeparator = document.getElementById("le.loadedCssLibs.separator");
        Initializator.loadedJsLibsSeparator = document.getElementById("le.loadedJsLibs.separator");
        Initializator.loadedPiecesSeparator = document.getElementById("le.loadedPieces.separator");
        Initializator.screensAreaSandbox = document.getElementById("le.screensArea.sandbox");
        Initializator.bodyAllWebsiteContent = document.getElementById("le.body.allWebsiteContent");
        Initializator.clientErrorWarningPopUp = document.getElementById("le.clientErrorWarningPopUp");
    }

    static ChangeToLoadingBox(){
        //Cancel any time out that is in progress
        if(Initializator.loadingBoxesTransitionTimeOut != null)
            window.clearTimeout(Initializator.loadingBoxesTransitionTimeOut);

        //Reset the box
        Initializator.loadingScreenErrorBox.style.display = "block";
        Initializator.loadingScreenErrorBox.style.opacity = "1.0";
        Initializator.loadingScreenLoadBox.style.display = "none";
        Initializator.loadingScreenLoadBox.style.opacity = "0.0";

        //Change to loading box
        Initializator.loadingScreenErrorBox.style.opacity = "0.0";
        Initializator.loadingBoxesTransitionTimeOut = window.setTimeout(() => {
            Initializator.loadingScreenErrorBox.style.display = "none";
            Initializator.loadingScreenLoadBox.style.display = "block";
            Initializator.loadingScreenLoadBox.style.opacity = "1.0";
        }, 250);
    }

    static ChangeToErrorBox(){
        //Cancel any time out that is in progress
        if(Initializator.loadingBoxesTransitionTimeOut != null)
            window.clearTimeout(Initializator.loadingBoxesTransitionTimeOut);

        //Reset the box
        Initializator.loadingScreenLoadBox.style.display = "block";
        Initializator.loadingScreenLoadBox.style.opacity = "1.0";
        Initializator.loadingScreenErrorBox.style.display = "none";
        Initializator.loadingScreenErrorBox.style.opacity = "0.0";

        //Change to loading box
        Initializator.loadingScreenLoadBox.style.opacity = "0.0";
        Initializator.loadingBoxesTransitionTimeOut = window.setTimeout(() => {
            Initializator.loadingScreenLoadBox.style.display = "none";
            Initializator.loadingScreenErrorBox.style.display = "block";
            Initializator.loadingScreenErrorBox.style.opacity = "1.0";
        }, 250);
    }

    static GetStringWithGtAndLtConverted(stringToConvert){
        //Return a string with "&lt;" and "&gt;" to "<" and ">"

        /*
         * This is because after converting an XML string to a Parsed XML using "(new DOMParser()).parseFromString(str, "text/xml");", all ">" and "<" characters
         * that do not create Tags are converted to "&gt;" and "&lt;" respectively...
        */

        //Return the converted string...
        return (stringToConvert.replaceAll("&lt;", "<").replaceAll("&gt;", ">"));
    }

    static ProcessLoadedCssLibrary(cssSrc, sourceCode){
        //Protects the code against execution problems...
        try
        {
            //Get the "<style>" root tag from the PHP file...
            var parsedXmlRootNode = (new DOMParser()).parseFromString(sourceCode, "text/xml").documentElement;

            //Check if the CSS library file have the PHP extension...
            if((cssSrc.split("/").pop().split(".").pop()).toLowerCase() != "php"){
                Common.SendLog("E", ("There was a problem loading the CSS in \"" + cssSrc + "\". The file extension needs to be \"php\"."));
                return;
            }
            //Check if have found a root tag
            if(parsedXmlRootNode == null){
                Common.SendLog("E", ("There was a problem loading the CSS in \"" + cssSrc + "\". The root tag of the library file containing the nested CSS code could not be found."));
                return;
            }
            //Check if the root tag is the "style" tag
            if(parsedXmlRootNode.tagName.toLowerCase() != "style"){
                Common.SendLog("E", ("There was a problem loading the CSS in \"" + cssSrc + "\". The root tag is not a \"style\" tag."));
                return;
            }
            //Check if have a possible "z-index" attribute with "922000" or higher in the style
            var zIndexMatches = parsedXmlRootNode.innerHTML.match(/;?{?\s?z\s?-\s?index\s?:\s?[0-9]*\s?;/g);
            if(zIndexMatches != null)
                for(var i = 0; i < zIndexMatches.length; i++){
                    //Get the number of the z-index only
                    var zIndexNumber = zIndexMatches[i].replaceAll(" ", "").replace("z-index", "").replace("{", "").replace(";", "").replace(":", "");
                    //Check if the z-index found is greather than "922000"
                    if(parseFloat(zIndexNumber) >= 922000.0){
                        Common.SendLog("E", ("There was a problem loading the CSS in \"" + cssSrc + "\". The CSS code cannot use a \"z-index\" attribute with a value equal or greater than \"922000\" as any value above that, is reserved for Engine use only."));
                        return;
                    }
                }

            //Create the new style tag
            var newStyleTag = document.createElement("style");
            newStyleTag.type = "text/css"
            newStyleTag.innerHTML = parsedXmlRootNode.innerHTML;

            //Add it to the header, in loading order
            if(Initializator.lastStyleNodeAdded == null){
                Initializator.loadedCssLibsSeparator.parentNode.insertBefore(newStyleTag, Initializator.loadedCssLibsSeparator.nextSibling);
                Initializator.lastStyleNodeAdded = newStyleTag;
            }
            if(Initializator.lastStyleNodeAdded != null)
                Initializator.lastStyleNodeAdded.parentNode.insertBefore(newStyleTag, Initializator.lastStyleNodeAdded.nextSibling);
        }
        catch(e){ Common.SendLog("E", ("There was a problem loading the CSS in \"" + cssSrc + "\". Please check whether the PHP library file syntax complies with Lightmin Engine standards. More details: " + e + ".")); }
    }

    static ProcessLoadedJsLibrary(jsSrc, sourceCode){
        //Protects the code against execution problems...
        try
        {
            //Get the "<script>" root tag from the PHP file...
            var parsedXmlRootNode = (new DOMParser()).parseFromString(sourceCode, "text/xml").documentElement;

            //Check if the JS library file have the PHP extension...
            if((jsSrc.split("/").pop().split(".").pop()).toLowerCase() != "php"){
                Common.SendLog("E", ("There was a problem loading the JS in \"" + jsSrc + "\". The file extension needs to be \"php\"."));
                return;
            }
            //Check if have found a root tag
            if(parsedXmlRootNode == null){
                Common.SendLog("E", ("There was a problem loading the JS in \"" + jsSrc + "\". The root tag of the library file containing the nested JS code could not be found."));
                return;
            }
            //Check if the root tag is the "script" tag
            if(parsedXmlRootNode.tagName.toLowerCase() != "script"){
                Common.SendLog("E", ("There was a problem loading the JS in \"" + jsSrc + "\". The root tag is not a \"script\" tag."));
                return;
            }

            //Create the new script tag
            var newScriptTag = document.createElement("script");
            newScriptTag.type = "text/javascript"
            newScriptTag.innerHTML = Initializator.GetStringWithGtAndLtConverted(parsedXmlRootNode.innerHTML);

            //Add it to the header, in loading order
            if(Initializator.lastScriptNodeAdded == null){
                Initializator.loadedJsLibsSeparator.parentNode.insertBefore(newScriptTag, Initializator.loadedJsLibsSeparator.nextSibling);
                Initializator.lastScriptNodeAdded = newScriptTag;
            }
            if(Initializator.lastScriptNodeAdded != null)
                Initializator.lastScriptNodeAdded.parentNode.insertBefore(newScriptTag, Initializator.lastScriptNodeAdded.nextSibling);

            //Try to run a possible "OnLoad" method of the JS library...
            try{ eval("LE_OnLoad_" + (jsSrc.split("/").pop().replace(".php", "").replace(".PHP", "")) + "();"); } catch(e){  };
        }
        catch(e){ Common.SendLog("E", ("There was a problem loading the JS in \"" + jsSrc + "\". Please check whether the PHP library file syntax complies with Lightmin Engine standards. More details: " + e + ".")); }
    }

    static ProcessLoadedWebsitePiece(pieceSrc, sourceCode){
        //Protects the code against execution problems...
        try
        {
            //Get the "<piece>" root tag from the HTM file...
            var parsedXmlRootNode = (new DOMParser()).parseFromString(sourceCode, "text/xml").documentElement;

            //Get the piece file name
            var pieceFileName = pieceSrc.split("/").pop().replace(".htm", "").replace(".HTM", "");
            var styleNode = parsedXmlRootNode.getElementsByTagName("style")[0];
            var variablesNode = parsedXmlRootNode.getElementsByTagName("variables")[0];
            var codeNode = parsedXmlRootNode.getElementsByTagName("code")[0];
            var scriptNode = parsedXmlRootNode.getElementsByTagName("script")[0];

            //Check if the Piece file have the HTM extension...
            if((pieceSrc.split("/").pop().split(".").pop()).toLowerCase() != "htm"){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". The file extension needs to be \"htm\"."));
                return;
            }
            //Check if the Piece name have only letters
            if((/^[a-zA-Z]+$/).test(pieceFileName) == false){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". The Piece file name can only contain letters."));
                return;
            }
            //Check if the root tag name is correct
            if(parsedXmlRootNode.tagName.toLowerCase() != "piece"){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". The root tag \"piece\" cannot be found."));
                return;
            }
            //Check if the engine name is correct
            if(parsedXmlRootNode.getAttribute("for") != "lightmin-engine"){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". Check that the \"piece\" tag attributes are correct."));
                return;
            }
            //Check if the Piece name matches with file name
            if(pieceFileName != parsedXmlRootNode.getAttribute("name")){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". The name \"" + parsedXmlRootNode.getAttribute("name") + "\" defined in the \"name\" attribute does not match the name of the Piece in HTM file."));
                return;
            }
            //Check if all required nodes have been found
            if(styleNode == null || variablesNode == null || codeNode == null || scriptNode == null){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". Unable to find all the required Tags in the Piece code. Tags \"style\", \"variables\", \"code\" and \"script\" are mandatory."));
                return;
            }
            //Check if CSS have ID Selector...
            if((/#.*\s*?(?:{|,)/).test(styleNode.innerHTML) == true){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". A ID Selector were found in Piece CSS code. They are prohibited in Pieces code."));
                return;
            }
            //Check if CSS have Tag Selector...
            if((/(?:\s|\n|\r|,)\w+\s*?(?:{|,)/).test(styleNode.innerHTML) == true){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". A Tag Selector were found in Piece CSS code. They are prohibited in Pieces code."));
                return;
            }
            //Check if CSS have Type Selector...
            if((/\[type=/).test(styleNode.innerHTML) == true){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". A Type Selector were found in Piece CSS code. They are prohibited in Pieces code."));
                return;
            }
            //Check if CSS have Keyframe...
            if((/@.*\s*?(?:{|,)/).test(styleNode.innerHTML) == true){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". A Keyframe were found in Piece CSS code. They are prohibited in Pieces code."));
                return;
            }
            //Check if Class Selectors are in Lighmin Engine format (.Piece_PieceFileNameWithoutExtension_TheClassName {})
            var classSelectorsMatches = styleNode.innerHTML.match(/\..*\s*?(?:{|,)/g);
            if(classSelectorsMatches != null)
                for(var i = 0; i < classSelectorsMatches.length; i++){
                    //Get each Class Selector in each match
                    var classSelectorsByMatch = classSelectorsMatches[i].split(",");
                    //If the class not matches the Lightmin Engine format, cancel
                    for(var x = 0; x < classSelectorsByMatch.length; x++)
                        if(classSelectorsByMatch[x].includes((".Piece_" + pieceFileName + "_")) == false){
                            Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". A Class Selector was found in the Piece CSS code, which is not obeying the Pieces format. Every Class Selector must have a name in the "+
                                                "format of \".Piece_PieceFileNameWithoutExtension_TheClassName\" to work. For example, a Piece with name \"Example.htm\" should have Class Selectors similar to \".Piece_Example_TheClassName\" to work."));
                            return;
                        }
                }
            //Check if CSS have a possible "z-index" attribute with "922000" or higher
            var zIndexMatches = styleNode.innerHTML.match(/;?{?\s?z\s?-\s?index\s?:\s?[0-9]*\s?;/g);
            if(zIndexMatches != null)
                for(var i = 0; i < zIndexMatches.length; i++){
                    //Get the number of the z-index only
                    var zIndexNumber = zIndexMatches[i].replaceAll(" ", "").replace("z-index", "").replace("{", "").replace(";", "").replace(":", "");
                    //Check if the z-index found is greather than "922000"
                    if(parseFloat(zIndexNumber) >= 922000.0){
                        Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". The CSS code cannot use a \"z-index\" attribute with a value equal or greater than \"922000\" as any value above that, is reserved for Engine use only."));
                        return;
                    }
                }
            //Check if the JSON of variables is valid
            var isValidJson = true;
            try { var jsonTest = JSON.parse(variablesNode.innerHTML); } catch(e){ isValidJson = false; }
            if(isValidJson == false){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". The JSON code of the variables has some syntax error."));
                return;
            }
            //Check if the HTML code has some "style" attribute in some tag
            if((/style\s*?=\s*?"/).test(codeNode.innerHTML) == true){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". The HTML code cannot contain \"style\" attributes in any tag. Place your CSS code in the appropriate location in the Piece file."));
                return;
            }
            //Check if the HTML code has some "id" attribute in some tag
            if((/id\s*?=\s*?"/).test(codeNode.innerHTML) == true){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". The HTML code cannot contain \"id\" attributes in any tag. You must use Lightmin JavaScript API to find elements inside each Piece instance."));
                return;
            }
            //Check if the HTML code have "head", "body", "style", "script" or "html" tags
            if((/<\s*?\/?\s*?(?:html|head|body|script|style)\s*?\/?\s*?>/).test(codeNode.innerHTML) == true){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". The HTML code cannot contain the \"html\", \"head\", \"body\", \"style\" or \"script\" tags."));
                return;
            }
            //Check if the JS code have more than one class
            var classesFoundInJs = scriptNode.innerHTML.match(/class\s*\w+\s*{/g);
            if(classesFoundInJs != null)
                if(classesFoundInJs.length > 1){
                    Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". All Piece JavaScript code can only be inside a single class. In this Piece there is more than one class in the JavaScript code."));
                    return;
                }
            //Check if the JS code is all inside a class
            var leftAfterRemoveJsClassCode = scriptNode.innerHTML.replace(/\s*class\s*\w+\s*{(?:.|\n)*}\s*/gi, "");
            if(leftAfterRemoveJsClassCode != ""){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". All Piece JavaScript code must be inside a main class and all Functions and Variables in that class must be static. The code \"" + leftAfterRemoveJsClassCode + "\" is outside the main class."));
                return;
            }
            //Check if the JS main class is in Lightmin Engine format (class Piece_PieceFileNameWithoutExtension {})
            if((new RegExp(("class\\s*Piece_" + pieceFileName + "\\s*{")).test(scriptNode.innerHTML)) == false){
                Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". A class name was found in the Piece JS code, which is not obeying the Pieces format. The main JS class must have a name in the "+
                                                "format of \"class Piece_PieceFileNameWithoutExtension\" to work. For example, a Piece with name \"Example.htm\" should have class name similar to \"class Piece_Example\" to work."));
                return;
            }

            //Create the Piece delimiter tag
            var pieceCommentIdentifier = document.createComment(" Lightmin Engine loaded Piece: \"" + pieceFileName + "\" ");
            if(Initializator.lastPieceNodeAdded == null)
                Initializator.loadedPiecesSeparator.parentNode.insertBefore(pieceCommentIdentifier, Initializator.loadedPiecesSeparator.nextSibling);
            if(Initializator.lastPieceNodeAdded != null)
                Initializator.lastPieceNodeAdded.parentNode.insertBefore(pieceCommentIdentifier, Initializator.lastPieceNodeAdded.nextSibling);
            Initializator.lastPieceNodeAdded = pieceCommentIdentifier;

            //Create the Piece style tag
            var pieceStyleTag = document.createElement("style");
            pieceStyleTag.type = "text/css"
            pieceStyleTag.innerHTML = styleNode.innerHTML;
            Initializator.lastPieceNodeAdded.parentNode.insertBefore(pieceStyleTag, Initializator.lastPieceNodeAdded.nextSibling);
            Initializator.lastPieceNodeAdded = pieceStyleTag;

            //Create the Piece script tag
            var pieceScriptTag = document.createElement("script");
            pieceScriptTag.type = "text/javascript"
            pieceScriptTag.innerHTML = Initializator.GetStringWithGtAndLtConverted(scriptNode.innerHTML);
            Initializator.lastPieceNodeAdded.parentNode.insertBefore(pieceScriptTag, Initializator.lastPieceNodeAdded.nextSibling);
            Initializator.lastPieceNodeAdded = pieceScriptTag;

            //Create the Piece style tag that contains the JSON and HTML of Piece
            var pieceJsonHtmlTag = document.createElement("style");
            pieceJsonHtmlTag.type = "json/html";
            pieceJsonHtmlTag.innerHTML = ("<piece>\n<variables>\n" + variablesNode.innerHTML + "\n<variables>\n<code>\n" + codeNode.innerHTML + "\n</code>\n</piece>");
            Initializator.lastPieceNodeAdded.parentNode.insertBefore(pieceJsonHtmlTag, Initializator.lastPieceNodeAdded.nextSibling);
            Initializator.lastPieceNodeAdded = pieceJsonHtmlTag;

            //Disable the JSON/HTML style tag
            pieceJsonHtmlTag.disabled = true;
            //Try to run a possible "OnLoad" method of the Piece JS code...
            try{ eval("Piece_" + pieceFileName + ".OnLoad();"); } catch(e){  };

            //Store a reference for the Json/Html tag of this loaded Piece in the cache...
            Pieces.loadedPiecesAndReferencesToJsonHtmlElementOfEach[pieceFileName] = pieceJsonHtmlTag;
        }
        catch(e){ Common.SendLog("E", ("There was a problem loading the Piece in \"" + pieceSrc + "\". Please check whether the Piece HTM file syntax complies with Lightmin Engine standards. More details: " + e + ".")); }
    }

    static ProcessLoadedWebsiteScreen(screenSrc, sourceCode){
        //Protects the code against execution problems...
        try
        {
            //Get the "<screen>" root tag from the HTM file...
            var parsedXmlRootNode = (new DOMParser()).parseFromString(sourceCode, "text/xml").documentElement;

            //Get the screen file name
            var screenFileName = screenSrc.split("/").pop().replace(".htm", "").replace(".HTM", "");

            //Check if the Screen file have the HTM extension...
            if((screenSrc.split("/").pop().split(".").pop()).toLowerCase() != "htm"){
                Common.SendLog("E", ("There was a problem loading the Screen in \"" + screenSrc + "\". The file extension needs to be \"htm\"."));
                return;
            }
            //Check if the Screen name have only letters
            if((/^[a-zA-Z]+$/).test(screenFileName) == false){
                Common.SendLog("E", ("There was a problem loading the Screen in \"" + screenSrc + "\". The Screen file name can only contain letters."));
                return;
            }
            //Check if the root tag name is correct
            if(parsedXmlRootNode.tagName.toLowerCase() != "screen"){
                Common.SendLog("E", ("There was a problem loading the Screen in \"" + screenSrc + "\". The root tag \"screen\" cannot be found."));
                return;
            }
            //Check if the engine name is correct
            if(parsedXmlRootNode.getAttribute("for") != "lightmin-engine"){
                Common.SendLog("E", ("There was a problem loading the Screen in \"" + screenSrc + "\". Check that the \"screen\" tag attributes are correct."));
                return;
            }
            //Check if the Screen name matches with file name
            if(screenFileName != parsedXmlRootNode.getAttribute("name")){
                Common.SendLog("E", ("There was a problem loading the Screen in \"" + screenSrc + "\". The name \"" + parsedXmlRootNode.getAttribute("name") + "\" defined in the \"name\" attribute does not match the name of the Screen in HTM file."));
                return;
            }
            //Check if the HTML code has some "style" attribute in some tag
            if((/style\s*?=\s*?"/).test(parsedXmlRootNode.innerHTML) == true){
                Common.SendLog("E", ("There was a problem loading the Screen in \"" + screenSrc + "\". The HTML code cannot contain \"style\" attributes in any tag."));
                return;
            }
            //Check if the HTML code have "head", "body", "style", "script" or "html" tags
            if((/<\s*?\/?\s*?(?:html|head|body|script|style)\s*?\/?\s*?>/).test(parsedXmlRootNode.innerHTML) == true){
                Common.SendLog("E", ("There was a problem loading the Screen in \"" + screenSrc + "\". The HTML code cannot contain the \"html\", \"head\", \"body\", \"style\" or \"script\" tags."));
                return;
            }

            //Create the new div tag
            var newScreenTag = document.createElement("div");
            newScreenTag.setAttribute("name", screenFileName);
            newScreenTag.setAttribute("type", "text/screen");
            newScreenTag.setAttribute("class", "le_screensArea_sandbox_loadedScreen");
            newScreenTag.innerHTML = parsedXmlRootNode.innerHTML;

            //Add the screen to screen area sandbox
            Initializator.screensAreaSandbox.appendChild(newScreenTag);
            
            //Store a reference for the Div tag of this loaded Screen in the cache...
            Screens.loadedScreensAndReferencesToDivElementOfEach[screenFileName] = newScreenTag;
        }
        catch(e){ Common.SendLog("E", ("There was a problem loading the Screen in \"" + screenSrc + "\". Please check whether the Screen HTM file syntax complies with Lightmin Engine standards. More details: " + e + ".")); }
    }
}

</script>