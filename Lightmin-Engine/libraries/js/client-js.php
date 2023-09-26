<?php /* This is a JS file created to be applied to the Client */ ?>

<script>

//Receive the callback of full load of the engine

function LE_OnLoadEngineFinished(){
    //Run needed code...
    EnableAdditionalWindowButton();
    HighlightDefaultTabs();
}

//Client javascript code...

function OpenAdditionalWindowView(){
    //Get the components
    var addWindowBg = document.getElementById("additionalWindowBg");
    var addWindowBt = document.getElementById("additionalWindowButton");
    var addWindowBox = document.getElementById("additionalWindowBox");

    //Open the window
    addWindowBt.style.bottom = "-15%";
    addWindowBg.style.display = "block";
    window.setTimeout(() => { addWindowBg.style.opacity = "0.5"; }, 2);
    addWindowBox.style.display = "block";
    window.setTimeout(() => { addWindowBox.style.bottom = "0px"; }, 2);
}

function CloseAdditionalWindowView(){
    //Get the components
    var addWindowBg = document.getElementById("additionalWindowBg");
    var addWindowBt = document.getElementById("additionalWindowButton");
    var addWindowBox = document.getElementById("additionalWindowBox");

    //Open the window
    addWindowBg.style.opacity = "0.0";
    window.setTimeout(() => { addWindowBg.style.display = "none"; }, 250);
    addWindowBox.style.bottom = "-100%";
    window.setTimeout(() => { addWindowBox.style.display = "none"; }, 250);
    window.setTimeout(() => { addWindowBt.style.bottom = "0px"; }, 250);
}

function HighlightClientTab(tabName){
    //Get the tabs..
    var page1Tab = document.getElementById("client.tab1");
    var page2Tab = document.getElementById("client.tab2");
    var page3Tab = document.getElementById("client.tab3");

    //Highlight the desired tab...
    if(tabName == "Page 1"){
        page1Tab.style.backgroundColor = "";
        page2Tab.style.backgroundColor = "#d1d1d1";
        page3Tab.style.backgroundColor = "#d1d1d1";
    }
    if(tabName == "Page 2"){
        page1Tab.style.backgroundColor = "#d1d1d1";
        page2Tab.style.backgroundColor = "";
        page3Tab.style.backgroundColor = "#d1d1d1";
    }
    if(tabName == "Page 3"){
        page1Tab.style.backgroundColor = "#d1d1d1";
        page2Tab.style.backgroundColor = "#d1d1d1";
        page3Tab.style.backgroundColor = "";
    }
}

function HighlightAddWindowTab(tabName){
    //Get the tabs..
    var page1Tab = document.getElementById("client.addWindow.tab1");
    var page2Tab = document.getElementById("client.addWindow.tab2");

    //Highlight the desired tab...
    if(tabName == "Additional Page A"){
        page1Tab.style.backgroundColor = "";
        page2Tab.style.backgroundColor = "#d1d1d1";
    }
    if(tabName == "Additional Page B"){
        page1Tab.style.backgroundColor = "#d1d1d1";
        page2Tab.style.backgroundColor = "";
    }
}

//Auxiliar methods...

function EnableAdditionalWindowButton(){
    //Enable the additional window button
    var windowButton = document.getElementById("additionalWindowButton");
    windowButton.style.bottom = "0px";
}

function HighlightDefaultTabs(){
    //Enable the default tabs of website...
    HighlightClientTab("Page 1");
    HighlightAddWindowTab("Additional Page A");
}

</script>