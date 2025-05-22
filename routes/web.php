<?php

use App\Models\Role;
use App\Models\SideMenue;
use App\Models\Permission;
use App\Models\UserRolePermission;
use App\Models\SideMenuHasPermission;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RolePermissionController;

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
/*Admin routes
 * */

Route::get('/admin', [AuthController::class, 'getLoginPage']);
Route::post('/login', [AuthController::class, 'Login']);
Route::get('/admin-forgot-password', [AdminController::class, 'forgetPassword']);
Route::post('/admin-reset-password-link', [AdminController::class, 'adminResetPasswordLink']);
Route::get('/change_password/{id}', [AdminController::class, 'change_password']);
Route::post('/admin-reset-password', [AdminController::class, 'ResetPassword']);

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'getdashboard']);
    Route::get('profile', [AdminController::class, 'getProfile']);
    Route::post('update-profile', [AdminController::class, 'update_profile']);

    // ############ Privacy-policy #################
    Route::get('privacy-policy', [SecurityController::class, 'PrivacyPolicy'])->middleware('check.permission:privacypolicy,view');
    Route::get('privacy-policy-edit', [SecurityController::class, 'PrivacyPolicyEdit'])->middleware('check.permission:privacypolicy,edit');
    Route::post('privacy-policy-update', [SecurityController::class, 'PrivacyPolicyUpdate']) ->middleware('check.permission:privacypolicy,edit');
    Route::get('privacy-policy-view', [SecurityController::class, 'PrivacyPolicyView']) ->middleware('check.permission:privacypolicy,view');

    // ############ Role Permissions #################

    // Route::get('roles-permission', [RolePermissionController::class, 'index'])->name('role-permission')->middleware('check.permission:role,view');



            // ############ Roles #################

        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('check.permission:role,view');

        Route::get('/roles-create', [RoleController::class, 'create'])->name('create.role')->middleware('check.permission:role,create');

        Route::post('/store-role', [RoleController::class, 'store'])->name('store.role')->middleware('check.permission:role,create');


        Route::get('/roles-permissions/{id}', [RoleController::class, 'permissions'])->name('role.permissions')->middleware('check.permission:role,create');


        //////////////////////////////////////////
        Route::post('/admin/roles/{id}/permissions/store', [RoleController::class, 'storePermissions'])->name('roles.permissions.store')->middleware('check.permission:role,create');


        Route::delete('/delete-role/{id}', [RoleController::class, 'delete'])->name('delete.role')->middleware('check.permission:role,delete');

    

    // ############ Term & Condition #################
    Route::get('term-condition', [SecurityController::class, 'TermCondition']) ->middleware('check.permission:termcondition,view');
    Route::get('term-condition-edit', [SecurityController::class, 'TermConditionEdit']) ->middleware('check.permission:termcondition,edit');
    Route::post('term-condition-update', [SecurityController::class, 'TermConditionUpdate']) ->middleware('check.permission:termcondition,edit');
    Route::get('term-condition-view', [SecurityController::class, 'TermConditionView']) ->middleware('check.permission:termcondition,view');

    // ############ About Us #################
    Route::get('about-us', [SecurityController::class, 'AboutUs']) ->middleware('check.permission:aboutus,view');
    Route::get('about-us-edit', [SecurityController::class, 'AboutUsEdit']) ->middleware('check.permission:aboutus,edit');
    Route::post('about-us-update', [SecurityController::class, 'AboutUsUpdate']) ->middleware('check.permission:aboutus,edit');
    Route::get('about-us-view', [SecurityController::class, 'AboutUsView']) ->middleware('check.permission:aboutus,view');

    Route::get('logout', [AdminController::class, 'logout']);

        // ############ Faq #################
    Route::get('faq-index', [FaqController::class, 'Faq'])->middleware('check.permission:faq,view');
    Route::get('faq-edit/{id}', [FaqController::class, 'FaqsEdit'])->name('faq.edit') ->middleware('check.permission:faq,edit');
    Route::post('faq-update/{id}', [FaqController::class, 'FaqsUpdate'])->middleware('check.permission:faq,edit');
    Route::get('faq-view', [FaqController::class, 'FaqView']) ->middleware('check.permission:faq,view');
    Route::get('faq-create', [FaqController::class, 'Faqscreateview']) ->middleware('check.permission:faq,create');
    Route::post('faq-store', [FaqController::class, 'Faqsstore']) ->middleware('check.permission:faq,create');
    Route::delete('faq-destroy/{id}', [FaqController::class, 'faqdelete'])->name('faq.destroy') ->middleware('check.permission:faq,delete');
    Route::post('/faqs/reorder', [FaqController::class, 'reorder'])->name('faq.reorder');

    // ############ Users #################

    Route::get('/user', [UserController::class, 'Index'])->name('user.index') ->middleware('check.permission:users,view');
Route::get('/user-create', [UserController::class, 'createview'])->name('user.createview') ->middleware('check.permission:users,create');
Route::post('/user-store', [UserController::class, 'create'])->name('user.create') ->middleware('check.permission:users,create');
Route::get('/user-edit/{id}', [UserController::class, 'edit'])->name('user.edit') ->middleware('check.permission:users,edit');
Route::post('/user-update/{id}', [UserController::class, 'update'])->name('user.update') ->middleware('check.permission:users,edit');
Route::delete('/users-destory/{id}', [UserController::class, 'delete'])->name('user.delete') ->middleware('check.permission:users,delete');
Route::get('/users/trashed', [UserController::class, 'trashed']);
Route::post('/users/{id}/restore', [UserController::class, 'restore']);
Route::delete('/users/{id}/force', [UserController::class, 'forceDelete'])->name('user.forceDelete') ->middleware('check.permission:users,delete');

Route::post('/users/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggle-status');


    // ############ Sub Admin #################
    Route::controller(SubAdminController::class)->group(function () {
        Route::get('/subadmin',  'index')->name('subadmin.index') ->middleware('check.permission:subadmin,view');
        Route::get('/subadmin-create',  'create')->name('subadmin.create') ->middleware('check.permission:subadmin,create');
        Route::post('/subadmin-store',  'store')->name('subadmin.store') ->middleware('check.permission:subadmin,create');
        Route::get('/subadmin-edit/{id}',  'edit')->name('subadmin.edit') ->middleware('check.permission:subadmin,edit');
        Route::post('/subadmin-update/{id}',  'update')->name('subadmin.update') ->middleware('check.permission:subadmin,edit');
        Route::delete('/subadmin-destroy/{id}',  'destroy')->name('subadmin.destroy') ->middleware('check.permission:subadmin,delete');

        Route::post('/update-permissions/{id}', 'updatePermissions')->name('update.permissions');

        Route::post('/subadmin-StatusChange', 'StatusChange')->name('subadmin.StatusChange')->middleware('check.permission:subadmin,edit');

        Route::post('/admin/subadmin/toggle-status', [SubAdminController::class, 'toggleStatus'])->name('admin.subadmin.toggleStatus');

    });


            // ############ Blogs #################

    Route::get('/blogs-index', [BlogController::class, 'index'])->name('blog.index')->middleware('check.permission:Blogs,view');

    Route::get('/blogs-create', [BlogController::class, 'create'])->name('blog.createview')->middleware('check.permission:Blogs,create');

    Route::post('/blogs-store', [BlogController::class, 'store'])->name('blog.store')->middleware('check.permission:Blogs,create');

    Route::get('/blogs-edit/{id}', [BlogController::class, 'edit'])->name('blog.edit')->middleware('check.permission:Blogs,edit');
    Route::post('/blogs-update/{id}', [BlogController::class, 'update'])->name('blog.update')->middleware('check.permission:Blogs,edit');
    Route::delete('/blogs-destroy/{id}', [BlogController::class, 'delete'])->name('blog.destroy')->middleware('check.permission:Blogs,delete');

    Route::post('/blogs/toggle-status', [BlogController::class, 'toggleStatus'])->name('blog.toggle-status');




    // ############ Notifications #################
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notification',  'index')->name('notification.index') ->middleware('check.permission:notification,view');
        Route::post('/notification-store',  'store')->name('notification.store') ->middleware('check.permission:notification,create');
        Route::delete('/notification-destroy/{id}',  'destroy')->name('notification.destroy') ->middleware('check.permission:notification,delete');
    
    });






    // ############ Contact Us #################
Route::get('/admin/contact-us', [ContactController::class, 'index'])->name('contact.index') ->middleware('check.permission:contact,view');
Route::get('/admin/contact-us-create', [ContactController::class, 'create'])->name('contact.create') ->middleware('check.permission:contact,create');
Route::post('/admin/contact-us-store', [ContactController::class, 'store'])->name('contact.store') ->middleware('check.permission:contact,create');
Route::get('/admin/contact-us-edit/{id}', [ContactController::class, 'updateview'])->name('contact.updateview') ->middleware('check.permission:contact,edit');
Route::post('/admin/contact-us-update/{id}', [ContactController::class, 'update'])->name('contact.update') ;



});
