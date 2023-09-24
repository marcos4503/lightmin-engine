<?php /* This JavaScript file contains commonly used methods and variables that are useful for many different JavaScript files and for the Website. */ ?>

<script>

class Common {

    //Public methods

    static SendLog(logType, message){
        //If is a error...
        if(logType == "E" || logType == "e")
            console.error("Lightmin Engine: " + message);

        //If is a warning...
        if(logType == "W" || logType == "w")
            console.warn("Lightmin Engine: " + message);

        //If is a log...
        if(logType == "L" || logType == "l")
            console.log("Lightmin Engine: " + message);
    }

}

</script>