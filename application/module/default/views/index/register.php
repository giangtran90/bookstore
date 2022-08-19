<?php
@$dataForm = $this->arrParam['form'];
// INPUT
$inputUsername  = Helper::cmsInput('text', 'form[username]', 'username', @$dataForm['username'], 'contact_input');
$inputFullname  = Helper::cmsInput('text', 'form[fullname]', 'fullname', @$dataForm['fullname'], 'contact_input');
$inputEmail     = Helper::cmsInput('text', 'form[email]', 'email', @$dataForm['email'], 'contact_input');
$inputPassword  = Helper::cmsInput('text', 'form[password]', 'password', @$dataForm['password'], 'contact_input');
$inputSubmit    = Helper::cmsInput('submit', 'form[submit]', 'submit', 'register', 'register');
$inputToken     = Helper::cmsInput('hidden', 'form[token]', 'token', time());

// FORM ROWS
$rowUsername    = Helper::cmsRow('Username', $inputUsername);
$rowFullname    = Helper::cmsRow('Fullname', $inputFullname);
$rowEmail       = Helper::cmsRow('Email', $inputEmail);
$rowPassword    = Helper::cmsRow('Password', $inputPassword);
$rowSubmit      = Helper::cmsRow('Submit', $inputToken . $inputSubmit, true);

$linkAction = URL::createLink('default', 'index', 'register');

?>
<div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet1.gif" /></span>Đăng ký thành viên</div>

<div class="feat_prod_box_details">
    <div class="contact_form">
        <div class="form_subtitle">create new account</div>
        <?php echo @$this->errors;?>
        <form name="adminForm" action="<?= $linkAction?>" method="post">
            <?= $rowUsername . $rowFullname . $rowEmail . $rowPassword . $rowSubmit;?>
        </form>
    </div>
</div>

<div class="clear"></div>