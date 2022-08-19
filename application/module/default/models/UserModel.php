<?php
class UserModel extends Model
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
    private $_userInfo;

    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_USER);
        $userObj          = Session::get('user');
        @$this->_userInfo = $userObj['info'];
    }

    // LIST ITEM
    public function listItem($arrParam, $option = null)
    {
        if ($option['task'] == 'books-in-cart'){
            $cart = Session::get('cart');
            $ids = "";
            if (!empty($cart)){
                foreach ($cart['quantity'] as $key => $value) $ids .= "'$key' ,";
            }
            $query[] = "SELECT `b`.`id`, `b`.`name`, `b`.`picture`, `c`.`name` AS `category_name`, `b`.`category_id`";
            $query[] = "FROM `".TBL_BOOK."` AS `b`, `".TBL_CATEGORY."` AS `c`";
            $query[] = "WHERE `b`.`status` = 1 AND `b`.`id` IN ($ids '0') AND `b`.`category_id` = `c`.`id`";
    
            $query = implode(" ", $query);
            $result  = $this->fetchAll($query);

            foreach ($result as $key => $value) {
                $result[$key]['quantity']       = $cart['quantity'][$value['id']];
                $result[$key]['price']          = $cart['price'][$value['id']] / $cart['quantity'][$value['id']];
                $result[$key]['total_price']    = $cart['price'][$value['id']];
            }

            return $result;
        }    

        if ($option['task'] == 'books-in-history'){
            $username = $this->_userInfo['username'];

            $query[] = "SELECT `id`, `books`, `names`, `prices`, `quantities`, `pictures`, `date`, `categories_id`, `categories_name`";
            $query[] = "FROM `".TBL_CART."`";
            $query[] = "WHERE `username` = '$username'";
            $query[] = "ORDER BY `date` ASC";

            $query   = implode(" ", $query);
            $result  = $this->fetchAll($query);
            return $result;
        }       
               
        if ($option['task'] == 'book-order'){
            $book_id = $arrParam['book_id'];
            $query[] = "SELECT `b`.`id`, `b`.`name`, `b`.`category_id`, `c`.`name` AS `category_name`";
            $query[] = "FROM `".TBL_BOOK."` AS `b`, `".TBL_CATEGORY."` AS `c`";
            $query[] = "WHERE `b`.`id` = '$book_id' AND `b`.`category_id` = `c`.`id`";

            $query   = implode(" ", $query);
            $result  = $this->fetchRow($query);
            return $result;
        }       
    }
    // SAVE ITEM
    public function saveItem($arrParam, $option = null)
    {
        if ($option['task'] == 'submit-cart') {
            $id                 = $this->randomString(8);
            $username           = $this->_userInfo['username'];
            $books              = json_encode($arrParam['form']['bookid']);
            $prices             = json_encode($arrParam['form']['price']);
            $quantities         = json_encode($arrParam['form']['quantity']);
            $names              = json_encode($arrParam['form']['name']);
            $pictures           = json_encode($arrParam['form']['picture']);
            $categories_id      = json_encode($arrParam['form']['cateid']);
            $categories_name    = json_encode($arrParam['form']['catename'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);;;
            $status             = 0;

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $date       = date("Y-m-d H:i:s", time());

            $query = "INSERT INTO `".TBL_CART."` (`id`, `username`, `books`, `categories_id`, `prices`, `quantities`, `names`, `categories_name`, `pictures`, `status`, `date`) VALUES ('$id', '$username', '$books', '$categories_id', '$prices', '$quantities', '$names', N'$categories_name', '$pictures', '$status', '$date')";
            $this->query($query);
            Session::delete('cart');
        }
    }
    // Random String
    private function randomString($length = 5){
        $arrayCharacter = array_merge(range('a','z'), range('0','9'), range('A','Z'));
        $arrayCharacter = implode('',$arrayCharacter);
        $arrayCharacter = str_shuffle($arrayCharacter);
        $resultString   = substr($arrayCharacter, 0, $length);
        return $resultString;
    }
}