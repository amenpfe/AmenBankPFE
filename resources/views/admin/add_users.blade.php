@extends('dashboard-template')

@section('title')
Admin - Ajouter
@endsection

@section('page-stylesheets')
<style>
    .form-group {
        margin-bottom: 4% !important;
    }
</style>
@endsection

@section('user-name')
{{Auth::user()->name}}
@endsection

@section('user-role')
{{\App\Enums\UserRole::getEnumDescriptionByValue(Auth::user()->role)}}
@endsection

@section('navigation')
<li class="nav-parent nav-active nav-expanded">
    <a>
        <i class="fa fa-user" aria-hidden="true"></i>
        <span>Gérer les utilisateurs</span>
    </a>
    <ul class="nav nav-children">
        <li class="nav-active">
            <a href="">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Ajouter
            </a>
        </li>
        <li>
            <a href="{{ route('manage_users') }}">
                <i class="fa fa-table" aria-hidden="true"></i>
                Consulter
            </a>
        </li>
    </ul>
</li>
@endsection

@section('content-title')
Ajouter
@endsection

@section('content-path')
<li>
    <span>Gérer les utilisateurs</span>
</li>
<li>
    <span>Ajouter</span>
</li>
@endsection

@section('content')
<form id="form" method="POST" action="{{ route('add_user') }}" class="form-horizontal">
    {{ csrf_field() }}
    <section class="panel">
        <header class="panel-heading">

            <h2 class="panel-title">Ajouter un utilisateur</h2>
            <p class="panel-subtitle">
            </p>
        </header>
        <div class="panel-body">
            <div class="col-md-10 ">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nom <span class="required">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" id="name" name="name" class="form-control" placeholder="eg.: John Doe"
                            required />
                    </div>
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-sm-3 control-label">Email <span class="required">*</span></label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="eg.: email@email.com" required />

                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Rôle <span class="required">*</span></label>
                    <div class="col-sm-9">
                        <select id="role" name="role" class="form-control" required>
                            @foreach (\App\Enums\UserRole::getValues() as $key => $value)
                            <option value="{{$value}}"> {{\App\Enums\UserRole::getEnumDescriptionBykey($key)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="col-sm-3 col-sm-offset-9">
                    <button class="btn btn-primary">Valider</button>
                    <button type="reset" class="btn btn-default">Annuler</button>
                </div>
            </div>
        </footer>
    </section>
</form>
@endsection

@section('page-scripts')
{!! HTML::script('js/jquery.validate.js') !!}
{!! HTML::script('js/examples.validation.js') !!}

@endsection