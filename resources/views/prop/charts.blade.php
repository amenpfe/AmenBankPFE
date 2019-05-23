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
{{route('edit_prop')}}
@endsection

@section('new-notification-route')new-request-details-cdd @endsection
@section('opt-notification-route')opt-request-details-prop @endsection

@section('navigation')
<li class="nav-parent">
    <a>
        <i class="fa fa-table" aria-hidden="true"></i>
        <span>Consulter les demandes</span>
    </a>
    <ul class="nav nav-children">
        <li class="">
        <a href="{{route('get_prop_opt')}}">
                <i class="fa fa-wrench" aria-hidden="true"></i>
                    D'améliorations
            </a>
        </li>               
    </ul>
</li>
    <li class="nav-parent ">
        <a>
            <i class="fa fa-calendar" aria-hidden="true"></i>
            <span>Suivi des demande</span>
        </a>
        <ul class="nav nav-children">
            <li class="nav-">
                <a href="{{route('all_new_request_prop')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Des nouveaux projets
                </a>
            </li>
            <li class="">
            <a href="{{route('all_opt_request_prop')}}">
                    <i class="fa  fa-wrench" aria-hidden="true"></i>
                    D'améliorations
                </a>
            </li>
            
        </ul>
    </li>
    <li class="nav-parent">
        <a>
            <i class="fa fa-archive" aria-hidden="true"></i>
            <span>Archive des projets</span>
        </a>
        <ul class="nav nav-children">
            <li class="">
                <a href="{{route('get_prop_new_archive')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Des nouveaux projets
                </a>
            </li>
            <li class="">
            <a href="{{route('get_prop_opt_archive')}}">
                    <i class="fa  fa-wrench" aria-hidden="true"></i>
                    D'améliorations
                </a>
            </li>
            
        </ul>
<li class="nav-active">
    <a href="{{route('get_prop_stat')}}">
        <i class="fa fa-bar-chart-o" aria-hidden="true"></i>
        <span>Les Statistiques</span>
    </a>
</li>
    
@endsection

@section('content-title')
Les statistiques
@endsection

@section('content-path')
    <li>
        <span>Les statistiques</span>
    </li>
@endsection

@section('content')

<section class="panel">
        <div class="panel-body">
            <div class="col-sm-12" style="margin-bottom: 20px; margin-top: 20px; font-size: 7em; text-align: center; height: 100%; ">
                <div class="col-sm-4 col-sm-offset-2" style="padding-top: 30px; padding-bottom: 30px;"><strong><span class='numscroller' data-min='1' data-max='{{$untreatedCount}}' data-delay='5' data-increment='10'>{{$untreatedCount}}</span></strong></div>
                <div class="col-sm-4 " style="padding-top: 30px; padding-bottom: 30px; border-left: solid black 3px;"><strong><span class='numscroller' data-min='1' data-max='{{$avgHours}}' data-delay='5' data-increment='10'>{{$avgHours}}</span></strong></div>
            </div>
            <div  style="margin-top: 25%; margin-bottom: 25%">
                <div class="col-sm-6">
                    <canvas id="propProjCanvas"></canvas>
                </div>
                <div class="col-sm-6">
                    <canvas id="newProjCanvas"></canvas>
                </div>
            </div>
            

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
            $rest = 100 - $propProjPercentage;
        @endphp
        //propProjChart
        var propProjCtx = document.getElementById('propProjCanvas').getContext('2d');

        new Chart(propProjCtx, {
            type: 'doughnut',
            data: {
                labels: ["Autres", "Projets affectés"],
                datasets: [
                {
                    label: "TeamA Score",
                    data: [{{$propProjPercentage}}, {{$rest}}],
                    backgroundColor: [
                    "rgba(40,187,105)",
                    "rgba(37,129,188)"
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
                    text: "Les projets affectés",
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
        //End propProjChart

        //NewProjChart
        var newProjCtx = document.getElementById('newProjCanvas').getContext('2d');
        
        var dataFirst = {
            label: "Les demandes des nouveaux projets",
            data: {!!json_encode($newProjectRequestData)!!},
            lineTension: 0.3,
            fill: false,
            borderColor: 'rgba(239,135,46)',
            backgroundColor: 'transparent',
            pointBorderColor: 'rgba(239,135,46)',
            pointBackgroundColor: 'rgba(239,135,46,0.5)',
            pointRadius: 5,
            pointHoverRadius: 10,
            pointHitRadius: 30,
            pointBorderWidth: 2,
            pointStyle: 'rectRounded'
        };
            
        var dataSecond = {
            label: "Les demandes d'optimisation des projets existants",
            data: {!!json_encode($optProjectRequestData)!!},
            lineTension: 0.3,
            fill: false,
            borderColor: 'rgba(37,129,188)',
            backgroundColor: 'transparent',
            pointBorderColor: 'rgba(37,129,188)',
            pointBackgroundColor: 'rgba(37,129,188,0.5)',
            pointRadius: 5,
            pointHoverRadius: 10,
            pointHitRadius: 30,
            pointBorderWidth: 2,
            pointStyle: 'rectRounded'
        };

        var lineChart = new Chart(newProjCtx, {
            type: 'line',
            data: {
                labels: ["Janv.", "Fev.", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Sept.", "Oct.", "Nov.", "Dec."],
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
