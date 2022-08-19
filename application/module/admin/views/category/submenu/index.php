<?php
    $linkCategory   = URL::createLink('admin', 'category', 'index');
    $linkBook       = URL::createLink('admin', 'book', 'index');
?>
<div id="submenu-box">
    <div class="m">
        <ul id="submenu">
            <li><a class="active" href="<?= $linkCategory;?>">Category</a></li>
            <li><a href="<?= $linkBook;?>">Book</a></li>
        </ul>
        <div class="clr"></div>
    </div>
</div>