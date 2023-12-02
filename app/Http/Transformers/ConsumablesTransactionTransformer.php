<?php

namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\ConsumableTransaction;
use Gate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class ConsumablesTransactionTransformer
{
    public function transformTransactionConsumables(Collection $consumables, $total)
    {
        $array = [];
        foreach ($consumables as $consumable) {
            $array[] = self::transformTransactionConsumable($consumable);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformTransactionConsumable(ConsumableTransaction $consumable)
    {
        $array = [
            'id'            => (int) $consumable->id,
            'state'         => $consumable->state ? 'Pemasukkan' : 'Permintaan',
            'employee_num'  => ($consumable->employee_num) ? (int) $consumable->employee_num : null,
            'company_id'   => ($consumable->company_id) ? ['id' => (int) $consumable->company->id, 'name' => e($consumable->company->name)] : null,
            'purchase_date'  => Helper::getFormattedDateObject($consumable->purchase_date, 'date'),
            'notes'         => ($consumable->notes) ? e($consumable->notes) : null,
        ];

        //ada kode checkout, tapi belum dimasukkin

        $permissions_array['available_actions'] = [
            // 'checkout' => Gate::allows('checkout', ConsumableTransaction::class),
            // 'checkin' => Gate::allows('checkin', ConsumableTransaction::class),
            'update' => Gate::allows('update', ConsumableTransaction::class),
            'delete' => Gate::allows('delete', ConsumableTransaction::class),
        ];
        $array += $permissions_array;

        return $array;
    }

    public function transformCheckedoutConsumables(Collection $consumables_users, $total)
    {
        $array = [];
        foreach ($consumables_users as $user) {
            $array[] = (new UsersTransformer)->transformUser($user);
        }

        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }
}
