<?php
$linkChangePass = '#';
$linkViewCart   = URL::createLink('default', 'user', 'cart', null, 'cart.html');
$linkHistory    = URL::createLink('default', 'user', 'history', null, 'history.html');
$linkLogout     = URL::createLink('default', 'index', 'logout');
$arrMenu = [
                ['name' => 'Change Password', 'image' => 'changepass.png'   , 'link' => $linkChangePass],
                ['name' => 'View Cart'      , 'image' => 'cart.png'         , 'link' => $linkViewCart],
                ['name' => 'History'        , 'image' => 'history.png'      , 'link' => $linkHistory],
                ['name' => 'Logout'         , 'image' => 'logout.png'       , 'link' => $linkLogout]
            ];

$xhtmlMenu = '';
foreach ($arrMenu as $key => $value){
    $xhtmlMenu .= sprintf('<div class="new_prod_box">
                                <a href="%s" title=""> %s </a>
                                <div class="new_prod_bg">
                                    <a href="%s"><img width="60" height="90" class="thumb" src="'.$imageURL.'/%s"></a>
                                </div>
                            </div>', $value['link'], $value['name'], $value['link'], $value['image']);
}
?>
<div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet1.gif" alt="" title=""></span>My Account</div>

<div class="new_products">

    <?= $xhtmlMenu;?>

</div>