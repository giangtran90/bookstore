// CHANGE ICON STATUS
function changeStatus(url){
    $.get(url, function(data){
        /*
        * $xhtml    = '<a class="jgrid" id="status-' . $id . '" href="javascript:changeStatus(\'' . $link . '\');">
                            <span class="state ' . $strStatus . '"></span>
                        </a>';
        */
        var element     = 'a#status-' + data['id'];
        var classRemove = 'publish';
        var classAdd    = 'unpublish';
        if(data['status'] == 1) {
            classRemove = 'unpublish';
            classAdd    = 'publish';
        }
        $(element) . attr('href', "javascript:changeStatus('" + data['link'] + "')");
        $(element + ' span') . removeClass(classRemove). addClass(classAdd);
    }, 'json');
}

// CHANGE ICON GROUPACP
function changeGroupACP(url){
    $.get(url, function(data){
        var element = 'a#group_acp-' + data['id'];
        var classRemove = 'publish';
        var classAdd    = 'unpublish';
        if (data['group_acp'] == 1){
            classRemove = 'unpublish';
            classAdd    = 'publish';
        }
        $(element) . attr('href',"javascript:changeGroupACP('" + data['link'] + "')");
        $(element + ' span') . removeClass(classRemove) . addClass(classAdd);
    }, 'json');
}

// CHANGE ICON SPECIAL
function changeSpecial(url){
    $.get(url, function(data){
        var element = 'a#special-' + data['id'];
        var classRemove = 'publish';
        var classAdd    = 'unpublish';
        if (data['special'] == 1){
            classRemove = 'unpublish';
            classAdd    = 'publish';
        }
        $(element) . attr('href',"javascript:changeSpecial('" + data['link'] + "')");
        $(element + ' span') . removeClass(classRemove) . addClass(classAdd);
    }, 'json');
}

// SUBMIT FORM
function submitForm(url){
    $('#adminForm') . attr('action', url);
    $('#adminForm') . submit();
}

// SORT LIST
function sortList(column, order){
    $('input[name=filter_column]').val(column);
    $('input[name=filter_column_dir]').val(order);
    $('#adminForm') . submit();
}

// PAGINATION
function changePage(page){
    $('input[name=filter_page]').val(page);
    $('#adminForm') . submit();
}

// CHECK ALL - SEARCH - CLEAR
$(document).ready(function() {
    $('input[name=checkall-toggle]') . change(function() {
        var checkStatus = this.checked;//this thay cho toan bo noi dung 'input[name=checkall-toggle]'
        $('#adminForm') . find(':checkbox') . each(function() {
            this.checked = checkStatus;
        });
    });

    $('#filter-bar button[name=submit-keyword]') . click(function() {
        $('#adminForm') . submit();
    });

    $('#filter-bar button[name=clear-keyword]') . click(function() {
        $('#filter-bar input[name=filter_search]') . val('');
        $('#adminForm') . submit();
    });

    $('#filter-bar select[name=filter_state]') . change(function() {
        $('#adminForm') . submit();
    });

    $('#filter-bar select[name=filter_special]') . change(function() {
        $('#adminForm') . submit();
    });

    $('#filter-bar select[name=filter_group_acp]') . change(function() {
        $('#adminForm') . submit();
    });

    $('#filter-bar select[name=filter_group_id]') . change(function() {
        $('#adminForm') . submit();
    });

    $('#filter-bar select[name=filter_category_id]') . change(function() {
        $('#adminForm') . submit();
    });

})




