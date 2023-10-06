<?php /* This JavaScript file contains code related to the interaction and manipulation of Pieces in the Engine. */ ?>

<script>

class Pieces {

    //Public variables
    static loadedPiecesAndReferencesToJsonHtmlElementOfEach = [];  //<- The reference for all "style" tags containing Json/Html code for each loaded Piece is here in this array.

    //Public methods

    static InstantiatePieceAfter(name, instanceId, enabled, width, height, jsonVariables, targetWindowIdentifier, putAfterElement){
        //Repass the call to "ProcessPieceInstantiation()" to finish...
        if(enabled === true)
            Pieces.ProcessPieceInstantiation(name, instanceId, "true", width, height, jsonVariables, targetWindowIdentifier, putAfterElement);
        if(enabled === false)
            Pieces.ProcessPieceInstantiation(name, instanceId, "false", width, height, jsonVariables, targetWindowIdentifier, putAfterElement);
        if(enabled !== true && enabled !== false)
            Pieces.ProcessPieceInstantiation(name, instanceId, enabled, width, height, jsonVariables, targetWindowIdentifier, putAfterElement);
    }

    static GetComponentInPieceInstance(targetWindowIdentifier, instanceId, componentId){
        //Prepare the result
        var result = null;

        //If target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to obtain Component from an instantiated Piece. A Window with \"" + targetWindowIdentifier + "\" identifier, does not exist.");
            return result;
        }
        //If the instance id don't exists in the window, cancel
        if(Pieces.isPieceExistentInsideWindow(targetWindowIdentifier, instanceId) == false){
            Common.SendLog("E", "Unable to obtain Component from an instantiated Piece. There is no Piece instantiated within Window \"" + targetWindowIdentifier + "\" using instance ID \"" + instanceId + "\".");
            return result;
        }

        //Try to get a reference to the root of instantiated Piece
        var instantiatedPiece = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];
        //If found the instantiated Piece, continues...
        if(instantiatedPiece != null && instantiatedPiece != undefined){
            //Try to find the Component inside the Piece instance, using the informed ID...
            var componentFound = instantiatedPiece.querySelector("[component=\"" + componentId + "\"]");

            //If found, inform the Component found...
            if(componentFound != null)
                result = componentFound;
        }

        //Return the result
        return result;
    }

    static isPieceEnabled(targetWindowIdentifier, instanceId){ }

    static SetPieceEnabled(targetWindowIdentifier, instanceId, enabled){ }

    static GetPieceSize(targetWindowIdentifier, instanceId){ }

    static SetPieceSize(targetWindowIdentifier, instanceId, newWidth, newHeight){ }

    static DestroyPiece(targetWindowIdentifier, instanceId){ }

    static GetInstanceIdOfAllInstantiatedPiecesInWindow(targetWindowIdentifier){
        //Prepare the list of IDs of instantiated pieces in the window
        var piecesIds = [];
        
        //If the target window identifier, is not found, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "It was not possible to obtain the IDs of the Pieces instantiated in Window \"" + targetWindowIdentifier + "\". This Window does not exist.");
            return piecesIds;
        }

        //Build the list of instantiateds Pieces IDs..
        for (var piid in Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs)
            piecesIds.push(piid);

        //Return the result...
        return piecesIds;
    }

    static isPieceExistentInsideWindow(targetWindowIdentifier, instanceId){
        //Prepare the result
        var result = false;

        //If the target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "It was not possible to verify the existence of the instance of Piece \"" + instanceId + "\", within Window \"" + targetWindowIdentifier + "\". The informed Window does not exist.");
            return result;
        }

        //If the informed instance id of a Piece exists, inform
        if(Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId] !== undefined)
            result = true;

        //Return the result
        return result;
    }

    //Auxiliar methods

    static ProcessPageHtmlCodeAndInstantiateAllPieces(targetWindowIdentifier, targetWindowElementReference){
        //Get all "le.piece.instantiate" tags
        var pieceTags = targetWindowElementReference.getElementsByTagName("le.piece.instantiate");

        //Process each piece instantiation request
        for(var i = 0; i < pieceTags.length; i++){
            //Get piece instance request information...
            var name = pieceTags[i].getAttribute("name");
            var instanceId = pieceTags[i].getAttribute("piid");
            var enabled = pieceTags[i].getAttribute("enabled");
            var width = pieceTags[i].getAttribute("width");
            var height = pieceTags[i].getAttribute("height");
            var jsonVariables = pieceTags[i].innerHTML;

            //Process this piece instantiation...
            Pieces.ProcessPieceInstantiation(name, instanceId, enabled, width, height, jsonVariables, targetWindowIdentifier, pieceTags[i]);
        }

        //Remove all useless "le.piece.instantiate" tags...
        for(var i = (pieceTags.length - 1); i >= 0; i--)
            pieceTags[i].remove();
    }

    static AddDivOfErrorMessageAfterElement(errorMessage, width, height, putAfterElement){
        //Prepare the new element
        var pieceTag = document.createElement("div");
        pieceTag.setAttribute("style", "display: block; width: " + width + "; height: " + height + "; background-color: #850000; color: #f2f2f2; text-align: center; padding-top: 8px; padding-bottom: 8px; border-radius: 8px;");
        pieceTag.innerHTML = ("<b>Piece Instancing Error</b>: " + errorMessage);

        //Add it to DOM
        putAfterElement.parentNode.insertBefore(pieceTag, putAfterElement.nextSibling);
    }

    static ProcessPieceInstantiation(name, instanceId, enabled, width, height, jsonVariables, windowIdentifier, putAfterElement){
        //If some of values is null, cancel the request...
        if(name == null || instanceId == null || enabled == null || width == null || height == null || jsonVariables == null || windowIdentifier == null || putAfterElement == null){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". All information, parameters and attributes must be provided correctly to be able to instantiate a Piece.");
            return;
        }
        //If the target window not exists, cancel
        if(Windows.isWindowExistent(windowIdentifier) == false){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The Window identifier leads to a non-existing Window. Pieces can only be instantiated within Pages, on Windows.");
            return;
        }
        //If the element to put after, is not a element, cancel
        if(putAfterElement.tagName === undefined){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The element that the Piece will be placed after is not valid.");
            return;
        }
        //If the element to put after, is not child of the window, cancel
        if(Windows.existantWindowsInClientAndScreens[windowIdentifier].windowElementRef.contains(putAfterElement) == false){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The element that the Piece will be placed after, is not child of Window linked to the Window identifier.");
            return;
        }
        //If have a JSON code for the variables, and is invalid, cancel
        var isValidJson = true;
        try { var jsonTest = JSON.parse(jsonVariables); } catch(e){ isValidJson = false; }
        if(isValidJson == false){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The JSON code for the variables has syntax errors.");
            Pieces.AddDivOfErrorMessageAfterElement(
                "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The JSON code for the variables has syntax errors.",
                width, height, putAfterElement);
            return;
        }
        //If the height, is invalid, cancel
        if(height != "auto"){
            var isPercent = true;
            if((/[0-9]*%/).test(height) == false)
                isPercent = false;
            var isPixels = true;
            if((/[0-9]*px/).test(height) == false)
                isPixels = false;
            if(isPercent == false && isPixels == false){
                Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The height of the Piece must be \"auto\", \"X%\" or \"Xpx\".");
                Pieces.AddDivOfErrorMessageAfterElement(
                    "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The height of the Piece must be \"auto\", \"X%\" or \"Xpx\".",
                    width, height, putAfterElement);
                return;
            }
        }
        //If the width, is invalid, cancel
        if(width != "auto"){
            var isPercent = true;
            if((/[0-9]*%/).test(width) == false)
                isPercent = false;
            var isPixels = true;
            if((/[0-9]*px/).test(width) == false)
                isPixels = false;
            if(isPercent == false && isPixels == false){
                Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The width of the Piece must be \"auto\", \"X%\" or \"Xpx\".");
                Pieces.AddDivOfErrorMessageAfterElement(
                    "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The width of the Piece must be \"auto\", \"X%\" or \"Xpx\".",
                    width, height, putAfterElement);
                return;
            }
        }
        //If the enabled value, is not a bool, cancel
        if(enabled !== "true" && enabled !== "false"){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The \"enabled\" value needs to be \"true\" or \"false\" only.");
            Pieces.AddDivOfErrorMessageAfterElement(
                    "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The \"enabled\" value needs to be \"true\" or \"false\" only.",
                    width, height, putAfterElement);
            return;
        }
        //If instance ID is not string, or is empty, cancel
        if(typeof instanceId !== 'string' || instanceId == ""){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". You are required to provide an ID for the Piece instance. This ID needs to be a \"string\".");
            Pieces.AddDivOfErrorMessageAfterElement(
                    "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". You are required to provide an ID for the Piece instance. This ID needs to be a \"string\".",
                    width, height, putAfterElement);
            return;
        }
        //If name is not string, or is empty, cancel
        if(typeof name !== 'string' || name == ""){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". You must provide the Piece Name to be instantiated. This must be a \"string\" and reference an existing Piece.");
            Pieces.AddDivOfErrorMessageAfterElement(
                    "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". You must provide the Piece Name to be instantiated. This must be a \"string\" and reference an existing Piece.",
                    width, height, putAfterElement);
            return;
        }
        //If the requested instance id, already exists in this Window, cancel
        if(Pieces.GetInstanceIdOfAllInstantiatedPiecesInWindow(windowIdentifier).indexOf(instanceId) > -1){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The defined ID \"" + instanceId + "\" is already being used by another Piece on this Page, within this Window.");
            Pieces.AddDivOfErrorMessageAfterElement(
                    "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The defined ID \"" + instanceId + "\" is already being used by another Piece on this Page, within this Window.",
                    width, height, putAfterElement);
            return;
        }
        //If the requested piece, to be rendered, not exists, cancel
        if(Pieces.loadedPiecesAndReferencesToJsonHtmlElementOfEach[name] === undefined){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The requested Piece does not exist on the Website, or has not been loaded.");
            Pieces.AddDivOfErrorMessageAfterElement(
                    "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The requested Piece does not exist on the Website, or has not been loaded.",
                    width, height, putAfterElement);
            return;
        }



        //Load the Piece HTML and JSON, from XML cache
        var parsedXmlRootNode = (new DOMParser()).parseFromString(Pieces.loadedPiecesAndReferencesToJsonHtmlElementOfEach[name].innerHTML, "text/xml").documentElement;
        var pieceJsonObj = JSON.parse(parsedXmlRootNode.getElementsByTagName("variables")[0].innerHTML);
        var pieceHtmlCode = parsedXmlRootNode.getElementsByTagName("code")[0].innerHTML;

        //Get defined values for this Piece instantiation, in obj form
        var definedValuesJsonObj = JSON.parse(jsonVariables);
        var definedValuesJsonKeys = Object.keys(definedValuesJsonObj);

        //For each defined value in the Piece instantiation, update it in the Piece JSON obj
        for(var i = 0; i < definedValuesJsonKeys.length; i++)
            if(pieceJsonObj.hasOwnProperty(definedValuesJsonKeys[i]) == true)
                pieceJsonObj[definedValuesJsonKeys[i]] = definedValuesJsonObj[definedValuesJsonKeys[i]];

        //Replace all variables in the HTML code to use the piece JSON values
        var declaredVariablesJsonKeys = Object.keys(pieceJsonObj);
        for(var i = 0; i < declaredVariablesJsonKeys.length; i++)
            pieceHtmlCode = pieceHtmlCode.replaceAll(("__" + declaredVariablesJsonKeys[i] + "__"), pieceJsonObj[declaredVariablesJsonKeys[i]]);

        //Create the root DIV element of this Piece instance
        var pieceRootTag = document.createElement("div");
        pieceRootTag.setAttribute("piece", name);
        pieceRootTag.setAttribute("piid", instanceId);
        if(enabled === "true")
            pieceRootTag.style.display = "block";
        if(enabled === "false")
            pieceRootTag.style.display = "none";
        pieceRootTag.style.width = width;
        pieceRootTag.style.height = height;
        pieceRootTag.innerHTML = pieceHtmlCode;

        //Add the root DIV element in the DOM...
        putAfterElement.parentNode.insertBefore(pieceRootTag, putAfterElement.nextSibling);

        //Add this instantiated Piece ID in the list of instantiated Pieces of Window... (this list is cleared on Page unload)
        Windows.existantWindowsInClientAndScreens[windowIdentifier].instantiatedPiecesIdsAndRefs[instanceId] = pieceRootTag;

        //Try to run a possible "OnInstantiate" method of the Piece JS code...
        try{ eval("Piece_" + name + ".OnInstantiate(\"" + windowIdentifier + "\", \"" + instanceId + "\");"); } catch(e){  };
    }

}

</script>