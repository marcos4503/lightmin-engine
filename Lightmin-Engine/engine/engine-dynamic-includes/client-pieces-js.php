<?php /* This JavaScript file contains code related to the interaction and manipulation of Pieces in the Engine. */ ?>

<script>

class Pieces {

    //Public variables
    static loadedPiecesAndReferencesToJsonHtmlElementOfEach = [];  //<- The reference for all "style" tags containing Json/Html code for each loaded Piece is here in this array.

    //Public classes

    static IndividualStorage = class {
        
        //Public variables
        static individualStorage = [];

        //Public methods

        static AllocateForPieceInstance(windowIdentifier, instanceId){
            //If the target window identifier, is not found, cancel
            if(Windows.isWindowExistent(windowIdentifier) == false){
                Common.SendLog("E", "Unable to allocate space in Pieces Individual Storage in Window \"" + windowIdentifier + "\". This Window does not exist.");
                return;
            }
            //If the instance id don't exists in the window, cancel
            if(Pieces.isPieceExistentInsideWindow(windowIdentifier, instanceId) == false){
                Common.SendLog("E", "Unable to allocate space in Pieces Individual Storage. There is no Piece instantiated within Window \"" + windowIdentifier + "\" using instance ID \"" + instanceId + "\".");
                return;
            }
            //If the space is already allocated, cancel
            if(Pieces.IndividualStorage.isSpaceAllocatedForPieceInstance(windowIdentifier, instanceId) == true){
                Common.SendLog("E", "Unable to allocate space in Pieces Individual Storage. The Piece Instance ID \"" + instanceId + "\" already has a space allocated for it in the Pieces Individual Storage.");
                return;
            }

            //Allocate space for this piece
            Pieces.IndividualStorage.individualStorage[windowIdentifier].allocationsForPiecesInstances[instanceId] = [];
        }

        static isSpaceAllocatedForPieceInstance(windowIdentifier, instanceId){
            //Prepare to return
            var result = false;

            //If the target window identifier, is not found, cancel
            if(Windows.isWindowExistent(windowIdentifier) == false){
                Common.SendLog("E", "Unable to check space in Pieces Individual Storage in Window \"" + windowIdentifier + "\". This Window does not exist.");
                return result;
            }

            //If have space allocated for piece, inform it
            if(Pieces.IndividualStorage.individualStorage[windowIdentifier].allocationsForPiecesInstances.hasOwnProperty(instanceId) == true)
                result = true;

            //Return the result
            return result;
        }

        static StoreInPieceInstance(windowIdentifier, instanceId, slotKey, valueToStore){
            //If the target window identifier, is not found, cancel
            if(Windows.isWindowExistent(windowIdentifier) == false){
                Common.SendLog("E", "Unable to store value in Pieces Individual Storage in Window \"" + windowIdentifier + "\". This Window does not exist.");
                return;
            }
            //If the instance id don't exists in the window, cancel
            if(Pieces.isPieceExistentInsideWindow(windowIdentifier, instanceId) == false){
                Common.SendLog("E", "Unable to store value in Pieces Individual Storage. There is no Piece instantiated within Window \"" + windowIdentifier + "\" using instance ID \"" + instanceId + "\".");
                return;
            }
            //If slotKey is not string, or is empty, cancel
            if(typeof slotKey !== 'string' || slotKey == ""){
                Common.SendLog("E", "Unable to store value in Pieces Individual Storage. Please provide a valid Slot Key of type String.");
                return;
            }
            //If slotKey contains not valid characters, cancel
            if((/^[a-zA-Z]\w+$/).test(slotKey) == false){
                Common.SendLog("E", "Unable to store value in Pieces Individual Storage. The Slot Key must be a valid Variable name. It must start with a letter and can only contain Letters, Numbers and Underscores.");
                return;
            }
            //If the space is not allocated, cancel
            if(Pieces.IndividualStorage.isSpaceAllocatedForPieceInstance(windowIdentifier, instanceId) == false){
                Common.SendLog("E", "Unable to store value in Pieces Individual Storage. The Piece Instance ID \"" + instanceId + "\" does not have an allocated space in Pieces Individual Storage.");
                return;
            }

            //Store the value in individual storage of piece
            Pieces.IndividualStorage.individualStorage[windowIdentifier].allocationsForPiecesInstances[instanceId][slotKey] = valueToStore;
        }

        static RetrieveOfPieceInstance(windowIdentifier, instanceId, slotKey){
            //Prepare the result
            var result = null;

            //If the target window identifier, is not found, cancel
            if(Windows.isWindowExistent(windowIdentifier) == false){
                Common.SendLog("E", "Unable to retrieve value in Pieces Individual Storage in Window \"" + windowIdentifier + "\". This Window does not exist.");
                return result;
            }
            //If the instance id don't exists in the window, cancel
            if(Pieces.isPieceExistentInsideWindow(windowIdentifier, instanceId) == false){
                Common.SendLog("E", "Unable to retrieve value in Pieces Individual Storage. There is no Piece instantiated within Window \"" + windowIdentifier + "\" using instance ID \"" + instanceId + "\".");
                return result;
            }
            //If slotKey is not string, or is empty, cancel
            if(typeof slotKey !== 'string' || slotKey == ""){
                Common.SendLog("E", "Unable to retrieve value in Pieces Individual Storage. Please provide a valid Slot Key of type String.");
                return result;
            }
            //If slotKey contains not valid characters, cancel
            if((/^[a-zA-Z]\w+$/).test(slotKey) == false){
                Common.SendLog("E", "Unable to retrieve value in Pieces Individual Storage. The Slot Key must be a valid Variable name. It must start with a letter and can only contain Letters, Numbers and Underscores.");
                return result;
            }
            //If the space is not allocated, cancel
            if(Pieces.IndividualStorage.isSpaceAllocatedForPieceInstance(windowIdentifier, instanceId) == false){
                Common.SendLog("E", "Unable to retrieve value in Pieces Individual Storage. The Piece Instance ID \"" + instanceId + "\" does not have an allocated space in Pieces Individual Storage.");
                return result;
            }

            //Retrieve the value from individual storage of piece
            if(Pieces.IndividualStorage.individualStorage[windowIdentifier].allocationsForPiecesInstances[instanceId].hasOwnProperty(slotKey) == true)
                result = Pieces.IndividualStorage.individualStorage[windowIdentifier].allocationsForPiecesInstances[instanceId][slotKey];

            //Return the result
            return result;
        }

        static DisposeOfPieceInstance(windowIdentifier, instanceId){
            //If the target window identifier, is not found, cancel
            if(Windows.isWindowExistent(windowIdentifier) == false){
                Common.SendLog("E", "Unable to dispose space in Pieces Individual Storage in Window \"" + windowIdentifier + "\". This Window does not exist.");
                return;
            }
            //If the space is already disposed, cancel
            if(Pieces.IndividualStorage.isSpaceAllocatedForPieceInstance(windowIdentifier, instanceId) == false){
                Common.SendLog("E", "Unable to dispose space in Pieces Individual Storage. The Piece Instance ID \"" + instanceId + "\" already NOT have a space allocated for it in the Pieces Individual Storage.");
                return;
            }

            //Dispose space for this piece
            delete Pieces.IndividualStorage.individualStorage[windowIdentifier].allocationsForPiecesInstances[instanceId];
        }

        static DebugPieceInstance(windowIdentifier, instanceId){
            //Debug individual storage of one piece instance
            console.log("Listing the space allocated the Piece \"" + windowIdentifier + "/" + instanceId + "\", in Pieces Individual Storage...");
            console.log(Pieces.IndividualStorage.individualStorage[windowIdentifier].allocationsForPiecesInstances[instanceId]);
            console.log("Done!");
        }

        static DebugAllPiecesInstances(){
            //Debug the all individual storage allocated now
            console.log("Listing all spaces allocated for Pieces now, and all data stored in Pieces Individual Storage...");
            console.log(Pieces.IndividualStorage.individualStorage);
            console.log("Done!");
        }
    }

    //Public methods

    static CheckByPieceTagsInWindowAndInterpretate(targetWindowIdentifier){
        //If the target window identifier, is not found, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to check for \"le.piece\" tags in Window \"" + targetWindowIdentifier + "\". This Window does not exist.");
            return;
        }

        //Detect the window root element reference
        var windowElementRef = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].windowElementRef;

        //Post-process the window content to instante all Pieces, if have...
        Pieces.ProcessPageHtmlCodeAndInstantiateAllPieces(targetWindowIdentifier, windowElementRef);
    }

    static InstantiatePieceAfter(name, instanceId, enabled, width, height, jsonVariables, targetWindowIdentifier, putAfterElement, autoRemoveAnchorElement){
        //Repass the call to "ProcessPieceInstantiation()" to finish...
        if(enabled === true)
            Pieces.ProcessPieceInstantiation(name, instanceId, "true", "default", width, height, jsonVariables, targetWindowIdentifier, putAfterElement);
        if(enabled === false)
            Pieces.ProcessPieceInstantiation(name, instanceId, "false", "default", width, height, jsonVariables, targetWindowIdentifier, putAfterElement);
        if(enabled !== true && enabled !== false)
            Pieces.ProcessPieceInstantiation(name, instanceId, enabled, "default", width, height, jsonVariables, targetWindowIdentifier, putAfterElement);

        //If desired, remove the anchor (element used as position reference)
        if (autoRemoveAnchorElement === true)
            putAfterElement.remove();
    }

    static InstantiatePieceInlineAfter(name, instanceId, enabled, jsonVariables, targetWindowIdentifier, putAfterElement, autoRemoveAnchorElement){
        //Repass the call to "ProcessPieceInstantiation()" to finish...
        if(enabled === true)
            Pieces.ProcessPieceInstantiation(name, instanceId, "true", "inline", "auto", "auto", jsonVariables, targetWindowIdentifier, putAfterElement);
        if(enabled === false)
            Pieces.ProcessPieceInstantiation(name, instanceId, "false", "inline", "auto", "auto", jsonVariables, targetWindowIdentifier, putAfterElement);
        if(enabled !== true && enabled !== false)
            Pieces.ProcessPieceInstantiation(name, instanceId, enabled, "inline", "auto", "auto", jsonVariables, targetWindowIdentifier, putAfterElement);

        //If desired, remove the anchor (element used as position reference)
        if (autoRemoveAnchorElement === true)
            putAfterElement.remove();
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
        if(Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId] !== undefined && Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId] !== null)
            result = true;

        //Return the result
        return result;
    }

    static DestroyPiece(targetWindowIdentifier, instanceId){
        //If target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to destroy Piece. A Window with \"" + targetWindowIdentifier + "\" identifier, does not exist.");
            return;
        }
        //If the instance id don't exists in the window, cancel
        if(Pieces.isPieceExistentInsideWindow(targetWindowIdentifier, instanceId) == false){
            Common.SendLog("E", "Unable to destroy Piece. There is no Piece instantiated within Window \"" + targetWindowIdentifier + "\" using instance ID \"" + instanceId + "\".");
            return;
        }

        //Try to get a reference to the root of instantiated Piece
        var instantiatedPiece = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];
        //If found the instantiated Piece, get the name of the instantiated Piece
        var instantiatedPieceName = "";
        if(instantiatedPiece != null && instantiatedPiece != undefined)
            instantiatedPieceName = instantiatedPiece.getAttribute("piece");
        //If found the instantiated Piece, continues...
        if(instantiatedPiece != null && instantiatedPiece != undefined)
            instantiatedPiece.remove();
        //Clear the reference of this destroyed Piece ID in the list of instantiated Pieces of Window...
        Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId] = null;
        delete Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];

        //Try to run a possible "OnDestroy" method of the Piece JS code...
        try{ eval("Piece_" + instantiatedPieceName + ".OnDestroy(\"" + targetWindowIdentifier + "\", \"" + instanceId + "\");"); } catch(e){  };
    }

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

    static GetPieceMode(targetWindowIdentifier, instanceId){
        //Prepare the result
        var result = "";

        //If target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to get Piece mode. A Window with \"" + targetWindowIdentifier + "\" identifier, does not exist.");
            return result;
        }
        //If the instance id don't exists in the window, cancel
        if(Pieces.isPieceExistentInsideWindow(targetWindowIdentifier, instanceId) == false){
            Common.SendLog("E", "Unable to get Piece mode. There is no Piece instantiated within Window \"" + targetWindowIdentifier + "\" using instance ID \"" + instanceId + "\".");
            return result;
        }

        //Try to get a reference to the root of instantiated Piece
        var instantiatedPiece = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];
        //If found the instantiated Piece, continues...
        if(instantiatedPiece != null && instantiatedPiece != undefined)
            result = instantiatedPiece.getAttribute("mode");

        //Return the result
        return result;
    }

    static isPieceEnabled(targetWindowIdentifier, instanceId){
        //Prepare the result
        var result = false;

        //If target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to check if Piece is enabled. A Window with \"" + targetWindowIdentifier + "\" identifier, does not exist.");
            return result;
        }
        //If the instance id don't exists in the window, cancel
        if(Pieces.isPieceExistentInsideWindow(targetWindowIdentifier, instanceId) == false){
            Common.SendLog("E", "Unable to check if Piece is enabled. There is no Piece instantiated within Window \"" + targetWindowIdentifier + "\" using instance ID \"" + instanceId + "\".");
            return result;
        }

        //Try to get a reference to the root of instantiated Piece
        var instantiatedPiece = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];
        //If found the instantiated Piece, continues...
        if(instantiatedPiece != null && instantiatedPiece != undefined)
            if(instantiatedPiece.style.display != "none")
                result = true;

        //Return the result
        return result;
    }

    static SetPieceEnabled(targetWindowIdentifier, instanceId, enabled){
        //If target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to set Piece enabled or disabled. A Window with \"" + targetWindowIdentifier + "\" identifier, does not exist.");
            return;
        }
        //If the instance id don't exists in the window, cancel
        if(Pieces.isPieceExistentInsideWindow(targetWindowIdentifier, instanceId) == false){
            Common.SendLog("E", "Unable to set Piece enabled or disabled. There is no Piece instantiated within Window \"" + targetWindowIdentifier + "\" using instance ID \"" + instanceId + "\".");
            return;
        }

        //Try to get a reference to the root of instantiated Piece
        var instantiatedPiece = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];
        //If found the instantiated Piece, continues...
        if(instantiatedPiece != null && instantiatedPiece != undefined){
            //Set Piece enabled or disabled...
            if(enabled == true){
                if(instantiatedPiece.getAttribute("mode") == "default")   //<- If the Piece is in "default" mode...
                    instantiatedPiece.style.display = "block";
                if(instantiatedPiece.getAttribute("mode") == "inline")    //<- If the Piece is in "inline" mode...
                    instantiatedPiece.style.display = "inline-block";
            }
            if(enabled == false)
                instantiatedPiece.style.display = "none";
        }
    }

    static GetPieceSize(targetWindowIdentifier, instanceId){
        //Prepare the result
        var result = { width: "", height: "" };

        //If target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to get Piece size. A Window with \"" + targetWindowIdentifier + "\" identifier, does not exist.");
            return result;
        }
        //If the instance id don't exists in the window, cancel
        if(Pieces.isPieceExistentInsideWindow(targetWindowIdentifier, instanceId) == false){
            Common.SendLog("E", "Unable to get Piece size. There is no Piece instantiated within Window \"" + targetWindowIdentifier + "\" using instance ID \"" + instanceId + "\".");
            return result;
        }

        //Try to get a reference to the root of instantiated Piece
        var instantiatedPiece = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];
        //If found the instantiated Piece, continues...
        if(instantiatedPiece != null && instantiatedPiece != undefined){
            //Get the Piece size...
            result.width = instantiatedPiece.style.width;
            result.height = instantiatedPiece.style.height;
        }

        //Return the result
        return result;
    }

    static SetPieceSize(targetWindowIdentifier, instanceId, newWidth, newHeight){
        //If target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to set Piece size. A Window with \"" + targetWindowIdentifier + "\" identifier, does not exist.");
            return;
        }
        //If the instance id don't exists in the window, cancel
        if(Pieces.isPieceExistentInsideWindow(targetWindowIdentifier, instanceId) == false){
            Common.SendLog("E", "Unable to set Piece size. There is no Piece instantiated within Window \"" + targetWindowIdentifier + "\" using instance ID \"" + instanceId + "\".");
            return;
        }

        //Try to get a reference to the root of instantiated Piece
        var instantiatedPiece = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];
        //If found the instantiated Piece, continues...
        if(instantiatedPiece != null && instantiatedPiece != undefined){
            //If the piece is not in "default" mode, cancel
            if(instantiatedPiece.getAttribute("mode") != "default"){
                Common.SendLog("E", "Unable to set Piece size. Target Piece is not in \"default\" mode.");
                return;
            }
            //Set the Piece size...
            instantiatedPiece.style.width = newWidth;
            instantiatedPiece.style.height = newHeight;
        }
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
            if(componentFound != null && componentFound != undefined)
                result = componentFound;
        }

        //Return the result
        return result;
    }

    static TryToDetectPieceInstanceIdAndOwnerWindowUsingDomElementAsBasis(domElement){
        //Prepare the result
        var result = { pieceDetected: false, ownerWindow: "", instanceId: "" };

        //If the element, is not a dom element, cancel
        if(domElement.tagName === undefined){
            Common.SendLog("E", "Could not detect the Instance ID and Window that owns the possible Piece instance. The element provided as the base is not a valid DOM Element.");
            return result;
        }

        //Prepare the current checking element
        var currentElementBeingChecked = domElement;
        //Try to analyze all parent elements until find root tag of this possible Piece...
        while(true){
            //Get the parent element of element being checked
            var parentElement = currentElementBeingChecked.parentElement;

            //If reached the HTML element, cancel the search
            if(parentElement.tagName == "HTML")
                break;

            //If reached the root tag of this possible Piece, cancel the search
            if(parentElement.tagName == "DIV")
                if(parentElement.getAttribute("piece") != null && parentElement.getAttribute("piece") != undefined){
                    result.pieceDetected = true;
                    result.ownerWindow = parentElement.getAttribute("in");
                    result.instanceId = parentElement.getAttribute("piid");
                    break;
                }

            //Inform the new current element being checked and go to next iteraction
            currentElementBeingChecked = parentElement;
        }

        //Return the result
        return result;
    }
    
    static SetInIndividualDataOfPiece(targetWindowIdentifier, instanceId, slotKey, valueToSet){
        //If target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to set the Value on the Individual Data of the Piece. A Window with \"" + targetWindowIdentifier + "\" identifier, does not exist.");
            return;
        }
        //If the instance id don't exists in the window, cancel
        if(Pieces.isPieceExistentInsideWindow(targetWindowIdentifier, instanceId) == false){
            Common.SendLog("E", "Unable to set the Value on the Individual Data of the Piece. There is no Piece instantiated within Window \"" + targetWindowIdentifier + "\" using instance ID \"" + instanceId + "\".");
            return;
        }
        //If slotKey is not string, or is empty, cancel
        if(typeof slotKey !== 'string' || slotKey == ""){
            Common.SendLog("E", "Unable to set the Value on the Individual Data of the Piece. Please provide a valid Slot Key of type String.");
            return;
        }
        //If slotKey contains not valid characters, cancel
        if((/^[a-zA-Z]\w+$/).test(slotKey) == false){
            Common.SendLog("E", "Unable to set the Value on the Individual Data of the Piece. The Slot Key must be a valid Variable name. It must start with a letter and can only contain Letters, Numbers and Underscores.");
            return;
        }
        //If valueToSet is not a primitive value, cancel
        if(typeof valueToSet !== 'boolean' && typeof valueToSet !== 'number' && typeof valueToSet !== 'string'){
            Common.SendLog("E", "Unable to set the Value on the Individual Data of the Piece. The Value to be set must be Primitive, i.e. Float, Int, String or Bool.");
            return;
        }

        //Try to get a reference to the root of instantiated Piece
        var instantiatedPiece = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];
        //If found the instantiated Piece, continues...
        if(instantiatedPiece != null && instantiatedPiece != undefined){
            //Recover the individual data of the Piece
            var pieceIndividualData = JSON.parse(instantiatedPiece.getAttribute("idata").replaceAll('&quot;', '"'));

            //Set the value in individual data
            pieceIndividualData[slotKey] = valueToSet;

            //Store the individual data in the Piece again
            instantiatedPiece.setAttribute("idata", JSON.stringify(pieceIndividualData).replaceAll('"', '&quot;'));
        }
    }

    static GetOfIndividualDataOfPiece(targetWindowIdentifier, instanceId, slotKey){
        //Prepare the result
        var result = null;

        //If target window, don't exists, cancel
        if(Windows.isWindowExistent(targetWindowIdentifier) == false){
            Common.SendLog("E", "Unable to get the Value on the Individual Data of the Piece. A Window with \"" + targetWindowIdentifier + "\" identifier, does not exist.");
            return;
        }
        //If the instance id don't exists in the window, cancel
        if(Pieces.isPieceExistentInsideWindow(targetWindowIdentifier, instanceId) == false){
            Common.SendLog("E", "Unable to get the Value on the Individual Data of the Piece. There is no Piece instantiated within Window \"" + targetWindowIdentifier + "\" using instance ID \"" + instanceId + "\".");
            return;
        }
        //If slotKey is not string, or is empty, cancel
        if(typeof slotKey !== 'string' || slotKey == ""){
            Common.SendLog("E", "Unable to get the Value on the Individual Data of the Piece. Please provide a valid Slot Key of type String.");
            return;
        }
        //If slotKey contains not valid characters, cancel
        if((/^[a-zA-Z]\w+$/).test(slotKey) == false){
            Common.SendLog("E", "Unable to get the Value on the Individual Data of the Piece. The Slot Key must be a valid Variable name. It must start with a letter and can only contain Letters, Numbers and Underscores.");
            return;
        }

        //Try to get a reference to the root of instantiated Piece
        var instantiatedPiece = Windows.existantWindowsInClientAndScreens[targetWindowIdentifier].instantiatedPiecesIdsAndRefs[instanceId];
        //If found the instantiated Piece, continues...
        if(instantiatedPiece != null && instantiatedPiece != undefined){
            //Recover the individual data of the Piece
            var pieceIndividualData = JSON.parse(instantiatedPiece.getAttribute("idata").replaceAll('&quot;', '"'));

            //If the requested key exists, recover the value
            if(pieceIndividualData.hasOwnProperty(slotKey) == true)
                result = pieceIndividualData[slotKey];
        }

        //Return the result
        return result;
    }

    //Auxiliar methods

    static ProcessPageHtmlCodeAndInstantiateAllPieces(targetWindowIdentifier, targetWindowElementReference){
        //Get all "le.piece" tags
        var pieceTags = targetWindowElementReference.getElementsByTagName("le.piece");

        //Process each piece instantiation request
        for(var i = 0; i < pieceTags.length; i++){
            //Get piece instance request information...
            var name = pieceTags[i].getAttribute("name");
            var instanceId = pieceTags[i].getAttribute("piid");
            var enabled = pieceTags[i].getAttribute("enabled");
            var mode = "default";
            if(pieceTags[i].getAttribute("mode") != null){
                if(pieceTags[i].getAttribute("mode") == "default")
                    mode = "default";
                if(pieceTags[i].getAttribute("mode") == "inline")
                    mode = "inline";
            }
            var width = pieceTags[i].getAttribute("width");
            var height = pieceTags[i].getAttribute("height");
            if (mode == "inline"){
                width = "auto";
                height = "auto";
            }
            var jsonVariables = pieceTags[i].innerHTML;

            //Process this piece instantiation...
            Pieces.ProcessPieceInstantiation(name, instanceId, enabled, mode, width, height, jsonVariables, targetWindowIdentifier, pieceTags[i]);
        }

        //Remove all useless "le.piece" tags...
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

    static ProcessPieceInstantiation(name, instanceId, enabled, mode, width, height, jsonVariables, windowIdentifier, putAfterElement){
        //If some of values is null, cancel the request...
        if(name == null || instanceId == null || enabled == null || mode == null || width == null || height == null || jsonVariables == null || windowIdentifier == null || putAfterElement == null){
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
        //If the mode value is not valid, cancel
        if(mode !== "default" && mode !== "inline"){
            Common.SendLog("E", "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The \"mode\" value is invalid.");
            Pieces.AddDivOfErrorMessageAfterElement(
                    "It was not possible to instantiate Piece \"" + name + "\" within Window \"" + windowIdentifier + "\". The \"mode\" value is invalid.",
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
        pieceRootTag.setAttribute("in", windowIdentifier);
        pieceRootTag.setAttribute("piid", instanceId);
        pieceRootTag.setAttribute("mode", mode);
        pieceRootTag.setAttribute("style", "");
        if(enabled === "true"){
            if(mode == "default")
                pieceRootTag.style.display = "block";
            if(mode == "inline")
                pieceRootTag.style.display = "inline-block";
        }
        if(enabled === "false")
            pieceRootTag.style.display = "none";
        pieceRootTag.style.width = width;
        pieceRootTag.style.height = height;
        pieceRootTag.setAttribute("idata", "{}");
        pieceRootTag.innerHTML = pieceHtmlCode;

        //Add the root DIV element in the DOM...
        var insertedElement = putAfterElement.parentNode.insertBefore(pieceRootTag, putAfterElement.nextSibling);

        //Add this instantiated Piece ID in the list of instantiated Pieces of Window... (this list is cleared on Page unload)
        Windows.existantWindowsInClientAndScreens[windowIdentifier].instantiatedPiecesIdsAndRefs[instanceId] = insertedElement;

        //Try to run a possible "OnInstantiate" method of the Piece JS code...
        try{ eval("Piece_" + name + ".OnInstantiate(\"" + windowIdentifier + "\", \"" + instanceId + "\");"); } catch(e){  };
    }

}

</script>