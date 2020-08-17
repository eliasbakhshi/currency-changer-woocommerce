jQuery(function ($) {
    $(document).ready(function () {
        // Passes vars ------
        // defaultCurrencyK
        // defaultCurrencyKValue
        // defaultCurrencyKSymbol
        // numberDecimalsK
        // DecimalSeparatorSymbolK
        // ThousandSeparatorSymbolK
        // *********
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

        /* ---- Set cookies ---- */
        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        /* ---- Get cookies ---- */
        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        /* ---- When user choose a currency ---- */
        function changeCurrency() {
            selectedCurrency = $(this).children("option:selected").text();
            selectedCurrencyValue = parseFloat($(this).children("option:selected").val()).toFixed(numberDecimalsK);
            selectedCurrencySymbol = $(this).children("option:selected").attr('symbol');
            /* ---- Change currency in dropbox(es) ---- */
            if ($('#krokedilCurrencyWidget').length) {
                $('#krokedilCurrencyWidget option[id = ' + defaultCurrencyK + ']').removeAttr('selected');
                $('#krokedilCurrencyWidget option[id = ' + selectedCurrency + ']').attr('selected', true);
            }
            if ($('#krokedilCurrencyShortcode').length) {
                $('#krokedilCurrencyShortcode option[id = ' + defaultCurrencyK + ']').removeAttr('selected');
                $('#krokedilCurrencyShortcode option[id = ' + selectedCurrency + ']').attr('selected', true);
            }
            defaultCurrencyK = selectedCurrency;

            /* ---- Change prices in the page ---- */
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
            setCookie('usersCurrency', selectedCurrency, 365);
        }

        /* ---- When the page loads ---- */
        $('#krokedilCurrencyWidget option[id = ' + defaultCurrencyK + ']').attr('selected', true);
        $('span.woocommerce-Price-amount').each(function(){
            defaultCurrencyMount = parseFloat($(this).text().split(ThousandSeparatorSymbolK).join('')).toFixed(numberDecimalsK);

            var symbol = $(this).children('span.woocommerce-Price-currencySymbol').text();
            selectedCurrencySymbol = decodeHTMLEntities(selectedCurrencySymbol);
            if ( symbol === selectedCurrencySymbol) {
                newCurrencyValue =  parseFloat((defaultCurrencyMount * 1).toString()).toFixed(numberDecimalsK);
            } else {
                newCurrencyValue =  parseFloat((defaultCurrencyMount * defaultCurrencyKValue).toString()).toFixed(numberDecimalsK);
            }
            newCurrencyValue = addThousand(newCurrencyValue, DecimalSeparatorSymbolK, numberDecimalsK);
            $(this).replaceWith("<span class=\"woocommerce-Price-amount amount\">" + newCurrencyValue + "&nbsp;<span class=\"woocommerce-Price-currencySymbol\">" + selectedCurrencySymbol + "</span></span>");
        });

        /* ---- Decode HTML-entities (is this case symbols) ---- */
        function decodeHTMLEntities(text) {
            return $("<textarea/>")
                .html(text)
                .text();
        }

        /* ---- Change currencies again ---- */
        function loadedAgain(){
            $('span.woocommerce-Price-amount').each(function(){
                defaultCurrencyMount = parseFloat($(this).text().split(ThousandSeparatorSymbolK).join('')).toFixed(numberDecimalsK);
                var symbol = $(this).children('span.woocommerce-Price-currencySymbol').text();
                selectedCurrencySymbol = decodeHTMLEntities(selectedCurrencySymbol);
                if ( symbol === selectedCurrencySymbol) {
                    newCurrencyValue =  parseFloat((defaultCurrencyMount * 1).toString()).toFixed(numberDecimalsK);

                } else {
                    newCurrencyValue =  parseFloat((defaultCurrencyMount * defaultCurrencyKValue).toString()).toFixed(numberDecimalsK);
                }
                newCurrencyValue = addThousand(newCurrencyValue, DecimalSeparatorSymbolK, numberDecimalsK);
                $(this).replaceWith("<span class=\"woocommerce-Price-amount amount\">" + newCurrencyValue + "&nbsp;<span class=\"woocommerce-Price-currencySymbol\">" + selectedCurrencySymbol + "</span></span>");
            });
        }

        /* ---- When user select a currency from dropdown ---- */
        $("#krokedilCurrencyWidget").change(changeCurrency);
        $("#krokedilCurrencyShortcode").change(changeCurrency);

        /* ---- Update currencies for the cart, checkout and other parts ---- */
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

