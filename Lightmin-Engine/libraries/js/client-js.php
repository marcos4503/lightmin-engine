<?php /* This is a JS file created to be applied to the Client */ ?>

<script>

//Receive the callback of full load of the engine

function LE_OnLoadEngineFinished(){
    //Run needed code...
    EnableAdditionalWindowButton();
}

//Client javascript code...

function EnableAdditionalWindowButton(){
    //Enable the additional window button
    var windowButton = document.getElementById("additionalWindowButton");
    windowButton.style.bottom = "0px";
}

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

</script>