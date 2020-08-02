jQuery(function ($) {
    $(document).ready(function () {
        // Passes vars ------
        // defaultCurrencyK
        // defaultCurrencyKValue
        // defaultCurrencyKSymbol
        // currencyAllSymbols
        // numberDecimalsK
        // DecimalSeparatorSymbolK
        // ThousandSeparatorSymbolK
        // *********
        // var baseCurrencyValue = 1;
        var defaultCurrencyMount = 0;
        var selectedCurrency = defaultCurrencyK;
        var selectedCurrencyValue = 1;
        var selectedCurrencySymbol = defaultCurrencyKSymbol;
        var newCurrencyValue = "";


        /* Add thousand symbol to the price ---------------------------------------------------------  */
        function addThousand(number, decimalSymbol, decimalsNumber) {
            number = number.toString();
            decimalSymbol = decimalSymbol || "."; // Default to period as decimal separator
            decimalsNumber = decimalsNumber || 0; // Default decimal number
            var output = Number(number.split(decimalSymbol)[0]).toLocaleString('en');
            if (decimalsNumber) {
                output += decimalSymbol + number.split(decimalSymbol)[1];
            }
            return output;
        }

        /* When user choose a currency  --------------------------------------------------------- */
        function changeCurrency() {
            selectedCurrency = $(this).children("option:selected").text();
            selectedCurrencyValue = parseFloat($(this).children("option:selected").val()).toFixed(numberDecimalsK);
            selectedCurrencySymbol = $(this).children("option:selected").attr('symbol');
            $('#krokedilCurrency option[id = ' + defaultCurrencyK + ']').removeAttr('selected');
            $(this).children("option:selected").attr('selected', true);
            defaultCurrencyK = selectedCurrency;
            $('span.woocommerce-Price-amount').each(function(){
                defaultCurrencyMount = parseFloat($(this).text().split(ThousandSeparatorSymbolK).join('')).toFixed(numberDecimalsK);
                var symbol = $(this).children('span.woocommerce-Price-currencySymbol').text();
                selectedCurrencySymbol = decodeHTMLEntities(selectedCurrencySymbol);
                if ( symbol === selectedCurrencySymbol) {
                    newCurrencyValue =  parseFloat(((defaultCurrencyMount / 1) * selectedCurrencyValue).toString()).toFixed(numberDecimalsK);
                } else {
                    newCurrencyValue =  parseFloat(((defaultCurrencyMount / defaultCurrencyKValue) * selectedCurrencyValue).toString()).toFixed(numberDecimalsK);
                }
                newCurrencyValue = addThousand(newCurrencyValue, DecimalSeparatorSymbolK, numberDecimalsK);
                // newCurrencyValue =  parseFloat(newCurrencyValue).toFixed(numberDecimalsK);
                $(this).replaceWith("<span class=\"woocommerce-Price-amount amount\">" + newCurrencyValue + "&nbsp;<span class=\"woocommerce-Price-currencySymbol\">" + selectedCurrencySymbol + "</span></span>");
            });
            defaultCurrencyKValue = selectedCurrencyValue;
            console.log("clicked");
        }

        /* When the page loads --------------------------------------------------------- */
        $('#krokedilCurrency option[id = ' + defaultCurrencyK + ']').attr('selected', true);
        $('span.woocommerce-Price-amount').each(function(){
            defaultCurrencyMount = parseFloat($(this).text().split(ThousandSeparatorSymbolK).join('')).toFixed(numberDecimalsK);

            var symbol = $(this).children('span.woocommerce-Price-currencySymbol').text();
            selectedCurrencySymbol = decodeHTMLEntities(selectedCurrencySymbol);
            if ( symbol === selectedCurrencySymbol) {
                newCurrencyValue =  parseFloat((defaultCurrencyMount * 1).toString()).toFixed(numberDecimalsK);
            } else {
                newCurrencyValue =  parseFloat((defaultCurrencyMount * defaultCurrencyKValue).toString()).toFixed(numberDecimalsK);
            }
            // defaultCurrencyMount = parseFloat($(this).text().replace(ThousandSeparatorSymbolK, ''));
            newCurrencyValue = addThousand(newCurrencyValue, DecimalSeparatorSymbolK, numberDecimalsK);
            $(this).replaceWith("<span class=\"woocommerce-Price-amount amount\">" + newCurrencyValue + "&nbsp;<span class=\"woocommerce-Price-currencySymbol\">" + selectedCurrencySymbol + "</span></span>");

            console.log("loaded");

        });

        /* Decode HTML-entities (is this case symbols) ---------------------------------------------------------  */
        function decodeHTMLEntities(text) {
            return $("<textarea/>")
                .html(text)
                .text();
        }

        /* Change currencies again ---------------------------------------------------------  */
        function loadedAgain(){
            $('span.woocommerce-Price-amount').each(function(){
                defaultCurrencyMount = parseFloat($(this).text().split(ThousandSeparatorSymbolK).join('')).toFixed(numberDecimalsK);
                var symbol = $(this).children('span.woocommerce-Price-currencySymbol').text();
                selectedCurrencySymbol = decodeHTMLEntities(selectedCurrencySymbol);
                if ( symbol === selectedCurrencySymbol) {
                    newCurrencyValue =  parseFloat((defaultCurrencyMount * 1).toString()).toFixed(numberDecimalsK);
                    console.log("new1:" + newCurrencyValue);
                    console.log("new1:" + symbol);

                } else {
                    newCurrencyValue =  parseFloat((defaultCurrencyMount * defaultCurrencyKValue).toString()).toFixed(numberDecimalsK);
                    console.log("new2:" + newCurrencyValue);
                    console.log("new2:" + defaultCurrencyKValue);
                    console.log("new2:" + symbol);
                }
                newCurrencyValue = addThousand(newCurrencyValue, DecimalSeparatorSymbolK, numberDecimalsK);
                // newCurrencyValue =  parseFloat(newCurrencyValue).toFixed(numberDecimalsK);
                $(this).replaceWith("<span class=\"woocommerce-Price-amount amount\">" + newCurrencyValue + "&nbsp;<span class=\"woocommerce-Price-currencySymbol\">" + selectedCurrencySymbol + "</span></span>");

            });
            console.log("applied_coupon");
        }

        /* When user select a currency from dropdown  --------------------------------------------------------- */
        $("#krokedilCurrency").change(changeCurrency);
//updated_cart_totals removed_coupon removed_from_cart update_checkout updated_wc_div updated_shipping_method applied_coupon added_to_cart
        /* Update currencies for the cart, checkout  */
        $( document.body ).on( 'added_to_cart', loadedAgain);
        $( document.body ).on( 'removed_from_cart', loadedAgain);
        $( document.body ).on( 'updated_checkout', loadedAgain);
        $( document.body ).on( 'updated_wc_div', loadedAgain);
        $( document.body ).on( 'updated_shipping_method', loadedAgain);
        $( document.body ).on( 'applied_coupon', loadedAgain);
        $( document.body ).on( 'removed_coupon', loadedAgain);
        $( document.body ).on( 'updated_cart_totals', loadedAgain);
    });
});