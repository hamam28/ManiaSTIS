<?php

namespace App\Presenters;

use App\Helpers\Helper;
use App\Models\Setting;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Auth;

/**
 * Class UserPresenter
 */
class UserPresenter extends Presenter
{
    /**
     * Json Column Layout for bootstrap table
     * @return string
     */
    public static function dataTableLayout()
    {
        $layout = [
            [
                'field' => 'checkbox',
                'checkbox' => true,
            ],
            [
                'field' => 'id',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => 'No. Urut',
                'visible' => false,
            ],
            [
                'field' => 'avatar',
                'searchable' => false,
                'sortable' => false,
                'switchable' => true,
                'title' => 'Foto Pegawai',
                'visible' => false,
                'formatter' => 'imageFormatter',
            ],
            [
                'field' => 'name',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('admin/users/table.name'),
                'visible' => true,
                'formatter' => 'usersLinkFormatter',
            ],
            [
                'field' => 'employee_num',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.employee_number'),
                'visible' => false,
                'formatter' => 'usersLinkFormatter',
            ],
            [
                'field' => 'nip_baru',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => 'NIP Baru',
                'visible' => false,
                'formatter' => 'usersLinkFormatter',
            ],

            // [
            //     'field' => 'first_name',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'title' => trans('general.first_name'),
            //     'visible' => false,
            //     'formatter' => 'usersLinkFormatter',
            // ],

            // [
            //     'field' => 'last_name',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'title' => trans('general.last_name'),
            //     'visible' => false,
            //     'formatter' => 'usersLinkFormatter',
            // ],
            [
                'field' => 'company',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('admin/companies/table.title'),
                'visible' => false,
                'formatter' => 'companiesLinkObjFormatter',
            ],
            [
                'field' => 'department',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.department'),
                'visible' => true,
                'formatter' => 'departmentsLinkObjFormatter',
            ],
            [
                'field' => 'jobtitle',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('admin/users/table.title'),
                'visible' => true,
                'formatter' => 'usersLinkFormatter',
            ],
            // [
            //     'field' => 'remote',
            //     'searchable' => false,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('admin/users/general.remote'),
            //     'visible' => false,
            //     'formatter' => 'trueFalseFormatter',
            // ],
            [
                'field' => 'email',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('admin/users/table.email'),
                'visible' => true,
                'formatter' => 'emailFormatter',
            ],
            // [
            //     'field' => 'phone',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('admin/users/table.phone'),
            //     'visible' => true,
            //     'formatter'    => 'phoneFormatter',
            // ],
            // [
            //     'field' => 'address',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('general.address'),
            //     'visible' => false,
            // ],
            // [
            //     'field' => 'city',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('general.city'),
            //     'visible' => false,
            // ],
            // [
            //     'field' => 'state',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('general.state'),
            //     'visible' => false,
            // ],
            // [
            //     'field' => 'country',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('general.country'),
            //     'visible' => false,
            // ],
            // [
            //     'field' => 'zip',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('general.zip'),
            //     'visible' => false,
            // ],
            [
                'field' => 'username',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('admin/users/table.username'),
                'visible' => true,
                'formatter' => 'usersLinkFormatter',
            ],
            // [
            //     'field' => 'location',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('admin/users/table.location'),
            //     'visible' => true,
            //     'formatter' => 'locationsLinkObjFormatter',
            // ],
            // [
            //     'field' => 'manager',
            //     'searchable' => true,
            //     'sortable' => true,
            //     'title' => trans('admin/users/table.manager'),
            //     'visible' => true,
            //     'formatter' => 'usersLinkObjFormatter',
            // ],
            [
                'field' => 'assets_count',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'escape' => true,
                'title' => '<span class="hidden-xs" data-toggle="tooltip" title="Jumlah Perangkat Keras"><i class="fas fa-barcode" aria-hidden="true"></i></span><span class="hidden-md hidden-lg">'.trans('general.assets').'</span>',
                'visible' => true,
            ],
            [
                'field' => 'licenses_count',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => '<span class="hidden-xs" data-toggle="tooltip" title="Jumlah Perangkat Lunak"><i class="far fa-save"></i></span><span class="hidden-md hidden-lg">'.trans('general.licenses').'</span>',
                'visible' => true,
            ],
            [
                'field' => 'consumables_count',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => '<span class="hidden-xs" data-toggle="tooltip" title="Jumlah Barang Stok"><i class="fas fa-dolly-flatbed"></i></span><span class="hidden-md hidden-lg">'.trans('general.consumables').'</span>',
                'visible' => true,
            ],
            [
                'field' => 'accessories_count',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => '<span class="hidden-xs" data-toggle="tooltip" title="Jumlah Aksesori"><i class="far fa-keyboard"></i></span><span class="hidden-md hidden-lg">'.trans('general.accessories').'</span>',
                'visible' => true,
            ],
            [
                'field' => 'notes',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.notes'),
                'visible' => true,
            ],
            [
                'field' => 'groups',
                'searchable' => false,
                'sortable' => false,
                'switchable' => true,
                'title' => trans('general.groups'),
                'visible' => true,
                'formatter' => 'groupsFormatter',
            ],
            // [
            //     'field' => 'ldap_import',
            //     'searchable' => false,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('admin/settings/general.ldap_enabled'),
            //     'visible' => false,
            //     'formatter' => 'trueFalseFormatter',
            // ],
            // [
            //     'field' => 'two_factor_enrolled',
            //     'searchable' => false,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('admin/users/general.two_factor_enrolled'),
            //     'visible' => false,
            //     'formatter' => 'trueFalseFormatter',
            // ],
            // [
            //     'field' => 'two_factor_activated',
            //     'searchable' => false,
            //     'sortable' => false,
            //     'switchable' => true,
            //     'title' => trans('admin/users/general.two_factor_active'),
            //     'visible' => false,
            //     'formatter' => 'trueFalseFormatter',
            // ],
            // [
            //     'field' => 'activated',
            //     'searchable' => false,
            //     'sortable' => true,
            //     'switchable' => true,
            //     'title' => trans('general.login_enabled'),
            //     'visible' => true,
            //     'formatter' => 'trueFalseFormatter',
            // ],
            [
                'field' => 'created_by',
                'searchable' => false,
                'sortable' => true,
                'title' => trans('general.created_by'),
                'visible' => false,
                'formatter' => 'usersLinkObjFormatter',
            ],
            [
                'field' => 'created_at',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.created_at'),
                'visible' => false,
                'formatter' => 'dateDisplayFormatter',
            ],
            [
                'field' => 'last_login',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.last_login'),
                'visible' => false,
                'formatter' => 'dateDisplayFormatter',
            ],
        ];

        $layoutadmin = [
            [
                'field' => 'checkbox',
                'checkbox' => true,
            ],
            [
                'field' => 'id',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => 'No. Urut',
                'visible' => false,
            ],
            [
                'field' => 'avatar',
                'searchable' => false,
                'sortable' => false,
                'switchable' => true,
                'title' => 'Foto Pegawai',
                'visible' => false,
                'formatter' => 'imageFormatter',
            ],
            [
                'field' => 'name',
                'searchable' => true,
                'sortable' => true,
                'title' => trans('admin/users/table.name'),
                'visible' => true,
                'formatter' => 'usersLinkFormatter',
            ],
            [
                'field' => 'employee_num',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.employee_number'),
                'visible' => false,
                'formatter' => 'usersLinkFormatter',
            ],
            [
                'field' => 'nip_baru',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => 'NIP Baru',
                'visible' => false,
                'formatter' => 'usersLinkFormatter',
            ],
            [
                'field' => 'company',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('admin/companies/table.title'),
                'visible' => false,
                'formatter' => 'companiesLinkObjFormatter',
            ],
            [
                'field' => 'department',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.department'),
                'visible' => true,
                'formatter' => 'departmentsLinkObjFormatter',
            ],
            [
                'field' => 'jobtitle',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('admin/users/table.title'),
                'visible' => true,
                'formatter' => 'usersLinkFormatter',
            ],
            [
                'field' => 'email',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('admin/users/table.email'),
                'visible' => true,
                'formatter' => 'emailFormatter',
            ],
            [
                'field' => 'username',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('admin/users/table.username'),
                'visible' => true,
                'formatter' => 'usersLinkFormatter',
            ],
            [
                'field' => 'assets_count',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'escape' => true,
                'title' => '<span class="hidden-xs" data-toggle="tooltip" title="Jumlah Perangkat Keras"><i class="fas fa-barcode" aria-hidden="true"></i></span><span class="hidden-md hidden-lg">'.trans('general.assets').'</span>',
                'visible' => true,
            ],
            [
                'field' => 'licenses_count',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => '<span class="hidden-xs" data-toggle="tooltip" title="Jumlah Perangkat Lunak"><i class="far fa-save"></i></span><span class="hidden-md hidden-lg">'.trans('general.licenses').'</span>',
                'visible' => true,
            ],
            [
                'field' => 'consumables_count',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => '<span class="hidden-xs" data-toggle="tooltip" title="Jumlah Barang Stok"><i class="fas fa-dolly-flatbed"></i></span><span class="hidden-md hidden-lg">'.trans('general.consumables').'</span>',
                'visible' => true,
            ],
            [
                'field' => 'accessories_count',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => '<span class="hidden-xs" data-toggle="tooltip" title="Jumlah Aksesori"><i class="far fa-keyboard"></i></span><span class="hidden-md hidden-lg">'.trans('general.accessories').'</span>',
                'visible' => true,
            ],
            [
                'field' => 'notes',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.notes'),
                'visible' => true,
            ],
            [
                'field' => 'groups',
                'searchable' => false,
                'sortable' => false,
                'switchable' => true,
                'title' => trans('general.groups'),
                'visible' => true,
                'formatter' => 'groupsFormatter',
            ],
            [
                'field' => 'created_by',
                'searchable' => false,
                'sortable' => true,
                'title' => trans('general.created_by'),
                'visible' => false,
                'formatter' => 'usersLinkObjFormatter',
            ],
            [
                'field' => 'created_at',
                'searchable' => true,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.created_at'),
                'visible' => false,
                'formatter' => 'dateDisplayFormatter',
            ],
            [
                'field' => 'last_login',
                'searchable' => false,
                'sortable' => true,
                'switchable' => true,
                'title' => trans('general.last_login'),
                'visible' => false,
                'formatter' => 'dateDisplayFormatter',
            ],
            [
                'field' => 'actions',
                'searchable' => false,
                'sortable' => false,
                'switchable' => false,
                'title' => trans('table.actions'),
                'visible' => true,
                'formatter' => 'usersActionsFormatter',
            ],
        ];

        if (!Auth::user()->isSuperUser()) {
            return json_encode($layout);
        } else {
            return json_encode($layoutadmin);
        }
    }

    public function emailLink()
    {
        if ($this->email) {
            return '<a href="mailto:'.$this->email.'">'.$this->email.'</a>'
                .'<a href="mailto:'.$this->email.'" class="hidden-xs hidden-sm"><i class="far fa-envelope"></i></a>';
        }

        return '';
    }

    /**
     * Returns the user full name, it simply concatenates
     * the user first and last name.
     *
     * @return string
     */
    public function fullName()
    {
        return html_entity_decode($this->first_name.' '.$this->last_name, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    /**
     * Standard accessor.
     * @TODO Remove presenter::fullName() entirely?
     * @return string
     */
    public function name()
    {
        return $this->fullName();
    }

    /**
     * Returns the user Gravatar image url.
     *
     * @return string
     */
    public function gravatar()
    {
        if ($this->avatar) {
            /**
             * If the user using SSO login, don't add url() base before
             *
             * By Rihan Y. (yosral@bps.go.id) at 11/10/22
             */
            if ($this->created_by == '0' || ($this->isSuperUser() && strlen($this->id) == 9)){
                return $this->avatar;
            }
            return Storage::disk('public')->url('avatars/'.e($this->avatar));
        }

        if (Setting::getSettings()->load_remote == '1') {
            if ($this->model->gravatar != '') {
                $gravatar = md5(strtolower(trim($this->model->gravatar)));

                return '//gravatar.com/avatar/'.$gravatar;
            } elseif ($this->email != '') {
                $gravatar = md5(strtolower(trim($this->email)));

                return '//gravatar.com/avatar/'.$gravatar;
            }
        }

        // Set a fun, gender-neutral default icon
        return url('/').'/img/default-sm.png';
    }

    /**
     * Formatted url for use in tables.
     * @return string
     */
    public function nameUrl()
    {
        return (string) link_to_route('users.show', $this->fullName(), $this->id);
    }

    /**
     * Url to view this item.
     * @return string
     */
    public function viewUrl()
    {
        return route('users.show', $this->id);
    }

    public function glyph()
    {
        return '<i class="fas fa-user" aria-hidden="true"></i>';
    }
}
