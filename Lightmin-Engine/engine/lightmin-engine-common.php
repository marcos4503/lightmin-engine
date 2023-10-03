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

    public static function GetPageTitle(string $pageUri){
        //Prepare the result
        $result = "Page Not Found";

        //Try to get the page DOM element...
        $pageDomElement = self::GetPageDomFromPageUri($pageUri);

        //If is null, return empty
        if($pageDomElement === null)
            return $result;
    
        //Try to get the page title...
        try{
            //Get the TITLE tag
            $titleElement = $pageDomElement->getElementsByTagName("title")->item(0);
            
            //Return the title
            return (self::GetDomElementInnerHTML($titleElement));
        }
        catch(Exception $e) { }

        //If have an error, return empty
        return $result;
    }

    public static function GetPageDescription(string $pageUri){
        //Prepare the result
        $result = "Page Not Found";

        //Try to get the page DOM element...
        $pageDomElement = self::GetPageDomFromPageUri($pageUri);

        //If is null, return empty
        if($pageDomElement === null)
            return $result;

        //Try to get the page title...
        try{
            //Get the META tags
            $metaElements = $pageDomElement->getElementsByTagName("meta");

            //Get the content of meta tag of type "description"...
            for($i = 0; $i < ($metaElements->length); $i++)
                if($metaElements->item($i)->attributes->getNamedItem("name")->value == "description")
                    return $metaElements->item($i)->attributes->getNamedItem("content")->value; 
        }
        catch(Exception $e) { }

        //If have an error, return empty
        return $result;
    }

    public static function GetPageImage(string $pageUri){
        //Prepare the result
        $result = "https://page-not.found/no-image.png";

        //Try to get the page DOM element...
        $pageDomElement = self::GetPageDomFromPageUri($pageUri);

        //If is null, return empty
        if($pageDomElement === null)
            return $result;

        //Try to get the page title...
        try{
            //Get the META tags
            $metaElements = $pageDomElement->getElementsByTagName("meta");

            //Get the content of meta tag of type "description"...
            for($i = 0; $i < ($metaElements->length); $i++)
                if($metaElements->item($i)->attributes->getNamedItem("name")->value == "image")
                    return $metaElements->item($i)->attributes->getNamedItem("content")->value; 
        }
        catch(Exception $e) { }

        //If have an error, return empty
        return $result;
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

    public static function GetDomElementInnerHTML(DOMNode $element){ 
        $innerHTML = ""; 
        $children  = $element->childNodes;

        foreach ($children as $child) 
        { 
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML; 
    } 

    public static function GetPageDomFromPageUri(string $pageUri){
        //Build the page file path string, using the default page as base
        $pageFilePath = ("pages/" . self::GetManifestDefaultPage());

        //If have a requested page, change to use the file path of requested page...
        if($pageUri != "")
            $pageFilePath = ("pages/" . $pageUri);

        //If the final file path don't exists, return null
        if(file_exists($pageFilePath) == false || $pageFilePath == "pages/")
            return null;

        //Get the file content
        $pageFileContent = file_get_contents($pageFilePath);

        //Try to get the DOM from file...
        try{
            //Get the DOM and load the HTML
            $pageDom = new DOMDocument();
            $pageDom->loadHTML($pageFileContent);

            //Return the obtained page DOM
            return $pageDom;
        }
        catch(Exception $e) { }

        //If have a error, return null
        return null;
    }
}

?>