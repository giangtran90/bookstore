   <?php
    if (!empty($this->Items)) {
        $xhtml = '';
        foreach ($this->Items as $key => $value) {
            $bookNameURL        = URL::filterURL($value['name']) . '-' . $value['category_id'] . '-' . $value['id'] . '.html';
            $categoryNameURL    = URL::filterURL($value['category_name']);
            $linkBookHTML       = $categoryNameURL . DS . $bookNameURL;
            $link               = URL::createLink('default', 'book', 'detail', ['category_id' => $value['category_id'], 'book_id' => $value['id']], $linkBookHTML);
            $picture            = Helper::createImage('book', $value['picture'], ['class' => 'thumb', 'width' => 90, 'height' => 150]);
            
            $shortName          = substr($value['name'], 0, 30);
            $shortDescription   = substr($value['description'], 0, 200);
            $xhtml .= sprintf('<div class="feat_prod_box">
                                    <div class="prod_img"><a href="%s">%s</a></div>
                                    <div class="prod_det_box">
                                        <span class="special_icon"><img src="%s/special_icon.gif"></span>
                                        <div class="box_top"></div>
                                        <div class="box_center">
                                            <div class="prod_title" title="%s">%s</div>
                                            <p class="details">%s</p>
                                            <a href="%s" class="more">- more details -</a>
                                            <div class="clear"></div>
                                        </div>                         
                                        <div class="box_bottom"></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>'
                                , $link, $picture, $imageURL, $value['name'], $shortName, $shortDescription, $link);
        }
    } else {
        $xhtml = '<div class="feat_prod_box">Nội dung đang cập nhật!</div>';
    }
    ?>
   <div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet1.gif" /></span><?= $this->category_name ?></div>

   <?= $xhtml; ?>
