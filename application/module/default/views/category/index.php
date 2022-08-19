<?php
$xhtml = '';
if (!empty($this->Items)) {
    foreach ($this->Items as $key => $value) {
        $linkCateHTML   = URL::filterURL($value['name']) . '-' . $value['id'] . '.html';
        $link           = URL::createLink('default', 'book', 'list', ['category_id' => $value['id']], $linkCateHTML);
        $name           = $value['name'];
        $shortName      = substr($name, 0, 20);
        $picture        = Helper::createImage('category', $value['picture'], ['class' => 'thumb', 'width' => 60, 'height' => 90]);

        $xhtml .= sprintf('<div class="new_prod_box">
                                <a href="%s" title="%s">%s</a>
                                <div class="new_prod_bg">
                                    <a href="%s">%s</a>
                                </div>
                            </div>', $link, $name, $shortName, $link, $picture);
    }
}
?>
<!-- TITLE-->
<div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet1.gif" alt="" title="" /></span>Category books</div>
<!-- LIST PRODUCT-->
<div class="new_products">

    <?= $xhtml; ?>

</div>

<div class="clear"></div>