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
                            <input type="hidden" name="invoice_id" id="txt-input-invoice_id">
                            <div class="card-body">
                                <div class="col">
                                    <div class="mb-4">
                                        <label class="form-label" for="select-input-student_id">Siswa</label>
                                        <select name="student_id" id="select-input-student_id" class="form-control select2" onchange="get_student_invoice()">

                                        </select>
                                    </div>
                                </div>

                                <!-- NAV TAB -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <button class="nav-link active position-relative bg-transparent" id="active-bill-tab" data-bs-toggle="tab" data-bs-target="#active-bill" type="button" role="tab" aria-controls="active-bill" aria-selected="false">Tagihan Aktif
                                                </button>
                                                <button class="nav-link position-relative bg-transparent" id="bill-history-tab" data-bs-toggle="tab" data-bs-target="#bill-history" type="button" role="tab" aria-controls="bill-history" aria-selected="false">Riwayat Tagihan
                                                </button>
                                                <button class="nav-link position-relative bg-transparent" id="payment-history-tab" data-bs-toggle="tab" data-bs-target="#payment-history" type="button" role="tab" aria-controls="payment-history" aria-selected="false">Riwayat Pembayaran
                                                </button>
                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="active-bill" role="tabpanel" aria-labelledby="active-bill-tab" tabindex="0">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <tr>
                                                                    <th style="color: #000"><b>No</b></th>
                                                                    <th style="color: #000"><b>Nama Tagihan</b></th>
                                                                    <th style="color: #000"><b>Bulan</b></th>
                                                                    <th style="color: #000"><b>Tahun</b></th>
                                                                    <th style="color: #000; text-align: right;"><b>Tagihan</b></th>
                                                                    <th style="color: #000; text-align: right;"><b>Dibayar</b></th>
                                                                    <th style="color: #000; text-align: right;"><b>Sisa Bayar</b></th>
                                                                    <th style="color: #000; text-align: right;"><b>#</b></th>
                                                                    <th style="color: #000; text-align: right;"></th>
                                                                </tr>
                                                                <tbody id="student-invoice-container">

                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show" id="bill-history" role="tabpanel" aria-labelledby="bill-history-tab" tabindex="0">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <tr>
                                                                    <th><b>No</b></th>
                                                                    <th><b>Bulan</b></th>
                                                                    <th><b>Tahun</b></th>
                                                                    <th style="text-align: right;"><b>Total Tagihan</b></th>
                                                                    <th style="text-align: right;"><b>Total Bayar</b></th>
                                                                    <th style="text-align: right;"><b>Aksi</b></th>
                                                                </tr>
                                                                <tbody id="student-paid-invoice-container">

                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show" id="payment-history" role="tabpanel" aria-labelledby="payment-history-tab" tabindex="0">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <tr>
                                                                    <th><b>No</b></th>
                                                                    <th><b>Metode Pembayaran</b></th>
                                                                    <th style="text-align: right;"><b>Jumlah Bayar</b></th>
                                                                    <th style="text-align: right;"><b>Aksi</b></th>
                                                                </tr>
                                                                <tbody id="invoice-payment-history-container">

                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-light" onclick="history.back()" type="button">Tutup</button>
                                <button class="btn btn-secondary" type="button" onclick="save_and_send_wa()">Simpan dan kirim WA</button>
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="due_price_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ubah Jumlah Tagihan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="due_id" hidden>
                <div class="row">
                    <div class="col">
                        <label for="due_price">Jumlah Tagihan</label>
                        <input type="text" class="form-control" name="due_price" id="due_price">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="due_price_update()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paid_invoice_detail_modal" tabindex="-1" role="dialog" aria-labelledby="paid_invoice_detail_modal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Tagihan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><b>No</b></th>
                                <th><b>Nama Tagihan</b></th>
                                <th><b>Bulan</b></th>
                                <th><b>Tahun</b></th>
                                <th style="text-align: right;"><b>Tagihan</b></th>
                                <th style="text-align: right;"><b>Dibayar</b></th>
                                <th style="text-align: right;"><b>Sisa Bayar</b></th>
                            </tr>
                            <tbody id="paid-invoice-detail-container">

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="payment_history_detail_modal" tabindex="-1" role="dialog" aria-labelledby="payment_history_detail_modal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><b>No</b></th>
                                <th><b>Nama Tagihan</b></th>
                                <th><b>Bulan</b></th>
                                <th><b>Tahun</b></th>
                                <th style="text-align: right;"><b>Nominal</b></th>
                            </tr>
                            <tbody id="payment-history-detail-container">

                            </tbody>
                        </table>

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
    var student_id = "<?= $student_id ?>";
    var student_text = "<?= $student_text ?>";
    var month_list = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    var amount_should_be_paid = 0;

    $(document).ready(function() {
        if (student_id != "" || student_id != null) {
            $("#select-input-student_id").append(new Option(student_text, student_id, true, true)).trigger('change');
        }
    });

    $("#due_price").on("input", function() {
        var value = $(this).val().replace(/[\s,.]/g, "");
        var formattedValue = addCommas(value);
        $(this).val(formattedValue);
    });

    function addCommas(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $("#select-input-student_id").select2({
        ajax: {
            dataType: 'json',
            type: "get",
            url: "{{ url('general/search-active-student') }}",
            delay: 250,
            data: function(params) {
                return {
                    data: $.trim(params.term)
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            }
        },
        cache: true,
        placeholder: "Pilih Siswa",
    });

    $("#select-input-student_id").on("select2:select", function(e) {
        var student_id = e.params.data.encrypted_id;
        var url = new URL(window.location.href);
        url.searchParams.set('student_id', student_id);
        window.history.pushState({}, '', url);
    });

    window.addEventListener('popstate', (event) => {
        location.reload();
    });

    function get_student_invoice() {
        loading('show');
        $.ajax({
            type: "GET",
            url: "{{ url('/transaction/due-payment/get-student-active-due') }}",
            data: {
                "student_id": $("#select-input-student_id").val()
            },
            success: function(response) {
                var student_invoice_id = response.invoice_id;
                var student_dues = response.student_dues;
                var grand_total = 0;

                $("#txt-input-invoice_id").val(student_invoice_id);
                $("#student-invoice-container").empty();
                for (var i = 0; i < student_dues.length; i++) {
                    var due_id = student_dues[i]['id']
                    var price_raw = student_dues[i]['price']

                    var price = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['price']);

                    var payed_amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['payed_amount']);

                    var remaining_amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['price'] - student_dues[i]['payed_amount']);


                    $("#student-invoice-container").append(`
                    <tr>
                        <input type="hidden" id="txt-input-due_price-${ due_id }" value="${ student_dues[i]['price'] }">
                        <input type="hidden" id="txt-input-payed_amount-${ due_id }" value="${ student_dues[i]['payed_amount'] }">

                        <td style="color: #000">${ i + 1 }</td>
                        <td style="color: #000">${ student_dues[i]['due_name'] }</td>
                        <td style="color: #000">${ month_list[parseInt(student_dues[i]['payment_for_month']) - 1] }</td>
                        <td style="color: #000">${ student_dues[i]['payment_for_year'] }</td>
                        <td style="color: #000" align="right">${ price }</td>
                        <td style="color: #000" align="right">${ payed_amount }</td>
                        <td style="color: #000" align="right">${ remaining_amount }</td>
                        <td style="color: #000" align="right">
                        <button class="btn btn-sm btn-danger" type="button" onclick="due_delete(${ due_id })"><i class="fa fa-trash"></i></button>
                        <button class="btn btn-sm btn-primary" type="button" onclick="due_price_modal(${due_id}, ${price_raw})"><i class="fa fa-pencil"></i></button>
                        </td>
                        <td>
                            <!--<input type="checkbox" id="checkbox-input-selected_due-${ due_id }" checked name="selected_due[]" value="${ student_dues[i].due_id }" onchange="recalculate_total()">-->
                            <input type="checkbox" id="checkbox-input-selected_due-${ due_id }" checked name="selected_invoice_detail_id[]" value="${ student_dues[i].id }" onchange="recalculate_total()">
                        </td>
                    </tr>
                `);
                    grand_total += (student_dues[i]['price'] - student_dues[i]['payed_amount']);
                }

                amount_should_be_paid = grand_total;

                var formatted_grand_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(grand_total);

                $("#student-invoice-container").append(`
                <tr>
                    <td style="color: #000" colspan="7">Grand Total</td>
                    <td style="color: #000" align="right" id="txt-display-grand_total">${ formatted_grand_total }</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="color: #000" colspan="7">Metode Pembayaran</td>
                    <td style="color: #000" colspan="2" align="left">
                        <select name="bank_id" class="form-control select2" id="select-input-bank_id">
                            <option value="1" selected>Cash</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="color: #000" colspan="7">Tanggal Bayar</td>
                    <td style="color: #000" colspan="2" align="center"><input name="date" value="{{ date('Y-m-d') }}" type="date" class="form-control" placeholder="Masukkan Tanggal Bayar" id="date"/></td>
                </tr>
                <tr>
                    <td style="color: #000" colspan="7">Jumlah Bayar</td>
                    <td style="color: #000" colspan="2" align="center"><input name="pay_amount" value="${ grand_total }" readonly class="form-control text-end" placeholder="Masukkan Jumlah Dibayar" id="pay_amount"/></td>
                </tr>
            `);
                $("#pay_amount").on("input", function() {
                    var value = $(this).val().replace(/[\s,.]/g, "");
                    var formattedValue = addCommas(value);
                    $(this).val(formattedValue);
                });

                $("#select-input-bank_id").select2({
                    ajax: {
                        dataType: 'json',
                        type: "get",
                        url: "{{ url('general/search-bank') }}",
                        delay: 250,
                        data: function(params) {
                            return {
                                data: $.trim(params.term)
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        }
                    },
                    width: '100%',
                    placeholder: "Pilih Bank",
                });

                $.when(get_student_paid_due_per_month(), get_student_payment_history()).done(function() {
                    loading('hide');
                });
            },
            error: function(request, error) {
                loading('hide');
                swal.fire({
                    title: "Gagal!",
                    text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                    icon: "error"
                });
            }
        });
    }

    function recalculate_total() {
        var grand_total = 0;

        $("input[name='selected_invoice_detail_id[]']:checked").each(function() {
            var id = $(this).attr("id").split("-")[3];
            var price = $("#txt-input-due_price-" + id).val();
            var payed_amount = $("#txt-input-payed_amount-" + id).val();
            grand_total += parseInt(price) - parseInt(payed_amount);
        });

        var formatted_grand_total = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(grand_total);

        $("#txt-display-grand_total").text(formatted_grand_total);
        $("#pay_amount").val(grand_total);
    }

    function get_student_paid_due_per_month() {
        return $.ajax({
            type: "GET",
            url: "{{ url('/transaction/due-payment/get-student-paid-due-per-month') }}",
            data: {
                "student_id": $("#select-input-student_id").val()
            },
            success: function(response) {
                var student_dues = response.student_dues;
                var grand_total = 0;

                $("#student-paid-invoice-container").empty();
                for (var i = 0; i < student_dues.length; i++) {
                    var due_id = student_dues[i]['id']
                    var invoice_id = student_dues[i]['invoice_id']
                    var price_raw = student_dues[i]['price']

                    var total_price = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['total_price']);

                    var total_payed_amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['total_payed_amount']);

                    $("#student-paid-invoice-container").append(`
                        <tr>
                            <td style="color: #000">${ i + 1 }</td>
                            <td style="color: #000">${ month_list[parseInt(student_dues[i]['payment_for_month']) - 1] }</td>
                            <td style="color: #000">${ student_dues[i]['payment_for_year'] }</td>
                            <td style="color: #000" align="right">${ total_price }</td>
                            <td style="color: #000" align="right">${ total_payed_amount }</td>
                            <td style="color: #000" align="right">
                                <button class="btn btn-sm btn-primary" type="button" onclick="view_paid_invoice_detail('${invoice_id}', ${student_dues[i]['payment_for_month']}, ${student_dues[i]['payment_for_year']})">Lihat Detail</button>
                            </td>
                        </tr>
                    `);
                    grand_total += student_dues[i]['payed_amount'];
                }

                var formatted_grand_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(grand_total);

                // $("#student-paid-invoice-container").append(`
                //     <tr>
                //         <td style="color: #000" colspan="4">Grand Total</td>
                //         <td style="color: #000" align="right">${ formatted_grand_total }</td>
                //         <td></td>
                //     </tr>
                // `);
                $("#pay_amount").on("input", function() {
                    var value = $(this).val().replace(/[\s,.]/g, "");
                    var formattedValue = addCommas(value);
                    $(this).val(formattedValue);
                });

            },
            error: function(request, error) {
                swal.fire({
                    title: "Gagal!",
                    text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                    icon: "error"
                });
            }
        });
    }

    function view_paid_invoice_detail(invoice_id, payment_for_month, payment_for_year) {
        loading('show');
        $.ajax({
            type: "GET",
            url: "{{ url('/transaction/due-payment/get-student-paid-due-detail') }}",
            data: {
                "invoice_id": invoice_id,
                "payment_for_month": payment_for_month,
                "payment_for_year": payment_for_year
            },
            success: function(response) {
                var student_dues = response.student_dues;
                var price_total = 0;
                var payed_total = 0;
                var remaining_total = 0;

                $("#paid-invoice-detail-container").empty();
                for (var i = 0; i < student_dues.length; i++) {
                    var due_id = student_dues[i]['id']
                    var price_raw = student_dues[i]['price']

                    var price = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['price']);

                    var payed_amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['payed_amount']);

                    var remaining_amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['price'] - student_dues[i]['payed_amount']);

                    $("#paid-invoice-detail-container").append(`
                    <tr>
                        <td style="color: #000">${ i + 1 }</td>
                        <td style="color: #000">${ student_dues[i]['due_name'] }</td>
                        <td style="color: #000">${ month_list[parseInt(student_dues[i]['payment_for_month']) - 1] }</td>
                        <td style="color: #000">${ student_dues[i]['payment_for_year'] }</td>
                        <td style="color: #000" align="right">${ price }</td>
                        <td style="color: #000" align="right">${ payed_amount }</td>
                        <td style="color: #000" align="right">${ remaining_amount }</td>
                    </tr>
                `);
                    price_total += student_dues[i]['price'];
                    payed_total += student_dues[i]['payed_amount'];
                    remaining_total += student_dues[i]['price'] - student_dues[i]['payed_amount'];
                }

                var formatted_price_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(price_total);

                var formatted_payed_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(payed_total);

                var formatted_remaining_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(remaining_total);


                $("#paid-invoice-detail-container").append(`
                    <tr>
                        <td style="color: #000" colspan="4">Total</td>
                        <td style="color: #000" align="right">${ formatted_price_total }</td>
                        <td style="color: #000" align="right">${ formatted_payed_total }</td>
                        <td style="color: #000" align="right">${ formatted_remaining_total }</td>
                    </tr>
                `);

                loading('hide');
                $("#paid_invoice_detail_modal").modal('show');
            },
            error: function(request, error) {
                loading('hide');
                swal.fire({
                    title: "Gagal!",
                    text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                    icon: "error"
                });
            }
        });
    }

    function get_student_payment_history() {
        return $.ajax({
            type: "GET",
            url: "{{ url('/transaction/due-payment/get-student-payment-history') }}",
            data: {
                "student_id": $("#select-input-student_id").val()
            },
            success: function(response) {
                var invoice_payments = response.invoice_payments;
                var grand_total = 0;

                $("#invoice-payment-history-container").empty();
                for (var i = 0; i < invoice_payments.length; i++) {
                    var invoice_payment_id = invoice_payments[i]['invoice_payment_id']

                    var price = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(invoice_payments[i]['price']);

                    $("#invoice-payment-history-container").append(`
                        <tr>
                            <td style="color: #000">${ i + 1 }</td>
                            <td style="color: #000">${ invoice_payments[i]['bank_name'] }</td>
                            <td style="color: #000" align="right">${ price }</td>
                            <td style="color: #000" align="right">
                                <button class="btn btn-sm btn-primary" type="button" onclick="view_student_payment_history_detail('${invoice_payment_id}')">Lihat Detail</button>
                                <button class="btn btn-sm btn-success" type="button" onclick="send_wa_student_payment_history_detail('${invoice_payment_id}')">Kirim WhatsApp</button>
                            </td>
                        </tr>
                    `);
                }
            },
            error: function(request, error) {
                swal.fire({
                    title: "Gagal !",
                    text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                    icon: "error"
                });
            }
        });
    }

    function view_student_payment_history_detail(invoice_payment_id) {
        loading('show');
        $.ajax({
            type: "GET",
            url: "{{ url('/transaction/due-payment/get-student-payment-history-detail') }}",
            data: {
                "invoice_payment_id": invoice_payment_id
            },
            success: function(response) {
                var invoice_detail_payments = response.invoice_detail_payments;
                var total_payed = 0;

                $("#payment-history-detail-container").empty();
                for (var i = 0; i < invoice_detail_payments.length; i++) {
                    var price = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(invoice_detail_payments[i]['price']);

                    $("#payment-history-detail-container").append(`
                        <tr>
                            <td style="color: #000">${ i + 1 }</td>
                            <td style="color: #000">${ invoice_detail_payments[i]['due_name'] }</td>
                            <td style="color: #000">${ month_list[parseInt(invoice_detail_payments[i]['payment_for_month']) - 1] }</td>
                            <td style="color: #000">${ invoice_detail_payments[i]['payment_for_year'] }</td>
                            <td style="color: #000" align="right">${ price }</td>
                        </tr>
                    `);

                    total_payed += invoice_detail_payments[i]['price'];
                }

                var formatted_payed_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(total_payed);

                $("#payment-history-detail-container").append(`
                    <tr>
                        <td style="color: #000" colspan="4">Jumlah Bayar</td>
                        <td style="color: #000" align="right"><b>${ formatted_payed_total }</b></td>
                    </tr>
                `);

                loading('hide');
                $("#payment_history_detail_modal").modal('show');
            },
            error: function(request, error) {
                loading('hide');
                swal.fire({
                    title: "Gagal !",
                    text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                    icon: "error"
                });
            }
        });
    }

    function send_wa_student_payment_history_detail(invoice_payment_id) {
        loading('show');
        $.ajax({
            type: "GET",
            url: "{{ url('/transaction/due-payment/send-wa-student-payment-history-detail') }}",
            data: {
                "invoice_payment_id": invoice_payment_id
            },
            success: function(data) {
                var response = data.client_response;
                loading('hide');
                swal.fire(response.title, response.message, response.type);
            },
            error: function(request, error) {
                loading('hide');
                swal.fire({
                    title: "Gagal !",
                    text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                    icon: "error"
                });
            }
        });
    }

    function get_student_paid_due() {
        loading('show');
        $.ajax({
            type: "GET",
            url: "{{ url('/transaction/due-payment/get-student-paid-due') }}",
            data: {
                "student_id": $("#select-input-student_id").val()
            },
            success: function(response) {
                var student_invoice_id = response.invoice_id;
                var student_dues = response.student_dues;
                var grand_total = 0;

                $("#txt-input-invoice_id").val(student_invoice_id);
                $("#student-paid-invoice-container").empty();
                for (var i = 0; i < student_dues.length; i++) {
                    var due_id = student_dues[i]['id']
                    var price_raw = student_dues[i]['price']

                    var price = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['price']);

                    var payed_amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['payed_amount']);

                    var remaining_amount = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(student_dues[i]['price'] - student_dues[i]['payed_amount']);


                    $("#student-paid-invoice-container").append(`
                    <tr>
                        <td style="color: #000">${ i + 1 }</td>
                        <td style="color: #000">${ student_dues[i]['due_name'] }</td>
                        <td style="color: #000">${ month_list[parseInt(student_dues[i]['payment_for_month']) - 1] }</td>
                        <td style="color: #000">${ student_dues[i]['payment_for_year'] }</td>
                        <td style="color: #000" align="right">${ price }</td>
                        <td style="color: #000" align="right">${ payed_amount }</td>
                        <td style="color: #000" align="right">${ remaining_amount }</td>
                        <td style="color: #000" align="right">${ remaining_amount }</td>

                    </tr>
                `);
                    grand_total += student_dues[i]['payed_amount'];
                }

                var formatted_grand_total = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(grand_total);

                // $("#student-paid-invoice-container").append(`
                //     <tr>
                //         <td style="color: #000" colspan="7">Grand Total</td>
                //         <td style="color: #000" align="right">${ formatted_grand_total }</td>
                //         <td></td>
                //     </tr>
                // `);
                $("#pay_amount").on("input", function() {
                    var value = $(this).val().replace(/[\s,.]/g, "");
                    var formattedValue = addCommas(value);
                    $(this).val(formattedValue);
                });

                loading('hide');
            },
            error: function(request, error) {
                loading('hide');
                swal.fire({
                    title: "Gagal!",
                    text: "Terjadi kesalahan dalam memproses, harap menghubungi Administrator",
                    icon: "error"
                });
            }
        });
    }

    function due_price_modal(due_id, price) {
        $("#due_id").val(due_id);
        $("#due_price_modal").modal('show');
    }

    function due_price_update() {
        var id = $("#due_id").val();
        var price = $("#due_price").val().replace(/,/g, "");

        let form_data = new FormData();
        form_data.append('id', id);
        form_data.append('price', price);

        form_data.append('_token', '{{ csrf_token() }}');

        submit_form_data("{{ url('/invoice/payment-detail/do-invoice-payment-detail-update') }}/" + id, form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })

    }

    function due_delete(id) {
        swal.fire({
            title: 'Konfirmasi Hapus Iuran Siswa',
            text: 'Iuran berikut akan dihapus dari, lanjutkan?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, hapus"
        }).then((result) => {
            if (result.isConfirmed) {
                loading('show');
                $.ajax({
                    type: "post",
                    url: "{{ url('/invoice/payment-detail/do-invoice-payment-detail-delete') }}/" + id,
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        loading('hide');
                        var response = data;
                        swal.fire(response.title, response.message, response.type)
                            .then((result) => {
                                // data_table.ajax.reload(null, false);
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                    },
                    error: function(xhr, status, error) {
                        loading('hide')
                        var response = xhr.responseJSON;
                        if (xhr.status == 406) {
                            swal.fire(response.title, response.message, response.type);
                        }
                        if (xhr.status == 404) {
                            swal.fire("Proses Gagal!", "Halaman tidak ditemukan", "error");
                        }
                        if (xhr.status == 500) {
                            swal.fire("Internal Servel Error 500", "Hubungi admin untuk mendapatkan bantuan terkait masalah", "error");
                        }
                    }
                });
            }
        });

    }

    // function add_to_payment(id) {
    //     var selected_dues = $("#txt-input-due_price-" + id).val();
    //     if ($("#cb-input-add_to_bill-" + id).is(":checked")) {
    //         grand_total = grand_total + parseInt(selected_dues);
    //         $("#txt-input-due_id-" + id).attr("name", "due_id[]");
    //         $("#txt-input-due_price-" + id).attr("name", "due_price[]");
    //     } else {
    //         grand_total = grand_total - parseInt(selected_dues);
    //         $("#txt-input-due_id-" + id).attr("name", "");
    //         $("#txt-input-due_price-" + id).attr("name", "");
    //     }
    //     $("#txt-display-grand_total").text(grand_total);
    // }

    $("#input-form").submit(function(e) {
        e.preventDefault();
        var pay_amount = $("#pay_amount").val().replace(/,/g, "");
        $("#pay_amount").val(pay_amount);

        if (parseInt(pay_amount) == 0 || pay_amount == "") {
            swal.fire("Jumlah Bayar Kosong", "Jumlah bayar tidak boleh kosong", "error");
            return;
        }

        if (parseInt(pay_amount) > amount_should_be_paid) {
            swal.fire("Jumlah Bayar lebih", "Jumlah bayar tidak boleh lebih dari jumlah yang harus dibayar", "error");
            return;
        }

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#input-form")[0]);

        if (!can_input) return;
        can_input = false;
        loading("show");

        $.ajax({
            type: "post",
            url: "{{ url('/invoice/payment/do-invoice-payment') }}",
            data: form_data,
            processData: false,
            contentType: false,
            // Jika request berhasil
            success: function (data) {
                loading("hide");
                var response = data;
                var wa_message = data.data.wa_message;
                var invoice_payment_id = data.data.invoice_payment_id;

                window.open("{{ url('/invoice/payment/get-payment-receipt') }}/" + invoice_payment_id, "_blank");

                swal.fire({
                    title: response.title,
                    text: response.message,
                    icon: response.type,
                    showDenyButton: true,
                    confirmButtonText: "Input lagi",
                    denyButtonText: "Kembali ke halaman utama",
                }).then(function (result) {
                    if (result.isConfirmed) {
                        swal.close();
                        window.open(wa_message, "_blank");
                        location.reload();
                    } else if (result.isDenied) {
                        location.href = "{{ $information['route'] }}";
                    }
                });

                // resolve(response);
            },

            // Jika request gagal
            error: function (xhr, status, error) {
                loading("hide");
                var response = xhr.responseJSON;
                if (xhr.status == 406)
                    swal.fire(response.title, response.message, response.type);
                if (xhr.status == 404)
                    swal.fire("Proses Gagal!", "Halaman tidak ditemukan", "error");
                if (xhr.status == 422)
                    swal.fire("Proses gagal!", response.message, "error");
                if (xhr.status == 500)
                    swal.fire(
                        "Internal Servel Error 500",
                        "Hubungi admin untuk mendapatkan bantuan terkait masalah",
                        "error"
                    );
                can_input = true;
                // reject(response);
            },
        });
        // submit_form_data("{{ url('/invoice/payment/do-invoice-payment') }}", form_data, {
        //     reload: "Input Lagi",
        //     close: "Tutup Halaman",
        //     redirect_url: "{{ $information['route'] }}"
        // })
    });

    function save_and_send_wa() {
        var pay_amount = $("#pay_amount").val().replace(/,/g, "");
        $("#pay_amount").val(pay_amount);

        if (parseInt(pay_amount) == 0 || pay_amount == "") {
            swal.fire("Jumlah Bayar Kosong", "Jumlah bayar tidak boleh kosong", "error");
            return;
        }

        if (parseInt(pay_amount) > amount_should_be_paid) {
            swal.fire("Jumlah Bayar lebih", "Jumlah bayar tidak boleh lebih dari jumlah yang harus dibayar", "error");
            return;
        }

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#input-form")[0]);

        form_data.append('send_wa', true);

        if (!can_input) return;
        can_input = false;
        loading("show");

        $.ajax({
            type: "post",
            url: "{{ url('/invoice/payment/do-invoice-payment') }}",
            data: form_data,
            processData: false,
            contentType: false,
            // Jika request berhasil
            success: function (data) {
                loading("hide");
                var response = data;
                var invoice_payment_id = data.data.invoice_payment_id;

                window.open("{{ url('/invoice/payment/get-payment-receipt') }}/" + invoice_payment_id, "_blank");

                swal.fire({
                    title: response.title,
                    text: response.message,
                    icon: response.type,
                    showDenyButton: true,
                    confirmButtonText: "Input lagi",
                    denyButtonText: "Kembali ke halaman utama",
                }).then(function (result) {
                    if (result.isConfirmed) {
                        swal.close();
                        location.reload();
                    } else if (result.isDenied) {
                        location.href = "{{ $information['route'] }}";
                    }
                });

                // resolve(response);
            },

            // Jika request gagal
            error: function (xhr, status, error) {
                loading("hide");
                var response = xhr.responseJSON;
                if (xhr.status == 406)
                    swal.fire(response.title, response.message, response.type);
                if (xhr.status == 404)
                    swal.fire("Proses Gagal!", "Halaman tidak ditemukan", "error");
                if (xhr.status == 422)
                    swal.fire("Proses   gagal!", response.message, "error");
                if (xhr.status == 500)
                    swal.fire(
                        "Internal Servel Error 500",
                        "Hubungi admin untuk mendapatkan bantuan terkait masalah",
                        "error"
                    );
                can_input = true;
                // reject(response);
            },
        });
    }
</script>

<script>
    // Simpan tab terakhir yang dibuka di local storage, kemuadian load tab tersebut saat halaman direfresh
    window.addEventListener('DOMContentLoaded', (event) => {
        const nav_tabs = document.querySelectorAll('.nav-link');
        const active_tab_key = 'active_tab';

        const saved_active_tab = localStorage.getItem(active_tab_key);
        if (saved_active_tab) {
            const saved_tab = document.querySelector(`[data-bs-target='${saved_active_tab}']`);
            if (saved_tab) {
                nav_tabs.forEach(tab => {
                    tab.classList.remove('active');
                    tab.setAttribute('aria-selected', 'false');
                });
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });

                saved_tab.classList.add('active');
                saved_tab.setAttribute('aria-selected', 'true');
                document.querySelector(saved_active_tab).classList.add('show', 'active');
            }
        }

        nav_tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                localStorage.setItem(active_tab_key, this.getAttribute('data-bs-target'));
            });
        });
    });
</script>


@endsection
