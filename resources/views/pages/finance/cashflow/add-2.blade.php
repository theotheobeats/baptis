@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row mt-3">

                <div class="col-12">
                    <h5 class="txt-title">Input {{ $information['title'] }}</h5>
                    <div class="card bg-white">

                        <form class="form theme-form" id="input-form" action="{{ url($information['route']) }}/store" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_number">Nomor Transaksi<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-transaction_number" name="transaction_number" type="text" placeholder="Nomor Transaksi" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-transaction_date">Tanggal Transaksi<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-transaction_date" name="transaction_date" type="date" value="{{ now()->format('Y-m-d') }}" placeholder="Tanggal Transaksi" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-note">Keterangan<span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="txt-input-note" name="note" placeholder="Keterangan" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <table class="table table-sm table-dashed mt-3">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Akun</th>
                                                        <th class="text-center">Sub Detail</th>
                                                        <th class="text-center">Debit</th>
                                                        <th class="text-center">Kredit</th>
                                                        <th class="text-center">Attachment (Multiple)</th>
                                                        <th class="text-center">#</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="items-container">

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="99" align="center">
                                                            <button type="button" style="width: 100%;" onclick="add_item()" class="btn btn-sm btn-primary">+ Arus Kas</button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-light" onclick="history.back()" type="button">Tutup</button>
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection


@section ('js_after')
<script>
    var can_input = true;
    var items = [];
    var local_id = 1;

    $(document).ready(function() {
        $("#select-input-employee_id").select2({
            placeholder: "Pilih Pegawai",
            width: "100%"
        });

        $("#select-input-debit_account_id").select2({
            ajax: {
                url: "{{ route('finance.coa.search') }}",
                dataType: 'json',
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih Akun"
        });

        $("#select-input-credit_account_id").select2({
            ajax: {
                url: "{{ route('finance.coa.search') }}",
                dataType: 'json',
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih Akun"
        });

        $("#txt-input-amount").on("input", function() {
            var value = $(this).val().replace(/[\s,.]/g, "");
            var formattedValue = addCommas(value);
            $(this).val(formattedValue);
        });


    });

    function addCommas(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function add_row_file_handover() {
         $("#container-file_handover").append(`
            <div class="row">
                <div class="col-9">
                    <div class="mb-4">
                        <input class="form-control" name="file_handover[]" accept="image/jpeg,image/png,application/pdf" type="file">
                    </div>
                </div>
                <div class="col-3 mt-auto">
                    <div class="mb-4">
                        <button class="btn btn-danger" id='delete-row'><i class='fa fa-trash'></i></a></button>
                    </div>
                </div>
            </div>
        `);

        $(document).on('click', '#delete-row', function() {
            $(this).closest('.row').remove();
        });
    }

    function add_item() {
        var item = {
            local_id: local_id++,
            account_id: '',
            sub_detail: '',
            debit: 0,
            credit: 0,
            file_handover: []
        };

        $("#items-container").append(`
            <tr id='item-container-${item.local_id}' class="context-menu" data-local_id='${ item.local_id }'>
                <td width='30%'>
                    <select class='form-control p-1' id='select-input-account_id-${ item.local_id }' onchange='update_item("${ item.local_id }")'>
                    </select>
                </td>
                <td><input disabled type="text" class='form-control p-1 text-center' id='txt-input-sub_detail-${ item.local_id }' onchange='update_item("${ item.local_id }")' placeholder='Sub Detail' value='${ item.sub_detail }' /></td>
                <td><input type="text" class='form-control p-1 text-center' id='txt-input-debit-${ item.local_id }' onchange='update_item("${ item.local_id }")' placeholder='Debit' value='${ item.debit }' /></td>
                <td><input type="text" class='form-control p-1 text-center' id='txt-input-credit-${ item.local_id }' onchange='update_item("${ item.local_id }")' placeholder='Kredit' value='${ item.credit }' /></td>
                <td>
                    <input type="file" class='form-control' id='file-input-file_handover-${ item.local_id }' onchange='update_item("${ item.local_id }")' accept='image/jpeg,image/png,application/pdf' multiple/>
                </td>
                <td widht='10%' align="center">
                    <div class="btn-group">
                        <button type="button" onclick="remove_item(${ item.local_id })" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `);

        $(`#select-input-account_id-${item.local_id}`).select2({
            ajax: {
                url: "{{ route('finance.coa.search') }}",
                dataType: 'json',
                delay: 300,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: "Pilih Akun"
        }).on('select2:select', function (e) {
            if (e.params.data.sub_detail != null) {
                $(`#txt-input-sub_detail-${item.local_id}`).val(e.params.data.sub_detail);
                $(`#txt-input-sub_detail-${item.local_id}`).prop('disabled', false);
                update_item(item.local_id);
            } else {
                $(`#txt-input-sub_detail-${item.local_id}`).val('');
                $(`#txt-input-sub_detail-${item.local_id}`).prop('disabled', true);
                update_item(item.local_id);
            }
        });

        $(`#txt-input-debit-${ item.local_id }`).on("input", function() {
            var value = $(this).val().replace(/[\s,.]/g, "");
            var formattedValue = addCommas(value);
            $(this).val(formattedValue);
        });

        $(`#txt-input-credit-${ item.local_id }`).on("input", function() {
            var value = $(this).val().replace(/[\s,.]/g, "");
            var formattedValue = addCommas(value);
            $(this).val(formattedValue);
        });

        items.push(item);
    }

    function update_item(local_id) {
        var item = items.find(item => item.local_id == local_id);
        item.account_id = $(`#select-input-account_id-${local_id}`).val();
        item.sub_detail = $(`#txt-input-sub_detail-${local_id}`).val();
        item.debit = $(`#txt-input-debit-${local_id}`).val().replace(/,/g, "");
        item.credit = $(`#txt-input-credit-${local_id}`).val().replace(/,/g, "");
        item.file_handover= $(`#file-input-file_handover-${local_id}`).prop('files');
    }

    function remove_item(local_id) {
        $(`#item-container-${local_id}`).remove();
        items = items.filter(item => item.local_id != local_id);
    }

    $("#input-form").submit(function(e) {
        e.preventDefault();

        if (!can_input) return;
        can_input = false;

        let form_data = new FormData($("#input-form")[0]);

        for (let i = 0; i < items.length; i++) {
            if (items[i].account_id == "" || items[i].account_id == null) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Harap memilih akun',
                    icon: 'warning'
                });
                can_input = true;
                return;
            }

            if ((items[i].debit == "" || items[i].debit == 0) && (items[i].credit == "" || items[i].credit == 0)) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Debit atau Kredit harus diisi',
                    icon: 'warning'
                });
                can_input = true;
                return;
            }

            form_data.append(`items[${i}][account_id]`, items[i].account_id);
            form_data.append(`items[${i}][sub_detail]`, items[i].sub_detail);
            form_data.append(`items[${i}][debit]`, items[i].debit);
            form_data.append(`items[${i}][credit]`, items[i].credit);

            for (let j = 0; j < items[i].file_handover.length; j++) {
                form_data.append(`items[${i}][file_handover][${j}]`, items[i].file_handover[j]);
            }
        }

        // Method dibawah disimpan di script.js
        submit_form_data("{{ url($information['route']) }}/store2", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        }, {
            success: function () { can_input = false; },
            error: function () { can_input = true; }
        });
    });
</script>
@endsection
