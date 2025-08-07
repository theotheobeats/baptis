<?php

use App\Helpers\DataHelper;
use App\Helpers\InvoicePublisherHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\AccessibilityController;
use App\Http\Controllers\AddressDistrictController;
use App\Http\Controllers\AddressVillageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BillIssuanceController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassChangeController;
use App\Http\Controllers\ClassGradePromotionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DueController;
use App\Http\Controllers\DueDayOffController;
use App\Http\Controllers\DuePaymentController;
use App\Http\Controllers\DueSubscriptionController;
use App\Http\Controllers\DueUnsubscriptionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinanceAccountController;
use App\Http\Controllers\FinanceCashFlowController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailPaymentController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\PaymentRefundController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TestingPaymentController;
use App\Http\Controllers\UserController;
use App\Models\DueDayOff;
use App\Models\DueSubscription;
use App\Models\DueUnsubscription;
use Faker\Provider\ar_EG\Payment;
use App\Helpers\PaymentGatewayHelper;
use App\Helpers\WhatsappNotificationHelper;
use App\Http\Controllers\CashierPaymentController;
use App\Http\Controllers\CashierTransactionController;
use App\Http\Controllers\PublishVaManualController;
use App\Models\InvoicePayment;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// QR Code, invoice / pembayaran
Route::get("/check/payment/{invoice_id}/{payment_code}/{created_at}", [InvoicePaymentController::class, "check_payment"]);
Route::get("resync-password", [InvoicePaymentController::class, "resync_password"]);
Route::get('/', function () {
    return view('layouts.app');
});

Route::get("/login", [AuthController::class, "login"])->name("loginpage");
Route::post("/auth/login", [AuthController::class, "do_login"]);
Route::get("/logout", [AuthController::class, "logout"]);

Route::get("sync-payment-to-cashflow", [FinanceCashFlowController::class, "sync_payment_to_cashflow"]);

Route::middleware(["auth.session"])->group(function () {
    Route::get("/", [DashboardController::class, "index"]);
    Route::get("/table/api-espay-payment-notifications-maspion", [DashboardController::class, "table_api_espay_payment_notifications_maspion"]);

    Route::get("/profile", [ProfileController::class, "index"])->name('profile');
    Route::post("/profile/update-employee/{id}", [ProfileController::class, "update_employee"]);
    Route::post("/profile/update-password/{id}", [ProfileController::class, "update_password"]);

    Route::group(["prefix" => "general"], function () {
        Route::get("/search-district", [GeneralController::class, "search_district"]);
        Route::get("/search-village", [GeneralController::class, "search_village"]);
        Route::get("/search-student", [GeneralController::class, "search_student"]);
        Route::get("/search-classroom", [GeneralController::class, "search_classroom"]);
        Route::get("/search-school-year", [GeneralController::class, "search_school_year"]);
        Route::get("/search-due", [GeneralController::class, "search_due"]);
        Route::get("/search-finance-account", [GeneralController::class, "search_finance_account"]);
        Route::get("/search-bank", [GeneralController::class, "search_bank"]);
        Route::get("/search-employee", [GeneralController::class, "search_employee"]);

        Route::get("/get-student-current-classroom", [GeneralController::class, "get_student_current_classroom"]);

        Route::get("/search-active-student", [GeneralController::class, "search_active_student"]);
    });

    Route::group(["prefix" => "master"], function () {
        Route::group(["prefix" => "user", "as" => "master.user"], function () {
            Route::get("/", [UserController::class, "index"]);
            Route::get("/create", [UserController::class, "create"]);
            Route::get("/edit/{id}", [UserController::class, "edit"]);
            Route::post("/store", [UserController::class, "store"]);
            Route::post("/update/{id}", [UserController::class, "update"]);
            Route::post("/delete/{id}", [UserController::class, "destroy"]);

            Route::get("toggle-dark-theme", [UserController::class, "toggle_dark_theme"]);

            Route::get("/access", [UserController::class, "access"]);

            Route::post("/export/excel", [UserController::class, "export_excel"]);
            Route::post("/import/excel", [UserController::class, "import_excel"]);
        });

        Route::group(["prefix" => "employee", "as" => "master.employee"], function () {
            Route::get("/", [EmployeeController::class, "index"]);
            Route::get("/create", [EmployeeController::class, "create"]);
            Route::get("/edit/{id}", [EmployeeController::class, "edit"]);
            Route::post("/store", [EmployeeController::class, "store"]);
            Route::post("/update/{id}", [EmployeeController::class, "update"]);
            Route::post("/delete/{id}", [EmployeeController::class, "destroy"]);
            Route::post("/export/excel", [EmployeeController::class, "export_excel"]);
            Route::post("/import/excel", [EmployeeController::class, "import_excel"]);
        });

        Route::group(["prefix" => "position", "as" => "master.position"], function () {
            Route::get("/", [PositionController::class, "index"]);
            Route::get("/create", [PositionController::class, "create"]);
            Route::get("/edit/{id}", [PositionController::class, "edit"]);
            Route::post("/store", [PositionController::class, "store"]);
            Route::post("/update/{id}", [PositionController::class, "update"]);
            Route::post("/delete/{id}", [PositionController::class, "destroy"]);
            Route::post("/export/excel", [PositionController::class, "export_excel"]);
            Route::post("/import/excel", [PositionController::class, "import_excel"]);
        });

        Route::group(["prefix" => "student", "as" => "master.student"], function () {
            Route::get("/", [StudentController::class, "index"]);
            Route::get("/create", [StudentController::class, "create"]);
            Route::get("/edit/{id}", [StudentController::class, "edit"]);
            Route::post("/store", [StudentController::class, "store"]);
            Route::post("/update/{id}", [StudentController::class, "update"]);
            Route::post("/delete/{id}", [StudentController::class, "destroy"]);
            Route::post("/export/excel", [StudentController::class, "export_excel"]);
            Route::post("/import/excel", [StudentController::class, "import_excel"]);

            Route::post("/due-bind/{id}", [StudentController::class, "due_bind"]);

            Route::post("/activate/{id}", [StudentController::class, "student_activate"]);
            Route::post("/deactivate/{id}", [StudentController::class, "student_deactivate"]);
        });

        Route::group(["prefix" => "teacher", "as" => "master.teacher"], function () {
            Route::get("/", [TeacherController::class, "index"]);
            Route::get("/create", [TeacherController::class, "create"]);
            Route::get("/edit/{id}", [TeacherController::class, "edit"]);
            Route::post("/store", [TeacherController::class, "store"]);
            Route::post("/update/{id}", [TeacherController::class, "update"]);
            Route::post("/delete/{id}", [TeacherController::class, "destroy"]);
            Route::post("/export/excel", [TeacherController::class, "export_excel"]);
            Route::post("/import/excel", [TeacherController::class, "import_excel"]);
        });

        Route::group(["prefix" => "classroom", "as" => "master.classroom"], function () {
            Route::get("/", [ClassroomController::class, "index"]);
            Route::get("/create", [ClassroomController::class, "create"]);
            Route::get("/edit/{id}", [ClassroomController::class, "edit"]);
            Route::post("/store", [ClassroomController::class, "store"]);
            Route::post("/update/{id}", [ClassroomController::class, "update"]);
            Route::post("/delete/{id}", [ClassroomController::class, "destroy"]);
            Route::post("/export/excel", [ClassroomController::class, "export_excel"]);
            Route::post("/import/excel", [ClassroomController::class, "import_excel"]);
        });

        Route::group(["prefix" => "school-year", "as" => "master.school-year"], function () {
            Route::get("/", [SchoolYearController::class, "index"]);
            Route::get("/create", [SchoolYearController::class, "create"]);
            Route::get("/edit/{id}", [SchoolYearController::class, "edit"]);
            Route::post("/store", [SchoolYearController::class, "store"]);
            Route::post("/update/{id}", [SchoolYearController::class, "update"]);
            Route::post("/delete/{id}", [SchoolYearController::class, "destroy"]);
            Route::post("/export/excel", [SchoolYearController::class, "export_excel"]);
            Route::post("/import/excel", [SchoolYearController::class, "import_excel"]);
        });

        Route::group(["prefix" => "due", "as" => "master.due"], function () {
            Route::get("/", [DueController::class, "index"]);
            Route::get("/create", [DueController::class, "create"]);
            Route::get("/edit/{id}", [DueController::class, "edit"]);
            Route::post("/store", [DueController::class, "store"]);
            Route::post("/update/{id}", [DueController::class, "update"]);
            Route::post("/delete/{id}", [DueController::class, "destroy"]);
            Route::post("/export/excel", [DueController::class, "export_excel"]);
            Route::post("/import/excel", [DueController::class, "import_excel"]);
        });


        Route::group(["prefix" => "due", "as" => "master.due"], function () {
            Route::get("/", [DueController::class, "index"]);
            Route::get("/create", [DueController::class, "create"]);
            Route::get("/edit/{id}", [DueController::class, "edit"]);
            Route::post("/store", [DueController::class, "store"]);
            Route::post("/update/{id}", [DueController::class, "update"]);
            Route::post("/delete/{id}", [DueController::class, "destroy"]);
            Route::post("/export/excel", [DueController::class, "export_excel"]);
            Route::post("/import/excel", [DueController::class, "import_excel"]);
        });

        Route::group(["prefix" => "bank", "as" => "master.bank"], function () {
            Route::get("/", [BankController::class, "index"]);
            Route::get("/create", [BankController::class, "create"]);
            Route::get("/edit/{id}", [BankController::class, "edit"]);
            Route::post("/store", [BankController::class, "store"]);
            Route::post("/update/{id}", [BankController::class, "update"]);
            Route::post("/delete/{id}", [BankController::class, "destroy"]);
            Route::post("/export/excel", [BankController::class, "export_excel"]);
            Route::post("/import/excel", [BankController::class, "import_excel"]);
        });

        Route::group(["prefix" => "accessibility", "as" => "master.accessibility"], function () {
            Route::get("/", [AccessibilityController::class, "index"]);
            Route::get("/create", [AccessibilityController::class, "create"]);
            Route::get("/edit/{id}", [AccessibilityController::class, "edit"]);
            Route::post("/store", [AccessibilityController::class, "store"]);
            Route::post("/update/{id}", [AccessibilityController::class, "update"]);
            Route::post("/delete/{id}", [AccessibilityController::class, "destroy"]);
            Route::post("/export/excel", [AccessibilityController::class, "export_excel"]);
            Route::post("/import/excel", [AccessibilityController::class, "import_excel"]);
        });

        Route::group(["prefix" => "address-village", "as" => "master.address-village"], function () {
            Route::get("/", [AddressVillageController::class, "index"]);
            Route::get("/create", [AddressVillageController::class, "create"]);
            Route::get("/edit/{id}", [AddressVillageController::class, "edit"]);
            Route::post("/store", [AddressVillageController::class, "store"]);
            Route::post("/update/{id}", [AddressVillageController::class, "update"]);
            Route::post("/delete/{id}", [AddressVillageController::class, "destroy"]);
            Route::post("/export/excel", [AddressVillageController::class, "export_excel"]);
            Route::post("/import/excel", [AddressVillageController::class, "import_excel"]);
        });

        Route::group(["prefix" => "address-district", "as" => "master.address-district"], function () {
            Route::get("/", [AddressDistrictController::class, "index"]);
            Route::get("/create", [AddressDistrictController::class, "create"]);
            Route::get("/edit/{id}", [AddressDistrictController::class, "edit"]);
            Route::post("/store", [AddressDistrictController::class, "store"]);
            Route::post("/update/{id}", [AddressDistrictController::class, "update"]);
            Route::post("/delete/{id}", [AddressDistrictController::class, "destroy"]);
            Route::post("/export/excel", [AddressDistrictController::class, "export_excel"]);
            Route::post("/import/excel", [AddressDistrictController::class, "import_excel"]);
        });

        Route::group(["prefix" => "due-day-off", "as" => "master.due-day-off"], function () {
            Route::get("/", [DueDayOffController::class, "index"]);
            Route::get("/create", [DueDayOffController::class, "create"]);
            Route::get("/edit/{id}", [DueDayOffController::class, "edit"]);
            Route::post("/store", [DueDayOffController::class, "store"]);
            Route::post("/update/{id}", [DueDayOffController::class, "update"]);
            Route::post("/delete/{id}", [DueDayOffController::class, "destroy"]);
            Route::post("/export/excel", [DueDayOffController::class, "export_excel"]);
            Route::post("/import/excel", [DueDayOffController::class, "import_excel"]);
        });
    });

    // Handle Manajemen Kelas
    Route::group(["prefix" => "class-management"], function () {
        Route::get("/class-grade-promotion", [ClassGradePromotionController::class, "index"]);
        Route::post("/class-grade-promotion/export/excel", [ClassGradePromotionController::class, "export_excel"]);
        Route::post("/class-grade-promotion/import/excel", [ClassGradePromotionController::class, "import_excel"]);

        Route::get("/class-change", [ClassChangeController::class, "index"]);
        Route::get("/class-change/create", [ClassChangeController::class, "create"]);
        Route::post("/class-change/store", [ClassChangeController::class, "store"]);
        Route::post("/class-change/export/excel", [ClassChangeController::class, "export_excel"]);
        Route::post("/class-change/import/excel", [ClassChangeController::class, "import_excel"]);
    });

    Route::group(["prefix" => "finance"], function () {
        Route::group(["prefix" => "cashflow", "as" => "finance.cashflow"], function () {
            Route::get("/", [FinanceCashFlowController::class, "index"]);
            Route::get("/create", [FinanceCashFlowController::class, "create"]);
            Route::get("/create2", [FinanceCashFlowController::class, "create2"]);
            Route::get("/edit/{id}", [FinanceCashFlowController::class, "edit"]);
            Route::post("/store", [FinanceCashFlowController::class, "store"]);
            Route::post("/store2", [FinanceCashFlowController::class, "store2"]);
            Route::post("/update/{id}", [FinanceCashFlowController::class, "update"]);
            Route::post("/delete/{id}", [FinanceCashFlowController::class, "destroy"]);
            Route::post("/verify/{id}", [FinanceCashFlowController::class, "verify"]);
            Route::post("/export/excel", [FinanceCashFlowController::class, "export_excel"]);
            Route::post("/import/excel", [FinanceCashFlowController::class, "import_excel"]);

            Route::post("/delete-file-handover/{finance_cash_flow_file_id}", [FinanceCashFlowController::class, "delete_file_handover"]);

            Route::get("/cash-account/open/", [FinanceCashFlowController::class, "open_cash_account"]);
            Route::get("/cash-account/open/print/{id}/{type?}", [FinanceCashFlowController::class, "open_print"])->defaults("type", "default");
            Route::get("/cash-account/close/print/{id}", [FinanceCashFlowController::class, "close_print"]);
            Route::post("/cash-account/do-open", [FinanceCashFlowController::class, "do_open"]);
            Route::post("/cash-account/do-close", [FinanceCashFlowController::class, "do_close"]);
        });

        Route::group(["prefix" => "coa", "as" => "finance.coa"], function () {
            Route::get("/", [FinanceAccountController::class, "index"]);
            Route::get("/create", [FinanceAccountController::class, "create"]);
            Route::get("/edit/{id}", [FinanceAccountController::class, "edit"]);
            Route::post("/store", [FinanceAccountController::class, "store"]);
            Route::get("/search", [FinanceAccountController::class, "search"])->name(".search");
            Route::post("/update/{id}", [FinanceAccountController::class, "update"]);
            Route::post("/delete/{id}", [FinanceAccountController::class, "destroy"]);
            Route::post("/export/excel", [FinanceAccountController::class, "export_excel"]);
            Route::post("/import/excel", [FinanceAccountController::class, "import_excel"]);
        });
    });

    Route::group(["prefix" => "invoice"], function() {
        Route::post("/publish-monthly-invoice", [InvoiceController::class, "publish_monthly_invoice"]);
        Route::post("/publish-individual-invoice", [InvoiceController::class, "publish_individual_invoice"]);

        Route::group(["prefix" => "payment"], function() {
            Route::post("/do-invoice-payment", [InvoicePaymentController::class, "do_manual_invoice_payment"]);
            Route::get("/get-payment-receipt/{id}", [InvoicePaymentController::class, "get_payment_receipt"]);
        });

        Route::group(["prefix" => "payment-detail"], function() {
            Route::post("/do-invoice-payment-detail-update/{id}", [InvoiceDetailPaymentController::class, "do_invoice_payment_detail_update"]);
            Route::post("/do-invoice-payment-detail-delete/{id}", [InvoiceDetailPaymentController::class, "do_invoice_payment_detail_delete"]);
        });
    });

    Route::group(["prefix" => "transaction"], function () {
        Route::group(["prefix" => "bill-issuance", "as" => "transaction.bill-issuance"], function () {
            Route::get("/", [BillIssuanceController::class, "index"]);
            Route::get("/history", [BillIssuanceController::class, "history"]);
            Route::post("/store", [BillIssuanceController::class, "store"]);
            Route::get("/get-student-dues", [BillIssuanceController::class, "get_student_dues"]);
            Route::post("/send-invoice-notification", [BillIssuanceController::class, "send_invoice_notification"]);
            Route::post("/export/excel", [BillIssuanceController::class, "export_excel"]);
        });

        Route::group(["prefix" => "due-payment", "as" => "transaction.due-payment"], function () {
            Route::get("/", [DuePaymentController::class, "index"]);
            Route::get("/history", [DuePaymentController::class, "history"]);
            Route::post("/store", [DuePaymentController::class, "store"]);
            Route::get("/get-student-active-invoice", [DuePaymentController::class, "get_student_active_invoice"]);
            Route::get("/get-student-active-due", [DuePaymentController::class, "get_student_active_due"]);
            Route::get("/get-student-paid-due-per-month", [DuePaymentController::class, "get_student_paid_due_per_month"]);
            Route::get("/get-student-paid-due-detail", [DuePaymentController::class, "get_student_paid_due_detail"]);
            // Route::get("/get-student-paid-due", [DuePaymentController::class, "get_student_paid_due"]);

            Route::get("/get-student-payment-history", [DuePaymentController::class, "get_student_payment_history"]);
            Route::get("/get-student-payment-history-detail", [DuePaymentController::class, "get_student_payment_history_detail"]);
            Route::get("/send-wa-student-payment-history-detail", [DuePaymentController::class, "send_wa_student_payment_history"]);

        });

        Route::group(["prefix" => "payment-refund", "as" => "transaction.payment-refund"], function () {
            Route::get("/", [PaymentRefundController::class, "index"]);
            Route::get("/create", [PaymentRefundController::class, "create"]);
            Route::post("/store", [PaymentRefundController::class, "store"]);

            Route::get("/get-invoice-payment", [PaymentRefundController::class, "get_invoice_payment"]);
            Route::get("/get-payment-refund-detail", [PaymentRefundController::class, "get_payment_refund_detail"]);
            Route::get("/find-student-due-payment", [PaymentRefundController::class, "find_student_due_payment"]);
        });

        //cashier transaction
        Route::group(["prefix" => "cashier", "as" => "transaction.cashier"], function () {
            // Route::get("/", [CashierTransactionController::class, "index"]);
            // Route::get("/create", [CashierTransactionController::class, "create"]);
            // Route::get("/edit/{id}", [CashierTransactionController::class, "edit"]);
            // Route::post("/store", [CashierTransactionController::class, "store"]);
            // Route::post("/update/{id}", [CashierTransactionController::class, "update"]);
            // Route::post("/delete/{id}", [CashierTransactionController::class, "destroy"]);
            // Route::post("/export/excel", [CashierTransactionController::class, "export_excel"]);
            // Route::post("/import/excel", [CashierTransactionController::class, "import_excel"]);
        });

        //Pembayaran
        Route::group(["prefix" => "cashier-payment", "as" => "transaction.cashier-payment"], function () {
            Route::get("/", [CashierPaymentController::class, "index"]);
            Route::get("/create", [CashierPaymentController::class, "create"]);
            Route::get("/edit/{id}", [CashierPaymentController::class, "edit"]);
            Route::post("/store", [CashierPaymentController::class, "store"]);
            Route::post("/update/{id}", [CashierPaymentController::class, "update"]);
            Route::post("/delete/{id}", [CashierPaymentController::class, "destroy"]);
            Route::post("/export/excel", [CashierPaymentController::class, "export_excel"]);
            Route::post("/import/excel", [CashierPaymentController::class, "import_excel"]);
        });

        // Terbit VA manual
        Route::group(["prefix" => "publish-va-manual"], function () {
            Route::get("/", [PublishVaManualController::class, "index"]);
            Route::get("/create", [PublishVaManualController::class, "create"]);
            Route::post("/store", [PublishVaManualController::class, "store"]);
        });


    });

    Route::group(["prefix" => "report"], function () {
        Route::group(["prefix" => "payment", "as" => "report.payment"], function () {
            Route::get("/", [ReportController::class, "payment"]);
            Route::get("/print", [ReportController::class, "payment_print"]);
        });

        Route::group(["prefix" => "balance-sheet", "as" => "report.balance-sheet"], function () {
            Route::get("/", [ReportController::class, "balance_sheet"]);
            Route::get("/print", [ReportController::class, "balance_sheet_print"]);
        });

        Route::group(["prefix" => "cashflow", "as" => "report.cashflow"], function () {
            Route::get("/", [ReportController::class, "cashflow"]);
            Route::get("/print", [ReportController::class, "cashflow_print"]);
        });

        Route::group(["prefix" => "profit", "as" => "report.profit"], function () {
            Route::get("/", [ReportController::class, "profit"]);
            Route::get("/print", [ReportController::class, "profit_print"]);
        });

        Route::group(["prefix" => "cashier", "as" => "report.cashier"], function () {
            Route::get("/", [ReportController::class, "cashier"]);
            Route::get("/print", [ReportController::class, "cashier_print"]);
        });

        Route::group(["prefix" => "student-not-paid", "as" => "report.student-not-paid"], function () {
            Route::get("/", [ReportController::class, "student_not_paid"]);
            Route::get("/print", [ReportController::class, "student_not_paid_print"]);
        });

        Route::group(["prefix" => "student-paid-detail", "as" => "report.student-paid-detail"], function () {
            Route::get("/", [ReportController::class, "student_paid_detail"]);
            Route::get("/print", [ReportController::class, "student_paid_detail_print"]);
        });

        Route::group(["prefix" => "student-over-paid", "as" => "report.student-over-paid"], function () {
            Route::get("/", [ReportController::class, "student_over_paid"]);
            Route::get("/print", [ReportController::class, "student_over_paid_print"]);
        });

        Route::group(["prefix" => "bank-daily", "as" => "report.bank-daily"], function () {
            Route::get("/", [ReportController::class, "bank_daily"]);
            Route::get("/print", [ReportController::class, "bank_daily_print"]);
        });
    });

    Route::group(["prefix" => "due-management"], function () {
        Route::group(["prefix" => "subscription", "as" => "due-management.subscription"], function () {
            Route::get("/", [DueSubscriptionController::class, "index"]);
            Route::post("/store", [DueSubscriptionController::class, "store"]);
            Route::post("/due-price-change/{id}", [DueSubscriptionController::class, "student_due_price_change"]);
            Route::post("/single-unsubscribe/{id}", [DueUnsubscriptionController::class, "single_unsubscribe"]);
            Route::post("/export/excel", [DueSubscriptionController::class, "export_excel"]);
            Route::post("/import/excel", [DueSubscriptionController::class, "import_excel"]);
        });

        Route::group(["prefix" => "subscription-per-month-list", "as" => "due-management.subscription-per-month-list"], function () {
            Route::get("/", [DueSubscriptionController::class, "subscription_per_month_list"]);
        });

        Route::group(["prefix" => "unsubscription", "as" => "due-management.unsubscription"], function () {
            Route::get("/", [DueUnsubscriptionController::class, "index"]);
            Route::post("/store", [DueUnsubscriptionController::class, "store"]);
        });
    });

    Route::get("/invoice-testing", [TestingPaymentController::class, "invoice_testing_get"]);
    Route::post("/invoice-testing", [TestingPaymentController::class, "invoice_testing_post"]);

});

// Route::get('test', function () {
//     PaymentGatewayHelper::register_invoice(1);
//     // $key = "YPE2023";
//     // $rq_uuid = 12345;
//     // $rq_datetime = date("Y-m-d H:i:s");
//     // $order_id = 1;
//     // $amount = 500000;
//     // $ccy = "IDR";
//     // $comm_code = "YPEB01";// "01391";
//     // $mode = "SENDINVOICE";

//     // echo $rq_datetime;
//     // echo "<br>";
//     // $uppercase = strtoupper("##$key##$rq_uuid##$rq_datetime##$order_id##$amount##$ccy##$comm_code##$mode##");
//     // echo "Signature: ##$key##$rq_uuid##$rq_datetime##$order_id##$amount##$ccy##$comm_code##$mode##";
//     // echo "<br>";
//     // echo "Signature UpperCase: $uppercase";
//     // echo "<br>";
//     // $signature = hash('sha256', $uppercase);
//     // echo "Signature Hash: $signature";
//     // // return $uppercase;

//     // return $signature;
// });

// Route::get("get-bot", [WhatsappNotificationHelper::class, 'get_bot']);
Route::get("send-message/{wa_number}/{message}", [WhatsappNotificationHelper::class, 'send_message']);
Route::get("send-attachment-message/{wa_number}/{media}/{caption}", [WhatsappNotificationHelper::class, 'send_attachment_message']);
Route::get("get-bot", [WhatsappNotificationHelper::class, 'get_bot']);
Route::get("get-all-template", [WhatsappNotificationHelper::class, 'get_all_template']);
Route::get("send-message-template-custom", [WhatsappNotificationHelper::class, 'send_message_template_custom']);

Route::get("re-init-student-va", [DataHelper::class, "re_init_student_va"]);
Route::get("publish-all-invoice", [InvoicePublisherHelper::class, 'publish_all_invoice']);
Route::get("tessss/{id}", [InvoicePayment::class, 'generate_payment_proof']);
Route::get("tes-invoice/{id}", [InvoicePublisherHelper::class, 'generate_invoice_file']);
Route::get("single-notification/{id}", [WhatsappNotificationHelper::class, 'send_single_notification']);

Route::get("tes-invoice-format/{id}", [InvoicePublisherHelper::class, 'example_invoice_format']);

Route::get("publish-all-notification", [InvoicePublisherHelper::class, 'publish_all_notification']);
