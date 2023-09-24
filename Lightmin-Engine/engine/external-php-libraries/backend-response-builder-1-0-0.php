<?php

/*
 * Backend Response Builder
 * 
 * This class is part of "Backend Response Builder" library created with the mission of simplify creation of responses in PHP backends
*/

/**
 * Backend Response Builder
 *
 * This class is part of "Backend Response Builder" library created with the mission of simplify creation of responses in PHP backends
 *
 * @copyright  2023 Marcos Tomaz
 * @license    https://github.com/marcos4503/backend-response-builder/blob/main/LICENSE   MIT
 */
class ResponseBuilder{

    //Cache variables
    private $canDeclare = true;

    //Private variables
    private $scriptStartTime = 0;
    private $headerMessage = "";

    //Declared elements
    private $allDeclaredVars = array();    //<- Store Variables names/type declared in ROOT. Also all Variables names/type declared inside declared CLASSES. This will only be used to register the existence (and type) of each Variable.
    private $declaredVariables = array();  //<- Store all Variables, Types and Values declared in ROOT. This will be converted to JSON on the end.
    private $declaredClasses = array();    //<- Store a original state of all Classes declared. This Classes are used to be instanced in new objects.

    //Instanced objects
    private $instancedObjects = array();   //<- Store all instanced Objects of Classes. This holds all Objects of all instantiated Classes.

    //Core methods

    public function __construct(){
        //Inform that the response must be showed formatted as JSON
        header("Content-Type: application/json");

        //Set-up this response builder object
        $this->scriptStartTime = microtime(true);
        $this->headerMessage = "noResponseHeaderDefined";
    }

    public function SetSuccessHeader(bool $isSuccess){
        //Set success header or not
        if($isSuccess == true)
            $this->headerMessage = "success";
        if($isSuccess == false)
            $this->headerMessage = "error";
    }

    public function SetCustomHeader(string $newHeader){
        //If the new custom header is invalid, cancel
        if($this->isCustomHeaderValid($newHeader) == false)
            return;

        //Set the new custom header
        $this->headerMessage = $newHeader;
    }

    public function BuildAndPrintTheResponseToClient(){
        //Prepare the response data
        $responseData = new stdClass();

        //Insert all declared variables in the response data, to be converted to JSON lately
        foreach($this->declaredVariables as $key => $item){
            //Get variable Name and Value
            $varName = $key;
            $varValue = $item;

            //Add to the response data
            $responseData->{$varName} = $varValue;
        }

        //Add the elapsed time info
        $responseData->processingTime = number_format(round((microtime(true) - $this->scriptStartTime), 14), 14);

        //Print the response
        echo($this->headerMessage."\n<br/>\n".json_encode($responseData, JSON_PRETTY_PRINT));
    }

    //Elements declaration methods

    public function DeclareStructuredClass(string $className){
        //===================================== VALIDATION PROCCESS =====================================//

        //Start the validation proccess...
        if($this->isElementsDeclarationAvailable($className) == false)
            return;
        if($this->isElementNameValid($className) == false)
            return;
        if($this->isElementAlreadyDeclared("ROOT", $className) == true)
            return;

        //=================================== DO THE DESIRED FUNCTION ===================================//

        //Add the new class to list of declared classes
        $this->declaredClasses[$className] = (new stdClass());

        //Register the new class created
        $this->allDeclaredVars[("ROOT_".$className)] = "CLASS";
    }

    public function DeclareVariablePrimitive(string $name, string $type){
        //Transfer the call to "DeclareVariablePrimitiveInClass()" informing ROOT as target class to declare!
        $this->DeclareVariablePrimitiveInClass("ROOT", $name, $type);
    }

    public function DeclareVariablePrimitiveInClass(string $targetClassName, string $name, string $type){
        //===================================== VALIDATION PROCCESS =====================================//

        //Start the validation proccess...
        if($this->isElementsDeclarationAvailable($name) == false)
            return;
        if($this->isElementNameValid($name) == false)
            return;
        if($this->isTheClassDeclared($targetClassName) == false)
            return;
        if($this->isElementAlreadyDeclared($targetClassName, $name) == true)
            return;
        if($this->isVariableTypeValid($name, $type) == false)
            return;

        //=================================== DO THE DESIRED FUNCTION ===================================//

        //Get the variable type, converted to upper case
        $variableType = strtoupper($type);

        //If is desired to declare the variable in the ROOT class
        if($targetClassName == "ROOT"){
            //Add the new variable to list
            if($variableType == "STRING")
                $this->declaredVariables[$name] = "";
            if($variableType == "INT")
                $this->declaredVariables[$name] = 0;
            if($variableType == "FLOAT")
                $this->declaredVariables[$name] = 0.0;
            if($variableType == "BOOL")
                $this->declaredVariables[$name] = false;
            if($variableType == "OBJECT")
                $this->declaredVariables[$name] = (new stdClass());

            //Register the new variable created
            $this->allDeclaredVars[("ROOT_".$name)] = $variableType;
        }

        //If is desired to declare the variable in a DECLARED CLASS
        if($targetClassName != "ROOT"){
            //Add the new variable to list
            if($variableType == "STRING")
                $this->declaredClasses[$targetClassName]->{$name} = "";
            if($variableType == "INT")
                $this->declaredClasses[$targetClassName]->{$name} = 0;
            if($variableType == "FLOAT")
                $this->declaredClasses[$targetClassName]->{$name} = 0.0;
            if($variableType == "BOOL")
                $this->declaredClasses[$targetClassName]->{$name} = false;
            if($variableType == "OBJECT")
                $this->declaredClasses[$targetClassName]->{$name} = (new stdClass());

            //Register the new variable created
            $this->allDeclaredVars[($targetClassName."_".$name)] = $variableType;
        }
    }

    public function DeclareVariableArray(string $name, string $type){
        //Transfer the call to "DeclareVariableArrayInClass()" informing ROOT as target class to declare!
        $this->DeclareVariableArrayInClass("ROOT", $name, $type);
    }

    public function DeclareVariableArrayInClass(string $targetClassName, string $name, string $type){
        //===================================== VALIDATION PROCCESS =====================================//

        //Start the validation proccess...
        if($this->isElementsDeclarationAvailable($name) == false)
            return;
        if($this->isElementNameValid($name) == false)
            return;
        if($this->isTheClassDeclared($targetClassName) == false)
            return;
        if($this->isElementAlreadyDeclared($targetClassName, $name) == true)
            return;
        if($this->isVariableTypeValid($name, $type) == false)
            return;

        //=================================== DO THE DESIRED FUNCTION ===================================//

        //Get the variable type, converted to upper case
        $variableType = strtoupper($type);

        //If is desired to declare the variable in the ROOT class
        if($targetClassName == "ROOT"){
            //Add the new variable to list
            $this->declaredVariables[$name] = array();

            //Register the new variable created
            $this->allDeclaredVars[("ROOT_".$name)] = ("ARRAY-".$variableType);
        }

        //If is desired to declare the variable in a DECLARED CLASS
        if($targetClassName != "ROOT"){
            //Add the new variable to list
            $this->declaredClasses[$targetClassName]->{$name} = array();

            //Register the new variable created
            $this->allDeclaredVars[($targetClassName."_".$name)] = ("ARRAY-".$variableType);
        }
    }

    //Objects instancing methods

    public function CreateNewObjectInstanceOfClass(string $targetClassName){
        //===================================== VALIDATION PROCCESS =====================================//

        //Start the validation proccess...
        if($this->isTheClassDeclared($targetClassName) == false)
            return NULL;

        //Inform that the edition was started and cannot declare more elements
        $this->canDeclare = false;

        //=================================== DO THE DESIRED FUNCTION ===================================//

        //Get the future reference to the object instance
        $instanceRef = ($targetClassName."#".count($this->instancedObjects));

        //Create a new object instance of the class
        $this->instancedObjects[$instanceRef] = clone $this->declaredClasses[$targetClassName];

        //Return the index to instancied class object
        return $instanceRef;
    }

    //Variable updating methods

    public function SetVariablePrimitiveValue(string $variableName, $newValue){
        //Transfer the call to "SetVariablePrimitiveValueInInstancedObject()" informing ROOT as target object!
        $this->SetVariablePrimitiveValueInInstancedObject("ROOT", $variableName, $newValue);
    }

    public function SetVariablePrimitiveValueInInstancedObject(string $objectReferenceOfVariable, string $variableName, $newValue){
        //===================================== VALIDATION PROCCESS =====================================//

        //Start the validation proccess...
        if($this->isObjectReferenceExistent($objectReferenceOfVariable) == false)
            return;
        if($this->isVariablePrimitiveExistent($objectReferenceOfVariable, $variableName) == false)
            return;
        if($this->isNewValueOfSameTypeOfVariablePrimitive($objectReferenceOfVariable, $variableName, $newValue) == false)
            return;

        //Inform that the edition was started and cannot declare more elements
        $this->canDeclare = false;

        //=================================== DO THE DESIRED FUNCTION ===================================//

        //If is desired to update a variable of the ROOT
        if($objectReferenceOfVariable == "ROOT")
            $this->declaredVariables[$variableName] = $newValue;

        //If is desired to update a variable of other instanced OBJECT
        if($objectReferenceOfVariable != "ROOT")
            $this->instancedObjects[$objectReferenceOfVariable]->{$variableName} = $newValue;
    }

    public function AddItemToVariableArray(string $variableName, $valueToAdd){
        //Transfer the call to "AddItemToVariableArrayInInstancedObject()" informing ROOT as target object!
        $this->AddItemToVariableArrayInInstancedObject("ROOT", $variableName, $valueToAdd);
    }

    public function AddItemToVariableArrayInInstancedObject(string $objectReferenceOfVariable, string $variableName, $valueToAdd){
        //===================================== VALIDATION PROCCESS =====================================//

        //Start the validation proccess...
        if($this->isObjectReferenceExistent($objectReferenceOfVariable) == false)
            return;
        if($this->isVariablePrimitiveExistent($objectReferenceOfVariable, $variableName) == false)
            return;
        if($this->isNewItemOfSameTypeOfVariableArray($objectReferenceOfVariable, $variableName, $valueToAdd) == false)
            return;

        //Inform that the edition was started and cannot declare more elements
        $this->canDeclare = false;

        //=================================== DO THE DESIRED FUNCTION ===================================//

        //If is desired to update a variable of the ROOT
        if($objectReferenceOfVariable == "ROOT")
            array_push($this->declaredVariables[$variableName], $valueToAdd);

        //If is desired to update a variable of other instanced OBJECT
        if($objectReferenceOfVariable != "ROOT")
            array_push($this->instancedObjects[$objectReferenceOfVariable]->{$variableName}, $valueToAdd);
    }

    //Instanced Objects linking methods

    public function LinkObjectToVariablePrimitive(string $variableName, string $objectReferenceToLink){
        //Transfer the call to "LinkObjectToVariablePrimitiveInInstancedObject()" informing ROOT as target object!
        $this->LinkObjectToVariablePrimitiveInInstancedObject("ROOT", $variableName, $objectReferenceToLink);
    }

    public function LinkObjectToVariablePrimitiveInInstancedObject(string $objectReferenceOfVariable, string $variableName, string $objectReferenceToLink){
        //===================================== VALIDATION PROCCESS =====================================//

        //Start the validation proccess...
        if($this->isObjectReferenceExistent($objectReferenceOfVariable) == false)
            return;
        if($this->isVariablePrimitiveExistent($objectReferenceOfVariable, $variableName) == false)
            return;
        if($this->isVariablePrimitiveOfTypeObject($objectReferenceOfVariable, $variableName) == false)
            return;
        if($this->isObjectReferenceExistent($objectReferenceToLink) == false)
            return;

        //Inform that the edition was started and cannot declare more elements
        $this->canDeclare = false;

        //=================================== DO THE DESIRED FUNCTION ===================================//

        //If is desired to link the instanced object to a variable of the ROOT
        if($objectReferenceOfVariable == "ROOT")
            $this->declaredVariables[$variableName] = &$this->instancedObjects[$objectReferenceToLink];

        //If is desired to link the instanced object to a variable of other instanced OBJECT
        if($objectReferenceOfVariable != "ROOT")
            $this->instancedObjects[$objectReferenceOfVariable]->{$variableName} = &$this->instancedObjects[$objectReferenceToLink];
    }

    public function AddLinkOfObjectToVariableArray(string $variableName, string $objectReferenceToAddLink){
        //Transfer the call to "AddLinkOfObjectToVariableArrayInInstancedObject()" informing ROOT as target object!
        $this->AddLinkOfObjectToVariableArrayInInstancedObject("ROOT", $variableName, $objectReferenceToAddLink);
    }

    public function AddLinkOfObjectToVariableArrayInInstancedObject(string $objectReferenceOfVariable, string $variableName, string $objectReferenceToAddLink){
        //===================================== VALIDATION PROCCESS =====================================//

        //Start the validation proccess...
        if($this->isObjectReferenceExistent($objectReferenceOfVariable) == false)
            return;
        if($this->isVariablePrimitiveExistent($objectReferenceOfVariable, $variableName) == false)
            return;
        if($this->isVariableArrayOfTypeObject($objectReferenceOfVariable, $variableName) == false)
            return;
        if($this->isObjectReferenceExistent($objectReferenceToAddLink) == false)
            return;
        
        //Inform that the edition was started and cannot declare more elements
        $this->canDeclare = false;

        //=================================== DO THE DESIRED FUNCTION ===================================//

        //If is desired to add link of the instanced object to a variable of the ROOT
        if($objectReferenceOfVariable == "ROOT"){
            $size = count($this->declaredVariables[$variableName]);
            array_push($this->declaredVariables[$variableName], 0);
            $this->declaredVariables[$variableName][$size] = &$this->instancedObjects[$objectReferenceToAddLink];
        }

        //If is desired to add link of the instanced object to a variable of other instanced OBJECT
        if($objectReferenceOfVariable != "ROOT"){
            $size = count($this->instancedObjects[$objectReferenceOfVariable]->{$variableName});
            array_push($this->instancedObjects[$objectReferenceOfVariable]->{$variableName}, 0);
            $this->instancedObjects[$objectReferenceOfVariable]->{$variableName}[$size] = &$this->instancedObjects[$objectReferenceToAddLink];
        }
    }

    //Auxiliar methods

    private function PrintErrorMessage(string $errorMessage){
        //Get the line of initial caller
        $debugBackTrace = debug_backtrace();
        $initialCallerTrace = end($debugBackTrace);
        $callingOnLine = $initialCallerTrace['line'];
        
        //Print the message
        echo("\nBackendResponseBuilder Error: ".$errorMessage." (line ".$callingOnLine.")\n\n");
    }

    private function isCustomHeaderValid(string $newHeader){
        //Prepare the response
        $result = true;

        //Check the custom header validation
        if($newHeader == "" || strlen($newHeader) > 128){
            $this->PrintErrorMessage("Could not set custom header. The new header cannot be empty and must be a maximum of 128 characters.");
            $result = false;
        }

        //Return the response
        return $result;
    }

    private function isElementsDeclarationAvailable(string $elementName){
        //Prepare the response
        $result = true;

        //Check if can declare elements
        if($this->canDeclare == false){
            $this->PrintErrorMessage("Could not declare element \"".$elementName."\". After starting to edit the Response, it is no longer possible to declare new classes, arrays or variables. It is recommended that you only declare new items at the beginning of your script.");
            $result = false;
        }
            
        //Return the response
        return $result;
    }

    private function isElementNameValid(string $elementName){
        //Prepare the response
        $result = true;

        //Check the name validation
        if($elementName == "" || strlen($elementName) > 64 || preg_match("/^[a-zA-Z_][a-zA-Z0-9_]*$/", $elementName) == false){
            $this->PrintErrorMessage("Could not declare element \"".$elementName."\". The name is invalid. The name cannot be empty, and can only contain a maximum of 64 characters. In addition, the name can also only contain letters, numbers and underscore. The first character of the name can only be a letter or an underscore.");
            $result = false;
        }

        //Return the response
        return $result;
    }

    private function isTheClassDeclared(string $classToCheck){
        //Prepare the response
        $result = false;

        //If is not the ROOT class, check if the class is declared
        if($classToCheck != "ROOT" && array_key_exists($classToCheck, $this->declaredClasses) == true)
            $result = true;
        //If is the ROOT class, inform that is declared
        if($classToCheck == "ROOT")
            $result = true;

        //If the class is not declared, inform the error
        if($result == false)
            $this->PrintErrorMessage("Unable to perform the operation. Class \"".$classToCheck."\" does not exist and has never been declared!");

        //Return the response
        return $result;
    }

    private function isElementAlreadyDeclared(string $targetClassToCheck, string $elementName){
        //Prepare the response
        $result = false;

        //Check if the element is already declared inside a ROOT or inside a DECLARED CLASS
        if(array_key_exists(($targetClassToCheck."_".$elementName), $this->allDeclaredVars) == true){
            $this->PrintErrorMessage("Could not declare element \"".$elementName."\". This element name has already been declared!");
            $result = true;
        }

        //Return the response
        return $result;
    }

    private function isVariableTypeValid(string $variableName, string $variableType){
        //Prepare the response
        $result = true;

        //Check if the variable type is invalid
        if(strtoupper($variableType) != "STRING" && strtoupper($variableType) != "INT" && strtoupper($variableType) != "FLOAT" && strtoupper($variableType) != "BOOL" && strtoupper($variableType) != "OBJECT"){
            $this->PrintErrorMessage("Could not declare variable \"".$variableName."\". It is only possible to declare variables of type STRING, INT, FLOAT, BOOL or OBJECT.");
            $result = false;
        }

        //Return the response
        return $result;
    }

    private function isObjectReferenceExistent(string $objectReference){
        //Prepare the response
        $result = false;

        //If is not the ROOT, check if the instanced object is existent
        if($objectReference != "ROOT" && array_key_exists($objectReference, $this->instancedObjects) == true)
            $result = true;
        //If is the ROOT, inform that exists
        if($objectReference == "ROOT")
            $result = true;

        //If the reference is to a inexistent object, inform the error
        if($result == false)
            $this->PrintErrorMessage("Unable to perform the operation. The reference leads to a non-existent instantiated object!");

        //Return the response
        return $result;
    }

    private function isVariablePrimitiveExistent(string $objectReference, string $variableName){
        //Prepare the response
        $result = true;

        //If is desired to check a variable in a instanced OBJECT
        if($objectReference != "ROOT" && property_exists($this->instancedObjects[$objectReference], $variableName) == false){
            $this->PrintErrorMessage("Unable to perform the operation. The variable to receive the link does not exist!");
            $result = false;
        }

        //If is desired to check a variable in the ROOT
        if($objectReference == "ROOT" && array_key_exists($variableName, $this->declaredVariables) == false){
            $this->PrintErrorMessage("Unable to perform the operation. The variable to receive the link does not exist!");
            $result = false;
        }

        //Return the response
        return $result;
    }

    private function isVariablePrimitiveOfTypeObject(string $objectReference, string $variableName){
        //Prepare the response
        $result = true;

        //If is desired to check a variable in a instanced OBJECT
        if($objectReference != "ROOT" && $this->allDeclaredVars[(explode("#", $objectReference)[0]."_".$variableName)] != "OBJECT"){
            $this->PrintErrorMessage("Unable to perform the operation. The variable to receive the link is not of type OBJECT!");
            $result = false;
        }

        //If is desired to check a variable in the ROOT
        if($objectReference == "ROOT" && $this->allDeclaredVars[("ROOT_".$variableName)] != "OBJECT"){
            $this->PrintErrorMessage("Unable to perform the operation. The variable to receive the link is not of type OBJECT!");
            $result = false;
        }

        //Return the response
        return $result;
    }

    private function isVariableArrayOfTypeObject(string $objectReference, string $variableName){
        //Prepare the response
        $result = true;

        //If is desired to check a variable in a instanced OBJECT
        if($objectReference != "ROOT" && $this->allDeclaredVars[(explode("#", $objectReference)[0]."_".$variableName)] != "ARRAY-OBJECT"){
            $this->PrintErrorMessage("Unable to perform the operation. The variable to receive the link is not of type array OBJECT!");
            $result = false;
        }

        //If is desired to check a variable in the ROOT
        if($objectReference == "ROOT" && $this->allDeclaredVars[("ROOT_".$variableName)] != "ARRAY-OBJECT"){
            $this->PrintErrorMessage("Unable to perform the operation. The variable to receive the link is not of type array OBJECT!");
            $result = false;
        }

        //Return the response
        return $result;
    }

    private function isNewValueOfSameTypeOfVariablePrimitive(string $objectReference, string $variableName, $newValue){
        //Prepare the response
        $result = true;

        //If the new value is null, inform that is invalid
        if($newValue === NULL){
            $this->PrintErrorMessage("Unable to update variable value. The new value cannot be NULL!");
            $result = false;
        }

        //If the new value is not null...
        if($newValue !== NULL){
            //Prepare the type of the variable...
            $variableType = "UNKNOWN";

            //Get the type of the variable
            if($objectReference != "ROOT")
                $variableType = $this->allDeclaredVars[(explode("#", $objectReference)[0]."_".$variableName)];
            if($objectReference == "ROOT")
                $variableType = $this->allDeclaredVars[("ROOT_".$variableName)];

            //Prepare the type of new value...
            $newValueType = "NONE";

            //Get the type of the new value
            if(is_string($newValue) == true)
                $newValueType = "STRING";
            if(is_int($newValue) == true)
                $newValueType = "INT";
            if(is_float($newValue) == true)
                $newValueType = "FLOAT";
            if(is_bool($newValue) == true)
                $newValueType = "BOOL";

            //Prepare the result of the comparation...
            $comparationResult = false;
            //Do the comparation
            if($variableType == "STRING")
                if($newValueType == "STRING")
                    $comparationResult = true;
            if($variableType == "INT")
                if($newValueType == "INT")
                    $comparationResult = true;
            if($variableType == "FLOAT")
                if($newValueType == "INT" || $newValueType == "FLOAT")
                    $comparationResult = true;
            if($variableType == "BOOL")
                if($newValueType == "BOOL")
                    $comparationResult = true;

            //If the variable type and new value type is not same, inform error
            if($comparationResult == false){
                $this->PrintErrorMessage("Unable to update variable value. The new value is of a different type than the variable. The variable is of type \"".$variableType."\" and the value is of type \"".$newValueType."\".");
                $result = false;
            }
        }

        //Return the response
        return $result;
    }

    private function isNewItemOfSameTypeOfVariableArray(string $objectReference, string $variableName, $newItem){
        //Prepare the response
        $result = true;

        //If the new value is null, inform that is invalid
        if($newItem === NULL){
            $this->PrintErrorMessage("Unable to add item to variable array. The new item cannot be NULL!");
            $result = false;
        }

        //If the new value is not null...
        if($newItem !== NULL){
            //Prepare the type of the variable...
            $variableType = "UNKNOWN";

            //Get the type of the variable
            if($objectReference != "ROOT")
                $variableType = $this->allDeclaredVars[(explode("#", $objectReference)[0]."_".$variableName)];
            if($objectReference == "ROOT")
                $variableType = $this->allDeclaredVars[("ROOT_".$variableName)];

            //Prepare the type of new value...
            $newItemType = "NONE";

            //Get the type of the new value
            if(is_string($newItem) == true)
                $newItemType = "ARRAY-STRING";
            if(is_int($newItem) == true)
                $newItemType = "ARRAY-INT";
            if(is_float($newItem) == true)
                $newItemType = "ARRAY-FLOAT";
            if(is_bool($newItem) == true)
                $newItemType = "ARRAY-BOOL";

            //Prepare the result of the comparation...
            $comparationResult = false;
            //Do the comparation
            if($variableType == "ARRAY-STRING")
                if($newItemType == "ARRAY-STRING")
                    $comparationResult = true;
            if($variableType == "ARRAY-INT")
                if($newItemType == "ARRAY-INT")
                    $comparationResult = true;
            if($variableType == "ARRAY-FLOAT")
                if($newItemType == "ARRAY-INT" || $newItemType == "ARRAY-FLOAT")
                    $comparationResult = true;
            if($variableType == "ARRAY-BOOL")
                if($newItemType == "ARRAY-BOOL")
                    $comparationResult = true;

            //If the variable type and new item type is not same, inform error
            if($comparationResult == false){
                $this->PrintErrorMessage("Unable to add item to variable array. The new item is of a different type than the variable array. The array is of type \"".$variableType."\" and the item is of type \"".$newItemType."\".");
                $result = false;
            }
        }

        //Return the response
        return $result;
    }
}

?>