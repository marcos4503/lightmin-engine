<?php /* This JavaScript file contains commonly used methods and variables that are useful for many different JavaScript files and for the Website. */ ?>

<script>

class Utils{

    //Cache variables
    static cacheFaviconElement = null;
    static cacheTitleElement = null;
    static cacheOgTitleElement = null;
    static cacheDescriptionElement = null;
    static cacheOgDescriptionElement = null;
    static cacheOgUrlElement = null;
    static cacheOgImageElement = null;

    //Public methods

    static RemoveAllListeners(element){
        //Re-create the DOM element to remove all listeners from him
        var newElementClone = element.cloneNode(true);
        element.parentNode.replaceChild(newElementClone, element);
    }

    static GetCurrentFaviconSrc(){
        //If don't have a favicon in cache, try to find a favicon for the cache
        if(Utils.cacheFaviconElement == null)
            Utils.cacheFaviconElement = document.querySelector("link[rel~='icon']");

        //If have a favicon in cache, get the HREF of it...
        if(Utils.cacheFaviconElement != null)
            return Utils.cacheFaviconElement.href;
        if(Utils.cacheFaviconElement == null)
            return "";
    }

    static ChangeFavicon(newFaviconSrc){
        //If don't have a favicon in cache...
        if(Utils.cacheFaviconElement == null){
            //Try to find a favicon for the cache
            Utils.cacheFaviconElement = document.querySelector("link[rel~='icon']");
            //If not found, create a favicon element
            if (Utils.cacheFaviconElement == null) {
                Utils.cacheFaviconElement = document.createElement('link');
                Utils.cacheFaviconElement.rel = 'icon';
                document.head.appendChild(Utils.cacheFaviconElement);
            }
        }

        //If have a favicon in cache, change it
        if(Utils.cacheFaviconElement != null)
            Utils.cacheFaviconElement.href = newFaviconSrc;
    }

    //Auxiliar methods

    static ChangeClientMetadataTitle(newTitle){
        //Get the cache, if don't have
        if(Utils.cacheTitleElement == null)
            Utils.cacheTitleElement = document.getElementById("le.website.title");
        if(Utils.cacheOgTitleElement == null)
            Utils.cacheOgTitleElement = document.getElementById("le.website.ogtitle");

        //Change the title
        Utils.cacheTitleElement.innerHTML = (Settings.GetManifestBaseTitle() + " - " + newTitle);
        Utils.cacheOgTitleElement.setAttribute("content", (Settings.GetManifestBaseTitle() + " - " + newTitle));
    }

    static ChangeClientMetadataDescription(newDescription){
        //Get the cache, if don't have
        if(Utils.cacheDescriptionElement == null)
            Utils.cacheDescriptionElement = document.getElementById("le.website.description");
        if(Utils.cacheOgDescriptionElement == null)
            Utils.cacheOgDescriptionElement = document.getElementById("le.website.ogdescription");

        //Change the description
        Utils.cacheDescriptionElement.setAttribute("content", newDescription);
        Utils.cacheOgDescriptionElement.setAttribute("content", newDescription);
    }

    static ChangeClientMetadataUrl(newUrl){
        //Get the cache, if don't have
        if(Utils.cacheOgUrlElement == null)
            Utils.cacheOgUrlElement = document.getElementById("le.website.ogurl");

        //Change the url
        Utils.cacheOgUrlElement.setAttribute("content", newUrl);
    }

    static ChangeClientMetadataImage(newImageUrl){
        //Get the cache, if don't have
        if(Utils.cacheOgImageElement == null)
            Utils.cacheOgImageElement = document.getElementById("le.website.ogimage");

        //Change the image
        Utils.cacheOgImageElement.setAttribute("content", newImageUrl);
    }
}

</script>