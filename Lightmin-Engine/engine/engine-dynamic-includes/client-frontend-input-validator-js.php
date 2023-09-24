<?php /* This is the "Frontend Input Validator" library that is included by default with the Lightmin Engine. */ ?>

<script>
/*
 * Frontend Input Validator
 * 
 * This class is part of "Frontend Input Validator" library created with the mission of simplify user input validation in frontend of websites
*/

class InputValidator {

    //Cache variables
    isInitialized = false;
    isCalledSetOnValidateCallback = false;
    isAssociatedFieldValid = true;

    //Private variables
    logsPrefix = "Lightmin Engine: ";
    inputElement;
    expectedType = "NONE";
    parameters;

    //Validation parameters               //Types that each parameter will be used to validate
    allowEmpty = true;                    //STRING, INT, FLOAT, FILE
    minChars = 0;                         //STRING
    maxChars = 0;                         //STRING
    allowNumbers = true;                  //STRING
    allowLetters = true;                  //STRING
    allowSpace = true;                    //STRING
    allowLineBreak = true;                //STRING
    specialCharsAllowed = "all";          //STRING
    hasProvidedCustomRegex = false;       //  | _____ STRING
    providedCustomRegex = /.*/g;          //  |
    minNumberValue = -9007199254740991;   //INT, FLOAT
    maxNumberValue = 9007199254740991;    //INT, FLOAT
    allowNumberZero = true;               //INT, FLOAT
    allowNumberNegative = true;           //INT, FLOAT
    allowNumberPositive = true;           //INT, FLOAT
    minFilesSelection = 0;                //FILE
    maxFilesSelection = 9007199254740991; //FILE
    requiredFilesExtensions = "any";      //FILE
    maxFilesSizeInKb = 0;                 //FILE

    //Validation callbacks
    onValidateCallback;

    //Core methods

    constructor(inputElementId, expectedType, parameters) {
        //Check if parameter is a object
        if (typeof parameters !== "object") {
            console.error(this.logsPrefix + "Could not initialize object \"InputValidator\". The informed parameters are not of type OBJECT.");
            return;
        }
        //Get the parameters
        this.parameters = parameters;

        //If the expected type don't is valid, cancel
        if (expectedType != "STRING" && expectedType != "INT" && expectedType != "FLOAT" && expectedType != "FILE") {
            console.error(this.logsPrefix + "Could not initialize object \"InputValidator\". The expected value type is not valid! It must be of type STRING, INT, FLOAT or FILE!");
            return;
        }
        //Get the expected type
        this.expectedType = expectedType;

        //Try to find the input element
        var inputElement = document.getElementById(inputElementId);
        //If the input element is not found, cancel
        if (inputElement == null) {
            console.error(this.logsPrefix + "Could not initialize object \"InputValidator\". The informed ID does not refer to any input element!");
            return;
        }
        //If the input element is not valid, cancel
        if (inputElement.tagName != "INPUT" && inputElement.tagName != "TEXTAREA") {
            console.error(this.logsPrefix + "Could not initialize object \"InputValidator\". The informed ID does not refer to any element of type INPUT or TEXTAREA!");
            return;
        }
        //Get the input element
        this.inputElement = inputElement;


        //Inform that is initialized successfully
        this.isInitialized = true;
    }

    SetCustomRegexForValidation(customRegex) {
        //If the expected type of this object instance is not a String, cancel this call
        if (this.expectedType != "STRING") {
            console.error(this.logsPrefix + "Could not define a custom Regex for validation on the Input Validator object! The object expects to validate an input of type \"" + this.expectedType + "\", and it is only possible to provide a custom Regex for inputs of Type STRING!");
            return;
        }
        //If the content provided is not a regex, cancel this call
        if (typeof customRegex !== "object") {
            console.error(this.logsPrefix + "Could not define a custom Regex for validation on the Input Validator object! The given content is not object of type Regex!");
            return;
        }

        //Get the custom regex
        this.hasProvidedCustomRegex = true;
        this.providedCustomRegex = customRegex;
    }

    SetOnValidateCallback(newCallback) {
        //If this object is not initialized successfully, cancel this call
        if (this.isInitialized == false)
            return;

        //Get reference to this object instance
        var thisObj = this;

        //Add hooks to the input element to auto validate it
        this.inputElement?.addEventListener("keydown", (evnt) => { thisObj.aux_Validate(); });
        this.inputElement?.addEventListener("keyup", (evnt) => { thisObj.aux_Validate(); });
        //this.inputElement?.addEventListener("focus", (evnt) => { thisObj.aux_Validate(); });
        this.inputElement?.addEventListener("focusout", (evnt) => { thisObj.aux_Validate(); });
        this.inputElement?.addEventListener("change", (evnt) => { thisObj.aux_Validate(); });
        //this.inputElement?.addEventListener("click", (evnt) => { thisObj.aux_Validate(); });

        //Register the new callback code informed
        this.onValidateCallback = newCallback;

        //Read the parameters and transport to this object instance to be used in the validation
        if (typeof this.parameters.allowEmpty == "boolean") { this.allowEmpty = this.parameters.allowEmpty; }
        if (typeof this.parameters.minChars == "number") { this.minChars = this.parameters.minChars; }
        if (typeof this.parameters.maxChars == "number") { this.maxChars = this.parameters.maxChars; }
        if (typeof this.parameters.allowNumbers == "boolean") { this.allowNumbers = this.parameters.allowNumbers; }
        if (typeof this.parameters.allowLetters == "boolean") { this.allowLetters = this.parameters.allowLetters; }
        if (typeof this.parameters.allowSpace == "boolean") { this.allowSpace = this.parameters.allowSpace; }
        if (typeof this.parameters.allowLineBreak == "boolean") { this.allowLineBreak = this.parameters.allowLineBreak; }
        if (typeof this.parameters.specialCharsAllowed == "string") { this.specialCharsAllowed = this.parameters.specialCharsAllowed; }
        if (typeof this.parameters.mustMatchWithRegex == "string") { this.mustMatchWithRegex = this.parameters.mustMatchWithRegex; }
        if (typeof this.parameters.minNumberValue == "number") { this.minNumberValue = this.parameters.minNumberValue; }
        if (typeof this.parameters.maxNumberValue == "number") { this.maxNumberValue = this.parameters.maxNumberValue; }
        if (typeof this.parameters.allowNumberZero == "boolean") { this.allowNumberZero = this.parameters.allowNumberZero; }
        if (typeof this.parameters.allowNumberNegative == "boolean") { this.allowNumberNegative = this.parameters.allowNumberNegative; }
        if (typeof this.parameters.allowNumberPositive == "boolean") { this.allowNumberPositive = this.parameters.allowNumberPositive; }
        if (typeof this.parameters.minFilesSelection == "number") { this.minFilesSelection = this.parameters.minFilesSelection; }
        if (typeof this.parameters.maxFilesSelection == "number") { this.maxFilesSelection = this.parameters.maxFilesSelection; }
        if (typeof this.parameters.requiredFilesExtensions == "string") { this.requiredFilesExtensions = this.parameters.requiredFilesExtensions; }
        if (typeof this.parameters.maxFilesSizeInKb == "number") { this.maxFilesSizeInKb = this.parameters.maxFilesSizeInKb; }

        //Inform that has called this method
        this.isCalledSetOnValidateCallback = true;
    }

    isTheAssociatedFieldValid() {
        //If this object is not initialized successfully, cancel this call
        if (this.isInitialized == false) {
            console.error(this.logsPrefix + "Could not check validation on object \"Input Validator\". The object was not properly initialized or instantiated.");
            return;
        }
        //If don't called "SetOnValidateCallback();" yet
        if (this.isCalledSetOnValidateCallback == false) {
            console.error(this.logsPrefix + "Could not check validation on object \"Input Validator\". Method \"SetOnValidateCallback()\" was not called on the object, so it is not prepared for validation and there is no callback to receive it!");
            return;
        }

        //Force the validation
        this.aux_Validate();

        //Return if the associated field is valid
        return this.isAssociatedFieldValid;
    }

    //Auxiliar methods

    aux_Validate() {
        //Prepare the response
        var isInputValid = true;

        //============================================ If expected type is STRING ============================================//
        if (this.expectedType == "STRING") {
            //Get the input value
            //@ts-ignore
            var valueToValidate = this.inputElement?.value;

            //Check if is empty
            if (this.allowEmpty == false && valueToValidate == false)
                isInputValid = false;
            //Check if have min chars
            if (this.minChars > 0 && valueToValidate.length < this.minChars)
                isInputValid = false;
            //Check if has passed max characters
            if (this.maxChars > 0 && valueToValidate.length > this.maxChars)
                isInputValid = false;
            //Check if have numbers
            if (this.allowNumbers == false && /\d/g.test(valueToValidate) == true)
                isInputValid = false;
            //Check if have letters
            if (this.allowLetters == false && /[A-Za-z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u00FF\u010D\u0107\u010C\u0106\u0160\u0161\u0178\u0153\u0152]/g.test(valueToValidate) == true) /*  /[A-Za-zÀ-ÖØ-öø-ÿčćČĆŠšŸœŒ]/g  */
                isInputValid = false;
            //Check if have space
            if (this.allowSpace == false && /\s/g.test(valueToValidate) == true)
                isInputValid = false;
            //Check if have line break
            if (this.allowLineBreak == false && /\r|\n/g.test(valueToValidate) == true)
                isInputValid = false;

            //Check if have unallowed special characters
            if (this.specialCharsAllowed != "all") {
                //Prepare the array of special chars allowed
                var charsAllowed = [];
                //If have specified special chars allowed, insert then into the array
                if (this.specialCharsAllowed != "none") {
                    var charsSplitted = this.specialCharsAllowed.split(",");
                    for (var i = 0; i < charsSplitted.length; i++) {
                        if (charsSplitted[i] == "comma")
                            charsAllowed.push(",");
                        if (charsSplitted[i] != "comma")
                            charsAllowed.push(charsSplitted[i]);
                    }
                }

                //Build a custom RegExp to allow especified special characters     (Supports A-z, 0-9, SPACE and "àáâãäåÀÁÂÃÄÅ çčćÇČĆ èéêëÈÉÊË ìíîïÌÍÎÏ ñÑ Šš òóôõöøÒÓÔÕÖØ ùúûüÙÚÛÜ ß ÿŸýÝ æÆ œŒ" by default!)
                var customRegExp = "^[a-zA-Z0-9 \u00C0-\u00D6 \u00D8-\u00F6 \u00F8-\u00FF \u010D\u0107\u010C\u0106\u0160\u0161\u0178\u0153\u0152 ";               /*  /[a-zA-Z0-9 À-Ö Ø-ö ø-ÿ čćČĆŠšŸœŒ]/g  */
                for (var i = 0; i < charsAllowed.length; i++) {
                    //If is a special character for regex syntax
                    if (charsAllowed[i] == ".") { customRegExp += "\\."; continue; }
                    if (charsAllowed[i] == "*") { customRegExp += "\\*"; continue; }
                    if (charsAllowed[i] == "+") { customRegExp += "\\+"; continue; }
                    if (charsAllowed[i] == "?") { customRegExp += "\\?"; continue; }
                    if (charsAllowed[i] == "^") { customRegExp += "\\^"; continue; }
                    if (charsAllowed[i] == "$") { customRegExp += "\\$"; continue; }
                    if (charsAllowed[i] == "|") { customRegExp += "\\|"; continue; }
                    if (charsAllowed[i] == "(") { customRegExp += "\\("; continue; }
                    if (charsAllowed[i] == ")") { customRegExp += "\\)"; continue; }
                    if (charsAllowed[i] == "[") { customRegExp += "\\["; continue; }
                    if (charsAllowed[i] == "]") { customRegExp += "\\]"; continue; }
                    if (charsAllowed[i] == "{") { customRegExp += "\\{"; continue; }
                    if (charsAllowed[i] == "}") { customRegExp += "\\}"; continue; }
                    if (charsAllowed[i] == "\\") { customRegExp += "\\\\"; continue; }
                    //If is a normal character
                    customRegExp += charsAllowed[i];
                }
                customRegExp += "]+$";

                //If the string don't match with the custom regex, the string have unallowed special chars, so is invalid
                if (typeof valueToValidate === "string" && valueToValidate != "")      //<- Only validate if is not empty
                    if (new RegExp(customRegExp, "g").test(valueToValidate) == false)
                        isInputValid = false;
            }

            //Check if match with the custom regex (if has provided a custom regex)
            if (this.hasProvidedCustomRegex == true && (new RegExp(this.providedCustomRegex)).test(valueToValidate) == false)
                isInputValid = false;
        }

        //============================================ If expected type is INT ============================================//
        if (this.expectedType == "INT") {
            //Get the input value
            //@ts-ignore
            var valueToValidate = this.inputElement?.value;

            //First, check if the value from input is a String
            if (typeof valueToValidate !== "string")
                isInputValid = false;
            //Check if is a valid number
            if (isNaN(valueToValidate) == true)
                isInputValid = false;
            //Check if have line break
            if (/\r|\n/g.test(valueToValidate) == true)
                isInputValid = false;
            //Try to parse float to check if is a real number
            var tryParseFloat = 0.0;
            try { tryParseFloat = parseFloat(valueToValidate); } catch (e) { isInputValid = false; }
            //Check if is a INTEGER number
            if (valueToValidate.includes(",") == true || valueToValidate.includes(".") == true)
                isInputValid = false;

            //Get the number converted to INT
            var valueConvertedToInt = parseInt(valueToValidate);

            //Check if is empty
            if (this.allowEmpty == false && valueToValidate == "")
                isInputValid = false;
            //Check if respects the min value
            if (this.minNumberValue != -9007199254740991 && valueConvertedToInt < this.minNumberValue)
                isInputValid = false;
            //Check if respects the max value
            if (this.maxNumberValue != 9007199254740991 && valueConvertedToInt > this.maxNumberValue)
                isInputValid = false;
            //Check if is zero
            if (this.allowNumberZero == false && valueConvertedToInt == 0)
                isInputValid = false;
            //Check if is negative
            if (this.allowNumberNegative == false && valueConvertedToInt < 0)
                isInputValid = false;
            //Check if is positive
            if (this.allowNumberPositive == false && valueConvertedToInt > 0)
                isInputValid = false;
        }

        //============================================ If expected type is FLOAT ============================================//
        if (this.expectedType == "FLOAT") {
            //Get the input value
            //@ts-ignore
            var valueToValidate = this.inputElement?.value;

            //First, check if the value from input is a String
            if (typeof valueToValidate !== "string")
                isInputValid = false;
            //Check if is a valid number
            if (isNaN(valueToValidate) == true)
                isInputValid = false;
            //Check if have line break
            if (/\r|\n/g.test(valueToValidate) == true)
                isInputValid = false;
            //Try to parse float to check if is a real number
            var tryParseFloat = 0.0;
            try { tryParseFloat = parseFloat(valueToValidate); } catch (e) { isInputValid = false; }

            //Get the number converted to FLOAT
            var valueConvertedToFloat = parseFloat(valueToValidate);

            //Check if is empty
            if (this.allowEmpty == false && valueToValidate == "")
                isInputValid = false;
            //Check if respects the min value
            if (this.minNumberValue != -9007199254740991 && valueConvertedToFloat < this.minNumberValue)
                isInputValid = false;
            //Check if respects the max value
            if (this.maxNumberValue != 9007199254740991 && valueConvertedToFloat > this.maxNumberValue)
                isInputValid = false;
            //Check if is zero
            if (this.allowNumberZero == false && valueConvertedToFloat == 0)
                isInputValid = false;
            //Check if is negative
            if (this.allowNumberNegative == false && valueConvertedToFloat < 0)
                isInputValid = false;
            //Check if is positive
            if (this.allowNumberPositive == false && valueConvertedToFloat > 0)
                isInputValid = false;
        }

        //============================================ If expected type is FILE ============================================//
        if (this.expectedType == "FILE") {
            //Get the input files
            //@ts-ignore
            var filesToValidate = this.inputElement?.files;

            //Check if is empty
            if (this.allowEmpty == false && filesToValidate.length == 0)
                isInputValid = false;
            //Check if respects the min files selection
            if (this.minFilesSelection != 0 && filesToValidate.length < this.minFilesSelection)
                isInputValid = false;
            //Check if respects the max files selection
            if (this.maxFilesSelection != 9007199254740991 && filesToValidate.length > this.maxFilesSelection)
                isInputValid = false;
            //Check if the files respects the files extension
            if (this.requiredFilesExtensions != "any") {
                //Prepare the array of allowed extensions
                var allowedExtensions = [];
                //Get the allowed extensions
                allowedExtensions = this.requiredFilesExtensions.split(",");

                //Check in all selected files if all respects the allowed extensions
                for (var i = 0; i < filesToValidate.length; i++) {
                    //If this file don't have extension, is invalid
                    if (filesToValidate[i].name.includes(".") == false) {
                        isInputValid = false;
                        break;
                    }

                    //Prepare the information
                    var thisFileHaveAllowedExtension = false;
                    //Check this file extension...
                    for (var y = 0; y < allowedExtensions.length; y++)
                        if (filesToValidate[i].name.split('.').pop().toLowerCase() == allowedExtensions[y].toLowerCase())
                            thisFileHaveAllowedExtension = true;
                    //If this file don't have a allowed extension, is invalid
                    if (thisFileHaveAllowedExtension == false)
                        isInputValid = false;
                }
            }
            //Check if the files respects the max size
            if (this.maxFilesSizeInKb != 0)
                for (var i = 0; i < filesToValidate.length; i++)
                    if ((filesToValidate[i].size / 1024) > this.maxFilesSizeInKb)
                        isInputValid = false;
        }

        //Return the response to the callback
        if (typeof this.onValidateCallback !== "undefined")
            this.onValidateCallback(isInputValid);
        //Register the response inside this object instance
        this.isAssociatedFieldValid = isInputValid;
    }

}
</script>