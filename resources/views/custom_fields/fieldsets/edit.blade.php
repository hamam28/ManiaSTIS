@extends('layouts.default')

{{-- Page title --}}
@section('title')
  {{ trans('admin/custom_fields/general.create_fieldset') }}
@parent
@stop

@section('header_right')
<a href="{{ URL::previous() }}" class="btn btn-primary pull-right">
  {{ trans('general.back') }}</a>
@stop


{{-- Page content --}}
@section('content')
<div class="row">
  <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12 col-sm-offset-0">

  {{ Form::open(['route' => 'fieldsets.store', 'class'=>'form-horizontal']) }}
    <!-- Horizontal Form -->
    <div class="box box-default">
      <div class="box-header with-border text-right">
        <div class="col-md-12 box-title text-right" style="padding: 0px; margin: 0px;">
        </div>
      </div>
      
      <div class="box-body">

          <!-- Name -->
        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
          <label for="name" class="col-md-3 control-label">
            {{ trans('admin/custom_fields/general.fieldset_name') }}
          </label>
          <div class="col-md-6">
            <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" required>
            {!! $errors->first('name', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
          </div>
        </div>

      </div> <!-- /.box-body-->
      <div class="box-footer text-right">
        <a class="btn btn-link text-right" href="{{ URL::previous() }}" style="padding-right: 15px">
            {{ trans('button.cancel') }}
        </a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-check icon-white" aria-hidden="true"></i> {{ trans('general.save') }}</button>
      </div>

    </div> <!-- /.box.box-default-->
    {{ Form::close() }}
  </div>
  <!-- <div class="col-md-3">
    <h2>{{ trans('admin/custom_fields/general.about_fieldsets_title') }}</h4>
    <p>{{ trans('admin/custom_fields/general.about_fieldsets_text') }}</p>
  </div> -->
</div>
@stop
