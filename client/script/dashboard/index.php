<script type="text/javascript">

    (function () {
        'use strict';

        Charts.init();

        var Performance = function Performance(id) {
            var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'line';
            var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
            options = Chart.helpers.merge({
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function callback(a) {
                            if (!(a % 20)) return a + " orang";
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function label(a, e) {
                        var t = e.datasets[a.datasetIndex].label || "",
                                o = a.yLabel,
                                r = "";
                            return 1 < e.datasets.length && (r += '<span class="popover-body-label mr-auto">' + t + "</span>"), r += '<span class="popover-body-value">$' + o + "k</span>";
                        }
                    }
                }
            }, options);
            var data = {
            labels: ["16 Okt", "17 Okt", "18 Okt", "19 Okt", "20 Okt", "21 Okt", "22 Okt"],
            datasets: [{
                    label: "Performance",
                    data: [0, 10, 5, 15, 10, 20, 15, 25, 20, 30, 25, 40, 30, 60, 35, 80, 40, 100]
                }]
            };
            Charts.create(id, type, options, data);
        };

        Performance('#performanceChart');
        Performance('#performanceAreaChart', 'line', {
            elements: {
                line: {
                    fill: 'start',
                    backgroundColor: settings.charts.colors.area
                }
            }
        });


        var ObatDoughnut = function ObatDoughnut(id) {
            var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'doughnut';
            var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
            options = Chart.helpers.merge({
            tooltips: {
                callbacks: {
                title: function title(a, e) {
                    return e.labels[a[0].index];
                },
                label: function label(a, e) {
                    var t = "";
                    return t += '<span class="popover-body-value">' + e.datasets[0].data[a.index] + "%</span>";
                }
                }
            }
            }, options);
            var data = {
                labels: ["Amoxilin", "Panadol", "Antibiotik", "Diabesol", "Aerosol"],
                datasets: [{
                    data: [20, 25, 15, 30, 10],
                    backgroundColor: [settings.colors.success[400], settings.colors.danger[400], settings.colors.primary[500], settings.colors.gray[300], settings.colors.primary[300]],
                    hoverBorderColor: "dark" == settings.charts.colorScheme ? settings.colors.gray[800] : settings.colors.white
                }]
            };
            Charts.create(id, type, options, data);
        }

        var SakitDoughnut = function SakitDoughnut(id) {
            var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'doughnut';
            var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
            options = Chart.helpers.merge({
            tooltips: {
                callbacks: {
                title: function title(a, e) {
                    return e.labels[a[0].index];
                },
                label: function label(a, e) {
                    var t = "";
                    return t += '<span class="popover-body-value">' + e.datasets[0].data[a.index] + "%</span>";
                }
                }
            }
            }, options);
            var data = {
                labels: ["Demam", "Flu", "Masuk Angin", "Insomnia", "Selalu lapar"],
                datasets: [{
                    data: [15, 10, 20, 25, 30],
                    backgroundColor: [settings.colors.success[400], settings.colors.danger[400], settings.colors.primary[500], settings.colors.gray[300], settings.colors.primary[300]],
                    hoverBorderColor: "dark" == settings.charts.colorScheme ? settings.colors.gray[800] : settings.colors.white
                }]
            };
            Charts.create(id, type, options, data);
        }

        SakitDoughnut('#sakitDoughnutChart');


        var ObatDoughnut = function ObatDoughnut(id) {
            var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'doughnut';
            var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
            options = Chart.helpers.merge({
            tooltips: {
                callbacks: {
                title: function title(a, e) {
                    return e.labels[a[0].index];
                },
                label: function label(a, e) {
                    var t = "";
                    return t += '<span class="popover-body-value">' + e.datasets[0].data[a.index] + "%</span>";
                }
                }
            }
            }, options);
            var data = {
                labels: ["Amoxilin", "Panadol", "Antibiotik", "Diabesol", "Aerosol"],
                datasets: [{
                    data: [20, 25, 15, 30, 10],
                    backgroundColor: [settings.colors.success[400], settings.colors.danger[400], settings.colors.primary[500], settings.colors.gray[300]],
                    hoverBorderColor: "dark" == settings.charts.colorScheme ? settings.colors.gray[800] : settings.colors.white
                }]
            };
            Charts.create(id, type, options, data);
        }

        ObatDoughnut('#obatDoughnutChart');
    })();
    

</script>