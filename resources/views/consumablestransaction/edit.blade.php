@extends('layouts/edit-form', [
    'createText' => 'Buat Transaksi Barang',
    'updateText' => 'Memperbarui Transaksi Barang',
    'helpPosition' => 'right',
    'helpText' => trans('help.consumables'),
    'formAction' => (isset($item->id)) ? route('consumablestransaction.update', ['consumablestransaction' => $item->id]) : route('consumablestransaction.store'),
])
{{-- Page content --}}
@section('inputFields')
    <!-- satuan unit kerja -->
    @include ('partials.forms.edit.company-select', ['translated_name' => trans('general.company'), 'fieldname' => 'company_id']) 
    <!-- status transaksi -->
    <div class="form-group">
            <label for="state" class="col-md-3 control-label">{{ 'Status Transaksi' }}</label>
            <div class="col-md-7 col-sm-12">
                <select class="form-control" data-placeholder="Pilih Status Transaksi" name="state" style="width: 100%" id="state">
                    <option value="0" {{ old('state', $item->state) == '0' ? 'selected' : '' }}>Permintaan</option>
                    <option value="1" {{ old('state', $item->state) == '1' ? 'selected' : '' }}>Pemasukkan</option>
                </select>
            </div>
    </div>
    <!-- nip pj -->
    <div id="nipSection">
        @include ('partials.forms.edit.nip', ['translated_name' => 'NIP Penanggung Jawab'])
    </div>
    <!-- tanggal pengadaan -->
    @include ('partials.forms.edit.purchase_date')
    <!-- catatan -->
    @include ('partials.forms.edit.notes')
    <!-- tabel -->
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <table>
                            <thead>
                            <tr>
                                <th style="width: 25%;">
                                    Nama Barang
                                </th>
                                <th style="width: 25%;">
                                    Kategori
                                </th>
                                <th style="width: 25%;">
                                    Biaya Pengadaan
                                </th>
                                <th style="width: 10%;">
                                    Jumlah
                                </th>
                                <th style="width: 15%;">
                                    Tindakan
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                        <div class="col-md-7 col-sm-12">
                                            <input class="form-control" style="width: 180%" type="text" name="name" aria-label="name" id="name" value="{{ old('name', $item->name) }}"{!!  (Helper::checkIfRequired($item, 'name')) ? ' data-validation="required" required' : '' !!} />
                                            {!! $errors->first('name', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div id="category_id" class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                                    <div class="col-md-7">
                                        <select class="js-data-ajax" style="width: 180%" data-endpoint="categories/consumable" data-placeholder="{{ trans('general.select_category') }}" name="category_id" style="width: 100%" id="category_select_id" aria-label="category_id" {!!  ((isset($item)) && (Helper::checkIfRequired($item, 'category_id'))) ? ' data-validation="required" required' : '' !!}>
                                            @if ($category_id = old('category_id', (isset($item)) ? $item->category_id : ''))
                                                <option value="{{ $category_id }}" selected="selected" role="option" aria-selected="true"  role="option">
                                                    {{ (\App\Models\Category::find($category_id)) ? \App\Models\Category::find($category_id)->name : '' }}
                                                </option>
                                            @else
                                                <option value=""  role="option">{{ trans('general.select_category') }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    {!! $errors->first('category_id', '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>') !!}
                                    </div>
                                </td>
                                <td>
                                <div class="form-group {{ $errors->has('purchase_cost') ? ' has-error' : '' }}">
                                    <div class="col-md-9">
                                        <div class="input-group col-md-7 col-sm-12" style="padding-left: 0px; width: 140%">
                                            <input class="form-control" type="text" name="purchase_cost" aria-label="purchase_cost" id="purchase_cost" value="{{ old('purchase_cost', Helper::formatCurrencyOutput($item->purchase_cost)) }}" />
                                            <span class="input-group-addon">
                                                {{ $snipeSettings->default_currency }}
                                            </span>
                                        </div>
                                        <div class="col-md-9" style="padding-left: 0px;">
                                            {!! $errors->first('purchase_cost', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                </td>
                                <td>
                                <div class="form-group {{ $errors->has('qty') ? ' has-error' : '' }}">
                                    <div class="col-md-7">
                                        <div class="col-md-2" style="padding-left:0px; width: 180%;">
                                            <input class="form-control" type="text" name="qty" aria-label="qty" id="qty" value="{{ old('qty', $item->qty) }}" {!!  (Helper::checkIfRequired($item, 'qty')) ? ' data-validation="required" required' : '' !!}/>
                                        </div>
                                        {!! $errors->first('qty', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                                    </div>
                                </div>
                                </td>
                                <td>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('moar_scripts')
    <script>
        $(document).ready(function () {

            if($("#state").val() == "1") {
                $("#nipSection").hide(); 
                $("#nipSection input").val("");
            }

            // Tambahkan event listener untuk memeriksa perubahan pada elemen select
            $("#state").on("change", function () {
                // Jika nilai select adalah "permintaan," tampilkan '#nipSection', jika tidak, sembunyikan
                if ($("#state").val() == "1") {
                    $("#nipSection").hide();
                    $("#nipSection input").val("");
                } else {
                    $("#nipSection").show();
                }
            });
        });

        $("#state").trigger("change");
    </script>
@stop