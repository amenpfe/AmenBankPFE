@extends('dashboard-template')

@section('title')
    Archive des projets
@endsection

@section('page-stylesheets')
    <!-- Specific Page Vendor CSS -->
    {!! HTML::style('css/select2.css') !!}
    {!! HTML::style('css/datatables.css') !!}
@endsection

@section('edit')
{{route('edit_ced')}}
@endsection

@section('new-notification-route')new-request-details-cdd @endsection
@section('opt-notification-route')opt-request-details-cdd @endsection


@section('navigation')
<li class="nav-parent">
    <a>
        <i class="fa fa-table" aria-hidden="true"></i>
        <span>Consulter les demandes</span>
    </a>
    <ul class="nav nav-children">
        <li class="">
                <a href="{{route('get_ced_new')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Des nouveaux projets
                </a>
            </li>
        <li class="">
        <a href="{{route('get_ced_opt')}}">
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
            <a href="{{route('all_new_request_ced')}}">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Des nouveaux projets
            </a>
        </li>
        <li class="">
        <a href="{{route('all_opt_request_ced')}}">
                <i class="fa  fa-wrench" aria-hidden="true"></i>
                D'améliorations
            </a>
        </li>
        
    </ul>
</li>
<li class="nav-parent nav-active nav-expanded">
    <a>
        <i class="fa fa-archive" aria-hidden="true"></i>
        <span>Archive</span>
    </a>
    <ul class="nav nav-children">
        <li class="">
            <a href="{{route('get_ced_new_archive')}}">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Des nouveaux projets
            </a>
        </li>
        <li class="nav-active">
        <a href="">
                <i class="fa  fa-wrench" aria-hidden="true"></i>
                Des projets améliorés
            </a>
        </li>
        
    </ul>
</li>
<li class="">
    <a href="{{route('get_ced_stat')}}">
        <i class="fa fa-bar-chart-o" aria-hidden="true"></i>
        <span>Les Statistiques</span>
    </a>
</li>
    
@endsection

@section('content-title')
Archive des projets
@endsection

@section('content-path')
    <li>
        <span>Archive des projets</span>
    </li>
    <li>
        <span>D'améliorations</span>
    </li>
@endsection

@section('content')

<section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Archive des projets traités</h2>
        </header>
        <div class="panel-body">
            <form method="POST" action="">
            {{ csrf_field() }}
                <table class="table table-bordered table-striped mb-none" id="datatable-default">
                    <thead>
                        <tr>
                            <th>#Ref</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Créé à</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projectrequest as $projectRequest)
                        <tr class="gradeX" id="row-{{$projectRequest->requestable->id}}">
                            <td class="userId">{{$projectRequest->requestable->id}}<input name="user[id]" class="u" hidden required type="number" value="{{$projectRequest->requestable->id}}"/></td>
                            <td class="input email email">{{App\Project::find($projectRequest->requestable->project_id)->name}}</td>
                            <td class="input email email">{{$projectRequest->requestable_type}}</td>
                            <td class="input email email">{{$projectRequest->requestable->created_at}}</td>
                            <td class="actions">
                                <a href="{{route('get_ced_opt_archive_details', $projectRequest->id)}}"><i class="fa fa-eye"></i></a>
                            </td>
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
