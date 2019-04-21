@extends('dashboard-template')

@section('title')
    Envoyer une demande
@endsection

@section('page-stylesheets')
    <!-- Specific Page Vendor CSS -->
    {!! HTML::style('css/bootstrap-fileupload.min.css') !!}
    {!! HTML::style('css/summernote.css') !!}
    {!! HTML::style('css/summernote-bs3.css') !!}
 
@endsection


@section('edit')
{{route('edit_user')}}
@endsection


@section('navigation')
    <li class="nav-parent ">
        <a>
            <i class="fa fa-table" aria-hidden="true"></i>
            <span>Consulter les demandes</span>
        </a>
        <ul class="nav nav-children">
                <li class="">
                    <a href="{{route('get_new')}}">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Des nouveaux projets
                    </a>
                </li>
            <li class="">
                <a href="{{route('get_opt')}}">
                    <i class="fa fa-wrench" aria-hidden="true"></i>
                    D'améliorations
                </a>
            </li>               
        </ul>
    </li>
    <li class="nav-parent nav-active nav-expanded">
        <a>
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            <span>Envoyer une demandes</span>
        </a>
        <ul class="nav nav-children">
            <li class="">
                <a href="{{route('add_new_request')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    D'un nouveau projet
                </a>
            </li>
            <li class="nav-active">
                <a href="">
                    <i class="fa  fa-wrench" aria-hidden="true"></i>
                    D'amélioration 
                </a>
            </li>
            
        </ul>
    </li>
    
@endsection

@section('content-title')
Demande d'amélioration
@endsection

@section('content-path')
    <li>
        <span>Envoyer une demande</span>
    </li>
    <li>
        <span>D'amélioration</span>
    </li>
@endsection

@section('new-notification-route')detail_request @endsection
@section('opt-notification-route')opt_detail_request @endsection

@section('content')

    <form class="form-horizontal form-bordered" method="POST" action="{{route('add_opt_request')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
        <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Demande d'amélioration</h2>
                </header>
                <div class="panel-body"> 
                    <div class="form-group">
                    <label class="col-md-3 control-label">Type</label>
                    <div class="col-md-6">
                        <select id="type" name="type" class="form-control" required>
                            @foreach (App\Enums\RequestTypes::getValues() as $key => $value)
                                <option value="{{$value}}">{{App\Enums\RequestTypes::getEnumDescriptionBykey($key)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputSuccess">Projet</label>
                        <div class="col-md-6">
                            <select class="form-control mb-md" name="project_id">
                                @foreach ($projects as $project)
                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('remarques') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">Remarques</label>
                            <div class="col-md-9">
                                <textarea class="summernote" data-plugin-summernote data-plugin-options='{ "height": 180, "codemirror": { "theme": "ambiance" } }' name="remarques">Start typing...</textarea>
                                @if ($errors->has('remarques'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('remarques') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                      
                    <div class="form-group {{ $errors->has('chd') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">Joindre un fichier</label>
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
                                            <input type="file" name="chd" accept=".pdf"/>
                                        </span>
                                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Retirer</a>
                                        <br><br>
                                    </div>
                                    
                                    @if ($errors->has('chd'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('chd') }}</strong>
                                    </span>
                                @endif
                                </div>
                            </div>
                        </div>
                </div>

                <footer class="panel-footer ">
                        <div class="row ">
                            <div class="col-sm-3 col-sm-offset-9">
                                <button class="btn btn-primary">Envoyer</button>
                                <button type="reset" class="btn btn-default">Annuler</button>
                            </div>
                        </div>
                </footer>
        </section>
    </form>     
        
@endsection
@section('page-scripts')

    <!-- Specific Page Vendor -->
    {!! HTML::script('js/jquery.autosize.js') !!}
    {!! HTML::script('js/bootstrap-fileupload.min.js') !!}
    {!! HTML::script('js/summernote.js') !!}
@endsection
