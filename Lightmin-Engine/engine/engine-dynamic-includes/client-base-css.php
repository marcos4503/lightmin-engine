<?php /* This file contains all base CSS of the Lightmin Engine */ ?>

<style>

/* General */

html, body {
    height: auto;
}
body{
    margin-top: 0px;
    margin-bottom: 0px;
    margin-left: 0px;
    margin-right: 0px;
}

/* Loading Screen */

.le_loadingScreenCredits{
    display: flex;
    position: fixed;
    bottom: 0px;
    left: 0px;
    z-index: 999505;
    width: 100%;
    height: auto;
    align-items: center;
    justify-content: center;
    transition: all 5000ms;
}
.le_loadingScreenCredits_text{
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    margin-bottom: 24px;
    opacity: 0.75;
}
.le_loadingScreenFront{
    display: flex;
    position: fixed;
    top: 0px;
    left: 0px;
    z-index: 999500;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 10%, #ffffff, #ffffff);
    align-items: center;
    justify-content: center;
    transition: all 350ms;
}
.le_loadingScreen_loadingBox{
    display: none;
    height: 168px;
    width: 128px;
    grid-template-rows: 128px 28px 4px 16px;
    opacity: 0.0;
    transition: all 250ms;
}
.le_loadingScreen_loadingBox_logo{
    width: 128px;
    height: 128px;
    background-image: url("engine/medias/images/ripple-load.gif");
    background-size: 50%;
    background-repeat: no-repeat;
    background-position: center;
}
.le_loadingScreen_loadingBox_logo_img{
    display: none;
    width: 100%;
    height: 100%;
    pointer-events: none;
}
@keyframes le_loadingScreen_loadingBox_logo_img_enable{
    0%   { transform: scale(0.0); }
    75%   { transform: scale(1.25); }
    100%   { transform: scale(1.0); }
}
.le_loadingScreen_loadingBox_text{
    display: flex;
    width: 100%;
    height: 28px;
    text-align: center;
    font-weight: normal;
    align-items: end;
    justify-content: center;
    opacity: 0.0;
    transition: all 1500ms;
}
.le_loadingScreen_loadingBox_spacer{
    width: 100%;
    height: 4px;
}
.le_loadingScreen_loadingBox_bar_bg{
    width: 0%;
    height: 16px;
    background-color: #6c7075;
    border-radius: 8px;
    margin-left: auto;
    margin-right: auto;
    transition: all 750ms;
}
.le_loadingScreen_loadingBox_bar_fg{
    width: 0%;
    height: 100%;
    background-color: #6ab8c4;
    border-radius: 8px;
    transition: all 250ms;
}
.le_loadingScreenBack{
    position: fixed;
    top: 0px;
    left: 0px;
    z-index: 999490;
    width: 100%;
    height: 100%;
    background-color: #ffffff;
    box-shadow: 0px 10px 63px 0px rgba(0,0,0,0.15);
    transition: all 350ms;
}
.le_loadingScreen_errorBox{
    display: none;
    height: 98px;
    width: 256px;
    grid-template-rows: 64px 34px;
    opacity: 0.0;
    transition: all 250ms;
}
.le_loadingScreen_errorBox_text{
    display: flex;
    height: 100%;
    width: 100%;
    text-align: center;
    align-items: center;
    justify-content: center;
}
.le_loadingScreen_errorBox_button{
    display: flex;
    padding: 8px;
    width: 100%;
    background-color: #107084;
    box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.0);
    color: white;
    border-radius: 8px;
    text-transform: uppercase;
    align-items: center;
    justify-content: center;
    font-weight: bolder;
    margin-right: auto;
    margin-left: auto;
    cursor: pointer;
    transition: all 250ms;
}
.le_loadingScreen_errorBox_button:hover{
    background-color: #1593ac;
    box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.75);
}
.le_clientErrorWarningPopUp{
    display: none;
    position: fixed;
    top: 0px;
    left: 0px;
    z-index: 999485;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.75);
    backdrop-filter: blur(2px);
    align-items: center;
    justify-content: center;
}
.le_clientErrorWarningPopUp_Box{
    display: block;
    width: auto;
    height: auto;
    background-color: #f7f7f7;
    border-radius: 8px;
    padding: 16px;
    grid-template-rows: 128px auto;
}
@media only screen and (min-device-width: 128px) and (max-device-width: 1023px)   { .le_clientErrorWarningPopUp_Box{ max-width: 80%; } }
@media only screen and (min-device-width: 1024px) and (max-device-width: 15360px) { .le_clientErrorWarningPopUp_Box{ max-width: 512px; } }
.le_clientErrorWarningPopUp_Box_Logo{
    width: 100%;
    pointer-events: none;
}
.le_clientErrorWarningPopUp_Box_Text{
    text-align: center;
}

/* Screen Area */

.le_screensArea_sandbox{
    display: block;
    position: fixed;
    top: 0px;
    left: 0px;
    z-index: 999450;
    width: 100%;
    height: 100%;
    pointer-events: none;
}
.le_screensArea_sandbox_loadedScreen{
    display: none;
    width: 100%;
    height: 100%;
    overflow-x: visible;
    overflow-y: visible;
    transition: all 250ms;
}

/* All Website Content */

.le_body_allWebsiteContent{
    display: none;
    width: 100%;
    height: auto;
}

</style>