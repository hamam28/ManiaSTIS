@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{'Transaksi Barang'}}
@parent
@stop

@section('header_right')
  @can('create', \App\Models\ConsumableTransaction::class)
  <a href="{{ route('consumablestransaction.create') }}" class="btn btn-primary pull-right"> {{ trans('general.create') }}</a>
  @endcan
@stop

{{-- Page content --}}
@section('content')

<div class="row">
  <div class="col-md-12">

    <div class="box box-default">
      <div class="box-body">
        <table
                data-columns="{{ \App\Presenters\ConsumableTransactionPresenter::dataTableLayout() }}"
                data-cookie-id-table="consumablesTransactionTable"
                data-pagination="true"
                data-id-table="consumablesTransactionTable"
                data-search="true"
                data-side-pagination="server"
                data-show-columns="true"
                data-show-export="true"
                data-show-fullscreen="true"
                data-show-footer="true"
                data-show-refresh="true"
                data-sort-order="asc"
                data-sort-name="id"
                data-toolbar="#toolbar"
                id="consumablesTransactionTable"
                class="table table-striped snipe-table"
                data-url="{{ route('api.consumablestransaction.index') }}"
                data-export-options='{
                "fileName": "export-consumables-{{ date('Y-m-d') }}",
                "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                }'>
        </table>

      </div><!-- /.box-body -->
    </div><!-- /.box -->

  </div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
@stop

@section('moar_scripts')
<!-- @include ('partials.bootstrap-table', ['exportFile' => 'consumables-export', 'search' => true,'showFooter' => true, 'columns' => \App\Presenters\ConsumablePresenter::dataTableLayout()]) -->
@include ('partials.bootstrap-table')
@stop
