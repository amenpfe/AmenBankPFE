@extends('dashboard-template')

@section('title')
 Modifier le profil
@endsection

@section('page-stylesheets')
<style>
    .form-group {
        margin-bottom: 4% !important;
    }
</style>
@endsection

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
<li class="nav-parent">
    <a>
        <i class="fa fa-calendar" aria-hidden="true"></i>
        <span>Suivi des demande</span>
    </a>
    <ul class="nav nav-children">
        <li class="">
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
@endsection

@section('content-title')
Modifier le profil
@endsection

@section('new-notification-route')opt-request-details-prop @endsection
@section('opt-notification-route')opt-request-details-prop @endsection


@section('content')
    <div id="dialog" class="modal-block mfp-hide">
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Saisir votre mot de passe actuel</h2>
            </header>
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class="modal-text">
                        <input required name="current_password" type="password" id="current_password" onkeyup="checkPassword(this)"/>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button id="dialogConfirm" class="btn btn-primary" type="button" onclick="submitForm()" disabled>Confirmer</button>
                        <button id="dialogCancel" class="btn btn-default" onclick="closePopup()" type="button">Annuler</button>
                    </div>
                </div>
            </footer>
        </section>
    </div>


    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Modifier le profil</h2>
            <p class="panel-subtitle">
            </p>
        </header>
        <div class="panel-body">
        @isset($success)
            <!--Success alert-->
            <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{$success}}</strong>
                </div>
           
        @endisset
        @isset($error)
            <!--Error alert-->
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>{{$error}}</strong>
            </div>
            
        @endisset
        <form class="form-horizontal" method="POST" action="{{route('submit_prop_edit')}}" id="form">
                {{ csrf_field() }}
                <h4 class="mb-xlg"> Informations personnelles</h4>
                <fieldset>
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label" for="name">Nom</label>
                        <div class="col-md-8">
                            <input type="text" id ="name" name="name" class="form-control" value="{{Auth::user()->name}}" required>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif    
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('adresse') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label" for="adresse">Adresse</label>
                        <div class="col-md-8">
                            <input type="text" id ="adresse" name="adresse" class="form-control" value="{{Auth::user()->adresse}}" required>
                            @if ($errors->has('adresse'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('adresse') }}</strong>
                                </span>
                                @endif    
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label" for="phone">Numéro</label>
                        <div class="col-md-8">
                            <input type="tel" id ="phone" name="phone" class="form-control" value="{{Auth::user()->phone}}" required>
                            @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif    
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label" for="email">Adresse E-mail</label>
                        <div class="col-md-8">
                            
                            <input type="email" id="email" name="email" class="form-control" value="{{Auth::user()->email}}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif    
                       
                        </div>
                    </div>
                </fieldset>
                <hr class="dotted tall">
                <h4 class="mb-xlg">Changer le mot de passe</h4>
                <fieldset class="mb-xl">
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label" for="password">Nouveau mot de passe</label>
                        <div class="col-md-8">
                            <input type="password" id="password" name="password" class="form-control">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label" for="password-confirm">Confirmer le nouveau mot de passe</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" id="password-confirm" name="password_confirmation">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </fieldset>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-9">
                            <button class="btn btn-primary" type="button" onclick="showPopup()">Valider</button>
                            <button type="reset" class="btn btn-default">Annuler</button>
                            @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                </footer>
        </form>
    </div>

    </section>
@endsection

@section('page-scripts')
{!! HTML::script('js/jquery.validate.js') !!}
{!! HTML::script('js/examples.validation.js') !!}
<script>
    function showPopup(){
        if($('#form').valid()){
            $.magnificPopup.open({
                items: {
                    src: '#dialog',
                    type: 'inline'
                },
                preloader: false,
                modal: true
            });
        }
    }

    function closePopup(){
        $.magnificPopup.close();
    }

    function submitForm() {
        var inputVal = $('#current_password').clone();
        var form = $('#form').append(inputVal);
        $.magnificPopup.close();
        form.submit();
    }

    function checkPassword(input){
        var passInput = $(input);
        if(passInput.val() == ''){
            $('#dialogConfirm').attr("disabled", true);
        }else{
            $('#dialogConfirm').attr("disabled", false);
        }
    }
</script>

@endsection