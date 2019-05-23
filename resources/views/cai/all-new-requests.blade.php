@extends('dashboard-template')

@section('title')
    Suivi des demandes
@endsection

@section('page-stylesheets')
    <!-- Specific Page Vendor CSS -->
    {!! HTML::style('css/select2.css') !!}
    {!! HTML::style('css/datatables.css') !!}
@endsection

@section('edit')
{{route('edit_cai')}}
@endsection


@section('navigation')
<li class="nav-parent">
    <a>
        <i class="fa fa-table" aria-hidden="true"></i>
        <span>Consulter les demandes</span>
    </a>
    <ul class="nav nav-children">
        <li class="">
        <a href="{{route('get_cai_new')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Des nouveaux projets
                </a>
            </li>
        <li class="">
        <a href="{{route('get_cai_opt')}}">
                <i class="fa fa-wrench" aria-hidden="true"></i>
                    D'améliorations
            </a>
        </li>               
    </ul>
</li>
    <li class="nav-parent nav-active nav-expanded">
        <a>
            <i class="fa fa-calendar" aria-hidden="true"></i>
            <span>Suivi des demande</span>
        </a>
        <ul class="nav nav-children">
            <li class="nav-active">
                <a href="">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Des nouveaux projets
                </a>
            </li>
            <li class="">
            <a href="{{route('all_opt_request_cai')}}">
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
            <a href="{{route('get_cai_new_archive')}}">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Des nouveaux projets
            </a>
        </li>
        <li class="nav-">
        <a href="{{route('get_cai_opt_archive')}}">
                <i class="fa  fa-wrench" aria-hidden="true"></i>
                D'améliorations
            </a>
        </li>
        
    </ul>
</li>
    
@endsection

@section('content-title')
Suivi des demandes des nouveaux projets
@endsection

@section('content-path')
    <li>
        <span>Suivi des demandes</span>
    </li>
    <li>
        <span>Des nouveaux projets</span>
    </li>
@endsection

@section('content')

<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Suivi des demandes</h2>
        </header>
        <div class="panel-body">
            <form method="POST" action="">
            {{ csrf_field() }}
                <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                        <tr>
                            <th>#Ref</th>
                            <th>Titre</th>
                            <th>Etat</th>
                            <th>Créé à</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($newprojectrequests as $NewprojectRequest)
                        <tr class="gradeX" id="row-{{$NewprojectRequest->id}}">
                            <td class="userId">{{$NewprojectRequest->id}}<input name="user[id]" class="u" hidden required type="number" value="{{$NewprojectRequest->id}}"/></td>
                            <td class="input email email">{{$NewprojectRequest->title}}</td>
                            <td class="input email email">{{App\Enums\StatusRequest::getEnumDescriptionByValue($NewprojectRequest->request->status)}}</td>
                            <td class="input email email">{{$NewprojectRequest->created_at}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </section>
@endsection



@section('page-scripts')

    <!-- Specific Page Vendor -->
    {!! HTML::script('js/select2.js') !!}
    {!! HTML::script('js/jquery.dataTables.js') !!}
    {!! HTML::script('js/dataTables.tableTools.min.js') !!}
    {!! HTML::script('js/datatables.js') !!}
    
    <!-- Table script -->
    {!! HTML::script('js/examples.datatables.default.js') !!}
@endsection
