<?php
$model = new Model();

if (isset($this->arrParam['book_id'])){
    $book_id        = $this->arrParam['book_id'];
    $query          = "SELECT `category_id` FROM `".TBL_BOOK."` WHERE `id` = '$book_id'";
    $result         = $model->fetchRow($query);
    $category_id    = $result['category_id'];
} else if (isset($this->arrParam['category_id'])){
    @$category_id    = $this->arrParam['category_id'];
}

$query = "SELECT `id`, `name` FROM `" . TBL_CATEGORY . "` WHERE `status` = 1 ORDER BY `ordering` ASC";
$listCategories = $model->fetchAll($query);
$xhtml = '';
if (!empty($listCategories)) {
    foreach ($listCategories as $key => $value) {
        $linkCateHTML   = URL::filterURL($value['name']) . '-' . $value['id'] . '.html';
        $link = URL::createLink('default', 'book', 'list', ['category_id' => $value['id']], $linkCateHTML);
        $name = $value['name'];
        $shortName = substr($name, 0, 20);
        if (@$category_id == $value['id']) {
            $xhtml .= sprintf('<li><a class="active-link" href="%s" title="%s">%s</a></li>', $link, $name, $shortName);
        } else {
            $xhtml .= sprintf('<li><a href="%s" title="%s">%s</a></li>', $link, $name, $shortName);
        }
       
    }
}

?>
<div class="right_box">
    <div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet5.gif" alt="" title="" /></span>Categories</div>
    <ul class="list">
        <?= $xhtml; ?>
    </ul>
</div>
<div class="clear"></div>
<div class="about"></div>