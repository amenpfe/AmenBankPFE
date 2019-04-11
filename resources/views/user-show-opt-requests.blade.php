@extends('dashboard-template')

@section('title')
    Consulter les demandes
@endsection

@section('page-stylesheets')
    <!-- Specific Page Vendor CSS -->
    {!! HTML::style('css/select2.css') !!}
    {!! HTML::style('css/datatables.css') !!}
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
            <a href="">
                <i class="fa fa-wrench" aria-hidden="true"></i>
                    Demandes d'améliorations
            </a>
        </li>               
    </ul>
</li>
    <li class="nav-parent">
        <a>
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            <span>Effectuer une demande</span>
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
Demandes d'amélioration
@endsection

@section('content-path')
    <li>
        <span>Consulter</span>
    </li>
    <li>
        <span>Demandes d'amélioration</span>
    </li>
@endsection

@section('content')

<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Liste des demandes</h2>
        </header>
        <div class="panel-body">
            <form method="POST" action="">
            {{ csrf_field() }}
                <table class="table table-bordered table-striped mb-none" id="datatable-editable">
                    <thead>
                        <tr>
                            <th>#Ref</th>
                            <th>Type</th>
                            <th>Remarques</th>
                            <th>Etat</th>
                            <th>Créé à</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($optimizationRequests as $optimizationRequest)
                        <tr class="gradeX" id="row-{{$optimizationRequest->id}}">
                            <td class="userId">{{$optimizationRequest->id}}<input name="user[id]" class="u" hidden required type="number" value="{{$optimizationRequest->id}}"/></td>
                            <td class="input email email">{{\App\Enums\TypeRequest::getEnumDescriptionByValue($optimizationRequest->type)}}</td>
                            <td class="input email email">{{$optimizationRequest->request->remarques}}</td>
                            <td class="input email email">{{App\Enums\StatusRequest::getEnumDescriptionByValue($optimizationRequest->request->status)}}</td>
                            <td class="input email email">{{$optimizationRequest->created_at}}</td>
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
    {!! HTML::script('js/users.datatable.js') !!}
@endsection
