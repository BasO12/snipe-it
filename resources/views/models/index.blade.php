@extends('layouts/default')

{{-- Page title --}}
@section('title')

  @if (Request::get('status')=='deleted')
    {{ trans('admin/models/general.view_deleted') }}
    {{ trans('admin/models/table.title') }}
    @else
    {{ trans('admin/models/general.view_models') }}
  @endif
@parent
@stop

{{-- Page title --}}
@section('header_right')
  @can('create', \App\Models\AssetModel::class)
    <a href="{{ route('models.create') }}" class="btn btn-primary pull-right"> {{ trans('general.create') }}</a>
  @endcan

  @if (Request::get('status')=='deleted')
    <a class="btn btn-default pull-right" href="{{ route('models.index') }}" style="margin-right: 5px;">{{ trans('admin/models/general.view_models') }}</a>
  @else
    <a class="btn btn-default pull-right" href="{{ route('models.index', ['status' => 'deleted']) }}" style="margin-right: 5px;">{{ trans('admin/models/general.view_deleted') }}</a>
  @endif

@stop


{{-- Page content --}}
@section('content')


<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-body">

        {{ Form::open([
          'method' => 'POST',
          'route' => ['models.bulkedit.index'],
          'class' => 'form-inline',
           'id' => 'modelsBulkForm']) }}
        <div class="row">
          <div class="col-md-12">

            @if (Request::get('status')!='deleted')
              <div id="modelBulkEditToolbar">
                <label for="bulk_actions" class="sr-only">{{ trans('general.bulk_actions') }}</label>
                <select id="bulk_actions" name="bulk_actions" class="form-control select2" aria-label="bulk_actions" style="width: 300px;">
                  <option value="edit">{{ trans('general.bulk_edit') }}</option>
                  <option value="delete">{{ trans('general.bulk_delete') }}</option>
                </select>
                <button class="btn btn-primary" id="bulkModelsEditButton" disabled>Go</button>
              </div>
            @endif
              <div class="table-responsive">
                <table
                        data-columns="{{ \App\Presenters\AssetModelPresenter::dataTableLayout() }}"
                        data-cookie-id-table="asssetModelsTable"
                        data-pagination="true"
                        data-id-table="asssetModelsTable"
                        data-search="true"
                        data-show-footer="true"
                        data-side-pagination="server"
                        data-show-columns="true"
                        data-toolbar="#modelsBulkEditToolbar"
                        data-bulk-button-id="#bulkModelsEditButton"
                        data-bulk-form-id="#modelsBulkForm"
                        data-show-export="true"
                        data-show-refresh="true"
                        data-sort-order="asc"
                        id="asssetModelsTable"
                        class="table table-striped snipe-table"
                        data-url="{{ route('api.models.index', ['status' => request('status')]) }}"
                        data-export-options='{
              "fileName": "export-models-{{ date('Y-m-d') }}",
              "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
              }'>
              </table>

          </div>
        </div>
        </div>
        {{ Form::close() }}
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>

@stop

@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'models-export', 'search' => true])

@stop
