<?php
$imageURL = $this->_dirImg;
// link
$linkHome       = URL::createLink('default', 'index', 'index', null, 'home.html');
$linkCategories = URL::createLink('default', 'category', 'index', null, 'categories.html');
$linkMyAccount  = URL::createLink('default', 'user', 'index', null, 'my-account.html');
$linkLogout     = URL::createLink('default', 'index', 'logout');
$linkRegister   = URL::createLink('default', 'index', 'register', null, 'register.html');
$linkLogin      = URL::createLink('default', 'index', 'login', null, 'login.html');
$linkACP        = URL::createLink('admin', 'index', 'index');

$userObj = Session::get('user');

$arrayMenu = [];
$arrayMenu[] = ['class' => 'index-index'    , 'link' => $linkHome, 'name' => 'Home'];
$arrayMenu[] = ['class' => 'category-index book-list book-detail' , 'link' => $linkCategories, 'name' => 'Categories'];
if (@$userObj['login'] == true){
    $arrayMenu[] = ['class' => 'user-index user-cart user-history'   , 'link' => $linkMyAccount, 'name' => 'My account'];
    $arrayMenu[] = ['class' => 'index-logout' , 'link' => $linkLogout, 'name' => 'Logout'];
} else {
    $arrayMenu[] = ['class' => 'index-register' , 'link' => $linkRegister, 'name' => 'Register'];
    $arrayMenu[] = ['class' => 'index-login'    , 'link' => $linkLogin   , 'name' => 'Login'];
}

if (@$userObj['group_acp'] == 1){
    $arrayMenu[] = ['class' => ''   , 'link' => $linkACP, 'name' => 'Admin Control Panel'];
}

$xhtml = '';
foreach ($arrayMenu as $key => $value) {
    $xhtml .= sprintf('<li class="%s"><a href="%s">%s</a></li>', $value['class'], $value['link'], $value['name']);
}
// Active
$controller = (!empty($this->arrParam['controller'])) ? $this->arrParam['controller'] : 'index';
$action     = (!empty($this->arrParam['action'])) ? $this->arrParam['action'] : 'index';
?>

<script type="text/javascript">
    $(document).ready(function(){
    var controller  = '<?= $controller ?>';
    var action      = '<?= $action ?>';
    var classSelect = controller + '-' + action;
    $('#menu ul li.' + classSelect).addClass('selected');
    });
</script>

<div class="header">
    <div class="logo"><a href="<?= $linkHome;?>"><img src="<?= $imageURL;?>/logo.gif" /></a></div>
    <div id="menu">
        <ul>
            <?= $xhtml;?>
        </ul>
    </div>
</div>