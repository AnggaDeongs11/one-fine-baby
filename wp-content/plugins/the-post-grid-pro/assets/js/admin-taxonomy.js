(function ($) {

    $(function () {
        if($("select.rt-select2").length){
            $("select.rt-select2").select2({
                theme: "classic",
                dropdownAutoWidth: true,
                width: '100%'
            });
        }

        $('#tpg-post-type').on('change', function () {
            var self = $(this),
                pt = self.val(),
                target = $('#tpg-taxonomy');
            if(pt){
                var data ="pt="+ pt;
                AjaxCall('tpg-get-taxonomy-list', data, function (data) {
                    if(!data.error) {
                        target.html(data.data);
                    }
                    $("#order-target").html("");
                });
            }
        });

        $('#tpg-taxonomy').on('change', function () {
            var self = $(this),
                tax = self.val(),
                target = $('#term-wrapper');
            if(tax){
                var data ="tax="+ tax;
                AjaxCall('tpg-get-term-list', data, function (data) {
                    if(!data.error) {
                        target.html(data.data);
                        var fixHelper = function (e, ui) {
                            ui.children().children().each(function () {
                                $(this).width($(this).width());
                            });
                            return ui;
                        };
                        $('#order-target').sortable({
                            items: 'li',
                            axis: 'y',
                            helper: fixHelper,
                            placeholder: 'placeholder',
                            opacity: 0.65,
                            update: function (e, ui) {
                                var target = $('#order-target'),
                                    taxonomy = target.attr("data-taxonomy"),
                                    terms = target.find('li').map(function () {
                                        return $(this).data('id');
                                    }).get(),
                                    data = "taxonomy="+ taxonomy +"&terms="+terms;
                                AjaxCall('tpg-update-term-order', data, function (data) {
                                    console.log(data);
                                    if(data.error){
                                        console.log('Error');
                                    }
                                });
                            }
                        });
                    }
                });
            }else{
                alert('Please select a taxonomy');
            }
        });

    });

    function AjaxCall(action, arg, handle) {
        var data;
        if (action) data = "action=" + action;
        if (arg) data = arg + "&action=" + action;
        if (arg && !action) data = arg;
        data = data + "&"+rttpg.nonceID+"=" + rttpg.nonce;
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            beforeSend: function() {
                $('body').append($("<div id='tpg-loading'><span class='tpg-loading'>Updating ...</span></div>"));
            },
            success: function(data) {
                $("#tpg-loading").remove();
                handle(data);
            },
            error: function( jqXHR, textStatus, errorThrown ) {
                $("#tpg-loading").remove();
                alert( textStatus + ' (' + errorThrown + ')' );
            }
        });
    }
})(jQuery);
