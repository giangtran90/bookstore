<?php
class GroupModel extends Model
{
    private $_columns = ['id', 'name', 'group_acp', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering'];

    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_GROUP);
    }

    public function countItem($arrParam, $option = null)
    {
        $query[] = "SELECT COUNT(`id`) AS `total`";
        $query[] = "FROM `$this->table`";

        // FILTER: KEYWORD
        $flagWhere = false;
        if (!empty($arrParam['filter_search'])) {
            $keyword    = $arrParam['filter_search'];
            $query[]    = "WHERE `name` LIKE '%$keyword%'";
            $flagWhere  = true;
        }

        // FILTER: STATUS
        if (isset($arrParam['filter_state']) && $arrParam['filter_state'] != 'default') {
            $status = $arrParam['filter_state'];
            if ($flagWhere == true) {
                $query[]    = "AND `status` = '$status'";
            } else {
                $query[]    = "WHERE `status` = '$status'";
                $flagWhere  = true;
            }
        }

        // FILTER: GROUP ACP
        if (isset($arrParam['filter_group_acp']) && $arrParam['filter_group_acp'] != 'default') {
            $groupACP = $arrParam['filter_group_acp'];
            if ($flagWhere == true) {
                $query[] = "AND `group_acp` = '$groupACP'";
            } else {
                $query[] = "WHERE `group_acp` = '$groupACP'";
            }
        }

        $query = implode(" ", $query);
        $result = $this->fetchRow($query);
        return $result['total'];
    }

    public function listItem($arrParam, $option = null)
    {
        $query[] = "SELECT `id`, `name`, `group_acp`, `created`, `created_by`, `modified`, `modified_by`, `status`, `ordering`";
        $query[] = "FROM `$this->table`";

        // FILTER: KEYWORD
        $flagWhere = false;
        if (!empty($arrParam['filter_search'])) {
            $keyword = $arrParam['filter_search'];
            $query[] = "WHERE `name` LIKE '%$keyword%'";
            $flagWhere = true;
        }

        // FILTER: STATUS
        if (isset($arrParam['filter_state']) && $arrParam['filter_state'] != 'default') {
            $status = $arrParam['filter_state'];
            if ($flagWhere == true) {
                $query[]    = "AND `status` = '$status'";
            } else {
                $query[]    = "WHERE `status` = '$status'";
                $flagWhere  = true;
            }
        }

        // FILTER: GROUP ACP
        if (isset($arrParam['filter_group_acp']) && $arrParam['filter_group_acp'] != 'default') {
            $groupACP = $arrParam['filter_group_acp'];
            if ($flagWhere == true) {
                $query[] = "AND `group_acp` = '$groupACP'";
            } else {
                $query[] = "WHERE `group_acp` = '$groupACP'";
            }
        }

        // SORT
        if (!empty($arrParam['filter_column']) && !empty($arrParam['filter_column_dir'])) {
            $column     = $arrParam['filter_column'];
            $columnDir  = $arrParam['filter_column_dir'];
            $query[]    = "ORDER BY `$column` $columnDir";
        } else {
            $query[]    = "ORDER BY `id` DESC";
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
    // CHANGE STATUS - GROUPACP
    public function changeStatus($arrParam, $option = null)
    {
        if ($option['task'] == 'change-ajax-status') {
            $status = ($arrParam['status'] == 0) ? 1 : 0;
            $id     = $arrParam['id'];
            $query  = "UPDATE `$this->table` SET `status` = '$status' WHERE `id` = '$id'";
            $this->query($query);
            $result = [
                'id'        => $id,
                'status'    => $status,
                'link'      => URL::createLink('admin', 'group', 'ajaxStatus', array('id' => $id, 'status' => $status))
            ];
            return $result;
        }

        if ($option['task'] == 'change-ajax-groupACP') {
            $group_acp   = ($arrParam['group_acp'] == 0) ? 1 : 0;
            $id         = $arrParam['id'];
            $query      = "UPDATE `$this->table` SET `group_acp` = '$group_acp' WHERE `id` = '$id'";
            $this->query($query);
            $result = [
                'id'        => $id,
                'group_acp'  => $group_acp,
                'link'      => URL::createLink('admin', 'group', 'ajaxGroupACP', array('id' => $id, 'group_acp' => $group_acp))
            ];
            return $result;
        }

        if ($option['task'] == 'change-status') {
            $status = $arrParam['type'];
            $cid    = $arrParam['cid'];
            if (!empty($cid)) {
                $ids = implode(', ', $cid);
                // $ids = $this->createWhereDeleteSQL($cid); cách 2 để lấy chuỗi ids
                $query  = "UPDATE `$this->table` SET `status` = '$status' WHERE `id` IN ($ids)";
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
            $query[]    = "SELECT `id`, `name`, `status`, `group_acp`, `ordering`";
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
        $userObj = Session::get('user');
        $userInfo = $userObj['info'];
        if ($option['task'] == 'add') {
            $arrParam['form']['created']    = date('Y-m-d', time());
            $arrParam['form']['created_by'] =  $userInfo['username'];
            $data = array_intersect_key($arrParam['form'], array_flip($this->_columns));
            $this->insert($data);
            Session::set('message', ['class' => 'success', 'content' => 'Dữ liệu đã được lưu thành công!']);
            return $this->lastID();
        }
        if ($option['task'] == 'edit') {
            $arrParam['form']['modified']    = date('Y-m-d', time());
            $arrParam['form']['modified_by'] = $userInfo['username'];
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
            $order  = $arrParam['order'];
            $i      = 0;
            if (!empty($order)) {
                foreach ($order as $id => $ordering) {
                    $i++;
                    $query      = "UPDATE `$this->table` SET `ordering` = '$ordering' WHERE `id` = '$id'";
                    $this->query($query);
                }
                Session::set('message', ['class' => 'success', 'content' => 'Có ' . $i . ' phần tử đã được thay đổi ordering!']);
            }
        }
    }
}
