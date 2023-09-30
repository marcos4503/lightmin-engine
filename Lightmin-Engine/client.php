<?php
//Include the needed files on Client...
include_once("engine/lightmin-engine-common.php");
include_once("engine/lightmin-engine-client-start.php");
?>

<!-- ┌───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐ -->
<!-- ├───────────────────────────────────────────────────────────────      CLIENT      ──────────────────────────────────────────────────────────────┤ -->
<!-- ├───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤ -->
<!-- ├────────────────────────────────────────────────   ====> [ START EDITING BELOW HERE! ] <====   ────────────────────────────────────────────────┤ -->
<!-- └───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘ -->

<!-- Create the grid to stack up the header, and content area -->
<div class="client_stackedGrid">
    <!-- background header -->
    <div class="client_stackedGrid_backgroundHeader"></div>
    <!-- content area -->
    <div class="client_stackedGrid_contentArea">
        <!-- logo -->
        <div class="client_stackedGrid_contentArea_logo">
            <img src="medias/images/client/lightmin-engine-logo.png" class="client_stackedGrid_contentArea_logo_img" />
        </div>
        <!-- navbar -->
        <div class="client_stackedGrid_contentArea_navbar">
            <div class="client_stackedGrid_contentArea_navbar_item_last"></div>
            <div class="client_stackedGrid_contentArea_navbar_item" id="client.tab3" onclick="Windows.LoadPage('ClientMainWindow', 'page-c.php', null);">Page C</div>
            <div class="client_stackedGrid_contentArea_navbar_item" id="client.tab2" onclick="Windows.LoadPage('ClientMainWindow', 'page-b.php', null);">Page B</div>
            <div class="client_stackedGrid_contentArea_navbar_item" id="client.tab1" onclick="Windows.LoadPage('ClientMainWindow', 'page-a.php', null);">Page A</div>
        </div>
        <!-- content area -->
        <div class="client_stackedGrid_contentArea_content">
            <le.window type="main" identifier="ClientMainWindow" scalingmode="parent-content">
                <!-- Lightmin Engine will render the pages content inside here... -->
            </le.window>
        </div>
        <!-- footer -->
        <div class="client_stackedGrid_contentArea_footer">
            <center>
                <small>
                    Developed and maintained with ❤ by Marcos Tomaz in <?php echo(date("Y")); ?>!
                    <div class="client_stackedGrid_contentArea_footer_separator"></div>
                    <a href="https://www.paypal.com/donate/?hosted_button_id=MVDJY3AXLL8T2" target="_blank">Donate</a>
                    •
                    <a href="https://github.com/marcos4503/lightmin-engine" target="_blank">
                        <div class="client_stackedGrid_contentArea_footer_githubLink"><img src="medias/images/client/github.png" class="client_stackedGrid_contentArea_footer_githubIcon"></div>
                        See Lightmin Engine On GitHub
                    </a>
                </small>
            </center>
        </div>
    </div>
</div>

<!-- Create the additional window view -->
<div class="client_additionalWindow_background" id="additionalWindowBg" onclick="CloseAdditionalWindowView()"></div>
<div class="client_additionalWindow_button" id="additionalWindowButton" onclick="OpenAdditionalWindowView();">
    Open Additional<br>Window
</div>
<div class="client_additionalWindow_root" id="additionalWindowBox">
    <div class="client_additionalWindow_box">
        <div class="client_additionalWindow_box_navbar">
            <div class="client_additionalWindow_box_navbar_item" id="client.addWindow.tab1" onclick="Windows.LoadPage('ClientAddWindow', 'additionals/additional-page-a.php', null);">Additional Page A</div>
            <div class="client_additionalWindow_box_navbar_item" id="client.addWindow.tab2" onclick="Windows.LoadPage('ClientAddWindow', 'additionals/additional-page-b.php', null);">Additional Page B</div>
        </div>
        <div class="client_additionalWindow_box_content">
            <div class="client_additionalWindow_box_content_scrollable">
                <le.window type="normal" identifier="ClientAddWindow" scalingmode="parent-content">
                    <!-- Lightmin Engine will render the pages content inside here... -->
                </le.window>
            </div>
        </div>
    </div>
</div>

<!-- ┌───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐ -->
<!-- ├────────────────────────────────────────────────   ====> [ STOP EDITING AFTER HERE! ] <====   ─────────────────────────────────────────────────┤ -->
<!-- └───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘ -->

<?php
//Finish the client including needed files...
include_once("engine/lightmin-engine-client-end.php");
?>