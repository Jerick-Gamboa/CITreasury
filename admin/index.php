<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include '../connection.php';
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/tailwind3.4.1.js"></script>
    <script src="../js/tailwind.config.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/apexcharts.js"></script>
    <script src="../js/predefined-script.js"></script>
    <script src="../js/defer-script.js" defer></script>
    <title>CITreasury - Dashboard</title>
</head>
<body>
    <?php
    if (isset($_COOKIE['cit-email']) && isset($_COOKIE['cit-password'])) {
        if ($_COOKIE['cit-type'] === 'user') {
            header("location: ../user/index.php");
        }
    } else {
        header("location: ../index.php");
    }
    ?>
    <nav class="fixed w-full bg-custom-purple flex flex-row shadow shadow-gray-800">
        <img src="../img/nobgcitsclogo.png" class="w-12 h-12 my-2 ml-8">
        <h1 class="text-3xl p-3 font-bold text-white">CITreasury</h1>
        <div class="w-full text-white">
            <svg id="mdi-menu" class="w-8 h-8 mr-3 my-4 p-1 float-right fill-current rounded transition-all duration-300-ease-in-out md:hidden hover:bg-white hover:text-custom-purple hover:cursor-pointer" viewBox="0 0 24 24"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" /></svg>
        </div>
    </nav>
    <div class="flex flex-col md:flex-row bg-custom-purplo min-h-screen">
        <div class="mt-18 md:mt-20 mx-2">
            <div id="menu-items" class="hidden md:inline-block w-64 h-full">
            </div>
        </div>
        <div id="menu-items-mobile" class="fixed block md:hidden h-fit top-16 w-full p-4 bg-custom-purplo opacity-95">
        </div>
        <div class="w-full bg-red-50 px-6 min-h-screen">
            <div class="mt-24">
                <h1 class="text-3xl text-custom-purplo font-bold mb-5">Dashboard</h1>
                <div class="flex lg:flex-row flex-col">
                    <div class="w-full bg-green-600 rounded shadow-lg mr-4 mb-4">
                        <h3 class="mx-3 my-5 text-white">1st Year</h3>
                        <div class="w-full px-3 py-2 bg-green-700 rounded-b">
                            <a href="#" class="text-xs font-bold text-white">View Details</a>
                        </div>
                    </div>
                    <div class="w-full bg-yellow-600 rounded shadow-lg mr-4 mb-4">
                        <h3 class="mx-3 my-5 text-white">2nd Year</h3>
                        <div class="w-full px-3 py-2 bg-yellow-700 rounded-b">
                            <a href="#" class="text-xs font-bold text-white">View Details</a>
                        </div>
                    </div>
                    <div class="w-full bg-red-600 rounded shadow-lg mr-4 mb-4">
                        <h3 class="mx-3 my-5 text-white">3rd Year</h3>
                        <div class="w-full px-3 py-2 bg-red-700 rounded-b">
                            <a href="#" class="text-xs font-bold text-white">View Details</a>
                        </div>
                    </div>
                    <div class="w-full bg-blue-600 rounded shadow-lg mb-4">
                        <h3 class="mx-3 my-5 text-white">4th Year</h3>
                        <div class="w-full px-3 py-2 bg-blue-700 rounded-b">
                            <a href="#" class="text-xs font-bold text-white">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="py-6" id="donut-chart"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">  
        const getChartOptions = () => {
          return {
            series: [320, 46],
            colors: ["#DD00DD", "#16BDCA"],
            chart: {
              height: 320,
              width: "100%",
              type: "donut",
            },
            stroke: {
              colors: ["transparent"],
              lineCap: "",
            },
            plotOptions: {
              pie: {
                donut: {
                  labels: {
                    show: true,
                    name: {
                      show: true,
                      offsetY: 20,
                    },
                    total: {
                      showAlways: true,
                      show: true,
                      label: "Total Students",
                      formatter: function (w) {
                        const sum = w.globals.seriesTotals.reduce((a, b) => {
                          return a + b
                        }, 0)
                        return sum // If decimal, use this: sum.toFixed(1)
                      },
                    },
                    value: {
                      show: true,
                      offsetY: -20,
                      formatter: function (value) {
                        return value
                      },
                    },
                  },
                  size: "80%",
                },
              },
            },
            labels: ["Without Sanctions", "With Sanctions"],
            dataLabels: {
              enabled: false,
            },
            legend: {
              position: "bottom",
              fontFamily: "Inter, sans-serif",
            },
            yaxis: {
              labels: {
                formatter: function (value) {
                  return value
                },
              },
            },
            xaxis: {
              labels: {
                formatter: function (value) {
                  return value
                },
              },
            },
          }
        }
        if (document.getElementById("donut-chart") && typeof ApexCharts !== 'undefined') {
          const chart = new ApexCharts(document.getElementById("donut-chart"), getChartOptions());
          chart.render();
        }
    </script>
</body>
</html>
