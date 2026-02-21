<?php

use App\Http\Controllers\CheckNumber;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\ReciveDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RigesterController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\SendVerifyCode;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopingCartController;
use App\Http\Middleware\CodeSended;
use App\Http\Middleware\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;





// Home route
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/exit', function () {
    Auth::logout();
    return view('home');
})->name('exit');

// Auth group
Route::group(['prefix' => 'auth'], function () {

    Route::get('/login', function () {
        return view('auth.login', ['type' => 'login']);
    })->name('login');

    Route::post('/Resumelogin', [LoginController::class, 'check'])
        ->name('Resumelogin');

    Route::get('/register', function () {
        return view('auth.register', ['type' => 'register']);
    })->name('register');

    Route::post('/ResumeRegister', function () {
        session([
            'TypeForAfterVerify' => 'register',
            'code_verified_expires' => now()->addMinutes(1),
            'phone' => request()->input('phone')
        ]);

        return app(CheckNumber::class)->check(request());
    })->name('ResumeRegister');

    Route::get('/set-username-password', function () {
        return view('auth.set-username-password', ['type' => 'set-username-password']);
    })->middleware(Verified::class)
        ->name('set-username-password');

    Route::post('/put-username-password', [RigesterController::class, 'PutData']
    )->name('put-username-password');

    Route::get('/forgot', function () {
        return view('auth.forgot', ['type' => 'forgot']);
    })->name('forgot');

    Route::post('/Resumeforgot', function () {
        session([
            'TypeForAfterVerify' => 'forgot',
            'code_verified_expires' => now()->addMinutes(1)
        ]);

        return app(CheckNumber::class)->check(request());
    })->name('Resumeforgot');

    Route::get('/show-password', function () {
        return view('auth.show-username-password', ['type' => 'show-username-password']);
    })->middleware(Verified::class)
        ->name('show-username-password');

    Route::get('/verify', function () {
        return view('auth.verify', ['type' => 'verify']);
    })->middleware(CodeSended::class)
        ->name('verify');

    Route::post('/VerifyCode', [SendVerifyCode::class, 'VerifyCode'])
        ->name('VerifyCode');
});

// Shopping cart
Route::get('/shopping-cart', function () {
    return view('shopping-cart');
})->name('shopping-cart');

// Edit profile
Route::get('/edit-profile', [ReciveDataController::class, 'ReciveAddresses'])
    ->name('edit-profile');

Route::get('/verify/edit-profile', [EditProfileController::class, 'EditCreditional'])
    ->name('verify-edit-profile');

Route::get('/edit-phone', function () {
    session(['ForRedirectrAfterVerify' => 'edit-phone']);
    return redirect()->route('register');
})->name('edit-phone');

// Ticket
Route::get('/ticket', function () {
    return view('ticket');
})->name('ticket');

// Exit


// Address routes
Route::get('/add-address', function () {
    return view('address');
})->name('add-address');

Route::post('/verify/address', [EditProfileController::class, 'UpdateAddress'])
    ->name('verify-addresa');

Route::get('/delete/address/{index}', [EditProfileController::class, 'DeleteAddress'])
    ->name('DeleteAddress');

// Order tracking
Route::get('/order-track', function () {
    return view('order-tracking');
})->name('order-track');

// Admin panel
Route::get('/admin', function () {
    return view('admin-panel');
})->name('admin');

Route::get('/admin/products', function () {
    return view('show-&-edit-products');
})->name('admin.products');

Route::get('/admin/orders-data', function () {
    return response()->json([
        'total' => 1250,
        'completed' => 72,
        'types' => [
            ['type' => 'پرداخت‌شده', 'percent' => 50, 'count' => 625],
            ['type' => 'در حال پردازش', 'percent' => 22, 'count' => 275],
            ['type' => 'ارسال‌شده', 'percent' => 20, 'count' => 250],
            ['type' => 'لغو‌شده', 'percent' => 8, 'count' => 100],
        ],
    ]);
})->name('admin.orders-data');

Route::get('/admin/comments-data', function () {
    return response()->json([
        ['author' => 'علی رضایی', 'date' => '1403/07/10', 'text' => 'خیلی عالی بود، ممنون!', 'rating' => 5],
        ['author' => 'مریم احمدی', 'date' => '1403/07/08', 'text' => 'کیفیت معمولی ولی ارسال سریع.', 'rating' => 3],
        ['author' => 'سارا موسوی', 'date' => '1403/07/05', 'text' => 'پشتیبانی خوب بود.', 'rating' => 4],
    ]);
})->name('admin.comments-data');


// Product views
Route::get('/produce-show/{id}', function ($id) {
    return view('produce-show',compact('id'));
})->name('produce-show');

Route::get('/add-product', function (Request $request) {
    $category = session('categoryinput');
    $keys = config($category);
    return view('add-products', compact('keys'));
})->name('add-products');

Route::post('/show-and-edit-products', function (Request $request) {
    $categoryinput = $request->input('category');
    if ($categoryinput == 'LaptopKeys') {
        $category = 'لپتاپ و اولترابوک';
    } elseif ($categoryinput == 'MobileKeys') {
        $category = 'موبایل و تلفن هوشمند';
    } elseif ($categoryinput == 'WatchKeys') {
        $category = 'ساعت هوشمند';
    } elseif ($categoryinput == 'AirPadKeys') {
        $category = 'ایرپاد و هندزفری';
    } else {
        $category = 'انتخاب نشده است';
    }
    Session::put('categoryinput', $categoryinput);
    Session::put('category', $category);
    return redirect(route('add-products'));
})->name('show-&-edit-products');

// Test upload
Route::post('/test-upload', function (Request $request) {
    $paths = [];

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $paths[] = $file->store('uploads', 'public');
        }
    }

    return response()->json([
        'message' => 'شما دارای دسترسی نمی باشید',
        'paths' => $paths,
    ]);
})->name('test');

// Products page
Route::get('/products', function (Request $request) {

        dump(session()->all());
    $type = session()->get('set_filters.params.type');

    $filters = config($type);


    return view('products', compact('filters'));
})->name('products');


// Products group
Route::group(['prefix' => 'products'], function () {

    Route::get('set_filters', function (Request $request) {



        if ($request->filled('type')) {
            session(['set_filters.type' => $request->type]);
        }

        if ($request->filled('category')) {
            session(['set_filters.category' => $request->category]);
        }

        if ($request->filled('search')) {
            session(['set_filters.search' => $request->search]);
        }

        $set_filters = $request->except(['_token']);
        // dd($set_filters);
        $data = [
          'filters' => $set_filters,
            'params' => [
              'type' =>  $set_filters['type'] ?? null,
              'category' => $set_filters['category'] ?? null,
              'search' => $set_filters['search'] ?? null
            ]
        ];
        unset($data['filters']['type'], $data['filters']['category'], $data['filters']['search']);


        session(['set_filters' => $data]);



        return redirect()->route('products');
    })->name('set_filters');

    Route::post('/filter', [ProductController::class, 'filter'])->name('filter');

    Route::get('/search', [ProductController::class, 'search'])->name('search');

    Route::get('/getById/{id}', [ProductController::class, 'getById'])->name('getById');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/AllUser', [ReciveDataController::class, 'ReciveAllUsers'])->name('ReciveALLUser');
});

Route::group(['prefix' => 'profile'], function () {
    Route::post('/shopping-cart/add', [ShopingCartController::class, 'add'])->name('AddToShopingCart')
    ->middleware('auth');;

    Route::get('/shopping-cart/remove/{id}', [ShopingCartController::class, 'remove'])->name('RemoveAsShopingCart');

    Route::post('/shopping-cart/BelongToUser', [ShopingCartController::class, 'BelongToUser'])->name('ProuductBelongToUser');

});


Route::Post('test', function (Request $request) {
    // dd($request->all());
    return view('test');
})->name('test');


