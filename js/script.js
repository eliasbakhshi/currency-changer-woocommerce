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


        /* Add thousand symbol to the price */
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

        function changeCurrency() {
            selectedCurrency = $(this).children("option:selected").text();
            selectedCurrencyValue = parseFloat($(this).children("option:selected").val()).toFixed(numberDecimalsK);
            selectedCurrencySymbol = $(this).children("option:selected").attr('symbol');
            // console.log("-3: " +selectedCurrency);
            // console.log("-2: " +selectedCurrencyValue);
            // console.log("-1: " +selectedCurrencySymbol);
            $('#krokedilCurrency option[id = ' + defaultCurrencyK + ']').removeAttr('selected');
            $(this).children("option:selected").attr('selected', true);
            defaultCurrencyK = selectedCurrency;
            $('span.woocommerce-Price-amount').each(function(){
                defaultCurrencyMount = parseFloat($(this).text().split(ThousandSeparatorSymbolK).join('')).toFixed(numberDecimalsK);
                newCurrencyValue =  parseFloat(((defaultCurrencyMount / defaultCurrencyKValue) * selectedCurrencyValue).toString()).toFixed(numberDecimalsK);
                // console.log("0: " + defaultCurrencyMount);
                // console.log("0: " + newCurrencyValue);
                newCurrencyValue = addThousand(newCurrencyValue, DecimalSeparatorSymbolK, numberDecimalsK);
                // console.log("1: " + defaultCurrencyMount);
                // console.log("2: " + defaultCurrencyKValue);
                // console.log("3: " + selectedCurrencyValue);
                // console.log("4: " + newCurrencyValue);
                // newCurrencyValue =  parseFloat(newCurrencyValue).toFixed(numberDecimalsK);
                $(this).replaceWith("<span class=\"woocommerce-Price-amount amount\">" + newCurrencyValue + "&nbsp;<span class=\"woocommerce-Price-currencySymbol\">" + selectedCurrencySymbol + "</span></span>");
            });
            defaultCurrencyKValue = selectedCurrencyValue;
            console.log("clicked");
        }

        /* When the page loads */
        $('#krokedilCurrency option[id = ' + defaultCurrencyK + ']').attr('selected', true);
        $('span.woocommerce-Price-amount').each(function(){
        // if ($('span.woocommerce-Price-currencySymbol') === defaultCurrencyK) {
        //     return;
        // }
            // defaultCurrencyMount = parseFloat($(this).text().replace(ThousandSeparatorSymbolK, ''));
            defaultCurrencyMount = parseFloat($(this).text().split(ThousandSeparatorSymbolK).join('')).toFixed(numberDecimalsK);
            // console.log(defaultCurrencyMount);

            newCurrencyValue =  parseFloat((defaultCurrencyMount * defaultCurrencyKValue).toString()).toFixed(numberDecimalsK);
            newCurrencyValue = addThousand(newCurrencyValue, DecimalSeparatorSymbolK, numberDecimalsK);
            $(this).replaceWith("<span class=\"woocommerce-Price-amount amount\">" + newCurrencyValue + "&nbsp;<span class=\"woocommerce-Price-currencySymbol\">" + selectedCurrencySymbol + "</span></span>");

            console.log("loaded");

        });

        function loaded2(){
            $('span.woocommerce-Price-amount').each(function(){
                // console.log("rwer");
                defaultCurrencyMount = parseFloat($(this).text().split(ThousandSeparatorSymbolK).join('')).toFixed(numberDecimalsK);
                newCurrencyValue =  parseFloat((defaultCurrencyMount * defaultCurrencyKValue).toString()).toFixed(numberDecimalsK);
                newCurrencyValue = addThousand(newCurrencyValue, DecimalSeparatorSymbolK, numberDecimalsK);
                // console.log("1: " + defaultCurrencyMount);
                // console.log("2: " + newCurrencyValue);
                // console.log("3: " + defaultCurrencyKValue);
                console.log("4: " + selectedCurrencySymbol);
                $(this).replaceWith("<span class=\"woocommerce-Price-amount amount\">" + newCurrencyValue + "&nbsp;<span class=\"woocommerce-Price-currencySymbol\">" + selectedCurrencySymbol + "</span></span>");
            });
            console.log("applied_coupon");
        }

        /* When user select a currency from dropdown */
        $("#krokedilCurrency").change(changeCurrency);
//updated_cart_totals removed_coupon removed_from_cart update_checkout updated_wc_div updated_shipping_method applied_coupon added_to_cart
        /* Update currencies for the cart, checkout  */
        // $( document.body ).on( 'removed_from_cart', loaded2);
        $( document.body ).on( 'added_to_cart', loaded2);
        $( document.body ).on( 'removed_from_cart', loaded2);
        $( document.body ).on( 'updated_checkout', loaded2);
        $( document.body ).on( 'updated_wc_div', loaded2);
        $( document.body ).on( 'updated_shipping_method', loaded2);
        $( document.body ).on( 'applied_coupon', loaded2);
        $( document.body ).on( 'removed_coupon', loaded2);
        $( document.body ).on( 'updated_cart_totals', loaded2);
    });
});