<?php
// INPUT
$inputEmail     = Helper::cmsInput('text', 'form[email]', 'email', @$dataForm['email'], 'contact_input');
$inputPassword  = Helper::cmsInput('text', 'form[password]', 'password', @$dataForm['password'], 'contact_input');
$inputSubmit    = Helper::cmsInput('submit', 'form[submit]', 'submit', 'login', 'register');
$inputToken     = Helper::cmsInput('hidden', 'form[token]', 'token', time());

// FORM ROWS
$rowEmail       = Helper::cmsRow('Email', $inputEmail);
$rowPassword    = Helper::cmsRow('Password', $inputPassword);
$rowSubmit      = Helper::cmsRow('Submit', $inputToken . $inputSubmit, true);

$linkAction = URL::createLink('default', 'index', 'login');

?>
<div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet1.gif" /></span>Đăng nhập</div>

<div class="feat_prod_box_details">
    <div class="contact_form">
        <div class="form_subtitle">Login</div>
        <?php echo @$this->errors;?>
        <form name="adminForm" action="<?= $linkAction?>" method="post">
            <?= $rowEmail . $rowPassword . $rowSubmit;?>
        </form>
    </div>
</div>

<div class="clear"></div>