<?php
class Pagination
{
    private $totalItems; // Tổng số phần tử
    private $totalItemsPerPage = 1;  // Tong so phan tu xuat hien trong 1 trang
    private $pageRange = 3; // So trang xuat hien
    private $totalPage; // Tong so trang
    private $currentPage = 1; // Trang hien tai

    public function __construct($totalItems, $pagination)
    {
        $this->totalItems           = $totalItems;
        $this->totalItemsPerPage    = $pagination['totalItemsPerPage'];
        if ($pagination['pageRange'] % 2 == 0) $pagination['pageRange'] = $pagination['pageRange'] + 1;
        $this->pageRange            = $pagination['pageRange'];
        $this->currentPage          = $pagination['currentPage'];
        $this->totalPage            = ceil($totalItems / $pagination['totalItemsPerPage']);
    }

    public function showPagination($link)
    {
        if ($this->currentPage < 1 || $this->currentPage > $this->totalPage) {
            
        }
        // Pagination
        $paginationHTML = '';

        if ($this->totalPage > 1) {
            $start         = '<div class="button2-right off"><div class="start"><span>Start</span></div></div>';
            $prev          = '<div class="button2-right off"><div class="prev"><span>Previous</span></div></div>';
            if ($this->currentPage > 1) {
                $start     = '<div class="button2-right"><div class="start"><a onclick="javascript:changePage(1)" href="#">Start</a></div></div>';
                $prev      = '<div class="button2-right"><div class="prev"><a onclick="javascript:changePage(' . ($this->currentPage - 1) . ')" href="#">Previous</a></div></div>';
            }
            $next = '<div class="button2-left off"><div class="next"><span>Next</span></div></div>';
            $end  = '<div class="button2-left off"><div class="end"><span>End</span></div></div>';
            if ($this->currentPage < $this->totalPage) {
                $next = '<div class="button2-left"><div class="next"><a onclick="javascript:changePage(' . ($this->currentPage + 1) . ')" href="#">Next</a></div></div>';
                $end  = '<div class="button2-left"><div class="end"><a onclick="javascript:changePage(' . $this->totalPage . ')" href="#">End</a></div></div>';
            }

            if ($this->pageRange < $this->totalPage) {
                if ($this->currentPage == 1) {
                    $startPage  = 1;
                    $endPage      = $this->pageRange;
                } else if ($this->currentPage == $this->totalPage) {
                    $startPage      = $this->totalPage  - $this->pageRange + 1;
                    $endPage        = $this->totalPage;
                } else {
                    $startPage      = $this->currentPage - ($this->pageRange - 1) / 2;
                    $endPage        = $this->currentPage + ($this->pageRange - 1) / 2;


                    if ($startPage < 1) {
                        $startPage      = 1;
                        $endPage        = $endPage + 1;
                    }
                    if ($endPage > $this->totalPage) {
                        $startPage      = $endPage  - $this->pageRange + 1;
                        $endPage        = $this->totalPage;
                    }
                }
            } else {
                $startPage  = 1;
                $endPage      = $this->totalPage;
            }
            $listPages = '<div class="button2-left"><div class="page">';
            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i == $this->currentPage) {
                    $listPages .= '<span>' . $i . '</span>';
                } else {
                    $listPages .= '<a onclick="javascript:changePage(' . $i . ')" href="#">' . $i . '</a>';
                }
            }
            $listPages .= '</div></div>';
            //<div class="limit">Page 1 of 2</div><input type="hidden" name="limitstart" value="0">
            $endPagination = sprintf('<div class="limit">Page %s of %s</div>', $this->currentPage, $this->totalPage);
            $paginationHTML = '<div class="pagination">' . $start . $prev . $listPages . $next . $end . $endPagination . '</div>';
        }
        return $paginationHTML;
    }
}
