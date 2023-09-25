<?php

/*
 * This PHP script contains commonly used methods and variables that are useful for many different PHP scripts, inside Lightmin Engine
 * and for the Website of the developer.
*/

class Common{

    //Core methods

    public static function GetManifestBaseTitle(){
        //Get the parsed manifest json object
        $manifestJsonObject = self::GetParsedManifestJson();

        //Prepare the variable to store the title
        $baseTitle = "";

        //Protect the script agains crashs...
        try
        {
            //Extract the variable from JSON Object
            $baseTitle = $manifestJsonObject->baseTitle;
        }
        catch(Exception $e) {  }

        //Return the base title
        return $baseTitle;
    }

    public static function GetManifestDefaultPage(){
        //Get the parsed manifest json object
        $manifestJsonObject = self::GetParsedManifestJson();

        //Prepare the variable to store the default page
        $defaultPage = "";

        //Protect the script agains crashs...
        try
        {
            //Extract the variable from JSON Object
            $defaultPage = $manifestJsonObject->defaultPage;
        }
        catch(Exception $e) {  }

        //Return the default page
        return $defaultPage;
    }

    //Auxiliar methods

    public static function isJson(string $string) {
        //Check if the string is a JSON code and return true if is
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function GetParsedManifestJson(){
        //Try to read the "manifest.json" file
        $manifestJsonString = file_get_contents("manifest.json");

        //If was runned a error, cancel entire script
        if($manifestJsonString == false)
            exit();
        //If the loaded string is not a JSON, cancel entire script
        if(self::isJson($manifestJsonString) == false)
            exit();

        //Parse the "manifest.json" to a JSON Object
        return json_decode($manifestJsonString);
    }

}

?>