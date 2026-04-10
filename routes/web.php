<?php

use App\Http\Controllers\AccountManagerAuthController;
use App\Http\Controllers\AccountManagerController;
use App\Http\Controllers\dashboard;
use App\Http\Controllers\fund;
use App\Http\Controllers\Login;
use App\Http\Controllers\GiveawayLoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\profile;
use App\Http\Controllers\package;
use App\Http\Controllers\member;
use App\Http\Controllers\Register;
use App\Http\Controllers\Withdraw;
use App\Http\Controllers\transfer;
use App\Http\Controllers\internaltransfer;
use App\Http\Controllers\sponsorpackage;
use App\Http\Controllers\deposithistory;
use App\Http\Controllers\lighthistory;
use App\Http\Controllers\withdrawhistory;
use App\Http\Controllers\bonushistory;
use App\Http\Controllers\rechargepurchase;
use App\Http\Controllers\lightController;
use App\Http\Controllers\rechargeprinting;
use App\Http\Controllers\datapurchase;
use App\Http\Controllers\dataShare;
use App\Http\Controllers\dstvSub;
use App\Http\Controllers\cardprinting;
use App\Http\Controllers\logout;
use App\Http\Controllers\support;
use App\Http\Controllers\serviceshistory;
use App\Http\Controllers\packagehistory;
use App\Http\Controllers\pointhistory;
use App\Http\Controllers\cardhistory;
use App\Http\Controllers\leaderboard;
use App\Http\Controllers\residualincome;
use App\Http\Controllers\annualshare;
use App\Http\Controllers\samplecards;
use App\Http\Controllers\preordercard;
use App\Http\Controllers\resetpassword;
use App\Http\Controllers\passwordemail;
use App\Http\Controllers\BulkSMSController;
use App\Http\Controllers\CronController;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\regadminController;
use App\Http\Controllers\loginadminController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\adminprofileController;
use App\Http\Controllers\adminuserController;
use App\Http\Controllers\edituser;
use App\Http\Controllers\adminfundController;
use App\Http\Controllers\adminbonushistory;
use App\Http\Controllers\addinterestController;
use App\Http\Controllers\transactions;
use App\Http\Controllers\adminRC;
use App\Http\Controllers\adminRP;
use App\Http\Controllers\adminDP;
use App\Http\Controllers\adminE;
use App\Http\Controllers\adminC;
use App\Http\Controllers\usedcardhistory;
use App\Http\Controllers\addfund;
use App\Http\Controllers\promoadmincontroller;
use App\Http\Controllers\createpin;
use App\Http\Controllers\adminBuyCard;
use App\Http\Controllers\adminBuyData;
use App\Http\Controllers\adminBuyLight;
use App\Http\Controllers\adminsupport;
use App\Http\Controllers\addcard;
use App\Http\Controllers\AddBonusController;
use App\Http\Controllers\adminpreorder;
use App\Http\Controllers\cardcount;
use App\Http\Controllers\adminwithController;
use App\Http\Controllers\transferhistory;
use App\Http\Controllers\adminlogoutController;
use App\Http\Controllers\adminpackage;
use App\Http\Controllers\AdminPointController;
use App\Http\Controllers\AdminPointHistoryController;
use App\Http\Controllers\totalTransaction;
use App\Http\Controllers\edittransaction;
use App\Http\Controllers\alladmin;
use App\Http\Controllers\EditPointController;
use App\Http\Controllers\AdminGiveawayController;
use App\Http\Controllers\WebhookController;

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

Route::get('/', function () {
    
    return view('home.home');
})->name('home');

Route::get('/aboutus', function () {
    return view('home.aboutus');
})->name('aboutus');

Route::get('/learn', function () {
    return redirect('https://learn.abovemarts.com');
})->name('aboutus');

Route::get('/services', function () {
    return view('home.services');
})->name('services');

Route::get('/packages', function () {
    return view('home.packages');
})->name('packages');

Route::get('/contact', function () {
    return view('home.contact');
})->name('contact');

Route::get('/faqs', function () {
    return view('home.questions');
})->name('faqs');

Route::get('/terms-conditions', function () {
    return view('home.terms');
})->name('terms');

Route::get('/comingsoon', function () {
    return view('user.comingsoon');
})->name('comingsoon');

Route::get('/lifestylepension', function () {
    return view('user.lifestylepension');
})->name('lifestylepension');


Route::view('/custom-419-error', 'home.404');

Route::get('/run-cron-jobs',  [CronController::class, "runCronJobs"]);


Route::get('/register', [Register::class, "index"])->name('register');
Route::post('/register', [Register::class, "store"])->name('register');

Route::get('/login', [Login::class, "index"])->name('login');
Route::post('/login', [Login::class, "store"])->name('login');

// Login form
Route::get('/account-manager/login', [AccountManagerAuthController::class, 'showLoginForm'])->name('account-manager.login');
// Login submit
Route::post('/account-manager/login', [AccountManagerAuthController::class, 'login'])->name('account-manager.login.submit');
// Logout
Route::post('/account-manager/logout', [AccountManagerAuthController::class, 'logout'])->name('account-manager.logout');

// Account Manager
Route::middleware(['account_manager'])->prefix('account-manager')->group(function () {
    Route::get('/dashboard', [AccountManagerController::class, 'dashboard'])->name('account-manager.dashboard');
    Route::get('/users', [AccountManagerController::class, 'assignedUsers'])->name('account-manager.users');
    Route::get('/users/{id}/transactions', [AccountManagerController::class, 'userTransactions'])->name('account-manager.user.transactions');
});

Route::middleware(['admin'])->prefix('account-managers')->group(function () {
    Route::get('/', [AccountManagerController::class, 'index'])->name('allmanagers');
    Route::get('/create', [AccountManagerController::class, 'create'])->name('createaccountmanagers');
    Route::post('/', [AccountManagerController::class, 'store'])->name('storeaccountmanagers');
    Route::get('/edit', [AccountManagerController::class, 'editindex'])->name('editaccountmanagers');
    Route::post('/update', [AccountManagerController::class, 'update'])->name('updateaccountmanagers');
    Route::delete('/{id}', [AccountManagerController::class, 'destroy'])->name('destroyaccount-managers');
    Route::get('/managers/{id}/users', [AccountManagerController::class, 'viewUsersOfManager'])->name('managers.users');

    Route::get('/assign-users', [AccountManagerController::class, 'assignUsersEqually'])->name('assignUsersEqually');
    Route::post('/assign-users', [AccountManagerController::class, 'assignUsers'])->name('assign');
    Route::get('/reassign-user', [AccountManagerController::class, 'reassign'])->name('reassignindex');
    Route::post('/reassign', [AccountManagerController::class, 'reassignUser'])->name('reassign');
});
Route::get('/resetpassword', [resetpassword::class, 'index'])->name('reset');
Route::post('/resetpassword', [resetpassword::class, 'update'])->name('reset');

Route::get('/passwordrecovery', [passwordemail::class, 'index'])->name('passwordemail');
Route::post('/passwordrecovery', [passwordemail::class, 'email'])->name('passwordemail');

Route::get('/dashboard', [dashboard::class, "index"])->name('dashboard');

Route::get('/profile', [profile::class, "index"])->name('profile');
Route::post('/profile', [profile::class, "updateprofile"])->name('profile');
Route::post('/profilephoto', [profile::class, "photoupdate"])->name('profileimage');
Route::post('/profilepass', [profile::class, "updatepassword"])->name('profilepass');
Route::post('/profilesponsor', [profile::class, "updatesponsor"])->name('profilesponsor');

Route::get('/fund', [fund::class, "index"])->name('fund');

Route::get('/payment/callback', [PaymentController::class, 'handleGatewayCallback']);
Route::post('/pay', [PaymentController::class, 'redirectToGateway'])->name('pay');
Route::post('/manualpay', [PaymentController::class, 'manualpay'])->name('manualpay');
Route::post('/accountnumber', [PaymentController::class, 'getAccount'])->name('getAccount');

// Route::post('/webhook', [PaymentWebhookController::class, 'handleWebhook'])->name('handleWebhook');
// Route::get('/webhook', [PaymentWebhookController::class, 'index'])->name('handleWebhook');
Route::post('/webhook/data-purchase', [WebhookController::class, 'handleDataPurchase']);



Route::get('/userpackages', [package::class, "index"])->name('userpackage');
Route::post('/userpackages', [package::class, "store"])->name('userpackage');

Route::get('/withdraw', [Withdraw::class, "index"])->name('withdraw');
Route::post('/withdraw', [Withdraw::class, "store"])->name('withdraw');

Route::get('/sponsorpackage', [sponsorpackage::class, "index"])->name('sponsorpackage');

Route::get('/teammembers', [member::class, "index"])->name('member');
Route::get('/teammembers', [member::class, 'search'])->name('member');

Route::get('/deposithistory', [deposithistory::class, "index"])->name('deposithistory');
Route::get('/withdrawhistory', [withdrawhistory::class, "index"])->name('withdrawhistory');
Route::get('/bonushistory', [bonushistory::class, "index"])->name('bonushistory');
Route::get('/serviceshistory', [serviceshistory::class, "index"])->name('serviceshistory');
Route::get('/packagehistory', [packagehistory::class, "index"])->name('packagehistory');
Route::get('/pointhistory', [pointhistory::class, "index"])->name('pointhistory');
Route::get('/lighthistory', [lighthistory::class, "index"])->name('lighthistory');

Route::get('/transfer', [transfer::class, 'index'])->name('transfer');
Route::post('/transfer', [transfer::class, 'store'])->name('transfer');

Route::get('/member-transfer', [internaltransfer::class, 'index'])->name('membertransfer');
Route::post('/member-transfer', [internaltransfer::class, 'store'])->name('membertransfer');

Route::get('/rechargepurchase', [rechargepurchase::class, 'index'])->name('rechargepurchase');
Route::post('/rechargepurchase', [rechargepurchase::class, 'store'])
    ->middleware('preventDuplicates')
    ->name('rechargepurchases');

Route::get('/datapurchase', [datapurchase::class, 'index'])->name('datapurchase');
Route::post('/datapurchase', [datapurchase::class, 'store'])->name('datapurchase');

Route::get('/electricitypurchase', [lightController::class, 'index'])->name('lightpurchase');
Route::post('/electricitypurchase', [lightController::class, 'store'])
    ->middleware('preventDuplicates')
    ->name('lightpurchase');
Route::get('/verify', [lightController::class, 'verify'])->name('verify');
Route::post('/verifystore', [lightController::class, 'verifystore'])->name('verifystore');
Route::get('/token', [lightController::class, 'token'])->name('token');

Route::get('/rechargeprinting', [rechargeprinting::class, 'index'])->name('rechargeprinting');
Route::post('/rechargeprinting', [rechargeprinting::class, 'store'])->name('rechargeprinting');

Route::get('/preordercard', [preordercard::class, 'index'])->name('preordercard');
Route::post('/preordercard', [preordercard::class, 'store'])->name('preordercard');

Route::get('/cardhistory', [cardhistory::class, 'index'])->name('cardhistory');
Route::post('/cardhistory', [cardhistory::class, 'store'])->name('cardhistory');
Route::get('/cardprintinghistory', [cardhistory::class, 'store'])->name('cardprintinghistory');

Route::get('/datashare', [dataShare::class, 'index'])->name('datashare');
Route::post('/datashare', [dataShare::class, 'store'])
    ->middleware('preventDuplicates')
    ->name('datashare');

Route::get('/samplecards', [samplecards::class, 'index'])->name('samplecards');
Route::post('/samplecards', [samplecards::class, 'store'])->name('samplecards');

Route::get('/support', [support::class, 'index'])->name('support');
Route::post('/support', [support::class, 'store'])->name('support');

Route::get('/tvsub', [dstvSub::class, 'index'])->name('tvsub');
Route::post('/tvsub', [dstvSub::class, 'store'])
    ->middleware('preventDuplicates')
    ->name('tvsub');
Route::post('/verifycable', [dstvSub::class, 'verifycable'])->name('verifycable');
Route::get('/verifycable', [dstvSub::class, 'verify'])->name('verifycable');

Route::get('/cardprinting', [cardprinting::class, 'index'])->name('cardprinting');

Route::get('/leaderboard', [leaderboard::class, 'index'])->name('leaderboard');
Route::get('/residualincome', [residualincome::class, 'index'])->name('residualincome');
Route::get('/annualshare', [annualshare::class, 'index'])->name('annualshare');

Route::get('/logout', [logout::class, 'logout'])->name('logout');

// Sms Routes
Route::any('/sms', [BulkSMSController::class, 'index'])->name('smshome');
Route::get('/contact_group', [BulkSMSController::class, 'contactGroup'])->name('contact_group');
Route::get('/view_group/{id}', [BulkSMSController::class, 'viewGroup'])->name('view_group');
Route::get('/smstransactions', [BulkSMSController::class, 'transactions'])->name('transactions');
Route::get('/view_details/{id}', [BulkSMSController::class, 'viewDetails'])->name('view_details');
Route::get('/delete_group/{id}', [BulkSMSController::class, 'deleteGroup'])->name('delete_group');
Route::post('/saveContacts', [BulkSMSController::class, 'saveContacts'])->name('saveContacts');
Route::post('/submitSMSForm', [BulkSMSController::class, 'submitSMSForm'])->name('submitSMSForm');
Route::post('/sendSMS2', [BulkSMSController::class, 'sendSMS2'])->name('sendSMS2');
Route::any('/resend_sms/{id}', [BulkSMSController::class, 'resendSMS'])->name('resend_sms');

// Admin Routes
Route::get('/adminregister', [regadminController::class, 'index'])->name('adminregister');
Route::post('/adminregister', [regadminController::class, 'store'])->name('adminregister');

Route::get('/adminlogin', [loginadminController::class, 'index'])->name('adminlogin');
Route::post('/adminlogin', [loginadminController::class, 'store'])->name('adminlogin');

Route::any('/adminabovefinexhub', [adminController::class, 'index'])->name('admin');
Route::any('/webmaster', [adminController::class, 'index'])->name('admin');

Route::get('/admin/profile', [adminprofileController::class, 'index'])->name('adminprofile');
Route::post('/admin/profile', [adminprofileController::class, 'updatewallet'])->name(
    'adminprofile'
);
Route::post('/admin/profilepass', [adminprofileController::class, 'updateadminpassword'])->name(
    'adminprofilepass'
);

Route::get('/admin/user', [adminuserController::class, 'index'])->name('adminusers');
Route::get('/admin/usersearch', [adminuserController::class, 'search'])->name('usersearch');
Route::get('/admin/users', [adminuserController::class, 'exportToCSV'])->name('exportusers');

Route::get('/edituser', [edituser::class, 'index'])->name('edituser');
Route::post('/edituser', [edituser::class, 'store'])->name('edituser');
Route::post('/edituserpassword', [edituser::class, 'updatepassword'])->name('edituserpassword');

Route::get('/admin/userpoint', [AdminPointController::class, 'index'])->name('adminuserspoint');
Route::get('/admin/usersearchpoint', [AdminPointController::class, 'search'])->name('usersearchpoint');
Route::get('/admin/userspoint', [AdminPointController::class, 'exportToCSV'])->name('exportuserspoint');

Route::get('/edituserpoint', [EditPointController::class, 'index'])->name('edituserpoint');
Route::post('/edituserpoint', [EditPointController::class, 'store'])->name('edituserpoint');

Route::get('/admin/walletfunds', [adminfundController::class, 'index'])->name('adminfunding');
Route::get('/admin/walletfunds', [adminfundController::class, 'search'])->name('adminfunding');

Route::get('/admin/package', [adminpackage::class, 'index'])->name('adminpackage');
Route::get('/admin/package', [adminpackage::class, 'search'])->name('adminpackage');
Route::get('/admin/packages', [adminpackage::class, 'exportToCSV'])->name('exportpackage');

Route::get('/admin/bonushistory', [adminbonushistory::class, 'index'])->name('adminbonushistory');
Route::get('/admin/bonushistory', [adminbonushistory::class, 'search'])->name('adminbonushistory');
Route::get('/admin/bonushistorys', [adminbonushistory::class, 'exportToCSV'])->name('exportbonus');

Route::get('/admin/pointhistory', [AdminPointHistoryController::class, 'index'])->name('adminpointhistory');
Route::get('/admin/pointhistory', [AdminPointHistoryController::class, 'search'])->name('adminpointhistory');

Route::get('/admin/giveaway', [AdminGiveawayController::class, 'index'])->name('admingiveaway');
Route::get('/admin/giveawaysearch', [AdminGiveawayController::class, 'search'])->name('admingiveawaysearch');

Route::get('/admin/transactions', [transactions::class, 'index'])->name('transactions');
Route::get('/admin/transactions', [transactions::class, 'search'])->name('transactions');
Route::get('/admin/transaction', [transactions::class, 'exportToCSV'])->name('exporttransaction');

Route::get('/admin/rechargepurchase', [adminRC::class, 'index'])->name('adminRC');
Route::get('/admin/rechargepurchase', [adminRC::class, 'search'])->name('adminRC');

Route::get('/admin/rechargeprinting', [adminRP::class, 'index'])->name('adminRP');
Route::get('/admin/rechargeprinting', [adminRP::class, 'search'])->name('adminRP');

Route::get('/admin/datapurchase', [adminDP::class, 'index'])->name('adminDP');
Route::get('/admin/datapurchase', [adminDP::class, 'search'])->name('adminDP');

Route::get('/admin/electricity', [adminE::class, 'index'])->name('adminE');
Route::get('/admin/electricity', [adminE::class, 'search'])->name('adminE');

Route::get('/admin/cables', [adminC::class, 'index'])->name('adminC');
Route::get('/admin/cables', [adminC::class, 'search'])->name('adminC');

Route::get('/admin/cardcount', [cardcount::class, 'index'])->name('cardcount');

Route::get('/admin/interest', [addinterestController::class, 'index'])->name('addinterest');
Route::post('/admin/interest', [addinterestController::class, 'store'])->name('addinterest');

Route::get('/admin/edittransaction', [edittransaction::class, 'index'])->name('edittransaction');
Route::post('/admin/edittransaction', [edittransaction::class, 'store'])->name('edittransaction');

Route::get('admin/walletfund', [addfund::class, 'index'])->name('walletfund');
Route::post('admin/walletfund', [addfund::class, 'store'])->name('walletfund');

Route::get('admin/addcard', [addcard::class, 'index'])->name('addcard');
Route::post('admin/addcard', [addcard::class, 'store'])->name('addcard');

Route::get('/admin/promo', [promoadmincontroller::class, 'index'])->name('adminpromo');
Route::post('/admin/promo', [promoadmincontroller::class, 'store'])->name('adminpromo');

Route::get('admin/createpin', [createpin::class, 'index'])->name('createpin');
Route::post('admin/createpin', [createpin::class, 'store'])->name('createpin');

Route::get('admin/addbonus', [AddBonusController::class, 'index'])->name('addbonus');
Route::post('admin/addbonus', [AddBonusController::class, 'store'])->name('addbonus');

Route::get('admin/adminbuycard', [adminBuyCard::class, 'index'])->name('adminbuycard');
Route::post('admin/adminbuycard', [adminBuyCard::class, 'store'])->name('adminbuycard');

Route::get('admin/adminbuydata', [adminBuyData::class, 'index'])->name('adminbuydata');
Route::post('admin/adminbuydata', [adminBuyData::class, 'store'])->name('adminbuydata');

Route::get('admin/adminbuylight', [adminBuyLight::class, 'index'])->name('adminbuylight');
Route::post('admin/adminbuylight', [adminBuyLight::class, 'store'])->name('adminbuylight');

Route::get('/admin/support', [adminsupport::class, 'index'])->name('adminsupport');
Route::post('/admin/support', [adminsupport::class, 'sendMail'])->name('adminsupport');

Route::get('/admin/preorder', [adminpreorder::class, 'index'])->name('adminpreorder');

Route::get('/admin/usedcard', [usedcardhistory::class, 'index'])->name('usedcardhistory');
Route::get('/admin/usedcard', [usedcardhistory::class, 'search'])->name('usedcardhistory');

Route::get('/admin/withdraw', [adminwithController::class, 'index'])->name('adminwithdraw');

Route::get('/admin/transfer', [transferhistory::class, 'index'])->name('transferhistory');

Route::get('/admin/totaltransaction', [totalTransaction::class, 'index'])->name('totaltransaction');

Route::get('/admin/totaltransaction', [totalTransaction::class, 'search'])->name(
    'totaltransaction'
);

Route::get('/admin/alladmin', [alladmin::class, 'index'])->name('alladmin');

Route::get('/adminlogout', [adminlogoutController::class, 'logout'])->name('adminlogout');


//Abovemart GiveAway
Route::get('/giveawaylogin', [GiveawayLoginController::class, "index"])->name('giveawaylogin');
Route::post('/giveawaylogin', [GiveawayLoginController::class, "store"])->name('giveawaylogin');


Route::get('/create-giveaway', [App\Http\Controllers\GiveawayLoginController::class, 'fun_giveaway_data'])->name('fun_giveaway_data')->middleware('auth');
Route::get('/my-giveaway', [App\Http\Controllers\GiveawayLoginController::class, 'my_giveaway'])->name('my_giveaway')->middleware('auth');
Route::get('/giveaway_participant/{id}', [App\Http\Controllers\GiveawayLoginController::class, 'giveaway_participants'])->name('giveaway_participants')->middleware('auth');
Route::get('/delete_giveaway/{slug}', [App\Http\Controllers\GiveawayLoginController::class, 'delete_giveaway'])->name('delete_giveaway')->middleware('auth');
Route::post('/createDataGiveaway', [App\Http\Controllers\GiveawayLoginController::class, 'createDataGiveaway'])->name('createDataGiveaway')->middleware('auth');
Route::post('/saveGiveAwayContacts', [App\Http\Controllers\GiveawayLoginController::class, 'saveGiveAwayContacts'])->name('saveGiveAwayContacts');

//Route for vouchers 
Route::get('/create-voucher', [App\Http\Controllers\VoucherController::class, 'create_voucher'])->name('create_vouchser')->middleware('auth');
Route::post('/store-voucher', [App\Http\Controllers\VoucherController::class, 'store_voucher'])->name('store_voucher')->middleware('auth');
Route::get('/buy-voucher', [App\Http\Controllers\VoucherController::class, 'buy_voucher'])->name('buy_voucher')->middleware('auth');
Route::get('/buy-vouchers', [App\Http\Controllers\VoucherController::class, 'buy_voucher'])->name('buy_voucher')->middleware('auth');

Route::get('/my-vouchers', [App\Http\Controllers\VoucherController::class, 'my_vouchers'])->name('my_vouchers')->middleware('auth');

Route::post('/purchase-voucher', [App\Http\Controllers\VoucherController::class, 'purchase_voucher'])->name('purchase_voucher')->middleware('auth');
Route::get('/manage-vouchers', [App\Http\Controllers\VoucherController::class, 'manage_vouchers'])->name('manage_vouchers')->middleware('auth');
Route::post('/updateRate', [App\Http\Controllers\VoucherController::class, 'updateRate'])->name('updateRate')->middleware('auth');
Route::get('/delete_voucher/{id}', [App\Http\Controllers\VoucherController::class, 'delete_voucher'])->name('delete_voucher')->middleware('auth');

Route::get('/{slug}', [App\Http\Controllers\GiveawayLoginController::class, 'giveawayHome'])->name('giveawayHome');


    //End Fun Giveaway