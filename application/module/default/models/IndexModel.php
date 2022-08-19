<?php
class IndexModel extends Model
{
    private $_columns = [
                            'id', 
                            'username', 
                            'email', 
                            'fullname', 
                            'password', 
                            'created', 
                            'created_by', 
                            'modified', 
                            'modified_by', 
                            'register_date',
                            'register_ip',
                            'status', 
                            'ordering', 
                            'group_id'
                        ];

    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_USER);
    }

    // SAVE ITEM
    public function saveItem($arrParam, $option = null)
    {
        if ($option['task'] == 'user-register') {
            $arrParam['form']['password']       = md5($arrParam['form']['password']);
            $arrParam['form']['register_date']  = date("Y-m-d H:i:s", time());
            $arrParam['form']['register_ip']    = $_SERVER['SERVER_ADDR'];
            $arrParam['form']['status']         = 0;
            $data = array_intersect_key($arrParam['form'], array_flip($this->_columns));
            $this->insert($data);
            return $this->lastID();
        }
    }
    // INFO ITEMS
    public function infoItems($arrParam, $option = null){
        if ($option === null){
            $email      = $arrParam['form']['email'];
            $password   = md5($arrParam['form']['password']);
            $query[] = "SELECT `u`.`id`, `u`.`username`, `u`.`fullname`, `u`.`email`, `u`.`group_id`, `g`.`group_acp`";
            $query[] = "FROM `user` AS `u` LEFT JOIN `group` AS `g` ON `u`.`group_id` = `g`.`id`";
            $query[] = "WHERE `email` = '$email' AND `password` = '$password'";
            $query = implode(' ', $query);

            $result = $this->fetchRow($query);
            return $result;
        }
    }
    // LIST ITEMS
    public function listItems($arrParam, $option = null)
    {
        if ($option['task'] == 'books-featured'){
            $query[] = "SELECT `b`.`id`, `b`.`name`, `b`.`picture`, `b`.`description`, `b`.`category_id`, `c`.`name` AS `category_name`";
            $query[] = "FROM `".TBL_BOOK."` AS `b`, `".TBL_CATEGORY."` AS `c`";
            $query[] = "WHERE `b`.`status` = 1 AND `b`.`special` = 1 AND `b`.`category_id` = `c`.`id`";
            $query[] = "ORDER BY `b`.`ordering` ASC";
            $query[] = "LIMIT 0,2";
    
            $query = implode(" ", $query);
            $result = $this->fetchAll($query);
            return $result;
        }

        if ($option['task'] == 'books-new'){
            $query[] = "SELECT `b`.`id`, `b`.`name`, `b`.`picture`, `b`.`category_id`, `c`.`name` AS `category_name`";
            $query[] = "FROM `".TBL_BOOK."` AS `b`, `".TBL_CATEGORY."` AS `c`";
            $query[] = "WHERE `b`. `id` > 0 AND `b`.`category_id` = `c`.`id`";
            $query[] = "ORDER BY `b`. `id` DESC";
            $query[] = "LIMIT 0,3";
    
            $query = implode(" ", $query);
            $result = $this->fetchAll($query);
            return $result;
        }
    }
}