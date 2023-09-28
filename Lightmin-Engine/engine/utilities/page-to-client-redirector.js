//Install the event
window.onload = (event) => { RedirectToClient(); };
//Install the loading screen
document.onreadystatechange = function (e) {
    if (document.readyState === 'complete')
        InstallLoadingScreen();
};

//Redirect the user to the "client.php" to view this Page, using as base the current URL in Browser
function RedirectToClient() {
    //Get the Browser URL
    var browserUrl = window.location.href;

    //Get the URL to the folder that "client.php" is
    var websiteRootUrl = browserUrl.split("/pages/")[0];
    var currentPageUri = browserUrl.split("/pages/")[1];

    //Build the URL to "client.php" to see this page
    var resultUrl = (websiteRootUrl + "/client.php?p=" + currentPageUri);

    //Redirect the user to the "client.php"
    window.setTimeout(() => { window.location.href = resultUrl; }, 1000);
}

//Install a loading screen instantly
function InstallLoadingScreen() {
    //Create the element
    var loadingScreen = document.createElement("div");
    loadingScreen.setAttribute("style", "display: flex; position: fixed; top: 0px; left: 0px; z-index: 999500; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: #ffffff;");

    //Add the element to body
    document.body.appendChild(loadingScreen);

    //Add the loading gif
    var browserUrl = window.location.href;
    var websiteRootUrl = browserUrl.split("/pages/")[0];
    loadingScreen.innerHTML = "<div style=\"width: 128px; height: 128px; background-image: url(" + websiteRootUrl + "/engine/medias/images/ripple-load.gif); background-size: 50%; background-repeat: no-repeat; background-position: center\"></div>";
}