<?php
class BookModel extends Model
{
    private $_columns = ['id', 'name', 'description', 'price', 'special', 'sale_off', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering', 'category_id'];
    private $_userInfo;
    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_BOOK);
        $userObj = Session::get('user');
        @$this->_userInfo = $userObj['info'];
    }

    public function countItem($arrParam, $option = null)
    {
        $query[] = "SELECT COUNT(`id`) AS `total`";
        $query[] = "FROM `$this->table`";
        $query[] = "WHERE `id` > 0";

        // FILTER: KEYWORD
        if (!empty($arrParam['filter_search'])) {
            $keyword    = $arrParam['filter_search'];
            $query[]    = "AND (`name` LIKE '%$keyword%')";
        }

        // FILTER: STATUS
        if (isset($arrParam['filter_state']) && $arrParam['filter_state'] != 'default') {
            $status     = $arrParam['filter_state'];
            $query[]    = "AND `status` = '$status'";
        }

        // FILTER: SPECIAL
        if (isset($arrParam['filter_special']) && $arrParam['filter_special'] != 'default') {
            $special     = $arrParam['filter_special'];
            $query[]     = "AND `special` = '$special'";
        }

        // FILTER: GROUP CATEGORY
        if (isset($arrParam['filter_category_id']) && $arrParam['filter_category_id'] != 'default') {
            $group      = $arrParam['filter_category_id'];
            $query[]    = "AND `category_id` = '$group'";
        }

        $query  = implode(" ", $query);
        $result = $this->fetchRow($query);
        return $result['total'];
    }

    public function itemInSelectbox($arrParam, $option = null)
    {
        if ($option == null) {
            $query                  = "SELECT `id`, `name` FROM `".TBL_CATEGORY."`";
            $result                 = $this->fetchPairs($query);
            $result['default']      = " - Select Category - ";
            ksort($result);
        }
        return $result;
    }

    public function listItem($arrParam, $option = null)
    {
        $query[] = "SELECT `b`.`id`, `b`.`name`, `b`.`picture`, `b`.`price`, `b`.`special`, `b`.`sale_off`, `b`.`created`, `b`.`created_by`, `b`.`modified`, `b`.`modified_by`, `b`.`status`, `b`.`ordering`, `b`.`category_id`, `c`.`name` AS  `category_name`";
        $query[] = "FROM `$this->table` AS `b` LEFT JOIN `" . TBL_CATEGORY . "` AS `c` ON `b`.`category_id` = `c`.`id`";
        $query[] = "WHERE `b`.`id` > 0";

        // FILTER: KEYWORD
        if (!empty($arrParam['filter_search'])) {
            $keyword = $arrParam['filter_search'];
            $query[] = "AND (`b`.`name` LIKE '%$keyword%')";
        }
        // FILTER: STATUS
        if (isset($arrParam['filter_state']) && $arrParam['filter_state'] != 'default') {
            $status     = $arrParam['filter_state'];
            $query[]    = "AND `b`.`status` = '$status'";
        }

        // FILTER: SPECIAL
        if (isset($arrParam['filter_special']) && $arrParam['filter_special'] != 'default') {
            $special     = $arrParam['filter_special'];
            $query[]     = "AND `b`.`special` = '$special'";
        }

        // FILTER: CATEGORY
        if (isset($arrParam['filter_category_id']) && $arrParam['filter_category_id'] != 'default') {
            $category   = $arrParam['filter_category_id'];
            $query[]    = "AND `b`.`category_id` = '$category'";
        }

        // SORT
        if (!empty($arrParam['filter_column']) && !empty($arrParam['filter_column_dir'])) {
            $column     = $arrParam['filter_column'];
            $columnDir  = $arrParam['filter_column_dir'];
            $query[]    = "ORDER BY `b`.`$column` $columnDir";
        } else {
            $query[]    = "ORDER BY `b`.`id` DESC";
        }

        // PAGINATION
        $pagination = $arrParam['pagination'];
        $totalItemsPerPage = $pagination['totalItemsPerPage'];
        if ($totalItemsPerPage > 0) {
            $position     = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
            $query[]     = "LIMIT $position, $totalItemsPerPage";
        }

        $query = implode(" ", $query);
        $result = $this->fetchAll($query);
        return $result;
    }
    // CHANGE STATUS - GROUP
    public function changeStatus($arrParam, $option = null)
    {
        if ($option['task'] == 'change-ajax-status') {
            $status         = ($arrParam['status'] == 0) ? 1 : 0;
            $id             = $arrParam['id'];
            $modified       = date('Y-m-d', time());
            $modified_by    = $this->_userInfo['username'];
            $query          = "UPDATE `$this->table` SET `status` = '$status', `modified` = '$modified', `modified_by` = '$modified_by' WHERE `id` = '$id'";
            $this->query($query);
            $result = [
                'id'        => $id,
                'status'    => $status,
                'link'      => URL::createLink('admin', 'book', 'ajaxStatus', array('id' => $id, 'status' => $status))
            ];
            return $result;
        }

        if ($option['task'] == 'change-ajax-special') {
            $special        = ($arrParam['special'] == 0) ? 1 : 0;
            $id             = $arrParam['id'];
            $modified       = date('Y-m-d', time());
            $modified_by    = $this->_userInfo['username'];
            $query          = "UPDATE `$this->table` SET `special` = '$special', `modified` = '$modified', `modified_by` = '$modified_by' WHERE `id` = '$id'";
            $this->query($query);
            $result = [
                'id'        => $id,
                'special'    => $special,
                'link'      => URL::createLink('admin', 'book', 'ajaxSpecial', array('id' => $id, 'special' => $special))
            ];
            return $result;
        }

        if ($option['task'] == 'change-status') {
            $status         = $arrParam['type'];
            $cid            = $arrParam['cid'];
            $modified       = date('Y-m-d', time());
            $modified_by    = $this->_userInfo['username'];
            if (!empty($cid)) {
                $ids = implode(', ', $cid);
                // $ids = $this->createWhereDeleteSQL($cid); cách 2 để lấy chuỗi ids
                $query  = "UPDATE `$this->table` SET `status` = '$status', `modified` = '$modified', `modified_by` = '$modified_by' WHERE `id` IN ($ids)";
                $this->query($query);
                Session::set('message', ['class' => 'success', 'content' => 'Có ' . $this->affectedRows() . ' phần tử được thay đổi trạng thái!']);
            } else {
                Session::set('message', ['class' => 'error', 'content' => 'Vui lòng chọn phần tử cần thay đổi trạng thái!']);
            }
        }
    }
    // DELETE ITEMS
    public function deleteItems($arrParam, $option = null)
    {
        if ($option == null) {
            $cid    = $arrParam['cid'];
            if (!empty($cid)) {
                $ids    = $this->createWhereDeleteSQL($cid);
                $query  = "DELETE FROM `$this->table` WHERE `id` IN ($ids)";
                $this->query($query);
                Session::set('message', ['class' => 'success', 'content' => 'Có ' . $this->affectedRows() . ' phần tử đã được xóa!']);
            } else {
                Session::set('message', ['class' => 'error', 'content' => 'Vui lòng chọn phần tử cần xóa!']);
            }
        }
    }
    // INFO ITEM
    public function infoItem($arrParam, $option = null)
    {
        if ($option == null) {
            $id         = $arrParam['id'];
            $query[]    = "SELECT `id`, `name`, `picture`, `description`, `price`, `special`, `sale_off`, `status`, `category_id`, `ordering`";
            $query[]    = "FROM `$this->table`";
            $query[]    = "WHERE `id` = '$id'";
            $query      = implode(" ", $query);
            $result     = $this->fetchRow($query);
            return $result;
        }
    }
    // SAVE ITEM
    public function saveItem($arrParam, $option = null)
    {
        require_once LIBRARY_EXTENDS_PATH . 'Upload.php';
        $uploadObj = new Upload();

        if ($option['task'] == 'add') {
            $arrParam['form']['picture']     = $uploadObj->uploadFile($arrParam['form']['picture'], 'book');
            $arrParam['form']['created']     = date('Y-m-d', time());
            $arrParam['form']['created_by']  = $this->_userInfo['username'];
            $arrParam['form']['description']    = $this->specialCharacters($arrParam['form']['description']);
            $arrParam['form']['name']           = $this->specialCharacters($arrParam['form']['name']);
            
            $data = array_intersect_key($arrParam['form'], array_flip($this->_columns));
            $this->insert($data);
            Session::set('message', ['class' => 'success', 'content' => 'Dữ liệu đã được lưu thành công!']);
            return $this->lastID();
        }
        if ($option['task'] == 'edit') {
            $arrParam['form']['modified']       = date('Y-m-d', time());
            $arrParam['form']['modified_by']    = $this->_userInfo['username'];
            $arrParam['form']['description']    = $this->specialCharacters($arrParam['form']['description']);
            $arrParam['form']['name']           = $this->specialCharacters($arrParam['form']['name']);

            if ($arrParam['form']['picture']['name'] == null){
                unset($arrParam['form']['picture']);
            } else {
                $uploadObj->removeFile($arrParam['form']['picture_hidden'], 'book');
                $arrParam['form']['picture']    = $uploadObj->uploadFile($arrParam['form']['picture'], 'book');
            }
            
            $data = array_intersect_key($arrParam['form'], array_flip($this->_columns));
            $this->update($data, [['id', $arrParam['form']['id']]]);
            Session::set('message', ['class' => 'success', 'content' => 'Dữ liệu đã được lưu thành công!']);
            return $arrParam['form']['id'];
        }
    }
    // CHANGE ORDERING
    public function ordering($arrParam, $option = null)
    {
        if ($option == null) {
            $order          = $arrParam['order'];
            $modified       = date('Y-m-d', time());
            $modified_by    = $this->_userInfo['username'];
            $i              = 0;
            if (!empty($order)) {
                foreach ($order as $id => $ordering) {
                    $i++;
                    $query      = "UPDATE `$this->table` SET `ordering` = '$ordering', `modified` = '$modified', `modified_by` = '$modified_by' WHERE `id` = '$id'";
                    $this->query($query);
                }
                Session::set('message', ['class' => 'success', 'content' => 'Có ' . $i . ' phần tử đã được thay đổi ordering!']);
            }
        }
    }
}
