<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ConsumablesTransactionTransformer;
use App\Http\Transformers\SelectlistTransformer;
use App\Models\Company;
use App\Models\ConsumableTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ImageUploadRequest;

class ConsumablesTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', ConsumableTransaction::class);

        // This array is what determines which fields should be allowed to be sorted on ON the table itself, no relations
        // Relations will be handled in query scopes a little further down.
        $allowed_columns = 
            [
                'id',
                'purchase_date',
                'company',
                'state',
                'notes',
                ];


        $consumables = Company::scopeCompanyables(
            ConsumableTransaction::select('consumables_transaction.*')
                ->with('company', 'users')
        );

        if ($request->filled('search')) {
            $consumables = $consumables->TextSearch(e($request->input('search')));
        }

        if ($request->filled('company_id')) {
            $consumables->where('company_id', '=', $request->input('company_id'));
        }

        if ($request->filled('state')){
            $consumables->where('state', '=', $request->input('state'));
        }

        if ($request->filled('notes')) {
            $consumables->where('notes','=',$request->input('notes'));
        }


        // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
        // case we override with the actual count, so we should return 0 items.
        $offset = (($consumables) && ($request->get('offset') > $consumables->count())) ? $consumables->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $allowed_columns = ['id', 'purchase_date', 'company', 'state', 'notes'];
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';

        $sort_override =  $request->input('sort');
        $column_sort = in_array($sort_override, $allowed_columns) ? $sort_override : 'created_at';


        switch ($sort_override) {
            case 'company':
                $consumables = $consumables->OrderCompany($order);
                break;
            default:
                $consumables = $consumables->orderBy($column_sort, $order);
                break;
        }

        $total = $consumables->count();
        $consumables = $consumables->skip($offset)->take($limit)->get();

        return (new ConsumablesTransactionTransformer)->transformTransactionConsumables($consumables, $total);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  \App\Http\Requests\ImageUploadRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageUploadRequest $request)
    {
        $this->authorize('create', ConsumableTransaction::class);
        $consumable = new ConsumableTransaction;
        $consumable->fill($request->all());
        $consumable = $request->handleImages($consumable);

        if ($consumable->save()) {
            return response()->json(Helper::formatStandardApiResponse('success', $consumable, trans('admin/consumables/message.create.success')));
        }

        return response()->json(Helper::formatStandardApiResponse('error', null, $consumable->getErrors()));
    }

    /**
     * Display the specified resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Consumable::class);
        $consumable = Consumable::findOrFail($id);

        return (new ConsumablesTransformer)->transformConsumable($consumable);
    }

    /**
     * Update the specified resource in storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  \App\Http\Requests\ImageUploadRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImageUploadRequest $request, $id)
    {
        $this->authorize('update', ConsumableTransaction::class);
        $consumable = ConsumableTransaction::findOrFail($id);
        $consumable->fill($request->all());
        $consumable = $request->handleImages($consumable);
        
        if ($consumable->save()) {
            return response()->json(Helper::formatStandardApiResponse('success', $consumable, trans('admin/consumables/message.update.success')));
        }

        return response()->json(Helper::formatStandardApiResponse('error', null, $consumable->getErrors()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', ConsumableTransaction::class);
        $consumable = ConsumableTransaction::findOrFail($id);
        $this->authorize('delete', $consumable);
        $consumable->delete();

        return response()->json(Helper::formatStandardApiResponse('success', null, trans('admin/consumables/message.delete.success')));
    }

    /**
    * Returns a JSON response containing details on the users associated with this consumable.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see \App\Http\Controllers\Consumables\ConsumablesController::getView() method that returns the form.
    * @since [v1.0]
    * @param int $consumableId
    * @return array
     */
    public function getDataView($consumableId)
    {
        $consumable = Consumable::with(['consumableAssignments'=> function ($query) {
            $query->orderBy($query->getModel()->getTable().'.created_at', 'DESC');
        },
        'consumableAssignments.admin'=> function ($query) {
        },
        'consumableAssignments.user'=> function ($query) {
        },
        ])->find($consumableId);

        if (! Company::isCurrentUserHasAccess($consumable)) {
            return ['total' => 0, 'rows' => []];
        }
        $this->authorize('view', Consumable::class);
        $rows = [];

        foreach ($consumable->consumableAssignments as $consumable_assignment) {
            $rows[] = [
                'name' => ($consumable_assignment->user) ? $consumable_assignment->user->present()->nameUrl() : 'Deleted User',
                'created_at' => Helper::getFormattedDateObject($consumable_assignment->created_at, 'datetime'),
                'note' => ($consumable_assignment->note) ? e($consumable_assignment->note) : null,
                'admin' => ($consumable_assignment->admin) ? $consumable_assignment->admin->present()->nameUrl() : null,
            ];
        }

        $consumableCount = $consumable->users->count();
        $data = ['total' => $consumableCount, 'rows' => $rows];

        return $data;
    }

    /**
     * Checkout a consumable
     *
     * @author [A. Gutierrez] [<andres@baller.tv>]
     * @param int $id
     * @since [v4.9.5]
     * @return JsonResponse
     */
    public function checkout(Request $request, $id)
    {
        // Check if the consumable exists
        if (is_null($consumable = Consumable::find($id))) {
            return response()->json(Helper::formatStandardApiResponse('error', null, trans('admin/consumables/message.does_not_exist')));
        }

        $this->authorize('checkout', $consumable);

        if ($consumable->qty > 0) {

            // Check if the user exists
            $assigned_to = $request->input('assigned_to');
            if (is_null($user = User::find($assigned_to))) {
                // Return error message
                return response()->json(Helper::formatStandardApiResponse('error', null, 'No user found'));
            }

            // Update the consumable data
            $consumable->assigned_to = e($assigned_to);

            $consumable->users()->attach($consumable->id, [
                'consumable_id' => $consumable->id,
                'user_id' => $user->id,
                'assigned_to' => $assigned_to,
                'note' => $request->input('note'),
            ]);

            // Log checkout event
            $logaction = $consumable->logCheckout(e($request->input('note')), $user);
            $data['log_id'] = $logaction->id;
            $data['eula'] = $consumable->getEula();
            $data['first_name'] = $user->first_name;
            $data['item_name'] = $consumable->name;
            $data['checkout_date'] = $logaction->created_at;
            $data['note'] = $logaction->note;
            $data['require_acceptance'] = $consumable->requireAcceptance();

            return response()->json(Helper::formatStandardApiResponse('success', null, trans('admin/consumables/message.checkout.success')));
        }

        return response()->json(Helper::formatStandardApiResponse('error', null, 'No consumables remaining'));
    }

    /**
    * Gets a paginated collection for the select2 menus
    *
    * @see \App\Http\Transformers\SelectlistTransformer
    */
    public function selectlist(Request $request)
    {
        $consumables = Consumable::select([
            'consumables.id',
            'consumables.name',
        ]);

        if ($request->filled('search')) {
            $consumables = $consumables->where('consumables.name', 'LIKE', '%'.$request->get('search').'%');
        }

        $consumables = $consumables->orderBy('name', 'ASC')->paginate(50);

        return (new SelectlistTransformer)->transformSelectlist($consumables);
    }
}
