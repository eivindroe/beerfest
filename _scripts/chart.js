
var Chart = function () {}

$.extend(Chart.prototype, {
    initialize: function (strName, aryData) {
        this.view(strName, aryData);
    },
    view: function(strName, aryData) {
        $('<div id="overlay"></div>').appendTo("body");
        var $objChart = $("#result-chart");
        $objChart.empty().show();
        var Plot = $.jqplot("result-chart", [aryData], {
            title: strName,
            animate: true,
            animateReplot: true,
            series:[{renderer:$.jqplot.BarRenderer}],
            axesDefaults: {
                tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                tickOptions: {
                    angle: -30,
                    fontSize: '10pt'
                }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer
                },
                yaxis: {
                    min: 0,
                    max: 10,
                    tickInterval: 1
                }
            }
            /*axesDefaults: {
                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                showLabel: true,
                tickOptions: {
                    showLabel: true
                }
            },
            animate: true,
            animateReplot: true,
            seriesDefaults: [{
                label: 'test'
            }
            ],
            series:[
                {
                    pointLabels: {
                        show: false
                    },
                    renderer: $.jqplot.BarRenderer,
                    showHighlight: false,
                    rendererOptions: {
                        animation: {
                            speed: 4000
                        },
                        barWidth: 30,
                        barPadding: -15,
                        barMargin: 0,
                        highlightMouseOver: false
                    }
                }
            ],
            axes: {
                xaxis: {
                    //tickInterval: 1,
                    drawMajorGridlines: false,
                    drawMinorGridlines: false,
                    drawMajorTickMarks: false,
                    rendererOptions: {
                        //tickInset: 1,
                        //minorTicks: 1
                    },
                    pad: 1
                },
                yaxis: {
                    min: 0, max: 10, tickInterval: 1
                }
            }*/
        });
    }
});