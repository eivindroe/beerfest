
var Chart = function () {}

$.extend(Chart.prototype, {
    initialize: function (strTarget, strName, aryData) {
        this.view(strTarget, strName, aryData);
    },
    view: function(strTarget, strName, aryData) {
        var $objChart = $("#" + strTarget);
        $objChart.empty();
        var plot1 = $.jqplot(strTarget, [aryData], {
            title: strName,
            axes: {
            }
        });
    }
});