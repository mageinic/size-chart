/**
 * MageINIC
 * Copyright (C) 2023 MageINIC <support@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package MageINIC_SizeChart
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

require([
    'jquery',
    'jquery/jquery.cookie',
    'jquery/ui',
    'Magento_Ui/js/modal/modal'
], function ($) {
    'use strict';

    $('#sizechartpopup').modal({
        title: 'Size Chart',
        trigger: '#sizechart_link'
    });

    /**
     *
     * @param num
     * @param arr
     * @returns {number}
     */
    var closestValueIndex = function (num, arr) {
        var index = 0;
        var curr = arr[0];
        var diff = Math.abs(num - curr);
        for (var val = 0; val < arr.length; val++) {
            var newdiff = Math.abs(num - arr[val]);
            if (newdiff < diff) {
                diff = newdiff;
                index = val;
            }
        }
        return index;
    };

    var findSize = function () {
        var inputs = $('#sizechart_form :input').serializeArray();
        var sizeMatches = [];
        $(inputs).each(function (i) {
            var values = [];
            var column = $('#chart_values tr td:nth-child(' + (i + 2) + ')');
            $(column).each(function () {
                $(this).removeClass("current");
                values.push(parseFloat($(this).text(), 10));
            });
            var closestIndex = closestValueIndex(this.value, values);
            sizeMatches.push(closestIndex);
            $(column[closestIndex]).addClass("current");
        });
        var sizeIndex = Math.max.apply(null, sizeMatches);
        var rows = $('#chart_values tr');
        $(rows).removeClass("recommended-size");
        $(rows).eq(sizeIndex).addClass("recommended-size");
        var recomendedSize = $('#chart_values tr td:nth-child(1)').eq(sizeIndex).text();
        $('#size-value').text(recomendedSize);
    };

    /**
     *
     * @param unit
     */
    var convertUnits = function (unit) {
        for (var i = 2; i <= 4; i++) {
            var column = $('#chart_values tr td:nth-child(' + (i) + ')');
            $(column).each(function () {
                $(this).removeClass("current");
                var value = parseFloat($(this).text(), 10);
                if (unit === 'in') {
                    var converted = value / 2.54;
                    var convertText = "Convert to Centimeters";
                } else {
                    var converted = value * 2.54;
                    var convertText = "Convert to Inches";
                }
                $(this).text(converted.toFixed(1));
                $("#convertsizeunits").html(convertText);
            });
        }
    };

    /**
     *
     * @param event
     */
    var changeUnits = function (event) {
        event.preventDefault();
        $("#sizechart_form :input").val("");
        var cookie = $.cookie('sizechart');
        if (null !== cookie && cookie === 'in') {
            $.cookie('sizechart', 'cm');
            convertUnits('cm');
        } else {
            $.cookie('sizechart', 'in');
            convertUnits('in');
        }
        $('#chart_values tr').removeClass("recommended-size");
        $('#size-value').text("");
    };

    /**
     *
     * @returns {string}
     */
    var getCurrentUnit = function () {
        var cookie = $.cookie('sizechart');
        if (null !== cookie && cookie === 'in') {
            return 'in';
        } else {
            return 'cm';
        }
    };
    if (getCurrentUnit() === 'cm') {
        convertUnits('cm');
    }
    $("#convertsizeunits").on("click", changeUnits);
    $("#sizechart_form input").on("input", findSize);
});
