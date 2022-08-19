<?php
include_once (MODULE_PATH . 'admin/views/toolbar.php');
include_once 'submenu/index.php';

// Input
@$dataForm      = $this->arrParam['form'];
$inputName      = Helper::cmsInput('text', 'form[name]', 'name', @$dataForm['name'], 'inputbox required', 40);
$inputOrdering  = Helper::cmsInput('text', 'form[ordering]', 'ordering', @$dataForm['ordering'], 'inputbox', 40);
$inputToken     = Helper::cmsInput('hidden', 'form[token]', 'token', time());
$selectStatus   = Helper::cmsSelectbox('form[status]', null, ['default' => '- Select Status -', 1 => 'Pushlish', 0 => 'Unpublish'], @$dataForm['status'], 'width:150px');
$selectGroupACP = Helper::cmsSelectbox('form[group_acp]', null, ['default' => '- Select Group ACP -', 1 => 'Yes', 0 => 'No'], @$dataForm['group_acp'], 'width:150px');

$inputID = '';
$rowID   = '';
if (isset($this->arrParam['id'])){
    $inputID      = Helper::cmsInput('text', 'form[id]', 'id', @$dataForm['id'], 'inputbox readonly');
    $rowID          = Helper::cmsRowForm('ID', $inputID);
}
// Row
$rowName        = Helper::cmsRowForm('Name', $inputName, true);
$rowOrdering    = Helper::cmsRowForm('Ordering', $inputOrdering);
$rowStatus      = Helper::cmsRowForm('Status', $selectStatus);
$rowGroupACP    = Helper::cmsRowForm('Group ACP', $selectGroupACP);

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
                        <?= $rowName . $rowStatus . $rowGroupACP . $rowOrdering . $rowID; ?>
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