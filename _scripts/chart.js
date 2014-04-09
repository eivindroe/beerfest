
var Chart = function () {}

$.extend(Chart.prototype, {
    initialize: function (strName, aryData, aryLabel) {
        this.view(strName, aryData, aryLabel);
    },
    view: function(strName, aryData, aryLabel) {
        $('<div id="overlay"></div>').appendTo("body");
        var $objChart = $("#result-chart");
        $objChart.empty().show();
        var Plot = $.jqplot("result-chart", aryData, {
            title: strName,
            stackSeries: true,
            captureRightClick: true,
            animate: true,
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: aryLabel,
                    tickOptions: {
                        angle: -90,
                        fontSize: '10pt'
                    }
                },
                yaxis: {
                    min: 0,
                    max: 10,
                    tickInterval: 1
                }
            },
            seriesDefaults:{
                renderer: $.jqplot.BarRenderer,
                rendererOptions: {
                    highlightMouseDown: false,
                    highlightMouseOver: false,
                    animation: {
                        speed: 6500
                    }
                },
                pointLabels: {
                    show: false
                }
            },
            series: [{
                    color: "#b04f35"
                }, {
                    color: "#82c9ff"
                }, {
                    color: "#a2a638"
                }
            ]
        });
    }
});