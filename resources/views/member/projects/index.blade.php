@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ $pageTitle }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ $pageTitle }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">

<style>
        .custom-action a {
            margin-right: 15px;
            margin-bottom: 15px;
        }
        .custom-action a:last-child {
            margin-right: 0px;
            float: right;
        }

        .dashboard-stats .white-box .list-inline {
            margin-bottom: 0;
        }

        .dashboard-stats .white-box {
            padding: 10px;
        }

        .dashboard-stats .white-box .box-title {
            font-size: 13px;
            text-transform: capitalize;
            font-weight: 300;
        }

        @media all and (max-width: 767px) {
            .custom-action a {
                margin-right: 0px;
            }

            .custom-action a:last-child {
                margin-right: 0px;
                float: none;
            }
        }
    </style>
@endpush

@section('content')

<div class="row dashboard-stats">
        <div class="col-md-2">
            <div class="white-box bg-inverse p-t-10 p-b-10">
                <h3 class="box-title text-white">@lang('modules.dashboard.totalProjects')</h3>
                <ul class="list-inline two-part">
                    <li><i class="icon-layers text-white"></i></li>
                    <li class="text-right"><span id="totalProjects" class="counter text-white">{{ $totalProjects }}</span></li>
                </ul>
            </div>
        </div>

        <div class="col-md-2">
            <div class="white-box p-t-10 p-b-10 bg-danger">
                <h3 class="box-title text-white">@lang('modules.tickets.overDueProjects')</h3>
                <ul class="list-inline two-part">
                    <li><i class="icon-layers text-white"></i></li>
                    <li class="text-right"><span id="overdueProjects" class="counter text-white">{{ $overdueProjects }}</span></li>
                </ul>
            </div>
        </div>

    </div>

    <div class="row dashboard-stats">

        <div class="col-md-2">
            <div class="white-box p-t-10 p-b-10 bg-warning">
                <h3 class="box-title text-white">@lang('app.notStarted') @lang('app.menu.projects')</h3>
                <ul class="list-inline two-part">
                    <li><i class="icon-layers text-white"></i></li>
                    <li class="text-right"><span class="text-white">{{ $notStartedProjects }}</span></li>
                </ul>
            </div>
        </div>

        <div class="col-md-2">
            <div class="white-box bg-success p-t-10 p-b-10">
                <h3 class="box-title text-white">@lang('modules.tickets.completedProjects')</h3>
                <ul class="list-inline two-part">
                    <li><i class="icon-layers text-white"></i></li>
                    <li class="text-right"><span id="completedProjects" class="counter text-white">{{ $finishedProjects }}</span></li>
                </ul>
            </div>
        </div>

        <div class="col-md-2">
            <div class="white-box p-t-10 p-b-10 bg-info">
                <h3 class="box-title text-white">@lang('app.inProgress') @lang('app.menu.projects')</h3>
                <ul class="list-inline two-part">
                    <li><i class="icon-layers text-white"></i></li>
                    <li class="text-right"><span id="inProcessProjects" class="counter text-white">{{ $inProcessProjects }}</span></li>
                </ul>
            </div>
        </div>

        <div class="col-md-2">
            <div class="white-box p-t-10 p-b-10 bg-warning">
                <h3 class="box-title text-white">@lang('app.onHold') @lang('app.menu.projects')</h3>
                <ul class="list-inline two-part">
                    <li><i class="icon-layers text-white"></i></li>
                    <li class="text-right"><span class="text-white">{{ $onHoldProjects }}</span></li>
                </ul>
            </div>
        </div>

        <div class="col-md-2">
            <div class="white-box p-t-10 p-b-10 bg-danger">
                <h3 class="box-title text-white">@lang('app.canceled') @lang('app.menu.projects')</h3>
                <ul class="list-inline two-part">
                    <li><i class="icon-layers text-white"></i></li>
                    <li class="text-right"><span class="text-white">{{ $canceledProjects }}</span></li>
                </ul>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <div class="row">
                    @if($user->can('add_projects'))
                        <div class="col-sm-2">
                            <div class="form-group">
                                <a href="{{ route('member.projects.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.projects.addNewProject') <i class="fa fa-plus" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <a href="{{ route('member.project-template.index') }}"  class="btn btn-outline btn-primary btn-sm">@lang('app.menu.addProjectTemplate') <i class="fa fa-plus" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-4">
                        <div class="form-group">
                        </div>
                    </div>
                </div>
                @if($user->can('view_projects'))
                    <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <select class="select2 form-control" data-placeholder="@lang('app.menu.projects') @lang('app.status')" id="status">
                                        <option selected value="all">@lang('app.all')</option>
                                        <option value="complete">@lang('app.complete')</option>
                                        <option value="incomplete">@lang('app.incomplete')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <select class="select2 form-control" data-placeholder="@lang('app.clientName')" id="client_id">
                                        <option selected value="all">@lang('app.all')</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="project-table">
                        <thead>
                        <tr>
                            <th>@lang('app.id')</th>
                            <th>@lang('modules.projects.projectName')</th>
                            <th>@lang('modules.projects.projectMembers')</th>
                            <th>@lang('modules.projects.deadline')</th>
                            <th>@lang('app.completion')</th>
                            <th>@lang('app.status')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script>
    var table;
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    $('.select2').val('all');
    $(function() {
        showData();
        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted project!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('member.projects.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });

        $('#createProject').click(function(){
            var url = '{{ route('admin.projectCategory.create')}}';
            $('#modelHeading').html('Manage Project Category');
            $.ajaxModal('#projectCategoryModal',url);
        })

    });

    function showData(){
        var status = "";
        var clientID = "";

        if($('#status').length){
            status = $('#status').val();
        }

        if($('#client_id').length){
            clientID = $('#client_id').val();
        }

        var searchQuery = "?status="+status+"&client_id="+clientID;

        table = $('#project-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: '{!! route('member.projects.data') !!}'+searchQuery,
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function( oSettings ) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'project_name', name: 'project_name'},
                { data: 'members', name: 'members' },
                { data: 'deadline', name: 'deadline' },
                { data: 'completion_percent', name: 'completion_percent' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ]
        });
    }

    $('#status').on('change', function(event) {
        event.preventDefault();
        showData();
    });

    $('#client_id').on('change', function(event) {
        event.preventDefault();
        showData();
    });

</script>
@endpush