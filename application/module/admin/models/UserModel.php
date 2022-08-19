<?php
class UserModel extends Model
{
    private $_columns = ['id', 'username', 'email', 'fullname', 'password', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering', 'group_id'];
    private $_userInfo;
    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_USER);
        $userObj = Session::get('user');
        $this->_userInfo = $userObj['info'];
    }

    public function countItem($arrParam, $option = null)
    {
        $query[] = "SELECT COUNT(`id`) AS `total`";
        $query[] = "FROM `$this->table`";
        $query[] = "WHERE `id` > 0";

        // FILTER: KEYWORD
        if (!empty($arrParam['filter_search'])) {
            $keyword    = $arrParam['filter_search'];
            $query[]    = "AND (`username` LIKE '%$keyword%' OR `email` LIKE '%$keyword%')";
        }

        // FILTER: STATUS
        if (isset($arrParam['filter_state']) && $arrParam['filter_state'] != 'default') {
            $status     = $arrParam['filter_state'];
            $query[]    = "AND `status` = '$status'";
        }

        // FILTER: GROUP
        if (isset($arrParam['filter_group_id']) && $arrParam['filter_group_id'] != 'default') {
            $group      = $arrParam['filter_group_id'];
            $query[]    = "AND `group_id` = '$group'";
        }

        $query = implode(" ", $query);
        $result = $this->fetchRow($query);
        return $result['total'];
    }

    public function itemInSelectbox($arrParam, $option = null)
    {
        if ($option == null) {
            $query                  = "SELECT `id`, `name` FROM `".TBL_GROUP."`";
            $result                 = $this->fetchPairs($query);
            $result['default']      = " - Select Group - ";
            ksort($result);
        }
        return $result;
    }

    public function listItem($arrParam, $option = null)
    {
        $query[] = "SELECT `u`.`id`, `u`.`username`, `u`.`email`, `u`.`fullname`, `u`.`created`, `u`.`created_by`, `u`.`modified`, `u`.`modified_by`, `u`.`status`, `u`.`ordering`, `g`.`name` AS  `group_name`";
        $query[] = "FROM `$this->table` AS `u` LEFT JOIN `" . TBL_GROUP . "` AS `g` ON `u`.`group_id` = `g`.`id`";
        $query[] = "WHERE `u`.`id` > 0";

        // FILTER: KEYWORD
        if (!empty($arrParam['filter_search'])) {
            $keyword = $arrParam['filter_search'];
            $query[] = "AND (`username` LIKE '%$keyword%' OR `email` LIKE '%$keyword%')";
        }

        // FILTER: STATUS
        if (isset($arrParam['filter_state']) && $arrParam['filter_state'] != 'default') {
            $status = $arrParam['filter_state'];
            $query[]    = "AND `u`.`status` = '$status'";
        }

        // FILTER: GROUP
        if (isset($arrParam['filter_group_id']) && $arrParam['filter_group_id'] != 'default') {
            $group = $arrParam['filter_group_id'];
            $query[]    = "AND `u`.`group_id` = '$group'";
        }

        // SORT
        if (!empty($arrParam['filter_column']) && !empty($arrParam['filter_column_dir'])) {
            $column     = $arrParam['filter_column'];
            $columnDir  = $arrParam['filter_column_dir'];
            $query[]    = "ORDER BY `u`.`$column` $columnDir";
        } else {
            $query[]    = "ORDER BY `u`.`id` DESC";
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
                'link'      => URL::createLink('admin', 'user', 'ajaxStatus', array('id' => $id, 'status' => $status))
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
                $ids = $this->createWhereDeleteSQL($cid);
                $query = "DELETE FROM `$this->table` WHERE `id` IN ($ids)";
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
            $query[]    = "SELECT `id`, `username`, `email`, `fullname`, `status`, `group_id`, `ordering`";
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
        if ($option['task'] == 'add') {
            $arrParam['form']['created']    = date('Y-m-d', time());
            $arrParam['form']['created_by'] = $this->_userInfo['username'];
            $arrParam['form']['password'] = md5($arrParam['form']['password']);
            $data = array_intersect_key($arrParam['form'], array_flip($this->_columns));
            $this->insert($data);
            Session::set('message', ['class' => 'success', 'content' => 'Dữ liệu đã được lưu thành công!']);
            return $this->lastID();
        }
        if ($option['task'] == 'edit') {
            unset($arrParam['form']['username']);
            $arrParam['form']['modified']       = date('Y-m-d', time());
            $arrParam['form']['modified_by']    = $this->_userInfo['username'];
            if ($arrParam['form']['password'] != null){
                $arrParam['form']['password']   = md5($arrParam['form']['password']);
            } else {
                unset($arrParam['form']['password']);
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
