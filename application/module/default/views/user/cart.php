<div class="title"><span class="title_icon"><img src="<?= $imageURL; ?>/bullet1.gif" alt="" title=""></span>My cart</div>
<div class="feat_prod_box_details">
    <?php
    $linkCategory   = URL::createLink('default', 'category', 'index', null, 'categories.html');
    $linkSubmitForm = URL::createLink('default', 'user', 'buy');
    if (!empty($this->items)) {
        $xhtmlCart  = '';
        $total      = 0;
        foreach ($this->items as $key => $value) {
            $bookNameURL    = URL::filterURL($value['name']) . '-' . $value['category_id'] . '-' . $value['id'] . '.html';
            $cateNameURL    = URL::filterURL($value['category_name']);
            $linkBookHTML   = $cateNameURL . DS . $bookNameURL;
            $linkBook       = URL::createLink('default', 'book', 'detail', ['category_id' => $value['category_id'], 'book_id' => $value['id']], $linkBookHTML);
            $picture        = Helper::createImage('book', $value['picture'], ['class' => 'thumb', 'width' => 40, 'height' => 60]);

            $name           = $value['name'];
            $unitPrice      = number_format($value['price']);
            $quantity       = $value['quantity'];
            $total_price    = number_format($value['total_price']);

            // INPUT
            $inputID        = Helper::cmsInput('hidden', 'form[bookid][]', 'input_book_' . $value['id'], $value['id']);
            $inputPrice     = Helper::cmsInput('hidden', 'form[price][]', 'input_price_' . $value['id'], $value['price']);
            $inputQuantity  = Helper::cmsInput('hidden', 'form[quantity][]', 'input_quantity_' . $value['id'], $value['quantity']);
            $inputName      = Helper::cmsInput('hidden', 'form[name][]', 'input_name_' . $value['id'], $value['name']);
            $inputPicture   = Helper::cmsInput('hidden', 'form[picture][]', 'input_picture_' . $value['id'], $value['picture']);
            $inputCateID    = Helper::cmsInput('hidden', 'form[cateid][]', 'input_category_id_' . $value['id'], $value['category_id']);
            $inputCateName  = Helper::cmsInput('hidden', 'form[catename][]', 'input_category_name_' . $value['id'], $value['category_name']);

            $xhtmlCart      .= sprintf('<tr><td><a href="%s">%s</a></td><td>%s</td><td>%s VND</td><td>%s</td><td>%s VND</td></tr>', $linkBook, $picture, $name, $unitPrice, $quantity, $total_price);
            $xhtmlCart      .= $inputID . $inputPrice . $inputQuantity . $inputName . $inputPicture . $inputCateID . $inputCateName;
            $total          += $value['total_price'];
        }

    ?>
        <div>
            <form action="<?= $linkSubmitForm ?>" method="post" name="adminForm" id="adminForm">
                <table class="cart_table">
                    <tbody>
                        <tr class="cart_title">
                            <td>Item pic</td>
                            <td>Book name</td>
                            <td width="18%">Unit price</td>
                            <td>Qty</td>
                            <td width="18%">Total</td>
                        </tr>

                        <?= $xhtmlCart; ?>

                        <tr>
                            <td colspan="4" class="cart_total"><span class="red">TOTAL:</span></td>
                            <td width="22%"> <?= number_format($total); ?> VND</td>
                        </tr>

                    </tbody>
                </table>
            </form>
        </div>

        <a href="<?= $linkCategory ?>" class="continue">&lt; continue</a>
        <a onclick="javascript:submitForm('<?= $linkSubmitForm; ?>')" href="#" class="checkout">checkout &gt;</a>
    <?php
    } else {
        echo  '<h3>Chưa có sách trong giỏ hàng</h3>';
    }
    ?>
</div>
<div class="clear"></div>