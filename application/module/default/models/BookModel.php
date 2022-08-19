<?php
class BookModel extends Model
{
    private $_columns = ['id', 'name', 'description', 'price', 'special', 'sale_off', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering', 'category_id'];
    private $_userInfo;

    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_BOOK);
        $userObj          = Session::get('user');
        @$this->_userInfo = $userObj['info'];
    }
    // LIST ITEM
    public function listItem($arrParam, $option = null)
    {
        if ($option['task'] == 'books-in-cat'){
            $category_id = $arrParam['category_id'];
            $query[] = "SELECT `b`.`id`, `b`.`name`, `b`.`picture`, `b`.`description`, `b`.`category_id`, `c`.`name` AS `category_name`";
            $query[] = "FROM `$this->table` AS `b`, `".TBL_CATEGORY."` AS `c`";
            $query[] = "WHERE `b`.`status` = 1 AND `b`.`category_id`='$category_id' AND `b`.`category_id` = `c`.`id`";
            $query[] = "ORDER BY `b`.`ordering` ASC";
    
            $query = implode(" ", $query);
            $result = $this->fetchAll($query);
            return $result;
        }
        
        if ($option['task'] == 'books-relate'){
            $book_id        = $arrParam['book_id'];
            $query          = "SELECT `category_id` FROM `$this->table` WHERE `id` = '$book_id'";
            $result         = $this->fetchRow($query);
            $category_id    = $result['category_id'];

            $queryRelate[]  = "SELECT `b`.`id`, `b`.`name`, `b`.`picture`, `b`.`category_id`, `c`.`name` AS `category_name`";
            $queryRelate[]  = "FROM `$this->table` AS `b`, `".TBL_CATEGORY."` AS `c`";
            $queryRelate[]  = "WHERE `b`.`status` = 1 AND `b`.`category_id`='$category_id' AND `b`.`id` <> '$book_id' AND `b`.`category_id` = `c`.`id`";
            $queryRelate[]  = "ORDER BY `b`.`ordering` ASC";
    
            $queryRelate    = implode(" ", $queryRelate);
            $resultRelate   = $this->fetchAll($queryRelate);
            return $resultRelate;
        }
    }
    // INFO ITEM
    public function infoItem($arrParam, $option = null)
    {
        if ($option['task'] == 'get-cat-name') {
            $category_id    = $arrParam['category_id'];
            $query          = "SELECT `name` FROM `".TBL_CATEGORY."` WHERE `id` = '$category_id'";
            $result         = $this->fetchRow($query);
            return $result['name'];
        }

        if ($option['task'] == 'book-detail') {
            $book_id        = $arrParam['book_id'];
            $query          = "SELECT `b`.`id`, `b`.`name`, `b`.`picture`, `b`.`price`, `b`.`sale_off`, `b`.`description`, `b`.`category_id`, `c`.`name` AS `category_name` FROM `$this->table` AS `b`, `".TBL_CATEGORY."` AS `c` WHERE `b`.`id` = '$book_id' AND `b`.`category_id` = `c`.`id`";
            $result         = $this->fetchRow($query);
            return $result;
        }
    }
}
