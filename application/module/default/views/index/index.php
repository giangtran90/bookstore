<?php
// Featured books
if (!empty($this->listFeaturedBooks)) {
    $xhtmlFeaturedBooks = '';
    foreach ($this->listFeaturedBooks as $key => $value) {
        $bookNameURL        = URL::filterURL($value['name']) . '-' . $value['category_id'] . '-' . $value['id'] . '.html';
        $categoryNameURL    = URL::filterURL($value['category_name']);
        $linkBookHTML       = $categoryNameURL . DS . $bookNameURL;
        $link               = URL::createLink('default', 'book', 'detail', ['category_id' => $value['category_id'], 'book_id' => $value['id']], $linkBookHTML);
        $picture            = Helper::createImage('book', $value['picture'], ['class' => 'thumb', 'width' => 90, 'height' => 150]);

        $shortName          = substr($value['name'], 0, 30);
        $shortDescription   = substr($value['description'], 0, 200);
        $xhtmlFeaturedBooks .= sprintf(
            '<div class="feat_prod_box">
                                            <div class="prod_img"><a href="%s">%s</a></div>                            
                                            <div class="prod_det_box">
                                                <div class="box_top"></div>
                                                <div class="box_center">
                                                    <div class="prod_title" title="%s">%s</div>
                                                    <p class="details" title="%s">%s</p>
                                                    <a href="%s" class="more">- more details -</a>
                                                    <div class="clear"></div>
                                                </div>                            
                                                <div class="box_bottom"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>',
            $link,
            $picture,
            $value['name'],
            $shortName,
            $value['description'],
            $shortDescription,
            $link
        );
    }
} else {
    $xhtmlFeaturedBooks = '<div class="feat_prod_box">Nội dung đang cập nhật!</div>';
}

// New books
if (!empty($this->listNewBooks)) {
    $xhtmlNewBooks = '';
    foreach ($this->listNewBooks as $key => $value) {
        $newBookNameURL     = URL::filterURL($value['name']) . '-' . $value['category_id'] . '-' . $value['id'] . '.html';
        $newCategoryNameURL = URL::filterURL($value['category_name']);
        $linkNewBookHTML    = $newCategoryNameURL . DS . $newBookNameURL;
        $link               = URL::createLink('default', 'book', 'detail', ['category_id' => $value['category_id'], 'book_id' => $value['id']], $linkNewBookHTML);
        $picture            = Helper::createImage('book', $value['picture'], ['class' => 'thumb', 'width' => 60, 'height' => 90]);

        $shortName          = substr($value['name'], 0, 20);
        $xhtmlNewBooks      .= sprintf(
            '<div class="new_prod_box">
                                            <a href="%s" title="%s">%s</a>
                                            <div class="new_prod_bg">
                                                <span class="new_icon"><img src="%s/new_icon.gif" alt="" title="" /></span>
                                                <a href="%s">%s</a>
                                            </div>
                                        </div>',
            $link,
            $value['name'],
            $shortName,
            $imageURL,
            $link,
            $picture
        );
    }
} else {
    $xhtmlNewBooks = '<div class="feat_prod_box">Nội dung đang cập nhật!</div>';
}
?>
<div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet1.gif" alt="" title="" /></span>Featured books</div>
<?= $xhtmlFeaturedBooks; ?>

<div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet2.gif" alt="" title="" /></span>New books</div>
<div class="new_products">
    <?= $xhtmlNewBooks; ?>
</div>
<div class="clear"></div>