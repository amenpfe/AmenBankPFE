@extends('dashboard-template')

@section('title')
    Admin - Consulter
@endsection

@section('page-stylesheets')
    <!-- Specific Page Vendor CSS -->
    {!! HTML::style('css/select2.css') !!}
    {!! HTML::style('css/datatables.css') !!}
@endsection

@section('edit')
{{route('edit_admin')}}
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
                    <i class="fa fa-table" aria-hidden="true"></i>
                    Consulter
                </a>
            </li>
            <li>
                <a href="{{ route('add_users') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Ajouter
                </a>
            </li>
        </ul>
    </li>
@endsection

@section('content-title')
    Consulter
@endsection

@section('content-path')
    <li>
        <span>Gérer les utilisateurs</span>
    </li>
    <li>
        <span>Consulter</span>
    </li>
@endsection

@section('content')
    <section class="panel">
        <header class="panel-heading">
    
            <h2 class="panel-title">Liste des utilisateurs</h2>
        </header>
        <div class="panel-body">
            <form method="POST" action="{{route('update_user')}}">
            {{ csrf_field() }}
                <table class="table table-bordered table-striped mb-none" id="datatable-editable">
                    <thead>
                        <tr>
                            <th>#Ref</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Adresse</th>
                            <th>Num. Tel.</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="gradeX" id="row-{{$user->id}}">
                                <td class="userId">{{$user->id}}<input name="user[id]" class="u" hidden required type="number" value="{{$user->id}}"/></td>
                                <td class="input name text">{{$user->name}}</td>
                                <td class="input email email">{{$user->email}}</td>
                                <td class="input adresse text">{{$user->adresse}}</td>
                                <td class="input phone text">{{$user->phone}}</td>
                                <td class="select role">{{\App\Enums\UserRole::getEnumDescriptionByValue($user->role)}}</td>
                                <td class="actions">
                                    <a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a><input type="submit" class="form_submit" hidden/>
                                    <a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
                                    @if (Auth::user()->id != $user->id)
                                        <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                        <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </section>

    <div id="dialog" class="modal-block mfp-hide">
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Êtes-vous sûr?</h2>
            </header>
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class="modal-text">
                        <p>Êtes-vous sûr de vouloir supprimer cet employé?</p>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <form method="POST" action="{{ route('delete_user') }}">
                            {{ csrf_field() }}
                            <input hidden name="userId" id="userId" value="1"/>
                            <input type="submit" class="btn btn-primary" value="Confirmer"/>
                            <button id="dialogCancel" class="btn btn-default">Annuler</button>
                        </form>
                    </div>
                </div>
            </footer>
        </section>
    </div>

        {{$errors->first('row')}}
@endsection

@section('page-scripts')
    <script>
        var userRoles = {!!json_encode(\App\Enums\UserRole::getValues())!!};
        var descriptions = {!!json_encode(\App\Enums\UserRole::getEnumDescriptions())!!};
        var errorMessage = '@if($errors->has('email')){!!$errors->first('email')!!}@endif ';
        var errorRow =  '@if($errors->has('email')){!!$errors->first('row')!!}@endif ';
        var oldMail =  '@if($errors->has('oldMail')){!!$errors->first('oldMail')!!}@endif ';
    </script>
    <!-- Specific Page Vendor -->
    {!! HTML::script('js/select2.js') !!}
    {!! HTML::script('js/jquery.dataTables.js') !!}
    {!! HTML::script('js/dataTables.tableTools.min.js') !!}
    {!! HTML::script('js/datatables.js') !!}

    <!-- Table script -->
    {!! HTML::script('js/users.datatable.js') !!}
@endsection