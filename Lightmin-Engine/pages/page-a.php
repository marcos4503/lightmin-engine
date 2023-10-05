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
            //Pieces.InstantiatePieceAfter("Example", "test2", true, "auto", "auto", '{}', "ClientMainWindow", document.getElementById("testing2"));
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
        <le.piece.instantiate name="Example" piid="test" enabled="true" width="auto" height="auto">
            {}
        </le.piece.instantiate>

        <!-- -->
        <div id="testing2"></div>
    </body>
</html>