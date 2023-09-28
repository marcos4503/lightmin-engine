<?php /* This is a CSS file created to be applied to the Client HTML code */ ?>

<style>

/* General */

body{
    background-color: #c9c9c9;
}
a{
    color: #375b68;
    text-decoration: none;
}

/* For HTML code (stacked grid) */

.client_stackedGrid{
    width: 100%;
    height: fit-content;
    display: grid;
    place-items: start normal;
    grid-template-areas: "inner-div";
}
.client_stackedGrid_backgroundHeader{
    grid-area: inner-div;
    width: 100%;
    height: 200px;
    background-image: url(medias/images/client/header-bg-opt.png);
    background-position: center bottom;
    background-repeat: no-repeat;
    background-size: cover;

}
.client_stackedGrid_contentArea{
    grid-area: inner-div;
    width: 90%;
    height: auto;
    margin-left: auto;
    margin-right: auto;
}
@media only screen and (min-device-width: 128px) and (max-device-width: 1023px)   { .client_stackedGrid_contentArea{ max-width: 90%; } }
@media only screen and (min-device-width: 1024px) and (max-device-width: 15360px) { .client_stackedGrid_contentArea{ max-width: 800px; } }
.client_stackedGrid_contentArea_logo{
    width: 50%;
    margin-top: 16px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
}
.client_stackedGrid_contentArea_logo_img{
    width: 100%;
}
@media only screen and (min-device-width: 128px) and (max-device-width: 1023px)   { .client_stackedGrid_contentArea_logo_img{ margin-top: -4%; margin-bottom: -6%; } }
@media only screen and (min-device-width: 1024px) and (max-device-width: 15360px) { .client_stackedGrid_contentArea_logo_img{ margin-top: -4%; margin-bottom: -5%; } }
.client_stackedGrid_contentArea_navbar{
    margin-top: 16px;
    width: 100%;
    height: 36px;
}
.client_stackedGrid_contentArea_navbar_item{
    float: right;
    width: fit-content;
    height: 36px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    background-color: #e8e8e8;
    margin-left: 8px;
    padding-left: 8px;
    padding-right: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 250ms;
}
.client_stackedGrid_contentArea_navbar_item:hover{
    background-color: #ffffff;
}
.client_stackedGrid_contentArea_navbar_item_last{
    float: right;
    width: 8px;
    height: 36px;
}
.client_stackedGrid_contentArea_content{
    width: calc(100% - 32px);
    height: auto;
    border-radius: 8px;
    padding: 16px;
    background-color: #ffffff;
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
}
.client_stackedGrid_contentArea_footer{
    margin-top: 16px;
    margin-bottom: 16px;
    width: calc(100% - 32px);
    height: auto;
    border-radius: 8px;
    padding: 16px;
    background-color: #ffffff;
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    font-size: 14px;
}
.client_stackedGrid_contentArea_footer_separator{
    width: 100%;
    height: 4px;
}
.client_stackedGrid_contentArea_footer_githubLink{
    display: inline;
}
.client_stackedGrid_contentArea_footer_githubIcon{
    width: 14px;
    height: 14px;
    border-radius: 14px;
    transform: translateY(2px);
}

/* For HTML code (additional window) */

.client_additionalWindow_background{
    display: none;
    position: fixed;
    top: 0px;
    left: 0px;
    height: 100%;
    width: 100%;
    background-color: #000000;
    opacity: 0.0;
    z-index: 100;
    cursor: pointer;
    transition: all 250ms;
}
.client_additionalWindow_button{
    display: block;
    position: fixed;
    bottom: -15%;
    right: 8px;
    z-index: 105;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    background-color: #f7f7f7;
    padding: 8px;
    box-shadow: 0px 0px 16px 0px rgba(0,0,0,0.35);
    font-size: 12px;
    cursor: pointer;
    text-align: center;
    transition: all 250ms;
}
.client_additionalWindow_button:hover{
    padding-top: 16px;
    padding-bottom: 16px;
    box-shadow: 0px 0px 16px 0px rgba(0,0,0,0.75);
}
.client_additionalWindow_root{
    display: none;
    position: fixed;
    bottom: -100%;
    left: 0px;
    width: 100%;
    height: 45%;
    z-index: 110;
    transition: all 250ms;
}
.client_additionalWindow_box{
    width: 100%;
    height: 100%;
}
.client_additionalWindow_box_navbar{
    width: 100%;
    height: 36px;
    display: flex;
    justify-content: center;
}
.client_additionalWindow_box_navbar_item{
    width: fit-content;
    height: 36px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    background-color: #e8e8e8;
    margin-left: 4px;
    margin-right: 4px;
    padding-left: 8px;
    padding-right: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 250ms;
}
.client_additionalWindow_box_navbar_item:hover{
    background-color: #ffffff;
}
.client_additionalWindow_box_content{
    width: 95%;
    height: calc(100% - 36px);
    margin-left: auto;
    margin-right: auto;
    background-color: #ffffff;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    padding-top: 8px;
    padding-bottom: 8px;
    padding-left: 8px;
}
.client_additionalWindow_box_content_scrollable{
    width: calc(100% - 8px);
    height: calc(100% - 16px);
    overflow-y: scroll;
}

</style>