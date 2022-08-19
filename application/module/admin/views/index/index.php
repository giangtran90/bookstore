<?php
$imageURL = $this->_dirImg;
$arrMenu = [
    ['link' => URL::createLink('admin', 'book', 'add'), 'name' => 'Add new book', 'image' => 'icon-48-article-add'],
    ['link' => URL::createLink('admin', 'book', 'index'), 'name' => 'Book manager', 'image' => 'icon-48-article'],
    ['link' => URL::createLink('admin', 'category', 'index'), 'name' => 'Category manager', 'image' => 'icon-48-category'],
    ['link' => URL::createLink('admin', 'group', 'index'), 'name' => 'Group manager', 'image' => 'icon-48-group'],
    ['link' => URL::createLink('admin', 'user', 'index'), 'name' => 'User manager', 'image' => 'icon-48-user']
];
$xhtml = '';
foreach ($arrMenu as $key => $value) {
    $image  = $imageURL . '/header/' . $value['image'] . '.png';
    $xhtml .= sprintf('<div class="icon-wrapper">
                        <div class="icon">
                            <a href="%s"><img src="%s" alt="%s"><span>%s</span></a>
                        </div>
                     </div>', $value['link'], $image, $value['name'], $value['name']);
}
?>
<div id="element-box">
    <div id="system-message-container"></div>
    <div class="m">
        <div class="adminform">
            <div class="cpanel-left">
                <div class="cpanel">
                    <?= $xhtml; ?>
                </div>
            </div>

        </div>
        <div class="clr"></div>
    </div>
</div>