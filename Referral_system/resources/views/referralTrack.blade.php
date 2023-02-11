@extends('Layouts/dashboardLayout')

@section('content-section')
    <div id="content" class="p-4 p-md-5 pt-5">
       <h2 class="mb-4"style = "float:left">Referral Track</h2>
       <canvas  id ="myChart"></canvas>

       <script type="text/javascript">

       var dataLabels=JSON.parse(@json($dataLabels));
       var dataData=JSON.parse(@json($dataData));


        var canvas = document.getElementById('myChart');
        var data = {
            labels: dataLabels,
            datasets: [
                {
                    label: "Referral User",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 5,
                    pointHitRadius: 10,
                    data: dataData,
                }
            ]
        };
        
        var option = {
          showLines: true
        };
        var myLineChart = Chart.Line(canvas,{
          data:data,
          options:option
        });
        
        </script>
        <style>
            #myChart{
                width: 100% !important;
            }
        </style>


 @endsection