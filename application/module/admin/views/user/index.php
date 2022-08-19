<?php
include_once (MODULE_PATH . 'admin/views/toolbar.php');
include_once 'submenu/index.php';

$columnPost     = @$this->arrParam['filter_column'];
$orderPost      = @$this->arrParam['filter_column_dir'];
$lblUserName    = Helper::cmsLinkSort('Username', 'username', $columnPost, $orderPost);
$lblEmail       = Helper::cmsLinkSort('Email', 'email', $columnPost, $orderPost);
$lblFullName    = Helper::cmsLinkSort('Fullname', 'fullname', $columnPost, $orderPost);
$lblGroup       = Helper::cmsLinkSort('Group', 'group_name', $columnPost, $orderPost);
$lblStatus      = Helper::cmsLinkSort('Status', 'status', $columnPost, $orderPost);
$lblOrdering    = Helper::cmsLinkSort('Ordering', 'ordering', $columnPost, $orderPost);
$lblCreated     = Helper::cmsLinkSort('Created', 'created', $columnPost, $orderPost);
$lblCreatedBy   = Helper::cmsLinkSort('Created By', 'created_by', $columnPost, $orderPost);
$lblModified    = Helper::cmsLinkSort('Modified', 'modified', $columnPost, $orderPost);
$lblModifiedBy  = Helper::cmsLinkSort('Modified By', 'modified_by', $columnPost, $orderPost);
$lblID          = Helper::cmsLinkSort('ID', 'id', $columnPost, $orderPost);

// SELECT BOX STATUS
$arrStatus = array('default' => '- Select Status -', 1 => 'Publish', 0 => 'Unpublish');
@$selectboxStatus = Helper::cmsSelectbox('filter_state', 'inputbox', $arrStatus, $this->arrParam['filter_state']);

// SELECT BOX GROUP
@$selectboxGroup  = Helper::cmsSelectbox('filter_group_id', 'inputbox', $this->slbGroup, $this->arrParam['filter_group_id']);

// PAGINATION
$paginationHTML = $this->pagination->showPagination(URL::createLink('admin', 'user', 'index'));

// MESSAGE
$message = Session::get('message');
Session::delete('message');
$strMessage = Helper::cmsMessage($message);
?>

<div id="system-message-container">
    <?= $strMessage; ?>
</div>

<div id="element-box">
    <div class="m">
        <form action="#" method="post" name="adminForm" id="adminForm">
            <!-- FILTER -->
            <fieldset id="filter-bar">
                <div class="filter-search fltlft">
                    <label class="filter-search-lbl" for="filter_search">Filter:</label>
                    <input type="text" name="filter_search" id="filter_search" value="<?php echo @$this->arrParam['filter_search']; ?>">
                    <button type="submit" name="submit-keyword">Search</button>
                    <button type="button" name="clear-keyword">Clear</button>
                </div>
                <div class="filter-select fltrt">
                    <?= $selectboxStatus . $selectboxGroup; ?>
                </div>
            </fieldset>
            <div class="clr"> </div>

            <table class="adminlist" id="modules-mgr">
                <!-- HEADER TABLE -->
                <thead>
                    <tr>
                        <th width="1%">
                            <input type="checkbox" name="checkall-toggle">
                        </th>
                        <th class="title"><?= $lblUserName; ?></th>
                        <th width="10%"><?= $lblEmail; ?></th>
                        <th width="10%"><?= $lblFullName; ?></th>
                        <th width="1%"><?= $lblGroup; ?></th>
                        <th width="6%"><?= $lblStatus; ?></th>
                        <th width="10%"><?= $lblOrdering; ?></th>
                        <th width="8%"><?= $lblCreated; ?></th>
                        <th width="10%"><?= $lblCreatedBy; ?></th>
                        <th width="8%"><?= $lblModified; ?></th>
                        <th width="10%"><?= $lblModifiedBy; ?></th>
                        <th width="1%" class="nowrap"><?= $lblID; ?></th>
                    </tr>
                </thead>
                <!-- FOOTER TABLE -->
                <tfoot>
                    <tr>
                        <td colspan="12">
                            <!-- PAGINATION -->
                            <div class="container">
                                <div class="pagination">
                                    <?= $paginationHTML; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <!-- BODY TABLE -->
                <tbody>
                    <?php
                    if (!empty($this->Items)) {
                        $i = 0;
                        foreach ($this->Items as $key => $value) {
                            $id             = $value['id'] . '';
                            $ckb            = '<input type="checkbox" name="cid[]" value="' . $id . '">';
                            $row            = ($i % 2 == 0) ? 'row0' : 'row1';
                            $username       = $value['username'];
                            $email          = $value['email'];
                            $fullname       = $value['fullname'];
                            $group_name     = $value['group_name'];
                            $status         = Helper::cmsStatus($value['status'], URL::createLink('admin', 'user', 'ajaxStatus', array('id' => $id, 'status' => $value['status'])), $id);
                            $ordering       = '<input type="text" name="order[' . $id . ']" size="5" value="' . $value['ordering'] . '" class="text-area-order">';;
                            $created        = Helper::formatDate('d-m-Y', $value['created']);
                            $created_by     = $value['created_by'];
                            $modified       = Helper::formatDate('d-m-Y', $value['modified']);
                            $modified_by    = $value['modified_by'];
                            $linkEdit = URL::createLink($this->arrParam['module'], $this->arrParam['controller'], 'form', ['id' => $id]);

                            echo '<tr class="' . $row . '">
                                <td class="center">' . $ckb . '</td>
                                <td><a href="' . $linkEdit . '">' . $username . '</a></td>
                                <td class="center">' . $email . '</td>
                                <td class="center">' . $fullname . '</td>
                                <td class="center">' . $group_name . '</td>
                                <td class="center"><a href="#">' . $status . '</a></td>
                                <td class="center">' . $ordering . '</td>
                                <td class="center">' . $created . '</td>
                                <td class="center">' . $created_by . '</td>
                                <td class="center">' . $modified . '</td>
                                <td class="center">' . $modified_by . '</td>
                                <td class="center">' . $id . '</td>
                            </tr>';
                            $i++;
                        };
                    }
                    ?>
                </tbody>
            </table>

            <div>
                <input type="hidden" name="filter_column" value="username">
                <input type="hidden" name="filter_page" value="1">
                <input type="hidden" name="filter_column_dir" value="asc">
            </div>
        </form>

        <div class="clr"></div>
    </div>
</div>