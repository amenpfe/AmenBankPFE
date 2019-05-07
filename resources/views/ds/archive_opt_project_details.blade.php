@extends('dashboard-template')
@section('title')
    Détails de demande
@endsection

@section('page-stylesheets')
    <!-- Specific Page Vendor CSS -->
    {!! HTML::style('css/bootstrap-fileupload.min.css') !!}
@endsection

@section('edit')
{{route('edit_cdd')}}
@endsection

@section('navigation')
<li class="nav-parent ">
    <a>
        <i class="fa fa-table" aria-hidden="true"></i>
        <span>Consulter les demandes</span>
    </a>
    <ul class="nav nav-children">
        <li class="{{route('get_ds_new')}}">
                <a href="">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Des nouveaux projets
                </a>
            </li>
        <li class="">
        <a href="{{route('get_ds_opt')}}">
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
            <a href="{{route('all_new_request_ds')}}">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Des nouveaux projets
            </a>
        </li>
        <li class="">
        <a href="{{route('all_opt_request_ds')}}">
                <i class="fa  fa-wrench" aria-hidden="true"></i>
                D'améliorations
            </a>
        </li>
        
    </ul>
</li>
<li class="nav-parent nav-active nav-expanded">
    <a>
        <i class="fa fa-archive" aria-hidden="true"></i>
        <span>Archive des projets</span>
    </a>
    <ul class="nav nav-children">
        <li class="">
            <a href="{{route('get_ds_new_archive')}}">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Des nouveaux projets
            </a>
        </li>
        <li class="nav-active">
        <a href="{{route('get_ds_opt_archive')}}">
                <i class="fa  fa-wrench" aria-hidden="true"></i>
                D'améliorations
            </a>
        </li>
        
    </ul>
</li>
@endsection

@section('content-title')
Détails de demande
@endsection


@section('content-path')
    <li>
        <span>Archive des projets</span>
    </li>
    <li>
        <span>D'améliorations</span>
    </li>
@endsection
@section('new-notification-route')new-request-details-cdd @endsection
@section('opt-notification-route')opt-request-details-cdd @endsection

@section('content')
    @php 
        $user = App\User::find($projectrequest->user_id); 
    @endphp
<section class="panel">
    <div class="panel-body">
        <div class="invoice">
            <header class="clearfix">
                <div class="row">
                    <div class="col-sm-6 mt-md">
                        <h2 class="h2 mt-none mb-sm text-dark text-bold">Réference</h2>
                    <h4 class="h4 m-none text-dark text-bold">#{{$projectrequest->requestable->id}}</h4><br>
                    </div>
                    <div class="col-sm-6 text-right mt-md mb-md">
                        <h2 class="h2 mt-none mb-sm text-dark text-bold">Etat</h2>
                        <h4 class="h4 m-none text-danger text-bold">{{App\Enums\StatusRequest::getEnumDescriptionByValue($projectrequest->status)}}</h4><br>
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
                            <span class="value">{{$projectrequest->requestable->created_at}}</span>
                            </p>
                            <p class="mb-none">
                                <span class="text-dark"><b>Dérniere modification:</b></span>
                                <span class="value">{{$projectrequest->requestable->updated_at}}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="table-responsive col-sm-10 col-sm-offset-1">
                <table class="table invoice-items" border="0">
                    <tbody>
                            <tr>
                                <td class="text-dark col-sm-3"><h4><b>Titre du projet</b></h4></td>
                                <td class="text-dark"><h4>{{App\Project::find($projectrequest->requestable->project_id)->name}}</h4></td>
                            </tr>
                            <tr>
                                <td class="text-dark col-sm-3"><h4><b>Type de demande</b></h4></td>
                                <td class="text-dark"><h4>{{App\Enums\RequestTypes::getEnumDescriptionByValue($projectrequest->requestable->type)}}</h4></td>
                            </tr>
                            <tr>
                                <td class="text-dark col-sm-3"><h4><b>Document d'expression des besoins</b></h4></td> 
                                <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$projectrequest->user_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                            </tr>
                            <tr>
                                <td class="text-dark col-sm-3"><h4><b>L'ensemble des actions à entreprendre</b></h4></td> 
                                <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$projectrequest->ced_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                            </tr>
                            <tr>
                                    <td class="text-dark col-sm-3"><h4><b>Cahier des charges</b></h4></td> 
                                    <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$projectrequest->chd_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                            </tr>
                            <tr>
                                    <td class="text-dark col-sm-3"><h4><b>Document d'analyse de besoins</b></h4></td> 
                                    <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$projectrequest->analyse_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                            </tr>
                            <tr>
                                    <td class="text-dark col-sm-3"><h4><b>Document de conception globale</b></h4></td> 
                                    <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$projectrequest->conception_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                            </tr>
                            <tr>
                                    <td class="text-dark col-sm-3"><h4><b>Le logiciel</b></h4></td> 
                                    <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$projectrequest->logiciel_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                            </tr>
                            <tr>
                                    <td class="text-dark col-sm-3"><h4><b>Document du test</b></h4></td> 
                                    <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$projectrequest->test_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                            </tr>
                            <tr>
                                    <td class="text-dark col-sm-3"><h4><b>Cahier de recette</b></h4></td> 
                                    <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$projectrequest->recette_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                            </tr>
                            <tr>
                                    <td class="text-dark col-sm-3"><h4><b>circulaire</b></h4></td> 
                                    <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$projectrequest->circulaire_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                            </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive col-sm-10 col-sm-offset-1"><br>
                <form class="form-horizontal form-bordered" method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <table class="table invoice-items" border="0">
                        <tbody>
                            <tr>
                                <div class="form-group {{ $errors->has('requestId') ? ' has-error' : '' }}">
                                    <div class="input-group input-group-icon">
                                            <input type="text" value="{{$projectrequest->id}}" name="requestId" hidden>
                                    </div>
                                    @if ($errors->has('requestId'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('requestId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </tr>
                        </tbody>
                    </table><br><br>
                    <footer>
                    </footer>
                </form>
            </div>  
        </div>
    </div>
</section>
@endsection

@section('page-scripts')
    
    <!-- Specific Page Vendor -->
    {!! HTML::script('js/bootstrap-fileupload.min.js') !!}
@endsection