<?php
class CategoryModel extends Model
{
    private $_columns = ['id', 'name', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering'];
    private $_userInfo;

    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_CATEGORY);
        $userObj         = Session::get('user');
        @$this->_userInfo = $userObj['info'];
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

        $query = implode(" ", $query);
        $result = $this->fetchRow($query);
        return $result['total'];
    }

    public function listItem($arrParam, $option = null)
    {
        $query[] = "SELECT `id`, `name`, `picture`, `created`, `created_by`, `modified`, `modified_by`, `status`, `ordering`";
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
    // CHANGE STATUS
    public function changeStatus($arrParam, $option = null)
    {
        if ($option['task'] == 'change-ajax-status') {
            $status         = ($arrParam['status'] == 0) ? 1 : 0;
            $id             = $arrParam['id'];
            $modified       = date('Y-m-d', time());
            $modified_by    = $this->_userInfo['username'];
            $query  = "UPDATE `$this->table` SET `status` = '$status', `modified` = '$modified', `modified_by` = '$modified_by' WHERE `id` = '$id'";
            $this->query($query);
            $result = [
                'id'        => $id,
                'status'    => $status,
                'link'      => URL::createLink('admin', 'category', 'ajaxStatus', array('id' => $id, 'status' => $status))
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
                // Remove image
                require_once LIBRARY_EXTENDS_PATH . 'Upload.php';
                $uploadObj = new Upload();
                $query = "SELECT `id`, `picture` AS `name` FROM `$this->table` WHERE `id` IN ($ids)";
                $arrImg = $this->fetchPairs($query);
                foreach ($arrImg as $value) {
                    $uploadObj->removeFile($value, 'category');
                }
                // Delete in database
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
            $query[]    = "SELECT `id`, `name`, `status`, `picture`, `ordering`";
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
            $arrParam['form']['picture']    = $uploadObj->uploadFile($arrParam['form']['picture'], 'category');
            $arrParam['form']['created']    = date('Y-m-d', time());
            $arrParam['form']['created_by'] =  $this->_userInfo['username'];
            $data = array_intersect_key($arrParam['form'], array_flip($this->_columns));
            $this->insert($data);
            Session::set('message', ['class' => 'success', 'content' => 'Dữ liệu đã được lưu thành công!']);
            return $this->lastID();
        }
        if ($option['task'] == 'edit') {
            $arrParam['form']['modified']    = date('Y-m-d', time());
            $arrParam['form']['modified_by'] = $this->_userInfo['username'];

            if ($arrParam['form']['picture']['name'] == null){
                unset($arrParam['form']['picture']);
            } else {
                $uploadObj->removeFile($arrParam['form']['picture_hidden'], 'category');
                $arrParam['form']['picture']    = $uploadObj->uploadFile($arrParam['form']['picture'], 'category');
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
