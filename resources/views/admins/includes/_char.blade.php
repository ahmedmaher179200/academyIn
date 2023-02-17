<style>
    body {
        background: #1D1F20;
        padding: 16px;
    }

    canvas {
        border: 1px dotted red;
    }

    .chart-container {
        position: relative;
        margin: auto;
        height: 80vh;
        width: 80vw;
    }

</style>

{{-- <div class="row">
    <div class="col-md-8 col-md-offset-1">
        <input type="date" class="form-control">
    </div>

</div> --}}

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="chart-container" style="width: 100%; height: 550px;">
            <canvas id="chart"></canvas>
        </div>

    </div>
</div>


<script src="{{ asset('public/admin/theme2/js/char/char.js') }}"></script>
<script>
    // $("#getData").click(function() {
    $.ajax({ //create an ajax request to display.php
        type: "GET",
        url: "{{ route('dashboard.ajaxdata') }}",
        success: function(obj) {
            console.log(obj);
            var ardata = Object.values(obj)
            var len = 0,
                nameArray = new Array(),
                callsArray = new Array();
            len = ardata.length;

            for (let index = 0; index < len; index++) {
                nameArray.push(ardata[index]['name']);
                callsArray.push(ardata[index]['latest_work']['calls']);
            }
            console.log(nameArray);
            var data = {
                labels: nameArray,
                datasets: [{
                    label: "Dataset #1",
                    backgroundColor: "rgba(255,99,132,0.2)",
                    borderColor: "rgba(255,99,132,1)",
                    borderWidth: 2,
                    hoverBackgroundColor: "rgba(255,99,132,0.4)",
                    hoverBorderColor: "rgba(255,99,132,1)",
                    data: callsArray,
                }]
            };

            var options = {
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        stacked: true,
                        gridLines: {
                            display: true,
                            color: "rgba(255,99,132,0.2)"
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            };

            Chart.Bar('chart', {
                options: options,
                data: data
            });
        }
    });
    // });

</script>
