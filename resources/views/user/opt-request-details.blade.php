@extends('dashboard-template')
@section('title')
    Détails de demande
@endsection
@section('navigation')
<li class="nav-parent nav-active nav-expanded">
    <a>
        <i class="fa fa-table" aria-hidden="true"></i>
        <span>Consulter</span>
    </a>
    <ul class="nav nav-children">
        <li class="">
                <a href="{{route('get_new')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Demandes des nouveaux projets
                </a>
            </li>
        <li class="nav-active">
        <a href="{{route('get_opt')}}">
                <i class="fa fa-wrench" aria-hidden="true"></i>
                    Demandes d'améliorations
            </a>
        </li>               
    </ul>
</li>
    <li class="nav-parent">
        <a>
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            <span>Envoyer une demande</span>
        </a>
        <ul class="nav nav-children">
            <li class="">
                <a href="{{route('add_new_request')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Demande d'un nouveau projet
                </a>
            </li>
            <li class="">
            <a href="{{route('add_opt_request')}}">
                    <i class="fa  fa-wrench" aria-hidden="true"></i>
                    Demande d'amélioration 
                </a>
            </li>
            
        </ul>
    </li>
    
@endsection

@section('content-title')
Détails de demande
@endsection

@section('new-notification-route')detail_request @endsection
@section('opt-notification-route')opt_detail_request @endsection

@section('content')
    @php 
        $user = App\User::find($request->user_id);
        $project = App\Project::find($request->requestable->project_id); 
    @endphp
<section class="panel">
    <div class="panel-body">
        <div class="invoice">
            <header class="clearfix">
                <div class="row">
                    <div class="col-sm-6 mt-md">
                        <h2 class="h2 mt-none mb-sm text-dark text-bold">Réference</h2>
                    <h4 class="h4 m-none text-dark text-bold">#{{$request->requestable->id}}</h4><br>
                    </div>
                    <div class="col-sm-6 text-right mt-md mb-md">
                        <h2 class="h2 mt-none mb-sm text-dark text-bold">Etat</h2>
                        <h4 class="h4 m-none text-danger text-bold">{{App\Enums\StatusRequest::getEnumDescriptionByValue($request->status)}}</h4><br>
                    </div>
                </div>
            </header>
            <div class="bill-info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="bill-to">
                            <p class="h5 mb-xs text-dark text-semibold"><b>Emetteur:</b></p>
                            <address>
                                {{$user->name}}
                                <br>
                                {{$user->adresse}}
                                <br>
                                Num.Tél : {{$user->phone}}
                                <br>
                                {{$user->email}}
                            </address>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bill-data text-right">
                            <p class="mb-none">
                                <span class="text-dark"><b>Crée le:</b></span>
                            <span class="value">{{$request->requestable->created_at}}</span>
                            </p>
                            <p class="mb-none">
                                <span class="text-dark"><b>Dérniere modification:</b></span>
                                <span class="value">{{$request->requestable->updated_at}}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="table-responsive col-sm-10 col-sm-offset-1">
                <table class="table invoice-items" border="0">
                    <tbody>
                        <tr>
                            <td class="text-dark col-sm-3"><h4><b>Projet</b></h4></td>
                            <td class="text-dark"><h4>{{$project->name}}</h4></td>
                        </tr>
                        <tr>
                            <td class="text-dark col-sm-3"><h4><b>Type d'amélioration</b></h4></td>
                            <td class="text-dark"><h4>{{App\Enums\RequestTypes::getEnumDescriptionByValue($request->requestable->type)}}</h4></td>
                        </tr>
                        <tr>
                            <td class="text-dark col-sm-3"><h4><b>Fichier</b></h4></td>
                        <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$request->user_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <h4 class="text-dark" style="padding-left: 0.5%"><b>Remarques</b></h4><br>
                    <h4><div class="" 
                        style="border-left: 5px solid #114E9E; border-top-left-radius: 5px; border-bottom-left-radius: 5px;
                        border-right: 5px solid #114E9E; border-top-right-radius: 5px; border-bottom-right-radius: 5px;
                        margin-top: 5px; padding-left: 1%;">
                            {!!$request->remarques!!}
                    </div><br></h4>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection