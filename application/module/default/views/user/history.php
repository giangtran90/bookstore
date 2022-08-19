<?php
if (!empty($this->items)) {
    $xhtml = '';
    $tableHeader = '<tr class="cart_title"><td>Item pic</td><td>Book name</td><td width="18%">Unit price</td><td>Qty</td><td width="18%">Total</td></tr>';
    foreach ($this->items as $key => $value) {
        $cardID         = $value['id'];
        $date           = date("H:i d/m/Y", strtotime($value['date']));
        $arrBookID      = json_decode($value['books']);
        $arrName        = json_decode($value['names']);
        $arrPrice       = json_decode($value['prices']);
        $arrQuantity    = json_decode($value['quantities']);
        $arrPicture     = json_decode($value['pictures']);
        $arrCateID      = json_decode($value['categories_id']);;
        $arrCateName    = json_decode($value['categories_name']);
        $tableContent   = '';
        $total          = 0;
        foreach ($arrBookID as $keyB => $valueB) {
            $bookNameURL    = URL::filterURL($arrName[$keyB]) . '-' . $arrCateID[$keyB] . '-' . $valueB . '.html';
            $cateNameURL    = URL::filterURL($arrCateName[$keyB]);
            $linkBookHTML  = $cateNameURL . DS . $bookNameURL;
            $linkBook       = URL::createLink('default', 'book', 'detail', ['category_id' => $arrCateID[$keyB], 'book_id' => $valueB], $linkBookHTML);
            $name           = $arrName[$keyB];
            $unitPrice      = $arrPrice[$keyB];
            $quantity       = $arrQuantity[$keyB];
            $priceReal      = $unitPrice * $quantity;
            $picture        = $arrPicture[$keyB];
            $pictureBook    = Helper::createImage('book', $picture, ['class' => 'thumb', 'width' => 40, 'height' => 60]);

            $total      += $priceReal;
            $tableContent .= '<tr>
                            <td><a href="' . $linkBook . '">' . $pictureBook . '</a></td>
                            <td>' . $name . '</td>
                            <td>' . number_format($unitPrice) . '</td>
                            <td>' . $quantity . '</td>
                            <td>' . number_format($priceReal) . '</td>
                        </tr>';
        }

        $xhtml .= '<div class="history-cart">
                        <h3>Mã đơn hàng: ' . $cardID . ' - Ngày đặt hàng: ' . $date . '</h3>
                        <table class="cart_table">
                            <tbody>
                                ' . $tableHeader . $tableContent . '
                                <tr>
                                    <td colspan="4" class="cart_total"><span class="red">TOTAL:</span></td>
                                    <td width="22%"> ' . number_format($total) . ' VND</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>';
    }
} else {
    $xhtml = 'Chưa có đơn hàng nào';
}
?>
<div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet1.gif" alt="" title=""></span>History</div>
<div class="feat_prod_box_details">
    <?= $xhtml; ?>
</div>
<div class="clear"></div>