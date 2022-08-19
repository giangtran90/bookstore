<?php
include_once (MODULE_PATH . 'admin/views/toolbar.php');
include_once 'submenu/index.php';

// Input
@$dataForm      = $this->arrParam['form'];
$inputUsername  = Helper::cmsInput('text', 'form[username]', 'username', @$dataForm['username'], 'inputbox required', 40);
$inputEmail     = Helper::cmsInput('text', 'form[email]', 'email', @$dataForm['email'], 'inputbox required', 40);
$inputFullname  = Helper::cmsInput('text', 'form[fullname]', 'fullname', @$dataForm['fullname'], 'inputbox', 40);
$inputPassword  = Helper::cmsInput('text', 'form[password]', 'password', @$dataForm['password'], 'inputbox required', 40);
$inputOrdering  = Helper::cmsInput('text', 'form[ordering]', 'ordering', @$dataForm['ordering'], 'inputbox', 40);
$inputToken     = Helper::cmsInput('hidden', 'form[token]', 'token', time());
$slbStatus      = Helper::cmsSelectbox('form[status]', null, ['default' => '- Select Status -', 1 => 'Pushlish', 0 => 'Unpublish'], @$dataForm['status'], 'width:150px');
$slbGroup       = Helper::cmsSelectbox('form[group_id]', null, $this->slbGroup, @$dataForm['group_id'], 'width:150px');

$inputID = '';
$rowID   = '';
if (isset($this->arrParam['id']) || @$dataForm['id'] ){
    $inputID       = Helper::cmsInput('text', 'form[id]', 'id', @$dataForm['id'], 'inputbox readonly');
    $inputUsername = Helper::cmsInput('text', 'form[username]', 'username', @$dataForm['username'], 'inputbox readonly', 40);
    $rowID         = Helper::cmsRowForm('ID', $inputID);
}
// Row
$rowUsername    = Helper::cmsRowForm('Username', $inputUsername, true);
$rowEmail       = Helper::cmsRowForm('Email', $inputEmail, true);
$rowFullname    = Helper::cmsRowForm('Fullname', $inputFullname);
$rowPassword    = Helper::cmsRowForm('Password', $inputPassword, true);
$rowOrdering    = Helper::cmsRowForm('Ordering', $inputOrdering);
$rowStatus      = Helper::cmsRowForm('Status', $slbStatus);
$rowGroup       = Helper::cmsRowForm('Group', $slbGroup);

// MESSAGE
$message = Session::get('message');

// Session::delete('message');
$strMessage = Helper::cmsMessage($message);
?>

<div id="system-message-container"><?php echo $strMessage . @$this->errors;?></div>
<div id="element-box">
    <div class="m">
        <form action="#" method="post" name="adminForm" id="adminForm" class="form-validate">
            <!-- FORM LEFT -->
            <div class="width-100 fltlft">
                <fieldset class="adminform">
                    <legend>Details</legend>
                    <ul class="adminformlist">
                        <?= $rowUsername . $rowEmail . $rowFullname . $rowPassword . $rowStatus . $rowGroup . $rowOrdering . $rowID; ?>
                    </ul>
                    <div class="clr"></div>
                    <div>
                        <?= $inputToken; ?>
                    </div>
                </fieldset>
            </div>
            <div class="clr"></div>
            <div>
            </div>
        </form>
        <div class="clr"></div>
    </div>
</div>