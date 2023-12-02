<?php

namespace App\Models;

use App\Models\Traits\Acceptable;
use App\Models\Traits\Searchable;
use App\Presenters\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Watson\Validating\ValidatingTrait;

class ConsumableDetails extends SnipeModel
{
    use HasFactory;

    protected $presenter = \App\Presenters\ConsumableDetailsPresenter::class;
    use CompanyableTrait;
    use Loggable, Presentable;
    use SoftDeletes;
    use Acceptable;

    protected $table = 'consumables_details';
    protected $casts = [
        'name'          => 'string',
        'category_id'   => 'integer',
        'purchase_cost' => 'numeric',
        'qty'           => 'integer',
     ];

    /**
     * Category validation rules
     */
    public $rules = [
        'name'        => 'required|min:3|max:255',
        'qty'         => 'required|integer|min:0',
        'category_id' => 'required|integer',
        'purchase_cost'   => 'numeric|nullable|gte:0',
    ];

    /**
     * Whether the model should inject it's identifier to the unique
     * validation rules before attempting validation. If this property
     * is not set in the model it will default to true.
     *
     * @var bool
     */
    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'qty',
        'category_id',
        'purchase_cost',
    ];

    use Searchable;

    /**
     * The attributes that should be included when searching the model.
     *
     * @var array
     */
    protected $searchableAttributes = ['company_id', 'purchase_date', 'notes'];

    /**
     * The relations and their attributes that should be included when searching the model.
     *
     * @var array
     */
    protected $searchableRelations = [
        'company'      => ['name'],
    ];

    /**
     * Establishes the consumable -> admin user relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Establishes the component -> assignments relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function consumableAssignments()
    {
        return $this->hasMany(\App\Models\ConsumableAssignment::class);
    }

    /**
     * Establishes the component -> company relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id');
    }

    /**
     * Establishes the component -> users relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'consumables_users', 'consumable_id', 'assigned_to')->withPivot('user_id')->withTrashed()->withTimestamps();
    }

    /**
     * Determine whether to send a checkin/checkout email based on
     * asset model category
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return bool
     */
    public function checkin_email()
    {
        return $this->category->checkin_email;
    }

    /**
     * Determine whether this asset requires acceptance by the assigned user
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return bool
     */
    public function requireAcceptance()
    {
        return $this->category->require_acceptance;
    }

    /**
     * Checks for a category-specific EULA, and if that doesn't exist,
     * checks for a settings level EULA
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return string | false
     */
    public function getEula()
    {
        $Parsedown = new \Parsedown();

        if ($this->category->eula_text) {
            return $Parsedown->text(e($this->category->eula_text));
        } elseif ((Setting::getSettings()->default_eula_text) && ($this->category->use_default_eula == '1')) {
            return $Parsedown->text(e(Setting::getSettings()->default_eula_text));
        } else {
            return null;
        }
    }

    /**
     * Check how many items within a consumable are checked out
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v5.0]
     * @return int
     */
    public function numCheckedOut()
    {
        $checkedout = 0;
        $checkedout = $this->users->count();

        return $checkedout;
    }

    /**
     * Checks the number of available consumables
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return int
     */
    public function numRemaining()
    {
        $checkedout = $this->users->count();
        $total = $this->qty;
        $remaining = $total - $checkedout;

        return $remaining;
    }

    /**
     * Query builder scope to order on company
     *
     * @param  \Illuminate\Database\Query\Builder  $query  Query builder instance
     * @param  string                              $order       Order
     *
     * @return \Illuminate\Database\Query\Builder          Modified query builder
     */
    public function scopeOrderCompany($query, $order)
    {
        return $query->leftJoin('companies', 'consumables_details.company_id', '=', 'companies.id')->orderBy('companies.name', $order);
    }
}
