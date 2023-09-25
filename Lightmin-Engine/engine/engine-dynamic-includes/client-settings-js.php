<?php /* This file contains the code that is responsible for storing the settings loaded on the initial load screen. */ ?>

<script>

class Settings{

    //Public variables
    static loadedWebsiteSettings = null;   //<- This is JSON Object result of settings loaded from "settings.php" at start of website
    static loadedBaseTitle = "";           //<- This is the website base title, defined in "manifest.json"
    static loadedDefaultPage = "";         //<- This is the website default page, defined in "manifest.json"

    //Public methods

    static Get(settingName){
        //If the loadedWebsiteSettings is null, cancel
        if(Settings.loadedWebsiteSettings == null){
            Common.SendLog("E", "Unable to obtain desired configuration. No configurations were loaded.");
            return;
        }
        //Check if the setting exists in loaded settings
        if(Settings.loadedWebsiteSettings.hasOwnProperty(settingName) == false){
            Common.SendLog("E", "Unable to obtain desired configuration. There is no configuration variable named as \""+settingName+"\".");
            return;
        }

        //Return the desired setting
        return Settings.loadedWebsiteSettings[settingName];
    }

    static GetManifestBaseTitle(){
        //Returnt the website base title defined in "manifest.json"
        return Settings.loadedBaseTitle;
    }

    static GetManifestDefaultPage(){
        //Returnt the website base title defined in "manifest.json"
        return Settings.loadedDefaultPage;
    }

}

</script>