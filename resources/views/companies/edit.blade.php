@extends('layouts/edit-form', [
    'createText' => trans('admin/companies/table.create') ,
    'updateText' => trans('admin/companies/table.update'),
    'helpPosition'  => 'right',
    'formAction' => (isset($item->id)) ? route('companies.update', ['company' => $item->id]) : route('companies.store'),
])

{{-- Page content --}}
@section('inputFields')
<!-- Kode Wilayah -->
<div class="form-group {{ $errors->has('kode_wil') ? ' has-error' : '' }}">
    <label for="kode_wil" class="col-md-3 control-label">Kode Wilayah</label>
    <div class="col-md-7 col-sm-12{{  (\App\Helpers\Helper::checkIfRequired($item, 'kode_wil')) ? ' required' : '' }}">
        <input class="form-control" type="text" name="kode_wil" aria-label="kode_wil" id="kode_wil" value="{{ old('kode_wil', $item->kode_wil) }}"{!!  (\App\Helpers\Helper::checkIfRequired($item, 'kode_wil')) ? ' data-validation="required" required' : '' !!} />
        {!! $errors->first('kode_wil', '<span class="alert-msg" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i> :message</span>') !!}
    </div>
</div>

<!-- Nama Satker -->
@include ('partials.forms.edit.name', ['translated_name' => trans('admin/companies/table.name')])

<!-- Image -->
@if ($item->image)
    <div class="form-group {{ $errors->has('image_delete') ? 'has-error' : '' }}">
        <label class="col-md-3 control-label" for="image_delete">{{ trans('general.image_delete') }}</label>
        <div class="col-md-5">
            {{ Form::checkbox('image_delete') }}
            <img src="{{ Storage::disk('public')->url(app('companies_upload_path').e($item->image)) }}" class="img-responsive" />
            {!! $errors->first('image_delete', '<span class="alert-msg">:message</span>') !!}
        </div>
    </div>
@endif

@include ('partials.forms.edit.image-upload')
@stop
