<?php
include_once(MODULE_PATH . 'admin/views/toolbar.php');
include_once 'submenu/index.php';

// Input
@$dataForm          = $this->arrParam['form'];
$inputName          = Helper::cmsInput('text', 'form[name]', 'name', @$dataForm['name'], 'inputbox required', 40);
$inputPicture       = @Helper::cmsInput('file', 'picture', 'picture', @$dataForm['picture'], 'inputbox', 40);
$inputDescription   = '<textarea name="form[description]">' . @$dataForm['description'] . '</textarea>';
$inputPrice         = Helper::cmsInput('text', 'form[price]', 'price', @$dataForm['price'], 'inputbox required', 40);
$inputSaleOff       = Helper::cmsInput('text', 'form[sale_off]', 'sale_off', @$dataForm['sale_off'], 'inputbox', 40);
$inputOrdering      = Helper::cmsInput('text', 'form[ordering]', 'ordering', @$dataForm['ordering'], 'inputbox required', 40);
$inputToken         = Helper::cmsInput('hidden', 'form[token]', 'token', time());
$slbStatus          = Helper::cmsSelectbox('form[status]', null, ['default' => '- Select Status -', 1 => 'Pushlish', 0 => 'Unpublish'], @$dataForm['status'], 'width:150px');
$slbSpecial         = Helper::cmsSelectbox('form[special]', null, ['default' => '- Select Special -', 1 => 'Yes', 0 => 'No'], @$dataForm['special'], 'width:150px');
$slbCategory        = Helper::cmsSelectbox('form[category_id]', null, $this->slbCategory, @$dataForm['category_id'], 'width:150px');

$inputID = '';
$rowID   = '';
if (isset($this->arrParam['id']) || @$dataForm['id']) {
    $inputID       = Helper::cmsInput('text', 'form[id]', 'id', @$dataForm['id'], 'inputbox readonly');
    $inputName     = Helper::cmsInput('text', 'form[name]', 'name', @$dataForm['name'], 'inputbox readonly', 40);
    $rowID         = Helper::cmsRowForm('ID', $inputID);
    $picture      = '<img width=60 height=90 src="' . UPLOAD_URL . 'book' . DS . @$dataForm['picture'] . '">';
    $inputPictureHidden   = Helper::cmsInput('hidden', 'form[picture_hidden]', 'picture_hidden', @$dataForm['picture'], 'inputbox', 40);
}
// Row
$rowName        = Helper::cmsRowForm('Name', $inputName, true);
$rowPicture     = Helper::cmsRowForm('Picture', $inputPicture . @$picture . @$inputPictureHidden);
$rowDescription = Helper::cmsRowForm('Description', $inputDescription);
$rowPrice       = Helper::cmsRowForm('Price', $inputPrice, true);
$rowSaleOff     = Helper::cmsRowForm('SaleOff', $inputSaleOff, true);
$rowOrdering    = Helper::cmsRowForm('Ordering', $inputOrdering, true);
$rowStatus      = Helper::cmsRowForm('Status', $slbStatus);
$rowSpecial     = Helper::cmsRowForm('Special', $slbSpecial);
$rowCategory    = Helper::cmsRowForm('Category', $slbCategory);

// MESSAGE
$message = Session::get('message');

// Session::delete('message');
$strMessage = Helper::cmsMessage($message);
?>

<div id="system-message-container"><?php echo $strMessage . @$this->errors; ?></div>
<div id="element-box">
    <div class="m">
        <form action="#" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
            <!-- FORM LEFT -->
            <div class="width-100 fltlft">
                <fieldset class="adminform">
                    <legend>Details</legend>
                    <ul class="adminformlist">
                        <?= $rowName . $rowPicture . $rowDescription . $rowPrice . $rowSaleOff . $rowStatus . $rowSpecial . $rowCategory . $rowOrdering . $rowID; ?>
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