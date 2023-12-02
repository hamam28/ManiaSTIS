<?php

namespace App\Http\Controllers\Consumables;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use App\Models\Company;
use App\Models\ConsumableTransaction;
use App\Models\ConsumableDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * This controller handles all actions related to Consumables for
 * the Snipe-IT Asset Management application.
 *
 * @version    v1.0
 */
class ConsumablesTransactionController extends Controller
{
    /**
     * Return a view to display component information.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @see ConsumablesController::getDatatable() method that generates the JSON response
     * @since [v1.0]
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', ConsumableTransaction::class);

        return view('consumablestransaction/index');
    }

    /**
     * Return a view to display the form view to create a new consumable
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @see ConsumablesController::postCreate() method that stores the form data
     * @since [v1.0]
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', ConsumableTransaction::class);

        return view('consumablestransaction/edit')->with('category_type', 'consumable')
            ->with('item', new ConsumableTransaction);
    }

    /**
     * Validate and store new consumable data.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @see ConsumablesController::getCreate() method that returns the form view
     * @since [v1.0]
     * @param ImageUploadRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ImageUploadRequest $request)
    {
        $this->authorize('create', ConsumableTransaction::class);
        $consumable = new ConsumableTransaction();
        $consumable->company_id             = Company::getIdForCurrentUser($request->input('company_id'));
        $consumable->purchase_date          = $request->input('purchase_date');
        $consumable->notes                  = $request->input('notes');
        $consumable->state                  = $request->input('state');
        $consumable->employee_num           = $request->input('nip');

        if ($consumable->employee_num == null) {
            $consumable->employee_num = 0;
        }

        $details = new ConsumableDetails();
        $details->name                      = $request->input('name');
        $details->category_id               = $request->input('category_id');
        $details->purchase_cost             = $request->input('purchase_cost');
        $details->qty                       = $request->input('qty');

        $consumable = $request->handleImages($consumable);

        if ($consumable->save()) {
            return redirect()->route('consumablestransaction.index')->with('success', trans('admin/consumables/message.create.success'));
        }

        return redirect()->back()->withErrors($consumable->getErrors());
    }

    /**
     * Returns a form view to edit a consumable.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param  int $consumableId
     * @see ConsumablesController::postEdit() method that stores the form data.
     * @since [v1.0]
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($consumableId = null)
    {
        if ($item = ConsumableTransaction::find($consumableId)) {
            $this->authorize($item);

            return view('consumablestransaction/edit', compact('item'))->with('consumable');
        }

        return redirect()->route('consumablestransaction.index')->with('error', trans('admin/consumables/message.does_not_exist'));
    }

    /**
     * Returns a form view to edit a consumable.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param ImageUploadRequest $request
     * @param  int $consumableId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @see ConsumablesController::getEdit() method that stores the form data.
     * @since [v1.0]
     */
    public function update(ImageUploadRequest $request, $consumableId = null)
    {
        if (is_null($consumable = ConsumableTransaction::find($consumableId))) {
            return redirect()->route('consumablestransaction.index')->with('error', trans('admin/consumables/message.does_not_exist'));
        }

        $this->authorize($consumable);

        $consumable->company_id             = Company::getIdForCurrentUser($request->input('company_id'));
        $consumable->purchase_date          = $request->input('purchase_date');
        $consumable->notes                  = $request->input('notes');
        $consumable->state                  = $request->input('state');
        $consumable->employee_num           = $request->input('nip');

        if ($consumable->employee_num == null) {
            $consumable->employee_num = 0;
        }

        $consumable = $request->handleImages($consumable);

        if ($consumable->save()) {
            return redirect()->route('consumablestransaction.index')->with('success', trans('admin/consumables/message.update.success'));
        }

        return redirect()->back()->withInput()->withErrors($consumable->getErrors());
    }

    /**
     * Delete a consumable.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param  int $consumableId
     * @since [v1.0]
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($consumableId)
    {
        if (is_null($consumable = ConsumableTransaction::find($consumableId))) {
            return redirect()->route('consumablestransaction.index')->with('error', trans('admin/consumables/message.not_found'));
        }
        $this->authorize($consumable);
        $consumable->delete();
        // Redirect to the locations management page
        return redirect()->route('consumablestransaction.index')->with('success', trans('admin/consumables/message.delete.success'));
    }

    /**
     * Return a view to display component information.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @see ConsumablesController::getDataView() method that generates the JSON response
     * @since [v1.0]
     * @param int $consumableId
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($consumableId = null)
    {
        $consumable = ConsumableTransaction::find($consumableId);
        $this->authorize($consumabletransaction);
        if (isset($consumabletransaction->id)) {
            return view('consumablestransaction/view', compact('consumable'));
        }

        return redirect()->route('consumablestransaction.index')
            ->with('error', trans('admin/consumables/message.does_not_exist'));
    }
}
