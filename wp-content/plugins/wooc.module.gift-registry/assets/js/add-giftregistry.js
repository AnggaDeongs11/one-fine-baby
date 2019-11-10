jQuery(document).ready(function ($) {
    jQuery("#accordion-giftregisty").accordion({
        collapsible: true,
        heightStyle: "content",
    });
    jQuery('#event_date_time').datepicker({
        dateFormat: 'dd-mm-yy',
    });
    //traslate language in calendar
    /*
    jQuery.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    jQuery.datepicker.setDefaults(jQuery.datepicker.regional['es']);
    */
    //co_r
    var co_re = jQuery('#co_r').val();
    switch (co_re) {
        case '1':
            jQuery('#co_registrants').show();
            break;
        default:
            jQuery('#co_registrants').hide();
            break;
    }
    jQuery('#co_r').on('change', function (event) {
        var co_re = jQuery(this).val();
        if (co_re == '1') {

        }
        switch (co_re) {
            case '1':
                jQuery('#co_registrants').show();
                break;
            default:
                jQuery('#co_registrants').hide();
                break;
        }
    });
    var $pass = jQuery('#role').val();
    switch ($pass) {
        case '1':
            jQuery('#check_pass').show();
            break;
        default:
            jQuery('#check_pass').hide();
            break;
    }
    jQuery('#role').on('change', function (event) {
        var $pass = jQuery(this).val();
        if ($pass == '1') {

        }
        switch ($pass) {
            case '1':
                jQuery('#check_pass').show();
                break;
            default:
                jQuery('#check_pass').hide();
                break;
        }
    });
    $( "#option_quantity" ).change(function() {
        if($('.dropdownlist').is(':selected')){
            $('#dropdownlist').attr('style','display:block');
            $('#textfield').attr('style','display:none');
        }else{
            $('#dropdownlist').attr('style','display:none');
            $('#textfield').attr('style','display:block');
        }
    });
});