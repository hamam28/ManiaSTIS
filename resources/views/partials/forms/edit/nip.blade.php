<!-- nip -->
<div class="form-group {{ $errors->has('nip') ? ' has-error' : '' }}">
    <label for="nip" class="col-md-3 control-label">{{ $translated_name }}</label>
    <div class="col-md-7 col-sm-12{{  (Helper::checkIfRequired($item, 'nip')) ? ' required' : '' }}">
        @if (old('state', $item->state) == 1)
            <input class="form-control" type="text" name="nip" aria-label="nip" id="nip" placeholder="Masukkan NIP Penanggung Jawab" value=""{!!  (Helper::checkIfRequired($item, 'nip')) ? ' data-validation="required" required' : '' !!} />
        @else
            <input class="form-control" type="text" name="nip" aria-label="nip" id="nip" placeholder="Masukkan NIP Penanggung Jawab" value="{{ old('nip', $item->employee_num) }}"{!!  (Helper::checkIfRequired($item, 'nip')) ? ' data-validation="required" required' : '' !!} />
        @endif
        {!! $errors->first('nip', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
    </div>
</div>
