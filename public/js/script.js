function loading(state) {
    if (state == "show") {
        $.blockUI();
    } else {
        $.unblockUI();
    }
}

function submit_data(form, params, callback = null) {
    var param_reload = params.reload == null ? "Input Lagi" : params.reload;
    var param_close = params.close == null ? "Tutup Halaman" : params.close;
    var param_redirect_url =
        params.redirect_url == null ? "Input Lagi" : params.redirect_url;

    loading("show");

    var url = form.attr("action");

    $.ajax({
        type: "post",
        url: url,
        data: form.serialize(),

        // Jika request berhasil
        success: function (data) {
            loading("hide");
            var response = data;

            swal.fire({
                title: response.title,
                text: response.message,
                icon: response.type,
                showDenyButton: true,
                confirmButtonText: "Input lagi",
                denyButtonText: "Kembali ke halaman utama",
            }).then(function (result) {
                if (result.isConfirmed) {
                    location.reload();
                } else if (result.isDenied) {
                    location.href = param_redirect_url;
                }
            });

            if (callback != null) {
                callback.success();
            }
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
            if (callback != null) {
                callback.error();
            }
        },
    });
}

// Sama seperti submit_data, bedanya yang dikirim FormData
function submit_form_data(url, form_data, params, callback = null) {
    var param_reload = params.reload == null ? "Input Lagi" : params.reload;
    var param_close = params.close == null ? "Tutup Halaman" : params.close;
    var param_redirect_url =
        params.redirect_url == null ? "Input Lagi" : params.redirect_url;

    loading("show");

    $.ajax({
        type: "post",
        url: url,
        data: form_data,
        processData: false,
        contentType: false,
        // Jika request berhasil
        success: function (data) {
            loading("hide");
            var response = data;

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
                    location.href = param_redirect_url;
                }
            });

            // resolve(response);

            if (callback != null) {
                callback.success();
            }
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

            // reject(response);

            if (callback != null) {
                callback.error();
            }
        },
    });
}

function export_excel(url, token, filename) {
    loading("show");
    $.ajax({
        type: "post",
        url: url + "/export/excel",
        data: {
            _token: token,
        },
        success: function (data) {
            loading("hide");
            var response = data;
            var tabular_data = [
                {
                    sheetName: "Sheet1",
                    data: response,
                },
            ];
            Jhxlsx.export(tabular_data, {
                fileName: filename,
            });
        },
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
        },
    });
}

function export_custom_excel(url, filename, params) {
    loading("show");
    $.ajax({
        type: "post",
        url: url + "/export/excel",
        data: params,
        success: function (data) {
            loading("hide");
            var response = data;
            var tabular_data = [
                {
                    sheetName: "Sheet1",
                    data: response,
                },
            ];
            Jhxlsx.export(tabular_data, {
                fileName: filename,
            });
        },
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
        },
    });
}

function import_excel(url, token) {
    if ($("#input-import-excel")[0].files[0] == null) {
        swal("Import Data Gagal!", "Tidak ada file yang diupload", "error");
        return false;
    }
    loading("show");
    var formData = new FormData();
    formData.append("_token", token);
    formData.append("file-excel", $("#input-import-excel")[0].files[0]);
    $.ajax({
        type: "post",
        url: url + "/import/excel",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            loading("hide");
            swal.fire({
                title: response.title,
                text: response.message,
                icon: response.type,
            }).then((value) => {
                location.reload();
            });
        },
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
        },
    });
}
