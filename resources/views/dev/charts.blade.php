@extends('dashboard-template')

@section('title')
    Statistiques
@endsection

@section('page-stylesheets')
    <!-- Specific Page Vendor CSS -->
    {!! HTML::style('css/select2.css') !!}
    {!! HTML::style('css/datatables.css') !!}
@endsection

@section('edit')
{{route('edit_chd')}}
@endsection

@section('new-notification-route')new-request-details-chd @endsection
@section('opt-notification-route')opt-request-details-chd @endsection

@section('navigation')
<li class="nav-parent nav-active nav-expanded">
    <a>
        <i class="fa fa-table" aria-hidden="true"></i>
        <span>Consulter les demandes</span>
    </a>
    <ul class="nav nav-children">
        <li class="nav-active">
                <a href="">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Des nouveaux projets
                </a>
            </li>
        <li class="">
        <a href="{{route('get_chd_opt')}}">
                <i class="fa fa-wrench" aria-hidden="true"></i>
                    D'améliorations
            </a>
        </li>               
    </ul>
</li>
<li class="nav-parent">
    <a>
        <i class="fa fa-calendar" aria-hidden="true"></i>
        <span>Suivi des demande</span>
    </a>
    <ul class="nav nav-children">
        <li class="">
            <a href="{{route('all_new_request_chd')}}">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Des nouveaux projets
            </a>
        </li>
        <li class="">
        <a href="{{route('all_opt_request_chd')}}">
                <i class="fa  fa-wrench" aria-hidden="true"></i>
                D'améliorations
            </a>
        </li>
        
    </ul>
</li>
    
@endsection

@section('content-title')
Demandes des nouveaux projets
@endsection

@section('content-path')
    <li>
        <span>Consulter les demandes</span>
    </li>
    <li>
        <span>Des nouveaux projets</span>
    </li>
@endsection

@section('content')

<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Liste des demandes</h2>
        </header>
        <div class="panel-body">
            <span class='numscroller' data-min='1' data-max='{{$untreatedCount}}' data-delay='5' data-increment='10'>{{$untreatedCount}}</span>
            <span class='numscroller' data-min='1' data-max='{{$avgHours}}' data-delay='5' data-increment='10'>{{$avgHours}}</span>
            <span class='numscroller' data-min='1' data-max='1000' data-delay='5' data-increment='10'>1000</span>

            <canvas id="devProjCanvas"></canvas>
            <canvas id="newProjCanvas"></canvas>

        </div>
    </section>
@endsection



@section('page-scripts')

    <!-- Specific Page Vendor -->
    {!! HTML::script('js/select2.js') !!}
    {!! HTML::script('js/jquery.dataTables.js') !!}
    {!! HTML::script('js/dataTables.tableTools.min.js') !!}
    {!! HTML::script('js/datatables.js') !!}
    {!! HTML::script('js/numscroller-1.0.js') !!}
    
    <!-- Table script -->
    {!! HTML::script('js/examples.datatables.default.js') !!}
    {!! HTML::script('js/chart.bundle.js') !!}

    <script>
        @php
            $rest = 100 - $devProjPercentage;
        @endphp
        //DevProjChart
        var devProjCtx = document.getElementById('devProjCanvas').getContext('2d');

        new Chart(devProjCtx, {
            type: 'doughnut',
            data: {
                labels: ["match1", "match2"],
                datasets: [
                {
                    label: "TeamA Score",
                    data: [{{$devProjPercentage}}, {{$rest}}],
                    backgroundColor: [
                    "#DEB887",
                    "#A9A9A9"
                    ],
                    borderColor: [
                    "#CDA776",
                    "#989898"
                    ],
                    borderWidth: [1, 1]
                }
                ]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    position: "top",
                    text: "Pie Chart",
                    fontSize: 18,
                    fontColor: "#111"
                },
                legend: {
                    display: true,
                    position: "bottom",
                    labels: {
                    fontColor: "#333",
                    fontSize: 16
                    }
                }
            }
        });
        //End DevProjChart

        //NewProjChart
        var newProjCtx = document.getElementById('newProjCanvas').getContext('2d');
        
        var dataFirst = {
            label: "Car A - Speed (mph)",
            data: [0, 59, 75, 20, 20, 55, 40],
            lineTension: 0.3,
            fill: false,
            borderColor: 'orange',
            backgroundColor: 'transparent',
            pointBorderColor: 'orange',
            pointBackgroundColor: 'rgba(255,150,0,0.5)',
            pointRadius: 5,
            pointHoverRadius: 10,
            pointHitRadius: 30,
            pointBorderWidth: 2,
            pointStyle: 'rectRounded'
        };
            
        var dataSecond = {
            label: "Car B - Speed (mph)",
            data: [20, 15, 60, 60, 65, 30, 70],
            lineTension: 0.3,
            fill: false,
            borderColor: 'blue',
            backgroundColor: 'transparent',
            pointBorderColor: 'blue',
            pointBackgroundColor: 'rgba(255,150,0,0.5)',
            pointRadius: 5,
            pointHoverRadius: 10,
            pointHitRadius: 30,
            pointBorderWidth: 2,
            pointStyle: 'rectRounded'
        };

        var lineChart = new Chart(newProjCtx, {
            type: 'line',
            data: {
                labels: ["0s", "10s", "20s", "30s", "40s", "50s", "60s"],
                datasets: [dataFirst, dataSecond]
            },
            options: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                    boxWidth: 80,
                    fontColor: 'black'
                    }
                }
            }
        });
    </script>
@endsection
