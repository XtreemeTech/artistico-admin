(function ($) {
  'use strict';
  $(function () {
    if ($("#orders-chart").length) {
      // Ensure the data is available before proceeding
      fetchOrders()
        .then(ordersData => {
          if (ordersData) {
            var currentChartCanvas = $("#orders-chart").get(0).getContext("2d");
            var currentChart = new Chart(currentChartCanvas, {
              type: 'bar',
              data: {
                labels: getPast12Months(),
                datasets: [{
                  label: 'Completed',
                  data: ordersData.completed_orders,
                  backgroundColor: '#392c70'
                },
                {
                  label: 'Total',
                  data: ordersData.total_orders,
                  backgroundColor: '#d1cede'
                }
                ]
              },
              options: {
                responsive: true,
                maintainAspectRatio: true,
                layout: {
                  padding: {
                    left: 0,
                    right: 0,
                    top: 20,
                    bottom: 0
                  }
                },
                scales: {
                  yAxes: [{
                    gridLines: {
                      drawBorder: false,
                    },
                    ticks: {
                      beginAtZero: true,
                      stepSize: 3,
                      fontColor: "#686868"
                    }
                  }],
                  xAxes: [{
                    stacked: true,
                    ticks: {
                      beginAtZero: true,
                      fontColor: "#686868"
                    },
                    gridLines: {
                      display: false,
                    },
                    barPercentage: 0.4
                  }]
                },
                legend: {
                  display: false
                },
                elements: {
                  point: {
                    radius: 0
                  }
                },
                legendCallback: function (chart) {
                  var text = [];
                  text.push('<ul class="legend' + chart.id + '">');
                  for (var i = 0; i < chart.data.datasets.length; i++) {
                    text.push('<li><span class="legend-label" style="background-color:' + chart.data.datasets[i].backgroundColor + '"></span>');
                    if (chart.data.datasets[i].label) {
                      text.push(chart.data.datasets[i].label);
                    }
                    text.push('</li>');
                  }
                  text.push('</ul>');
                  return text.join("");
                },
              }
            });
            document.getElementById('orders-chart-legend').innerHTML = currentChart.generateLegend();
          } else {
            console.error('Error: Orders data is not available.');
          }
          if ($('#sales-chart').length) {
            var lineChartCanvas = $("#sales-chart").get(0).getContext("2d");
            var step = 500;
            var data = {
              labels: getPast12Months(),
              datasets: [
                {
                  label: 'Earned',
                  backgroundColor: '#392c70',
                  pointBorderColor: '#392c70',
                  data: ordersData.completed_price,
                  borderColor: [
                    '#392c70'
                  ],
                  borderWidth: 3,
                  fill: false
                },
                {
                  label: 'Estimated',
                  data: ordersData.total_price,
                  pointBorderColor: '#d1cede',
                  backgroundColor: '#d1cede',
                  borderColor: [
                    '#d1cede'
                  ],
                  borderWidth: 3,
                  fill: false
                }
              ]
            };
            var options = {
              scales: {
                yAxes: [{
                  gridLines: {
                    drawBorder: false
                  },
                  ticks: {
                    stepSize: Math.ceil((Math.max(...ordersData.total_price) / 6) / step) * step,
                    fontColor: "#686868"
                  }
                }],
                xAxes: [{
                  display: true,
                  gridLines: {
                    drawBorder: false,
                    display: false
                  }
                }]
              },
              legend: {
                display: false
              },
              elements: {
                point: {
                  radius: 3
                }
              },
              stepsize: 1
            };
            var lineChart = new Chart(lineChartCanvas, {
              type: 'line',
              data: data,
              options: options
            });
          }
        });
      if ($("#sales-status-chart").length) {
        var pieChartCanvas = $("#sales-status-chart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas, {
          type: 'pie',
          data: {
            datasets: [{
              data: [75, 25, 15, 10],
              backgroundColor: [
                '#392c70',
                '#04b76b',
                '#ff5e6d',
                '#eeeeee'
              ],
              borderColor: [
                '#392c70',
                '#04b76b',
                '#ff5e6d',
                '#eeeeee'
              ],
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
              'Active users',
              'Subscribers',
              'New visitors',
              'Others'
            ]
          },
          options: {
            responsive: true,
            animation: {
              animateScale: true,
              animateRotate: true
            },
            legend: {
              display: false
            },
            legendCallback: function (chart) {
              var text = [];
              text.push('<ul class="legend' + chart.id + '">');
              for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
                text.push('<li><span class="legend-label" style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '"></span>');
                if (chart.data.labels[i]) {
                  text.push(chart.data.labels[i]);
                }
                text.push('<label class="badge badge-light badge-pill legend-percentage ml-auto">' + chart.data.datasets[0].data[i] + '%</label>');
                text.push('</li>');
              }
              text.push('</ul>');
              return text.join("");
            }
          }
        });
        document.getElementById('sales-status-chart-legend').innerHTML = pieChart.generateLegend();
      }
      if ($("#daily-sales-chart").length) {
        var dailySalesChartData = {
          datasets: [{
            data: [50, 10, 10, 30],
            backgroundColor: [
              '#392c70',
              '#04b76b',
              '#e9e8ef',
              '#ff5e6d'
            ],
            borderWidth: 0
          }],

          // These labels appear in the legend and in the tooltips when hovering different arcs
          labels: [
            'Mail order sales',
            'Instore sales',
            'Download sales',
            'Sales return'
          ]
        };
        var dailySalesChartOptions = {
          responsive: true,
          maintainAspectRatio: true,
          animation: {
            animateScale: true,
            animateRotate: true
          },
          legend: {
            display: false
          },
          legendCallback: function (chart) {
            var text = [];
            text.push('<ul class="legend' + chart.id + '">');
            for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
              text.push('<li><span class="legend-label" style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '"></span>');
              if (chart.data.labels[i]) {
                text.push(chart.data.labels[i]);
              }
              text.push('</li>');
            }
            text.push('</ul>');
            return text.join("");
          },
          cutoutPercentage: 70
        };
        var dailySalesChartCanvas = $("#daily-sales-chart").get(0).getContext("2d");
        var dailySalesChart = new Chart(dailySalesChartCanvas, {
          type: 'doughnut',
          data: dailySalesChartData,
          options: dailySalesChartOptions
        });
        document.getElementById('daily-sales-chart-legend').innerHTML = dailySalesChart.generateLegend();
      }
      if ($("#inline-datepicker-example").length) {
        $('#inline-datepicker-example').datepicker({
          enableOnReadonly: true,
          todayHighlight: true,
        });
      }
    }
  });
})(jQuery);

function getPast12Months() {
  const months = [];
  const currentDate = new Date();

  // Loop through the past 12 months, including the current month
  for (let i = 11; i >= 0; i--) {
    const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
    const month = date.toLocaleString('default', { month: 'long' });  // Get month name (e.g., "September")
    months.push(`${month.slice(0, 3)}`);
  }

  return months;
}

async function fetchOrders() {
  try {
    const response = await fetch('fetch/orders_stat.php', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json'
      }
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching orders:', error);
    return {}
  }
}
