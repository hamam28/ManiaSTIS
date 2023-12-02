<div class="form-group {{ $errors->has('transaction-table') ? ' has-error' : '' }}">
    <div class="col-md-7 col-sm-12">
        <table>
            <tbody>
                <td><div class="col-md-7 col-sm-12{{  (Helper::checkIfRequired($item, 'name')) ? ' required' : '' }}">
                    <input class="form-control" type="text" name="name" aria-label="name" id="name" value="{{ old('name', $item->name) }}"{!!  (Helper::checkIfRequired($item, 'name')) ? ' data-validation="required" required' : '' !!} />
                    {!! $errors->first('name', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                </div></td>
                <td><div class="col-md-7{{  ((isset($required)) && ($required=='true')) ? ' required' : '' }}">
                    <select class="js-data-ajax" data-endpoint="categories/{{ (isset($category_type)) ? $category_type : 'assets' }}" data-placeholder="{{ trans('general.select_category') }}" name="{{ $fieldname }}" style="width: 100%" id="category_select_id" aria-label="{{ $fieldname }}" {!!  ((isset($item)) && (Helper::checkIfRequired($item, $fieldname))) ? ' data-validation="required" required' : '' !!}>
                        @if ($category_id = old($fieldname, (isset($item)) ? $item->{$fieldname} : ''))
                            <option value="{{ $category_id }}" selected="selected" role="option" aria-selected="true"  role="option">
                                {{ (\App\Models\Category::find($category_id)) ? \App\Models\Category::find($category_id)->name : '' }}
                            </option>
                        @else
                            <option value=""  role="option">{{ trans('general.select_category') }}</option>
                        @endif

                    </select>
                    {!! $errors->first($fieldname, '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>') !!}
                </div></td>
            </tbody>
        </table>
    </div>
</div>