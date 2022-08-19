<?php
// Book - Detail 
if (!empty($this->bookDetail)) {
    $xhtmlBookDetail    = '';
    $name               = $this->bookDetail['name'];
    $descriptionFull    = $this->bookDetail['description'];
    $description        = substr($descriptionFull, 0, 400);
    $price              = $this->bookDetail['price'];
    $sale_off           = $this->bookDetail['sale_off'];

    $picture            = Helper::createImage('book', $this->bookDetail['picture'], ['class' => 'thumb', 'width' => 90, 'height' => 150]);
    $picturePath        = UPLOAD_PATH . 'book' . DS . $this->bookDetail['picture'];
    $pictureFull        = '';
    if (file_exists($picturePath) == true) {
        $pictureFull    = UPLOAD_URL . 'book' . DS . $this->bookDetail['picture'];
    }

    $priceReal = 0;
    if ($sale_off > 0) {
        $price      = ' <span class="red-through">' . number_format($this->bookDetail['price']) . '</span>';
        $priceReal  = $this->bookDetail['price'] - $this->bookDetail['price'] * $sale_off / 100;
        $price      .= ' <span class="red">' . number_format($priceReal) . ' VND</span>';
    } else {
        $priceReal  = $this->bookDetail['price'];
        $price = '<span class="red">' . number_format($priceReal) . ' VND</span>';
    }

    // $bookNameURL    = URL::filterURL($this->bookDetail['name']) . '-' . $this->bookDetail['category_id'] . '-' . $this->bookDetail['id'] . '.html';
    // $cateNameURL    = URL::filterURL($this->bookDetail['category_name']);
    // $linkOrderHTML  = $cateNameURL . DS . $bookNameURL;
    $linkOrder      = URL::createLink('default', 'user', 'order', ['category_id' => $this->bookDetail['category_id'], 'book_id' => $this->bookDetail['id'], 'price' => $priceReal]);

    $xhtmlBookDetail .= sprintf('<div class="title"><span class="title_icon"><img src="%s/bullet1.gif" alt="" title="" /></span>%s</div>
                                <div class="feat_prod_box_details">
                                    <div class="prod_img"><a href="#">%s</a>
                                        <br /><br />
                                        <a id="single_image" href="%s" rel="lightbox"><img src="%s/zoom.gif" alt="" title="" border="0" /></a>
                                    </div>
                                    <div class="prod_det_box">
                                        <div class="box_top"></div>
                                        <div class="box_center">
                                            <div class="prod_title">Details</div>
                                            <p class="details" title="%s">%s</p>
                                            <div class="price"><strong>PRICE:</strong> %s</div>
                                            <a href="' . $linkOrder . '" class="more"><img src="%s/order_now.gif" alt="" title="" border="0" /></a>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="box_bottom"></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>', $imageURL, $name, $picture, $pictureFull, $imageURL, $this->bookDetail['description'], $description, $price, $imageURL);
}

// Book - Related
if (!empty($this->bookRelated)) {
    $xhtmlBookRelated = '';
    foreach ($this->bookRelated as $key => $value) {
        $bookNameURL        = URL::filterURL($value['name']) . '-' . $value['category_id'] . '-' . $value['id'] . '.html';
        $categoryNameURL    = URL::filterURL($value['category_name']);
        $linkBookHTML       = $categoryNameURL . DS . $bookNameURL;
        
        $link               = URL::createLink('default', 'book', 'detail', ['category_id' => $value['category_id'],'book_id' => $value['id']], $linkBookHTML);
        $name               = substr($value['name'], 0, 20);
        $picture            = Helper::createImage('book', $value['picture'], ['class' => 'thumb', 'width' => 60, 'height' => 90]);

        $xhtmlBookRelated .= sprintf('<div class="new_prod_box">
                                        <a href="%s" title="%s">%s</a>
                                        <div class="new_prod_bg">
                                            <a href="%s">%s</a>
                                        </div>
                                    </div>', $link, $value['name'], $name, $link, $picture);
    }
}
?>
<?= $xhtmlBookDetail; ?>


<div id="demo" class="demolayout">

    <ul id="demo-nav" class="demolayout">
        <li><a class="tab1 active" href="#">More details</a></li>
        <li><a class="tab2" href="#">Related books</a></li>
    </ul>

    <div class="tabs-container">

        <div style="display: none;" class="tab" id="tab1">
            <p class="more_details"><?= $descriptionFull; ?></p>
        </div>

        <div style="display: block;" class="tab" id="tab2">
            <?= $xhtmlBookRelated; ?>
            <div class="clear"></div>
        </div>

    </div>
</div>

<div class="clear"></div>