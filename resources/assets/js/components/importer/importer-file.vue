<template>
  <tr v-show="processDetail">
    <td colspan="5">
    <div class="col-md-12">

            <div class="row">
                <div class="dynamic-form-row">
                    <div class="col-md-5 col-xs-12">
                        <label for="import-type">Jenis Impor:</label>
                    </div>

                    <div class="col-md-7 col-xs-12">
                        <select2 :options="options.importTypes" v-model="options.importType" required>
                            <option disabled value="0"></option>
                        </select2>
                    </div>

                </div><!-- /dynamic-form-row -->
                <div class="dynamic-form-row">
                    <div class="col-md-5 col-xs-12">
                        <label for="import-update">Perbarui existing data?:</label>
                    </div>
                    <div class="col-md-7 col-xs-12">
                        <input type="checkbox" class="icheckbox_minimal" name="import-update" v-model="options.update">
                    </div>
                </div><!-- /dynamic-form-row -->

                <!-- <div class="dynamic-form-row">
                    <div class="col-md-5 col-xs-12">
                        <label for="send-welcome">Send Welcome Email for new Users?</label>
                    </div>
                    <div class="col-md-7 col-xs-12">
                        <input type="checkbox" class="icheckbox_minimal" name="send-welcome" v-model="options.send_welcome">
                    </div>
                </div> -->
                <!-- /dynamic-form-row -->

                <!-- <div class="dynamic-form-row">
                    <div class="col-md-5 col-xs-12">
                        <label for="run-backup">Backup before importing?</label>
                    </div>
                    <div class="col-md-7 col-xs-12">
                        <input type="checkbox" class="icheckbox_minimal" name="run-backup" v-model="options.run_backup">
                    </div>
                </div> -->
                <!-- /dynamic-form-row -->

                <div class="alert col-md-8 col-md-offset-2" style="text-align:left"
                     :class="alertClass"
                     v-if="statusText">
                    {{ this.statusText }}
                </div><!-- /alert -->
        </div> <!-- /div row -->

        <div class="row">
            <div class="col-md-12" style="padding-top: 30px;">
                <div class="col-md-4 text-right"><h4>Nama Kolom</h4></div>
                <div class="col-md-4"><h4>Kolom Impor</h4></div>
                <div class="col-md-4"><h4>Contoh Data</h4></div>
            </div>
        </div><!-- /div row -->

        <template v-for="(header, index) in file.header_row">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4 text-right">
                        <label :for="header" class="control-label">{{ header }}</label>
                    </div>
                    <div class="col-md-4 form-group">
                        <div required>
                            <select2 :options="columns" v-model="columnMappings[header]">
                                <option value="0">Tidak Diimpor</option>
                            </select2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p class="form-control-static">{{ activeFile.first_row[index] }}</p>
                    </div>
                </div><!-- /div col-md-8 -->
            </div><!-- /div row -->
        </template>

        <div class="row">
            <div class="col-md-6 col-md-offset-2 text-right" style="padding-top: 20px;">
                <button type="button" class="btn btn-sm btn-default" @click="processDetail = false">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary" @click="postSave">Impor</button>
                <br><br>
            </div>
        </div><!-- /div row -->
        <div class="row">
            <div class="alert col-md-8 col-md-offset-2" style="padding-top: 20px;"
                 :class="alertClass"
                 v-if="statusText">
                {{ this.statusText }}
            </div>
        </div><!-- /div row -->

    </div><!-- /div v-show -->

    </td>
  </tr>
</template>

<script>
    var baseUrl = $('meta[name="baseUrl"]').attr('content');
    export default {
        props: ['file', 'customFields'],
        data() {
            return {
                activeFile: this.file,
                processDetail: false,
                statusText: null,
                statusType: null,
                options: {
                    importType: this.file.import_type,
                    update: true,
                    send_welcome: false,
                    run_backup: false,
                    importTypes: [
                        { id: 'asset', text: 'Perangkat Keras' },
                        // { id: 'accessory', text: 'Accessories' },
                        // { id: 'consumable', text: 'Consumables' },
                        // { id: 'component', text: 'Components' },
                        { id: 'license', text: 'Perangkat Lunak' },
                        // { id: 'user', text: 'Pengguna' }
                    ],
                    statusText: null,
                },
                columnOptions: {
                    general: [
                        {id: 'category', text: 'Kategori' },
                        {id: 'company', text: 'Satuan Kerja'},
                        // {id: 'email', text: 'Email' },
                        // {id: 'koderuang', text: 'Kode Ruang' },
                        {id: 'manufacturer', text: 'Merek' },
                        {id: 'purchase_cost', text: 'Biaya Pengadaan' },
                        {id: 'purchase_date', text: 'Tanggal Pengadaan' },
                        // {id: 'requestable', text: 'Requestable' },
                        {id: 'serial', text: 'Serial Number/Service Tag' },
                        {id: 'supplier', text: 'Penyedia/Pemasok' },
                        // {id: 'username', text: 'Username' },
                        {id: 'department', text: 'Unit Eselon 2/3' },
                    ],
                    accessories:[
                        {id: 'model_number', text: 'Model Number'},
                        {id: 'notes', text: 'Notes' },
                    ],
                    assets: [
                        {id: 'item_name', text: 'Nama Perangkat' },
                        {id: 'asset_tag', text: 'NUP' },
                        {id: 'asset_model', text: 'Nama Model/Tipe' },
                        {id: 'asset_notes', text: 'Catatan' },
                        // {id: 'model_notes', text: 'Model Notes' },
                        // {id: 'checkout_class', text: 'Checkout Type' },
                        {id: 'checkout_location', text: 'Ruangan/Lokasi Alokasi' },
                        // {id: 'image', text: 'Image Filename' },
                        {id: 'model_number', text: 'Nomor Model' },
                        // {id: 'asset_notes', text: 'Asset Notes' },
                        // {id: 'model_notes', text: 'Model Notes' },
                        {id: 'full_name', text: 'Nama Pemegang' },
                        {id: 'status', text: 'Status Perangkat' },
                        {id: 'warranty_months', text: 'Garansi' },
                        // {id: 'last_audit_date', text: 'Last Audit Date' },
                        // {id: 'next_audit_date', text: 'Audit Date' },
                        {id: 'location', text: 'Ruangan/Lokasi Awal' },
                        {id: 'order_number', text: 'Nomor BAST' },
                        {id: 'bmn', text: 'Nomor BMN' },
                    ],
                    consumables: [
                        {id: 'item_no', text: "Item Number"},
                        {id: 'model_number', text: "Model Number"},
                        {id: 'min_amt', text: "Minimum Quantity"},
                        {id: 'notes', text: 'Notes' },
                        {id: 'quantity', text: 'Quantity' },
                    ],
                    licenses: [
                        {id: 'item_name', text: 'Nama Lisensi' },
                        {id: 'asset_tag', text: 'NUP Perangkat Teralokasi Lisensi'},
                        {id: 'expiration_date', text: 'Tanggal Masa Habis Pakai' },
                        {id: 'mulaiberlaku', text: 'Tanggal Mulai Berlaku Lisensi' },
                        {id: 'full_name', text: 'Nama Pemegang Lisensi' },
                        {id: 'notes', text: 'Catatan' },
                        // {id: 'license_email', text: 'Licensed To Email' },
                        {id: 'license_name', text: 'Berlisensi Untuk Nama' },
                        {id: 'notes', text: 'Catatan' },
                        {id: 'purchase_order', text: 'Nomor Kontrak Pembelian' },
                        {id: 'reassignable', text: 'Dapat Direalokasi?' },
                        {id: 'seats', text: 'Total Seats' },
                        {id: 'maintained', text: 'Dalam Perawatan?' },
                        {id: 'order_number', text: 'Nomor Sertifikat' },
                    ],
                    users: [
                        {id: 'employee_num', text: 'Employee Number' },
                        {id: 'first_name', text: 'First Name' },
                        {id: 'jobtitle', text: 'Job Title' },
                        {id: 'last_name', text: 'Last Name' },
                        {id: 'phone_number', text: 'Phone Number' },
                        {id: 'manager_first_name', text: 'Manager First Name' },
                        {id: 'notes', text: 'Notes' },
                        {id: 'manager_last_name', text: 'Manager Last Name' },
                        {id: 'notes', text: 'Notes' },
                        {id: 'activated', text: 'Activated' },
                        {id: 'address', text: 'Address' },
                        {id: 'city', text: 'City' },
                        {id: 'state', text: 'State' },
                        {id: 'zip', text: 'ZIP' },
                        {id: 'country', text: 'Country' },
                        {id: 'zip', text: 'ZIP' },

                    ],
                    customFields: this.customFields,
                },
                columnMappings: this.file.field_map || {},
                activeColumn: null,
            }
        },
        created() {
            window.eventHub.$on('showDetails', this.toggleExtendedDisplay)
            this.populateSelect2ActiveItems();
        },
        computed: {
            columns() {
                // function to sort objects by their display text.
                function sorter(a,b) {
                    if (a.text < b.text)
                        return -1;
                    if (a.text > b.text)
                        return 1;
                    return 0;
                }
                switch(this.options.importType) {
                    case 'asset':
                        return this.columnOptions.general
                            .concat(this.columnOptions.assets)
                            .concat(this.columnOptions.customFields)
                            .sort(sorter);
                    case 'accessory':
                        return this.columnOptions.general
                            .concat(this.columnOptions.accessories)
                            .sort(sorter);
                    case 'consumable':
                    	console.log('Returned consumable');
                        return this.columnOptions.general
                            .concat(this.columnOptions.consumables)
                            .sort(sorter);
                    case 'license':
                        return this.columnOptions.general.concat(this.columnOptions.licenses).sort(sorter);
                    case 'user':
                        return this.columnOptions.general.concat(this.columnOptions.users).sort(sorter);
                }
                return this.columnOptions.general;
            },
            alertClass() {
                if(this.statusType=='success') {
                    return 'alert-success';
                }
                if(this.statusType=='error') {
                    return 'alert-danger';
                }
                return 'alert-info';
            },
        },
        watch: {
            columns() {
                this.populateSelect2ActiveItems();
            }
        },
        methods: {
            postSave() {
                console.log('saving');
                console.log(this.options.importType);
                if(!this.options.importType) {
                    this.statusType='error';
                    this.statusText= "Jenis impor perlu dipilih ";
                    return;
                }
                this.statusType='pending';
                this.statusText = "Mengimpor...";
                this.$http.post(baseUrl + 'api/v1/imports/process/' + this.file.id, {
                    'import-update': this.options.update,
                    'send-welcome': this.options.send_welcome,
                    'import-type': this.options.importType,
                    'run-backup': this.options.run_backup,
                    'column-mappings': this.columnMappings
                }).then( ({body}) => {
                    // Success
                    this.statusType="success";
                    this.statusText = "Proses impor sukses";
                    window.location.href = body.messages.redirect_url;
                }, ({body}) => {
                    // Failure
                    if(body.status == 'import-errors') {
                        window.eventHub.$emit('importErrors', body.messages);
                        this.statusType='error';
                        this.statusText = "Proses impor gagal";
                    } else {
                        this.$emit('alert', {
                            message: body.messages,
                            type: "danger",
                            visible: true,
                        })
                    }
                    this.displayImportModal=false;
                });
            },
            populateSelect2ActiveItems() {
                if(this.file.field_map == null) {
                    // Begin by populating the active selection in dropdowns with blank values.
                    for (var i=0; i < this.file.header_row.length; i++) {
                        this.$set(this.columnMappings, this.file.header_row[i], null);
                    }
                    // Then, for any values that have a likely match, we make that active.
                    for(var j=0; j < this.columns.length; j++) {
                        let column = this.columns[j];
                        let lower = this.file.header_row.map((value) => value.toLowerCase());
                        let index = lower.indexOf(column.text.toLowerCase())
                        if(index != -1) {
                            this.$set(this.columnMappings, this.file.header_row[index], column.id)
                        }
                    }
                }
            },
            toggleExtendedDisplay(fileId) {
                if(fileId == this.file.id) {
                    this.processDetail = !this.processDetail
                }
            },
            updateModel(header, value) {
                this.columnMappings[header] = value;
            }
        },
        components: {
            select2: require('../select2.vue').default
        }
    }
</script>
