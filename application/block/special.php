<?php
$query          = "SELECT `b`.`id`, `b`.`name`, `c`.`name` AS `category_name`, `b`.`picture`, `b`.`category_id` FROM `" . TBL_BOOK . "` AS `b`, `".TBL_CATEGORY."` AS `c` WHERE `b`.`special` = 1 AND `b`.`category_id` = `c`.`id` ORDER BY `b`.`ordering` ASC LIMIT 0,3";
$listSpecial    = $model->fetchAll($query);
$xhtml = '';
if (!empty($listSpecial)) {
    foreach ($listSpecial as $key => $value) {
        $name = $value['name'];
        $shortName = substr($name, 0, 20);
        $bookNameURL = URL::filterURL($name);
        $cateNameURL = URL::filterURL($value['category_name']);
        $linkBookHTML = $cateNameURL . DS . $bookNameURL . '-' . $value['category_id'] . '-' . $value['id'] . '.html';

        $link = URL::createLink('default', 'book', 'detail', ['category_id' =>$value['category_id'], 'book_id' => $value['id']], $linkBookHTML);
        $picture = Helper::createImage('book', $value['picture'], ['class' => 'thumb', 'width' => 60, 'height' => 90]);
            
        $xhtml .= sprintf('<div class="new_prod_box">
                                <a href="%s" title="%s">%s</a>
                                <div class="new_prod_bg">
                                    <span class="new_icon"><img src="%s/special_icon.gif" alt="" title="" /></span>
                                    <a href="%s">%s</a>
                                </div>
                            </div>', $link, $name, $shortName, $imageURL, $link, $picture);    
    }
}
?>
<div class="right_box">

    <div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet4.gif" alt="" title="" /></span>Specials</div>
    <?= $xhtml; ?>

</div>