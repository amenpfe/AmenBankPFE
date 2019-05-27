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
<li class="nav-parent  nav-expanded">
    <a>
        <i class="fa fa-table" aria-hidden="true"></i>
        <span>Consulter les demandes</span>
    </a>
    <ul class="nav nav-children">
        
        <li class="nav-parent  nav-expanded">
            <a><i class="fa fa-tasks" aria-hidden="true"></i> Activité</a>
            <ul class="nav nav-children">
                <li class="nav-parent  nav-expanded">
                    <a>Test unitaire</a>
                    <ul class="nav nav-children">
                        <li class="nav-active">
                            <a href="{{route('get_cdd_new')}}"><i class="fa fa-plus" aria-hidden="true"></i>
                                Des nouveaux projets</a>
                        </li>
                        <li>
                            <a href="{{route('get_cdd_opt')}}"><i class="fa fa-wrench" aria-hidden="true"></i>
                                D'améliorations</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-parent">
                    <a>Analyse des besoins</a>
                    <ul class="nav nav-children">
                        <li>
                            <a href="{{route('get_cddp_new')}}"><i class="fa fa-plus" aria-hidden="true"></i>
                                Des nouveaux projets</a>
                        </li>
                        <li>
                            <a href="{{route('get_cddp_opt')}}"><i class="fa fa-wrench" aria-hidden="true"></i>
                                D'améliorations</a>
                        </li>
                    </ul>
                </li>
                
            </ul>
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
                    <a href="{{route('all_new_request_cdd')}}">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Des nouveaux projets
                    </a>
                </li>
                <li class="">
                <a href="{{route('all_opt_request_cdd')}}">
                        <i class="fa  fa-wrench" aria-hidden="true"></i>
                        D'améliorations
                    </a>
                </li>
                
            </ul>
        </li>
        <li class="">
            <a href="{{route('get_cdd_stat')}}">
                <i class="fa fa-bar-chart-o" aria-hidden="true"></i>
                <span>Les Statistiques</span>
            </a>
        </li>
@endsection

@section('content-title')
Détails de demande
@endsection

@section('content')
    @php 
        $user = App\User::find($request->user_id); 
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
                            <td class="text-dark col-sm-3"><h4><b>Titre du projet</b></h4></td>
                            <td class="text-dark"><h4>{{$request->requestable->title}}</h4></td>
                        </tr>
                        <tr>
                            <td class="text-dark col-sm-3"><h4><b>Fichier</b></h4></td> 
                            <td class="text-dark"><h4><a href="{{URL::to('/')}}/files/{{$request->chd_doc}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Ouvrir</a></h4></td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <h4 class="text-dark" style="padding-left: 0.5%; padding-right: 0.5%"><b>Remarques</b></h4><br>
                    <h4><div class="" 
                        style="border-left: 5px solid #114E9E; border-top-left-radius: 5px; border-bottom-left-radius: 5px;
                        border-right: 5px solid #114E9E; border-top-right-radius: 5px; border-bottom-right-radius: 5px;
                        margin-top: 5px; padding-left: 1%;">
                            {!!$request->remarques!!}
                    </div><br><br><br></h4>
                </div>
            </div>
            <div class="table-responsive col-sm-10 col-sm-offset-1"><br>
                                <form class="form-horizontal form-bordered" method="POST" action="{{route('new-request-detail-cdd-submit')}}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div>{{$error}}</div>
                                        @endforeach
                                    @endif
                                    <table class="table invoice-items" border="0">
                                        <tbody>
                                            <tr>
                                                <div class="form-group {{ $errors->has('requestId') ? ' has-error' : '' }}">
                                                            <div class="input-group input-group-icon">
                                                                    <input type="text" value="{{$request->id}}" name="requestId" hidden>
                                                            </div>
                                                            @if ($errors->has('requestId'))
                                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('requestId') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                <td class="text-dark col-sm-3"><h4><b>Joindre un fichier</b></h4></td>
                                                <td class="text-dark"><h4><div class="form-group {{ $errors->has('doc') ? ' has-error' : '' }}">
                                                    <div class="col-md-6">
                                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                                            <div class="input-append">
                                                                <div class="uneditable-input">
                                                                    <i class="fa fa-file fileupload-exists"></i>
                                                                    <span class="fileupload-preview"></span>
                                                                </div>
                                                                <span class="btn btn-default btn-file">
                                                                    <span class="fileupload-exists">Changer</span>
                                                                    <span class="fileupload-new">Sélectionner</span>
                                                                    <input type="file" name="doc" accept=".pdf"/>
                                                                </span>
                                                                <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Retirer</a>
                                                                <br><br>
                                                            </div>
                                                                @if ($errors->has('doc'))
                                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('doc') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div></h4></td>
                                            </tr>
                                        </tbody>
                                    </table><br><br>
                                    <footer>
                                        <div class="row ">
                                            <div class="col-sm-3 col-sm-offset-9">
                                                <button class="btn btn-primary">Envoyer</button>
                                                <button type="reset" class="btn btn-default">Annuler</button>
                                            </div>
                                        </div>
                                    </footer>
                                </form>
                </div>  
            </div>
        </div>
    </div>
</section>
@endsection

@section('page-scripts')
    
    <!-- Specific Page Vendor -->
    {!! HTML::script('js/bootstrap-fileupload.min.js') !!}
@endsection