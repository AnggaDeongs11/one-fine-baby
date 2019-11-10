jQuery(document).ready(function ($) {
    var array = [] ;
    var is_select_all = true;
    $('input[class=items]').change(function(){
        if ($(this).is(':checked')) {
            array.push($(this).val());
        }
        else {
            array.splice( array.indexOf($(this).val()), 1 );
        }
        $('input[class=items]:checkbox').each(function(){
            if($(this).is(':checked')) is_select_all = true;
            else
            {
                is_select_all = false;
                return false;
            }
        });
        if(is_select_all == true){
            $('input[id=select_all]')[0].checked = true;
        }
        else{
            $('input[id=select_all]')[0].checked = false;
        }
        // $('#delete').attr("type", "submit");
    });
    $('input[id=select_all]').change(function(){
        if(this.checked) {
            // Iterate each checkbox
            $('input[class=items]:checkbox').each(function() {
                if(!$(this).is(':checked')){
                    this.checked = true;
                    array.push($(this).val());
                }
            });
        } else {
            $('input[class=items]:checkbox').each(function() {
                if($(this).is(':checked')){
                    this.checked = false;
                    array.splice( array.indexOf($(this).val()), 1 );
                }
            });
        }
        // array.splice( array.indexOf($(this).val()), 1 );
        console.log(array);
    });
    $('#delete').click(function () {
        $('#delete_id').attr('name','delete_button');
        window.location.href = window.location.href + "?update_giftregistry_item=1" +
            "&remove_item=1&item_id=" + array.toString();
        });
});