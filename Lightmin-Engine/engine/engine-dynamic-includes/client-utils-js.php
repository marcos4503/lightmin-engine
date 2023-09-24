<?php /* This JavaScript file contains commonly used methods and variables that are useful for many different JavaScript files and for the Website. */ ?>

<script>

class Utils{

    //Cache variables
    static cacheFaviconElement = null;

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

}

</script>