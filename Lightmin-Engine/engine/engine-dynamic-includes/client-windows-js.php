<?php /* This JavaScript file contains commonly used methods and variables that are useful for many different JavaScript files and for the Website. */ ?>

<script>

class Windows{

    //Cache variables
    static existantWindowsInClientAndScreens = [];  //<- This references Window elements and contains information related to each Window in the Client and in Screens.

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

    //Auxiliar methods

    static GetNoPageLoadedHtmlCode(){
        //Return a HTML of no page loaded in the Window yet...
        return "<div style=\"height: 64px;\"></div><div style=\"text-align: center; opacity: 0.35; font-style: italic;\">" + Settings.Get("noPageLoadedInWindowMessage") + "</div>";
    }

}

</script>