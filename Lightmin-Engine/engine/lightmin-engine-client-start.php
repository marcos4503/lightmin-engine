<!DOCTYPE html>
<html lang="en-US">
    <head>
        <!-- Lightmin Engine for Dynamic Websites -->
        <noscript><meta http-equiv="refresh" content="0; url=engine/lightmin-engine-no-javascript.php" /></noscript>
        <!-- Lightmin Engine base CSS -->
        <?php include_once("engine/engine-dynamic-includes/client-base-css.php"); ?>
        <!-- Lightmin Engine base JS -->
        <?php include_once("engine/engine-dynamic-includes/client-common-js.php"); ?>
        <?php include_once("engine/engine-dynamic-includes/client-settings-js.php"); ?>
        <?php include_once("engine/engine-dynamic-includes/client-events-js.php"); ?>
        <?php include_once("engine/engine-dynamic-includes/client-utils-js.php"); ?>
        <?php include_once("engine/engine-dynamic-includes/client-pieces-js.php"); ?>
        <?php include_once("engine/engine-dynamic-includes/client-screens-js.php"); ?>
        <?php include_once("engine/engine-dynamic-includes/client-frontend-http-request-js.php"); ?>
        <?php include_once("engine/engine-dynamic-includes/client-frontend-input-validator-js.php"); ?>
        <!-- Lightmin Engine initializator JS -->
        <?php include_once("engine/engine-dynamic-includes/client-initializator-js.php"); ?>
        <!-- Lightmin Engine Client metadata -->
        <title>Loading...</title>
        <link rel="icon" type="image/x-icon" href="engine/medias/icons/loading-favicon.ico">
        <meta id="le.websiteBrowserColor" name="theme-color" content="#FFFFFF">
        <meta name="viewport" content="width=device-width">
        <meta id="le.websiteCharset" charset="UTF-8">
        <!-- Lightmin Engine loaded CSS libs -->
        <meta id="le.loadedCssLibs.separator">
        <!-- Lightmin Engine loaded JS libs -->
        <meta id="le.loadedJsLibs.separator">
        <!-- Lightmin Engine loaded Pieces -->
        <meta id="le.loadedPieces.separator">
        <!-- Lightmin Engine everything else -->
    </head>
    <body style="overflow-x: visible; overflow-y: scroll;">
        <!-- The Start of base HTML of Lightmin Engine -->
        <!-- ////////////////////////////////   LOADING SCREEN   //////////////////////////////// -->
        <div id="le.loadingScreenFront" class="le_loadingScreenFront">
            <div id="le.loadingScreen.loadBox" class="le_loadingScreen_loadingBox">
                <div id="le.loadingScreen.loadBox.logo" class="le_loadingScreen_loadingBox_logo">
                    <img id="le.loadingScreen.loadBox.logo.img" class="le_loadingScreen_loadingBox_logo_img" src="" />
                </div>
                <div id="le.loadingScreen.loadBox.text" class="le_loadingScreen_loadingBox_text"></div>
                <div class="le_loadingScreen_loadingBox_spacer"></div>
                <div id="le.loadingScreen.loadBox.bar.bg" class="le_loadingScreen_loadingBox_bar_bg">
                    <div id="le.loadingScreen.loadBox.bar.fg" class="le_loadingScreen_loadingBox_bar_fg"></div>
                </div>
            </div>
            <div id="le.loadingScreen.errorBox" class="le_loadingScreen_errorBox">
                <div id="le.loadingScreen.errorBox.text" class="le_loadingScreen_errorBox_text">
                    There was a problem loading the Website. Please try again!
                </div>
                <div id="le.loadingScreen.errorBox.button" class="le_loadingScreen_errorBox_button">
                    Retry
                </div>
            </div>
        </div>
        <div id="le.loadingScreenBack" class="le_loadingScreenBack"></div>
        <div id="le.loadinScreenCredits" class="le_loadingScreenCredits">
            <div class="le_loadingScreenCredits_text">Powered by Lightmin Engine</div>
        </div>
        <!-- ////////////////////////////////   CLIENT ERROR   //////////////////////////////// -->
        <div id="le.clientErrorWarningPopUp" class="le_clientErrorWarningPopUp">
            <div class="le_clientErrorWarningPopUp_Box">
                <div>
                    <img src="engine/medias/images/lightmin-engine-logo.png" class="le_clientErrorWarningPopUp_Box_Logo" />
                </div>
                <div class="le_clientErrorWarningPopUp_Box_Text">
                    There was an error running the Website and it needed to be stopped. Please contact the Website Administrator. If you are the Administrator, please check the Browser console.
                </div>
            </div>
        </div>
        <!-- ////////////////////////////////   SCREENS AREA   //////////////////////////////// -->
        <div id="le.screensArea.sandbox" class="le_screensArea_sandbox"></div>
        <!-- The End of base HTML of Lightmin Engine -->
        <!-- ////////////////////////////////   WEBSITE CONTENT   //////////////////////////////// -->
        <!-- The Start of the Client.php -->
        <div name="Client.php" id="le.body.allWebsiteContent" class="le_body_allWebsiteContent">