jQuery(function ($) {
    $(document).ready(function () {
        // Passes vars
        // baseCurrency
        // baseCurrencySymbol
        // currencyAllSymbols
        // *********
        var baseCurrencyValue = 1;
        var defaultCurrencyValue = 1;
        var defaultCurrencySymbol = "";
        var defaultCurrencyMount = 0;
        var selectedCurrency = baseCurrency;
        var selectedCurrencyValue = 1;
        var selectedCurrencySymbol = baseCurrencySymbol;
        var newCurrencyValue = "";

        /* When user select a currency from dropdown */
        $("#krokedilCurrency").change(function () {
            selectedCurrency = $(this).children("option:selected").text();
            selectedCurrencyValue = parseFloat($(this).children("option:selected").val()).toFixed(2);
            selectedCurrencySymbol = $(this).children("option:selected").attr('symbol');
            $('span.woocommerce-Price-amount').each(function(){
                defaultCurrencyMount = parseFloat($(this).text());
                newCurrencyValue =  ((defaultCurrencyMount / defaultCurrencyValue) * selectedCurrencyValue).toString();
                newCurrencyValue =  parseFloat(newCurrencyValue).toFixed(2);
                $(this).replaceWith("<span class=\"woocommerce-Price-amount amount\">" + parseFloat(newCurrencyValue).toFixed(2) + "&nbsp;<span class=\"woocommerce-Price-currencySymbol\">" + selectedCurrencySymbol + "</span></span>");
            });
            defaultCurrencyValue = selectedCurrencyValue;
        });

    });
})(jQuery);