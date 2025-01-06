<html>
    <head>
        <!-- Page to Client Redirector -->
        <script type="text/javascript" src="https://example.com/engine/utilities/page-to-client-redirector.js"></script>
        <!-- Page Metadata -->
        <title>Page A</title>
        <meta name="description" content="Page Description"/>
        <meta name="image" content="https://example.com/image.png"/>
    </head>

    <script>
        /* Page Script */

        function LE_OnPageLoad(){
            //on load this page...
            Pieces.InstantiatePieceAfter("Example", "test23", true, "auto", "auto", '{}', "ClientMainWindow", document.getElementById("testing2"), true);
            Pieces.InstantiatePieceInlineAfter("Example", "test24", true, '{}', "ClientMainWindow", document.getElementById("testing3"), true);
        }

        function LE_OnPageUnload(){

        }



        
        function run_code(){
            
        }
    </script>

    <body>
        <!-- Page Content -->
        <h2>Page A</h2>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna 
        aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint 
        occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        <br/>
        <br/>
        <le.piece name="Example" piid="test" enabled="true" width="auto" height="auto">
            {
                "declaredVar1":"tttt"
            }
        </le.piece>
        <le.piece name="Example" piid="test2" enabled="true" width="auto" height="auto">
            {
                "declaredVar1":"aaaa"
            }
        </le.piece>
        <le.piece name="Example" piid="test3" enabled="true" mode="default" width="auto" height="auto">
            {
                "declaredVar1":"bbbb"
            }
        </le.piece>
        <br/>
        <br/>
        <br/>
        .Place Holder | <le.piece name="Example" piid="itest" enabled="true" mode="inline">{ }</le.piece> | <le.piece name="Example" piid="itest2" enabled="true" mode="inline">{ }</le.piece> | <hr id="testing3"/> | %piece% | Place Holder.
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <a onclick="run_code();">Run</a>

        <!-- -->
        <br/>
        <br/>
        <hr id="testing2"/>
        <hr id="testing5"/>
    </body>
</html>