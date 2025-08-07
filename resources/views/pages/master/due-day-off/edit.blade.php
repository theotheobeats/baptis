@extends('layouts.app')


@section('content')

<!-- Main Content Area -->
<div class="content-wraper-area">
    <div class="data-table-area">
        <div class="container-fluid">
            <div class="row mt-3">

                <div class="col-12">
                    <h5 class="txt-title">Edit {{ $information['title'] }}</h5>
                    <div class="card bg-white">

                        <form class="form theme-form" id="edit-form" action="{{ url($information['route']) }}/update/{{ Crypt::encrypt($due_day_off->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-day_off_date">Iuran<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-day_off_date" name="day_off_date" type="text" placeholder="Tanggal" required value="{{ $due->name }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label class="form-label" for="txt-input-day_off_date">Tanggal<span class="text-danger">*</span></label>
                                            <input class="form-control" id="txt-input-day_off_date" name="day_off_date" type="date" placeholder="Tanggal" required value="{{ $due_day_off->day_off_date }}">
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
    $(document).ready(function() {
        $("#txt-input-price").on("input", function() {
            var value = $(this).val().replace(/[\s,.]/g, "");
            var formattedValue = addCommas(value);
            $(this).val(formattedValue);
        });

        function addCommas(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    });


    $("#edit-form").submit(function(e) {
        e.preventDefault();

        var price = $("#txt-input-price").val().replace(/,/g, "");
        $("#txt-input-price").val(price);

        // Method dibawah disimpan di script.js
        let form_data = new FormData($("#edit-form")[0]);
        submit_form_data("{{ url($information['route']) }}/update/{{ Crypt::encrypt($due_day_off->id) }}", form_data, {
            reload: "Input Lagi",
            close: "Tutup Halaman",
            redirect_url: "{{ $information['route'] }}"
        })
    });
</script>
@endsection