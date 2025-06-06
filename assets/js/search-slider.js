// File: assets/js/search-slider.js
jQuery(document).ready(function($) {
    if ($('#price-slider').length) {
        $("#price-slider").slider({
            range: true,
            min: 0,
            max: 1000,
            values: [ parseInt($('#price_min').val()) || 0, parseInt($('#price_max').val()) || 1000 ],
            slide: function(event, ui) {
                $('#price_min').val(ui.values[0]);
                $('#price_max').val(ui.values[1]);
                $('#price-amount').val("$" + ui.values[0] + " - $" + ui.values[1]);
            }
        });
        $('#price-amount').val("$" + $("#price-slider").slider("values", 0) + " - $" + $("#price-slider").slider("values", 1));
    }
});

