<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::match(['post','get'],'/', "HomeController@index")->name('home');

Route::get('/login','HomeController@index')->name('login');
Route::post('/login','HomeController@process_login')->name('process_login');
Route::get('/logout','HomeController@logout')->name('logout');

Route::match(['post','get'],'/myprofile','HomeController@myprofile')->name('myprofile');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard','Dashboard@index')->name('dashboard');

    Route::prefix('ajax')->namespace('Ajax')->group(function () {
        Route::get('/findstock', ['as' => 'findstock', 'uses' => 'AjaxController@findstock']);
        Route::get('/findanystock', ['as' => 'findanystock', 'uses' => 'AjaxController@findanystock']);
        Route::get('/findselectstock', ['as' => 'findselectstock', 'uses' => 'AjaxController@findselectstock']);
        Route::get('/findimage', ['as' => 'findimage', 'uses' => 'AjaxController@findimage']);
        Route::get('/findpurchaseorderstock', ['as' => 'findpurchaseorderstock', 'uses' => 'AjaxController@findpurchaseorderstock']);
        Route::get('/findclothtype', ['as' => 'findclothtype', 'uses' => 'AjaxController@findclothtype']);
    });

    Route::middleware(['permit.task'])->group(function () {
        Route::prefix('access-control')->namespace('AccessControl')->group(function () {

            Route::prefix('user-group')->as('user.group.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'GroupController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'GroupController@list_all']);
                Route::get('create', ['as' => 'create', 'uses' => 'GroupController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'GroupController@store']);
                Route::match(['get', 'post'], '{id}/permission', ['as' => 'permission', 'uses' => 'GroupController@permission']);
                Route::get('{id}/fetch_task', ['as' => 'task', 'uses' => 'GroupController@fetch_task']);
                Route::get('{id}', ['as' => 'show', 'uses' => 'GroupController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'GroupController@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'GroupController@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'GroupController@update']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'GroupController@destroy']);
            });

            Route::prefix('user')->as('user.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'UserController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'UserController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'UserController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'UserController@store']);
                Route::get('{id}', ['as' => 'show', 'uses' => 'UserController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'UserController@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'UserController@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'UserController@update']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'UserController@destroy']);
            });

            Route::prefix('audit')->as('audit.')->group(function () {
                Route::match(['get','post'],'', ['as' => 'index', 'uses' => 'AuditsController@index', 'visible' => true, 'custom_label'=>'Audit Logs']);
            });

        });

        Route::prefix('settings')->namespace('Settings')->group(function () {

            Route::prefix('bank')->as('bank.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'BankController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'BankController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'BankController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'BankController@store']);
                Route::get('{id}', ['as' => 'show', 'uses' => 'BankController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'BankController@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'BankController@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'BankController@update']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'BankController@destroy']);
            });

            Route::prefix('category')->as('category.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'CategoryController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'CategoryController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'CategoryController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'CategoryController@store']);
                Route::get('{id}', ['as' => 'show', 'uses' => 'CategoryController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'CategoryController@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'CategoryController@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'CategoryController@update']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'CategoryController@destroy']);
            });


            Route::prefix('manufacturer')->as('manufacturer.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'ManufacturerController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'ManufacturerController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'ManufacturerController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'ManufacturerController@store']);
                Route::get('{id}', ['as' => 'show', 'uses' => 'ManufacturerController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'ManufacturerController@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'ManufacturerController@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'ManufacturerController@update']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'ManufacturerController@destroy']);
            });

            Route::prefix('payment_method')->as('payment_method.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'PaymentMethodController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'PaymentMethodController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'PaymentMethodController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'PaymentMethodController@store']);
                Route::get('{id}', ['as' => 'show', 'uses' => 'PaymentMethodController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'PaymentMethodController@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'PaymentMethodController@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'PaymentMethodController@update']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'PaymentMethodController@destroy']);
            });

            Route::prefix('supplier')->as('supplier.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'SupplierController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'SupplierController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'SupplierController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'SupplierController@store']);
                Route::get('{id}', ['as' => 'show', 'uses' => 'SupplierController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'SupplierController@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'SupplierController@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'SupplierController@update']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'SupplierController@destroy']);
            });

            Route::prefix('expenses_type')->as('expenses_type.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'ExpensesTypeController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'ExpensesTypeController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'ExpensesTypeController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'ExpensesTypeController@store']);
                Route::get('{id}', ['as' => 'show', 'uses' => 'ExpensesTypeController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'ExpensesTypeController@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'ExpensesTypeController@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'ExpensesTypeController@update']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'ExpensesTypeController@destroy']);
            });
            /*
                        if (config('app.store') == "hotel")
                        {

                            Route::prefix('room_type')->as('room_type.')->group(function () {
                                Route::get('', ['as' => 'index', 'uses' => 'RoomTypeController@index', 'visible' => true]);
                                Route::get('list', ['as' => 'list', 'uses' => 'RoomTypeController@listAll']);
                                Route::get('create', ['as' => 'create', 'uses' => 'RoomTypeController@create']);
                                Route::post('', ['as' => 'store', 'uses' => 'RoomTypeController@store']);
                                Route::get('{id}', ['as' => 'show', 'uses' => 'RoomTypeController@show']);
                                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'RoomTypeController@edit']);
                                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'RoomTypeController@toggle']);
                                Route::put('{id}', ['as' => 'update', 'uses' => 'RoomTypeController@update']);
                                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'RoomTypeController@destroy']);
                            });

                        }
            */
            Route::prefix('departments')->as('department.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'Department@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'Department@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'Department@create']);
                Route::post('', ['as' => 'store', 'uses' => 'Department@store']);
                Route::get('{id}', ['as' => 'show', 'uses' => 'Department@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'Department@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'Department@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'Department@update']);
                Route::get('{id}/set_as_default', ['as' => 'set_as_default', 'uses' => 'Department@set_as_default']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'Department@destroy']);
            });

            Route::prefix('store_settings')->as('store_settings.')->group(function () {
                Route::get('', ['as' => 'view', 'uses' => 'StoreSettings@show', 'visible' => true]);
                Route::put('update', ['as' => 'update', 'uses' => 'StoreSettings@update']);
            });

            Route::prefix('price_settings')->as('price_settings.')->group(function () {
                Route::get('', ['as' => 'view', 'uses' => 'PriceSettingsController@show', 'visible' => true]);
                Route::put('update', ['as' => 'update', 'uses' => 'PriceSettingsController@update']);
            });

        });

        Route::prefix('CustomerManager')->namespace('CustomerManager')->group(function () {

            Route::prefix('customer')->as('customer.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'CustomerController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'CustomerController@list_all']);
                Route::get('create', ['as' => 'create', 'uses' => 'CustomerController@create', 'visible' => true]);
                Route::post('', ['as' => 'store', 'uses' => 'CustomerController@store']);
                Route::get('{id}/show', ['as' => 'show', 'uses' => 'CustomerController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'CustomerController@edit']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'CustomerController@update']);
                Route::match(['get','post'],'/credit_report', ['as' => 'credit_report', 'uses' => 'CustomerController@credit_report', 'custom_label'=>"Customer Credit Report" ,'visible' => true]);
                Route::match(['get','post'],'/payment_report', ['as' => 'payment_report', 'uses' => 'CustomerController@payment_report', 'custom_label'=>"Customer Payment Report" ,'visible' => true]);
                Route::match(['get','post'],'/balance_sheet', ['as' => 'balance_sheet', 'uses' => 'CustomerController@balance_sheet', 'custom_label'=>"Customer Balance Sheet" ,'visible' => true]);
                Route::match(['get','post'],'/add_payment', ['as' => 'add_payment', 'uses' => 'CustomerController@add_payment', 'custom_label'=>"Add Credit Payment" ,'visible' => true]);
                Route::match(['get','post'],'{id}/delete_payment', ['as' => 'delete_payment', 'uses' => 'CustomerController@delete_payment', 'custom_label'=>"Delete Credit Payment"]);
            });

        });


        Route::prefix('stockmanager')->namespace('StockManager')->group(function () {

            Route::prefix('stock')->as('stock.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'StockController@index', 'visible' => true]);
                Route::get('available', ['as' => 'available', 'uses' => 'StockController@available','visible' => true]);
                Route::get('expired', ['as' => 'expired', 'uses' => 'StockController@expired','visible' => true]);
                Route::get('disable', ['as' => 'disable', 'uses' => 'StockController@disabled','visible' => true]);
                //Route::match(['post','get'],'conversion', ['as' => 'convert', 'uses' => 'StockController@conversion_of','visible' => true]);
                Route::get('export', ['as' => 'export', 'uses' => 'StockController@export']);
                Route::get('create', ['as' => 'create', 'uses' => 'StockController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'StockController@store']);
                Route::get('{id}/show', ['as' => 'show', 'uses' => 'StockController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'StockController@edit']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'StockController@update']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'StockController@toggle']);
                Route::match(['get','post'],'{id}/stock_report', ['as' => 'stock_report', 'uses' => 'StockController@stock_report', 'custom_label'=>'Stock Report']);
                //Route::match(['post','get'],'quick', ['as' => 'convert', 'uses' => 'StockController@conversion_of','visible' => true,'custom_label'=>'Quick Adjust Stock Quantity']);
                Route::match(['get','post'], 'quick',['as' => 'quick', 'uses' => 'StockController@quick', 'visible' => true,'custom_label'=>'Quick Adjust Stock Quantity']);

                Route::match(['get','post'], 'export_stock',['as' => 'export_stock', 'uses' => 'StockController@export_stock', 'visible' => true,'custom_label'=>'Export Stock Excel']);
                Route::match(['get','post'], 'import_current_stock',['as' => 'import_current_stock', 'uses' => 'StockController@import_current_stock', 'visible' => true,'custom_label'=>'Import/Existing Stock']);
                Route::match(['get','post'], 'import_new_stock',['as' => 'import_new_stock', 'uses' => 'StockController@import_new_stock', 'visible' => true,'custom_label'=>'Import New Stock']);
                Route::match(['get','post'], 'export_current_stock',['as' => 'export_current_stock', 'uses' => 'StockController@export_current_stock', 'visible' => true,'custom_label'=>'Export Current Stock']);
            });

            Route::prefix('stocklog')->as('stocklog.')->group(function () {
                Route::match(['post','get'],'add_log', ['as' => 'add_log', 'uses' => 'StockController@add_log', 'visible'=>true,'custom_label'=>'Add Stock Log']);
                Route::match(['post','get'],'usage_log_report', ['as' => 'usage_log_report', 'uses' => 'StockController@usage_log_report', 'visible'=>true,'custom_label'=>'Stock Log Report']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'StockController@edit_log']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'StockController@update_log']);
                Route::get('{id}/delete_log', ['as' => 'delete_log', 'uses' => 'StockController@delete_log']);
            });

            Route::prefix('stocktransfer')->as('stocktransfer.')->group(function () {
                Route::match(['post','get'],'add_transfer', ['as' => 'add_transfer', 'uses' => 'StockTransferController@add_transfer', 'visible'=>true,'custom_label'=>'New Stock Transfer']);
                Route::match(['post','get'],'transfer_report', ['as' => 'transfer_report', 'uses' => 'StockTransferController@transfer_report', 'visible'=>true,'custom_label'=>'Stock Transfer Report']);
                Route::get('{id}/delete_transfer', ['as' => 'delete_transfer', 'uses' => 'StockTransferController@delete_transfer']);
                Route::get('{id}/show', ['as' => 'show', 'uses' => 'StockTransferController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'StockTransferController@edit_transfer']);
                Route::get('{id}/complete', ['as' => 'complete', 'uses' => 'StockTransferController@complete']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'StockTransferController@update'] );
                Route::get('{id}/print_afour', ['as' => 'print_afour', 'uses' => 'StockTransferController@print_afour']);
            });


        });

        Route::prefix('invoiceandsales')->namespace('InvoiceAndSales')->group(function () {

            Route::prefix('invoiceandsales')->as('invoiceandsales.')->group(function () {
                Route::get('', ['as' => 'new', 'uses' => 'InvoiceController@new', 'visible' => true]);
                Route::post('create', ['as' => 'create', 'uses' => 'InvoiceController@create']);
                Route::get('draft', ['as' => 'draft', 'uses' => 'InvoiceController@draft', 'visible' => true]);
                Route::get('paid', ['as' => 'paid', 'uses' => 'InvoiceController@paid', 'visible' => true]);
                Route::get('{id}/pos_print', ['as' => 'pos_print', 'uses' => 'InvoiceController@print_pos' ]);
                Route::get('{id}/print_afour', ['as' => 'print_afour', 'uses' => 'InvoiceController@print_afour']);
                Route::get('{id}/print_way_bill', ['as' => 'print_way_bill', 'uses' => 'InvoiceController@print_way_bill']);
                Route::get('{id}/view', ['as' => 'view', 'uses' => 'InvoiceController@view']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'InvoiceController@edit']);
                Route::get('{id}/destroy', ['as' => 'destroy', 'uses' => 'InvoiceController@destroy']);
                Route::put('{id}/update', ['as' => 'update', 'uses' => 'InvoiceController@update']);
                Route::get('/return_invoice', ['as' => 'return_invoice', 'uses' => 'InvoiceController@return_invoice', 'visible' => true, 'custom_label'=>'Return Invoice']);
                Route::post('/add_return_invoice', ['as' => 'add_return_invoice', 'uses' => 'InvoiceController@add_return_invoice',  'custom_label'=>'Create Return Invoice']);

                Route::get('draft_invoice', ['as' => 'draft_invoice', 'uses' => 'InvoiceController@draft_invoice','custom_label'=>'Save Invoice to Draft']);
                Route::get('complete_invoice', ['as' => 'complete_invoice', 'uses' => 'InvoiceController@complete_invoice','custom_label'=>'Save Invoice to Complete']);
                Route::post('{id}/complete_invoice_no_edit', ['as' => 'complete_invoice_no_edit', 'uses' => 'InvoiceController@complete_invoice_no_edit','custom_label'=>'Complete / Pay Invoice from view invoice page']);
            });

            Route::prefix('cashbook')->as('cashbook.')->group(function () {
                Route::match(['get','post'],'', ['as' => 'index', 'uses' => 'CashBookController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'CashBookController@list_all']);
                Route::get('create', ['as' => 'create', 'uses' => 'CashBookController@create', 'visible' => true,'custom_label'=>'Add Cashbook Entry']);
                Route::post('/store', ['as' => 'store', 'uses' => 'CashBookController@store']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'CashBookController@edit']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'CashBookController@update']);
                Route::get('{id}/remove', ['as' => 'destroy', 'uses' => 'CashBookController@destroy']);
            });

        });

        Route::prefix('LaundryManager')->namespace('LaundryManager')->group(function () {

            Route::prefix('laundry')->as('laundry.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'LaundryController@create', 'visible' => true, 'custom_label'=>'New Laundry']);
                Route::get('draft', ['as' => 'draft', 'uses' => 'LaundryController@draft', 'visible' => true]);
                Route::get('paid', ['as' => 'paid', 'uses' => 'LaundryController@paid', 'visible' => true]);
                Route::get('{id}/view', ['as' => 'view', 'uses' => 'LaundryController@view']);
                Route::post('create', ['as' => 'create', 'uses' => 'LaundryController@store']);
                Route::post('{id}/complete_invoice_no_edit', ['as' => 'complete_invoice_no_edit', 'uses' => 'LaundryController@complete_invoice_no_edit','custom_label'=>'Complete / Pay Laundry Invoice from view Laundry page']);
                Route::put('{id}/update', ['as' => 'update', 'uses' => 'LaundryController@update']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'LaundryController@edit']);
                Route::get('{id}/pos_print', ['as' => 'pos_print', 'uses' => 'LaundryController@print_pos' ]);
                Route::get('{id}/print_afour', ['as' => 'print_afour', 'uses' => 'LaundryController@print_afour']);
                Route::get('draft_invoice', ['as' => 'draft_invoice', 'uses' => 'LaundryController@draft_invoice','custom_label'=>'Save Laundry to Draft']);
                Route::get('complete_invoice', ['as' => 'complete_invoice', 'uses' => 'LaundryController@complete_invoice','custom_label'=>'Save Laundry to Complete']);
                Route::match(['post','get'],'/user_monthly', ['as' => 'user_monthly', 'uses' => 'LaundryController@user_monthly', 'visible' => true,'User Monthly Invoice Report']);

                Route::match(['post','get'],'/monthly', ['as' => 'monthly', 'uses' => 'LaundryController@monthly_reports', 'visible' => true,'Monthly Invoice Report']);

            });

            Route::prefix('services')->as('services.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'LaundryServiceController@index', 'visible' => true]);
                Route::get('create', ['as' => 'create', 'uses' => 'LaundryServiceController@create', "custom_label"=>"New Laundry Service"]);
                Route::post('', ['as' => 'store', 'uses' => 'LaundryServiceController@store']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'LaundryServiceController@edit', "custom_label"=>"Update Laundry Service"]);
                Route::put('{id}/update', ['as' => 'update', 'uses' => 'LaundryServiceController@update']);
            });

            Route::prefix('cloths')->as('cloths.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'ClothController@index', 'visible' => true]);
                Route::get('create', ['as' => 'create', 'uses' => 'ClothController@create']);
                Route::post('', ['as' => 'store', 'uses' => 'ClothController@store']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'ClothController@edit']);
                Route::put('{id}/update', ['as' => 'update', 'uses' => 'ClothController@update']);
                Route::get('prices', ['as' => 'prices', 'uses' => 'ClothController@prices', 'visible' => true, "custom_label"=>"Cloth Service Prices"]);
            });



        });


        Route::prefix('expenses')->namespace('Expenses')->group(function () {
            Route::prefix('expenses')->as('expenses.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'ExpensesController@index', 'visible' => true]);
                Route::get('list', ['as' => 'list', 'uses' => 'ExpensesController@listAll']);
                Route::get('create', ['as' => 'create', 'uses' => 'ExpensesController@create', 'visible' => true, "custom_label"=>"New Expenses"]);
                Route::post('', ['as' => 'store', 'uses' => 'ExpensesController@store']);
                Route::get('{id}/show', ['as' => 'show', 'uses' => 'ExpensesController@show']);
                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'ExpensesController@edit']);
                Route::get('{id}/toggle', ['as' => 'toggle', 'uses' => 'ExpensesController@toggle']);
                Route::put('{id}', ['as' => 'update', 'uses' => 'ExpensesController@update']);
                Route::delete('{id}', ['as' => 'destroy', 'uses' => 'ExpensesController@destroy']);
                Route::match(['get','post'],'/expenses_report_by_type', ['as' => 'expenses_report_by_type', 'uses' => 'ExpensesController@expenses_report_by_type', 'visible' => true, "custom_label"=>"Expenses By Type"]);
                Route::match(['get','post'],'/expenses_report_by_department', ['as' => 'expenses_report_by_department', 'uses' => 'ExpensesController@expenses_report_by_department', 'visible' => true, "custom_label"=>"Expenses By Department"]);
            });
        });

        Route::prefix('payment')->namespace('PaymentReport')->group(function () {
            Route::match(['get','post'],'daily_payment_reports', ['as' => 'daily_payment_reports', 'uses' => 'PaymentReportController@daily_payment_reports', 'visible' => true,'custom_label'=>'Daily Report']);
            Route::match(['get','post'],'monthly_payment_reports', ['as' => 'monthly_payment_reports', 'uses' => 'PaymentReportController@monthly_payment_reports', 'visible' => true,'custom_label'=>'Monthly Report']);
            Route::match(['get','post'],'monthly_payment_report_by_method', ['as' => 'monthly_payment_report_by_method', 'uses' => 'PaymentReportController@monthly_payment_report_by_method', 'visible' => true,'custom_label'=>'Monthly Report By Method']);
            Route::match(['get','post'],'payment_analysis', ['as' => 'payment_analysis', 'uses' => 'PaymentReportController@payment_analysis', 'visible' => true,'custom_label'=>'Payment Analysis']);
            Route::match(['get','post'],'income_analysis', ['as' => 'income_analysis', 'uses' => 'PaymentReportController@income_analysis', 'visible' => true,'custom_label'=>'Income Analysis']);
            Route::match(['get','post'],'income_analysis_by_department', ['as' => 'income_analysis_by_department', 'uses' => 'PaymentReportController@income_analysis_by_department', 'visible' => true,'custom_label'=>'Income Analysis By Depart.']);
            Route::match(['get','post'],'monthly_payment_reports_by_customer', ['as' => 'monthly_payment_reports_by_customer', 'uses' => 'PaymentReportController@monthly_payment_reports_by_customer', 'visible' => true,'custom_label'=>'Monthly Report By Customer']);
            Route::match(['get','post'],'monthly_payment_report_by_method_by_customer', ['as' => 'monthly_payment_report_by_method_by_customer', 'uses' => 'PaymentReportController@monthly_payment_report_by_method_by_customer', 'visible' => true,'custom_label'=>'Monthly Report By Customer and Method']);

        });
        /*
        if(config('app.store') == "hotel") {
            Route::prefix('receptionist')->namespace('Receptionist')->group(function () {

                Route::prefix('room')->as('room.')->group(function () {
                    Route::get('', ['as' => 'index', 'uses' => 'RoomController@index', 'visible' => true]);
                    Route::get('list', ['as' => 'list', 'uses' => 'RoomController@listAll']);
                    Route::get('create', ['as' => 'create', 'uses' => 'RoomController@create']);
                    Route::post('', ['as' => 'store', 'uses' => 'RoomController@store']);
                    Route::get('{id}', ['as' => 'show', 'uses' => 'RoomController@show']);
                    Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'RoomController@edit']);
                    Route::put('{id}', ['as' => 'update', 'uses' => 'RoomController@update']);
                    Route::get('{id}/delete', ['as' => 'destroy', 'uses' => 'RoomController@destroy']);
                });

                Route::prefix('bookings_and_reservation')->as('bookings_and_reservation.')->group(function () {
                    Route::get('', ['as' => 'index', 'uses' => 'BookingsController@index', 'visible' => true, 'custom_label' => "Bookings and Reservation"]);
                    Route::get('list', ['as' => 'list', 'uses' => 'BookingsController@listAll']);
                    Route::get('create', ['as' => 'create', 'uses' => 'BookingsController@create', 'visible' => true, 'custom_label' => "New Booking / Reservation"]);
                    Route::post('', ['as' => 'store', 'uses' => 'BookingsController@store']);
                    Route::get('{id}/show', ['as' => 'show', 'uses' => 'BookingsController@show']);
                    Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'BookingsController@edit']);
                    Route::put('{id}', ['as' => 'update', 'uses' => 'BookingsController@update']);
                    Route::get('{id}/delete', ['as' => 'destroy', 'uses' => 'BookingsController@destroy']);
                    Route::match(['get', 'post'], '{id}/make_payment', ['as' => 'make_payment', 'uses' => 'BookingsController@make_payment', 'custom_label' => "Add Reservation Payment"]);
                    Route::match(['get', 'post'], '{id}/check_out', ['as' => 'check_out', 'uses' => 'BookingsController@check_out', 'custom_label' => "Check Out Guest"]);

                    Route::match(['get', 'post'], 'daily_report', ['as' => 'daily_report', 'uses' => 'BookingsController@daily_report', 'visible' => true, 'custom_label' => "Booking and Reservation Daily Report"]);
                    Route::match(['get', 'post'], 'monthly_report', ['as' => 'monthly_report', 'uses' => 'BookingsController@monthly_report', 'visible' => true, 'custom_label' => "Booking and Reservation Monthly Report"]);

                });


            });
        }
        */
        Route::prefix('purchaseorders')->namespace('PurchaseOrders')->group(function () {
            Route::prefix('purchaseorders')->as('purchaseorders.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'PurchaseOrder@index', 'visible' => true]);
                Route::get('{id}/show', ['as' => 'show', 'uses' => 'PurchaseOrder@show']);
                Route::get('create', ['as' => 'create', 'uses' => 'PurchaseOrder@create','visible' => true]);
                Route::post('store', ['as' => 'store', 'uses' => 'PurchaseOrder@store']);
                Route::get('{id}/remove', ['as' => 'destroy', 'uses' => 'PurchaseOrder@destroy']);
                Route::match(['post','get'],'/daily', ['as' => 'daily', 'uses' => 'PurchaseOrder@daily', 'visible' => true]);
                Route::match(['post','get'],'/monthly', ['as' => 'monthly', 'uses' => 'PurchaseOrder@monthly', 'visible' => true]);

                Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'PurchaseOrder@edit']);
                Route::get('{id}/markAsComplete', ['as' => 'markAsComplete', 'uses' => 'PurchaseOrder@markAsComplete', 'custom_label'=>'Complete Purchase Order']);
                Route::put('{id}/update', ['as' => 'update', 'uses' => 'PurchaseOrder@update']);


                Route::match(['get','post'],'/credit_report', ['as' => 'credit_report', 'uses' => 'PurchaseOrder@credit_report', 'custom_label'=>"Supplier Credit Report" ,'visible' => true]);
                Route::match(['get','post'],'/payment_report', ['as' => 'payment_report', 'uses' => 'PurchaseOrder@payment_report', 'custom_label'=>"Supplier Payment Report" ,'visible' => true]);
                Route::match(['get','post'],'/balance_sheet', ['as' => 'balance_sheet', 'uses' => 'PurchaseOrder@balance_sheet', 'custom_label'=>"Supplier Balance Sheet" ,'visible' => true]);
                Route::match(['get','post'],'/add_payment', ['as' => 'add_payment', 'uses' => 'PurchaseOrder@add_payment', 'custom_label'=>"Add Payment" ,'visible' => true]);
            });
        });

        Route::prefix('invoicereport')->namespace('InvoiceReport')->group(function () {
            Route::prefix('invoicereport')->as('invoicereport.')->group(function () {
                Route::match(['post','get'],'', ['as' => 'index', 'uses' => 'InvoiceReportController@daily', 'visible' => true]);
                Route::match(['post','get'],'/monthly', ['as' => 'monthly', 'uses' => 'InvoiceReportController@monthly', 'visible' => true]);
                Route::match(['post','get'],'/customer_monthly', ['as' => 'customer_monthly', 'uses' => 'InvoiceReportController@customer_monthly', 'visible' => true]);
                Route::match(['post','get'],'/user_monthly', ['as' => 'user_monthly', 'uses' => 'InvoiceReportController@user_monthly', 'visible' => true,'User Monthly Invoice Report']);
                Route::match(['post','get'],'/product_monthly', ['as' => 'product_monthly', 'uses' => 'InvoiceReportController@product_monthly', 'visible' => true]);
                Route::match(['post','get'],'/sales_analysis', ['as' => 'sales_analysis', 'uses' => 'InvoiceReportController@sales_analysis','custom_label'=>'Sales Analysis' ,'visible' => true]);
                Route::match(['post','get'],'/return_logs', ['as' => 'return_logs', 'uses' => 'InvoiceReportController@return_logs','custom_label'=>'Sales Return' ,'visible' => true]);
                Route::match(['post','get'],'/full_invoice_report', ['as' => 'full_invoice_report', 'uses' => 'InvoiceReportController@full_invoice_report','custom_label'=>'Complete Invoice Report' ,'visible' => true]);
            });
        });


        /*
        Route::prefix('stockcounting')->namespace('StockCounting')->group(function () {

            Route::prefix('counting')->as('counting.')->group(function () {
                Route::get('', ['as' => 'index', 'uses' => 'StockCountingController@index', 'visible' => true, 'custom_label'=>'List Stock Counting']);
                Route::get('create', ['as' => 'create', 'uses' => 'StockCountingController@create', 'visible' => true, 'custom_label'=>'New Stock Counting']);
                Route::post('', ['as' => 'store', 'uses' => 'StockCountingController@store']);
                Route::get('{id}/show', ['as' => 'show', 'uses' => 'StockCountingController@show']);
                Route::get('{id}/delete', ['as' => 'destroy', 'uses' => 'StockCountingController@destroy']);
                Route::get('{id}/export_excel', ['as' => 'export_excel', 'uses' => 'StockCountingController@export_excel']);
                Route::match(['get','post'],'{id}/import_excel', ['as' => 'import_excel', 'uses' => 'StockCountingController@import_excel']);
            });

        });
        */


    });

});
