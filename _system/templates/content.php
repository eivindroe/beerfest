<div id="page-header">
    <div class="center">
        <nav id="nav-menu">
            <ul>
                <?php
                $objMenu = new \Beerfest\Menu\Menu();
                echo $objMenu->getItemsAsHtml();
                ?>
            </ul>
        </nav>
        <nav id="nav-panel">
            <ul>
                <li><?=$objMenu->getActiveUserButton()?></li>
                <li><a href="#" id="page-panel-button" data-role="button" data-icon="bars" data-inline="true"><?=_MENU?></a></li>
            </ul>
            <div id="page-panel" data-position="right">
                <ul>
                    <?=$objMenu->getPanelAsHtml()?>
                </ul>
            </div>
        </nav>
    </div>
</div>
<div id="page-content" class="center">