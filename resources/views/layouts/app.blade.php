<!doctype html>
<html lang="en">

<?php
use App\Helpers\UserInfoHelper;

$access = json_decode(session()->get('user')->access, true);

$has_master_student_access = $access["student"]["student_view"] ?? false;
$has_master_classroom_access = $access["classroom"]["classroom_view"] ?? false;
$has_master_due_access = $access["due"]["due_view"] ?? false;
$has_master_school_year_access = $access["school_year"]["school_year_view"] ?? false;
$has_master_due_day_off_access = $access["due_day_off"]["due_day_off_view"] ?? false;

$has_master_employee_access = $access["employee"]["employee_view"] ?? false;
$has_master_position_access = $access["position"]["position_view"] ?? false;

$has_master_user_access = $access["user"]["user_view"] ?? false;
$has_master_accessibility_access = $access["accessibility"]["accessibility_view"] ?? false;
$has_master_bank_access = $access["bank"]["bank_view"] ?? false;
$has_master_accessibility_access = $access["accessibility"]["accessibility_view"] ?? false;
$has_master_address_village_access = $access["address_village"]["address_village_view"] ?? false;
$has_master_address_district_access = $access["address_district"]["address_district_view"] ?? false;

$has_class_management_class_grade_promotion_access = $access["class_grade_promotion"]["class_grade_promotion_view"] ?? false;
$has_class_management_class_change_access = $access["class_change"]["class_change_view"] ?? false;

$has_finance_coa_access = $access["coa"]["coa_view"] ?? false;
$has_finance_cashflow_access = $access["cashflow"]["cashflow_view"] ?? false;

$has_due_subscription_access = $access["due_subscription"]["due_subscription_view"] ?? false;

$has_transaction_bill_issuance_access = $access["bill_issuance"]["bill_issuance_view"] ?? false;
$has_transaction_due_payment_access = $access["due_payment"]["due_payment_view"] ?? false;
$has_transaction_payment_refund_access = $access["payment_refund"]["payment_refund_view"] ?? false;
$has_transaction_cashier_access = $access["cashier"]["cashier_view"] ?? false;
$has_transaction_cashier_payment_access = $access["cashier_payment"]["cashier_payment_view"] ?? false;

$has_report_payment_access = $access["payment_report"]["payment_report_view"] ?? false;
$has_report_balance_sheet_access = $access["balance_sheet_report"]["balance_sheet_report_view"] ?? false;
$has_report_cashflow_access = $access["cashflow_report"]["cashflow_report_view"] ?? false;
$has_report_expense_access = $access["expense_report"]["expense_report_view"] ?? false;
$has_report_profit_access = $access["profit_report"]["profit_report_view"] ?? false;
$has_report_cashier_access = $access["cashier_report"]["cashier_report_view"] ?? false;
$has_report_daily_bank_access = $access["daily_bank_report"]["daily_bank_report_view"] ?? false;
$has_report_student_not_paid_access = $access["student_not_paid_report"]["student_not_paid_report_view"] ?? false;
$has_report_student_paid_detail_access = $access["student_paid_detail_report"]["student_paid_detail_report_view"] ?? false;
$has_report_student_over_paid_access = $access["student_over_paid_report"]["student_over_paid_report_view"] ?? false;

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Title -->
    <title>{{ env('APP_NAME') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="img/core-img/favicon.ico">

    <!-- Plugins File -->
    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/assets/css/animate.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset('/assets/css/introjs.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('/assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/sweetalert2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/assets/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/select.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/daterangepicker-master/daterangepicker.css') }}" />


    <!-- Master Stylesheet [If you remove this CSS file, your file will be broken undoubtedly.] -->
    <link rel="stylesheet" href="{{ asset('/assets/style.css') }}">
    <style>
        .dataTables_filter {
            display: none;
        }
        input:not(:focus) {
            color: #000;
        }
        select:not(:focus) {
            color: #000;
        }
        textarea:not(:focus) {
            color: #000;
        }
        td, tr, th{
            color: #000 !important;
        }
    </style>

</head>

<body>
    <!-- Preloader -->
    <!-- <div id="preloader">
        <div class="preloader-book">
            <div class="inner">
                <div class="left"></div>
                <div class="middle"></div>
                <div class="right"></div>
            </div>
            <ul>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </div> -->
    <!-- /Preloader -->

    <!-- Choose Layout -->
    <!-- <div class="choose-layout-area">

        <div class="choose-layout" id="chooseLayout">
            <div class="quick-setting-tab">
                <div class="widgets-todo-list-area">
                    <div class="row">
                        <div class="col">
                            <h4 class="todo-title">Todo List:</h4>
                        </div>

                    </div>
                    <form id="form-add-todo" class="form-add-todo">
                        <input type="text" id="new-todo-item" class="new-todo-item form-control" name="todo" placeholder="Add New">
                        <input type="submit" id="add-todo-item" class="add-todo-item" value="+">
                    </form>

                    <form id="form-todo-list">
                        <ul id="flaptToDo-list" class="todo-list">
                        </ul>
                    </form>

                </div>
            </div>
        </div>

        <hr>

        <div id="settingCloseIcon">
            <i class="ti-close"></i>
        </div>
    </div> -->

    <!-- ======================================
    ******* Page Wrapper Area Start **********
    ======================================= -->
    <div class="flapt-page-wrapper">
        <!-- Sidemenu Area -->
        <div class="flapt-sidemenu-wrapper">
            <!-- Desktop Logo -->
            <div class="flapt-logo">
                <a href="#">
                    <img class="desktop-logo" src="{{ asset('/logo-baptis.png') }}" style="min-height: 40px !important;" alt="Desktop Logo">
                    <img class="small-logo" src="{{ asset('/logo-baptis.png') }}" alt="Mobile Logo">
                </a>
            </div>

            <!-- Side Nav -->
            <div class="flapt-sidenav" id="flaptSideNav">
                <!-- Side Menu Area -->
                <div class="side-menu-area">
                    <!-- Sidebar Menu -->
                    <nav>
                        <ul class="sidebar-menu" data-widget="tree">
                            <li class="menu-header-title">Apps</li>
                            <li><a href="{{ url('/') }}"><i class='bx bx-home-heart'></i><span>Halaman Utama</span></a></li>
                            @if ($has_master_student_access || $has_master_classroom_access || $has_master_due_access || $has_master_school_year_access || $has_master_due_day_off_access
                                || $has_master_employee_access || $has_master_position_access || $has_master_user_access || $has_master_accessibility_access
                                || $has_master_bank_access || $has_master_accessibility_access || $has_master_address_village_access || $has_master_address_district_access
                                || $has_class_management_class_grade_promotion_access || $has_class_management_class_change_access)
                            <li class="menu-header-title">Data Sekolah</li>
                            @endif
                            @if ($has_master_student_access || $has_master_classroom_access || $has_master_due_access || $has_master_school_year_access || $has_master_due_day_off_access)
                            <li class="treeview">
                                <a href="javascript:void(0)"><i class='bx bx-user'> </i><span>Siswa</span> <i class="fa fa-angle-right"></i></a>
                                <ul class="treeview-menu">
                                    @if ($has_master_student_access)
                                    <li><a href="{{ url('/master/student') }}">Siswa</a></li>
                                    @endif
                                    @if ($has_master_classroom_access)
                                    <li><a href="{{ url('/master/classroom') }}">Kelas</a></li>
                                    @endif
                                    @if ($has_master_due_access)
                                    <li><a href="{{ url('/master/due') }}">Iuran</a></li>
                                    @endif
                                    @if ($has_master_school_year_access)
                                    <li><a href="{{ url('/master/school-year') }}">Tahun Ajaran</a></li>
                                    @endif
                                    @if ($has_master_due_day_off_access)
                                    <li><a href="{{ url('/master/due-day-off') }}">Libur Iuran</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if ($has_master_employee_access || $has_master_position_access)
                            <li class="treeview">
                                <a href="javascript:void(0)"><i class='bx bx-user'> </i><span>Staff</span> <i class="fa fa-angle-right"></i></a>
                                <ul class="treeview-menu">
                                    @if ($has_master_employee_access)
                                    <li><a href="{{ url('/master/employee') }}">Pegawai</a></li>
                                    @endif
                                    @if ($has_master_position_access)
                                    <li><a href="{{ url('/master/position') }}">Jabatan</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if ($has_master_user_access || $has_master_accessibility_access || $has_master_bank_access || $has_master_accessibility_access
                                || $has_master_address_village_access || $has_master_address_district_access)
                            <li class="treeview">
                                <a href="javascript:void(0)"><i class="bx bx-table"></i> <span>Master Data</span> <i class="fa fa-angle-right"></i></a>
                                <ul class="treeview-menu">
                                    @if ($has_master_user_access)
                                    <li><a href="{{ url('/master/user') }}">User</a></li>
                                    @endif
                                    {{-- <li><a href="{{ url('/master/teacher') }}">Guru</a></li> --}}
                                    <!-- <li><a href="{{ url('/master/fix-fee') }}">Iuran Tetap</a></li>
                                    <li><a href="{{ url('/master/additional-fee') }}">Iuran Tambahan</a></li> -->
                                    @if ($has_master_accessibility_access)
                                    <li><a href="{{ url('/master/accessibility') }}">Hak Akses</a></li>
                                    @endif
                                    @if ($has_master_bank_access)
                                    <li><a href="{{ url('/master/bank') }}">Bank</a></li>
                                    @endif
                                    @if ($has_master_accessibility_access)
                                    <li><a href="{{ url('/master/address-village') }}">Kelurahan</a></li>
                                    @endif
                                    @if ($has_master_accessibility_access)
                                    <li><a href="{{ url('/master/address-district') }}">Kecamatan</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if ($has_class_management_class_grade_promotion_access || $has_class_management_class_change_access)
                            <li class="treeview">
                                <a href="javascript:void(0)"><i class='bx bx-archive'> </i><span>Manajemen Kelas</span> <i class="fa fa-angle-right"></i></a>
                                <ul class="treeview-menu">
                                    @if ($has_class_management_class_grade_promotion_access)
                                    <li><a href="{{ url('/class-management/class-grade-promotion') }}">Kenaikan Kelas</a></li>
                                    @endif
                                    @if ($has_class_management_class_change_access)
                                    <li><a href="{{ url('/class-management/class-change') }}">Pindah Kelas</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if ($has_finance_coa_access || $has_finance_cashflow_access)
                            <li class="menu-header-title">Finance</li>
                            @endif
                            <!-- <li><a href="{{ url('/finance/income') }}"><i class='bx bx-download'></i><span>Pemasukan</span></a></li>
                            <li><a href="{{ url('/finance/outcome') }}"><i class='bx bx-upload'></i><span>Pengeluaran</span></a></li> -->
                            @if ($has_finance_coa_access)
                            <li><a href="{{ url('/finance/coa') }}"><i class='bx bx-card'></i><span>COA</span></a></li>
                            @endif
                            @if ($has_finance_cashflow_access)
                            <li><a href="{{ url('/finance/cashflow') }}"><i class='bx bx-money'></i><span>Arus Kas</span></a></li>
                            @endif
                            <!-- <li><a href="{{ url('/finance/journal') }}"><i class='bx bx-book'></i><span>Jurnal</span></a></li> -->

                            @if ($has_due_subscription_access)
                            <li class="menu-header-title">Manajemen Iuran</li>
                            <li><a href="{{ url('/due-management/subscription') }}"><i class='bx bx-money'></i><span>Pendaftaran Iuran</span></a></li>
                            @endif

                            <!-- <li><a href="{{ url('/due-management/unsubscription') }}"><i class='bx bx-money'></i><span>Berhenti Iuran</span></a></li> -->

                            @if ($has_transaction_bill_issuance_access || $has_transaction_due_payment_access || $has_transaction_payment_refund_access)
                            <li class="menu-header-title">Transaksi</li>
                            @endif
                            <!-- <li><a href="{{ url('/transaction') }}"><i class='bx bx-money'></i><span>Transaksi</span></a></li> -->
                            @if ($has_transaction_bill_issuance_access)
                            <li><a href="{{ url('/transaction/bill-issuance') }}"><i class='bx bx-bell'></i><span>Tagihan</span></a></li>
                            @endif
                            <li><a href="{{ url('/due-management/subscription-per-month-list') }}"><i class='bx bx-search'></i><span>Cari Tagihan</span></a></li>
                            @if ($has_transaction_due_payment_access)
                            <li><a href="{{ url('/transaction/due-payment') }}"><i class='bx bx-money'></i><span>Bayar Tagihan</span></a></li>
                            @endif
                            @if ($has_transaction_payment_refund_access)
                            <li><a href="{{ url('/transaction/payment-refund') }}"><i class='bx bx-redo'></i><span>Refund Tagihan</span></a></li>
                            @endif
                            @if ($has_transaction_cashier_access)
                            <!-- <li><a href="{{ url('/transaction/cashier') }}"><i class='bx bx-money'></i><span>Penjualan Kasir</span></a></li> -->
                            @endif
                            @if ($has_transaction_cashier_payment_access)
                            <li><a href="{{ url('/transaction/cashier-payment') }}"><i class='bx bx-money'></i><span>Pembayaran</span></a></li>
                            @endif
                            <li><a href="{{ url('/transaction/publish-va-manual') }}"><i class='bx bx-bell'></i><span>Terbit VA Manual</span></a></li>
                            <!-- <li><a href="{{ url('/transaction/bill-issuance/history') }}"><i class='bx bx-history'></i><span>Riwayat Tagihan</span></a></li>
                            <li><a href="{{ url('/transaction/due-payment/history') }}"><i class='bx bx-history'></i><span>Pembayaran Tagihan</span></a></li> -->

                            @if ($has_report_payment_access || $has_report_balance_sheet_access || $has_report_cashflow_access || $has_report_expense_access || $has_report_profit_access || $has_report_cashier_access
                                || $has_report_student_not_paid_access || $has_report_student_paid_detail_access)
                            <li class="menu-header-title">Laporan</li>
                            @endif
                            @if ($has_report_payment_access || $has_report_balance_sheet_access || $has_report_cashflow_access || $has_report_expense_access || $has_report_profit_access || $has_report_cashier_access)
                            <li class="treeview">
                                <a href="javascript:void(0)"><i class="bx bx-file"></i> <span>Laporan Keuangan</span> <i class="fa fa-angle-right"></i></a>
                                <ul class="treeview-menu">
                                    @if ($has_report_payment_access)
                                    <li><a href="{{ url('/report/payment') }}">Pembayaran</a></li>
                                    @endif
                                    @if ($has_report_balance_sheet_access)
                                    <li><a href="{{ url('/report/balance-sheet') }}">Neraca</a></li>
                                    @endif
                                    @if ($has_report_cashflow_access)
                                    <li><a href="{{ url('/report/cashflow') }}">Arus Kas</a></li>
                                    @endif
                                    @if ($has_report_expense_access)
                                    <!-- <li><a href="{{ url('/report/expense') }}">Pengeluaran</a></li> -->
                                    @endif
                                    @if ($has_report_daily_bank_access)
                                    <li><a href="{{ url('/report/bank-daily') }}">Bank Harian</a></li>
                                    @endif
                                    @if ($has_report_profit_access)
                                    <li><a href="{{ url('/report/profit') }}">Laba Rugi</a></li>
                                    @endif
                                    @if ($has_report_cashier_access)
                                    <li><a href="{{ url('/report/cashier') }}">Harian Kasir</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if ($has_report_student_not_paid_access || $has_report_student_paid_detail_access)
                            <li class="treeview">
                                <a href="javascript:void(0)"><i class="bx bx-file"></i> <span>Laporan Siswa</span> <i class="fa fa-angle-right"></i></a>
                                <ul class="treeview-menu">
                                    @if ($has_report_student_not_paid_access)
                                    <li><a href="{{ url('/report/student-not-paid') }}">Siswa Belum Bayar</a></li>
                                    @endif
                                    @if ($has_report_student_paid_detail_access)
                                    <li><a href="{{ url('/report/student-paid-detail') }}">Pembayaran Siswa Detail</a></li>
                                    @endif
                                    @if ($has_report_student_over_paid_access)
                                    <li><a href="{{ url('/report/student-over-paid') }}">Siswa Bayar Lebih</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="flapt-page-content">
            <!-- Top Header Area -->
            <header class="top-header-area d-flex align-items-center justify-content-between">
                <div class="left-side-content-area d-flex align-items-center">
                    <!-- Mobile Logo -->
                    <div class="mobile-logo">
                        <a href="{{ url('/') }}"><img class="small-logo" src="{{ asset('/logo-baptis.png') }}" alt="Mobile Logo"></a>
                    </div>

                    <!-- Triggers -->
                    <div class="flapt-triggers">
                        <div class="menu-collasped" id="menuCollasped">
                            <i class='bx bx-grid-alt'></i>
                        </div>
                        <div class="mobile-menu-open" id="mobileMenuOpen">
                            <i class='bx bx-grid-alt'></i>
                        </div>
                    </div>

                    <!-- Left Side Nav -->
                    <!-- <ul class="left-side-navbar d-flex align-items-center">
                        <li class="hide-phone app-search">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="bx bx-search-alt"></span>
                        </li>
                    </ul> -->
                    <ul class="left-side-navbar d-flex align-items-center">
                        <li class="hide-phone app-search">
                            <div class="text-dark"><b>{{ env('APP_NAME') }}</b></div>
                        </li>
                    </ul>
                </div>

                <div class="right-side-navbar d-flex align-items-center justify-content-end">
                    <!-- Mobile Trigger -->
                    <div class="right-side-trigger" id="rightSideTrigger">
                        <i class='bx bx-menu-alt-right'></i>
                    </div>

                    <!-- Top Bar Nav -->
                    <ul class="right-side-content d-flex align-items-center">

                        <li class="nav-item dropdown">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class='bx bx-user'></i></button>
                            <div class="dropdown-menu profile dropdown-menu-right">
                                <!-- User Profile Area -->
                                <div class="user-profile-area">
                                    <a href="{{ url('/profile') }}" class="dropdown-item"><i class="bx bx-user font-15" aria-hidden="true"></i> Profil</a>
                                    <a href="{{ url('/logout') }}" class="dropdown-item"><i class="bx bx-power-off font-15" aria-hidden="true"></i> Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </header>

            <div class="main-content">

                @yield('content')

                <!-- Footer Area -->
                    <div class="row">
                        <div class="col-12">
                            <!-- Footer Area -->
                            <footer class="footer-area d-sm-flex justify-content-center align-items-center justify-content-between">
                                <!-- Copywrite Text -->
                                <div class="copywrite-text">
                                    <p class="font-13"></p>
                                </div>
                                <div class="fotter-icon text-center">
                                    <p class="mb-0 font-13">2023 &copy; {{ env('APP_NAME') }}</p>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>

        </div>
    </div>

    <!-- ======================================
    ********* Page Wrapper Area End ***********
    ======================================= -->

    <!-- Must needed plugins to the run this Template -->
    <script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/js/default-assets/setting.js') }}"></script>
    <script src="{{ asset('/assets/js/default-assets/scrool-bar.js') }}"></script>
    <script src="{{ asset('/assets/js/todo-list.js') }}"></script>

    <!-- Active JS -->
    <script src="{{ asset('/assets/js/default-assets/active.js') }}"></script>

    <!-- These plugins only need for the run this page -->
    <!-- <script src="{{ asset('/assets/js/apexcharts.min.js') }}"></script> -->
    <!-- <script src="{{ asset('/assets/js/intro.min.js') }}"></script> -->

    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.responsive.min.js') }}"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="{{ asset('/js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('/js/script.js') }}"></script>
    <script src="{{ asset('/assets/js/select2.min.js') }}"></script>
    <!-- <script src="{{ asset('/js/sweetalert2.js') }}"></script> -->
    <script src="{{ asset('/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/daterangepicker-master/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/daterangepicker-master/daterangepicker.js') }}"></script>

    <!-- <script src="{{ asset('/assets/js/dashboard-custom.js') }}"></script> -->

    @yield('js_after')

    <!-- <script src="{{ asset('/assets/js/intro-active.js') }}"></script> -->

</body>

</html>
