<?php

declare(strict_types=1);

use Slim\App as SlimApp;
use Slim\Interfaces\RouteCollectorProxyInterface as RouteCollectorProxy;
use App\Middleware\{
    Auth,
    Guest,
    Admin,
    Mod_Mu,
    Mu,
    Api
};

return function (SlimApp $app) {
    //$container = $app->getContainer();
    // Home
    $app->post('/spay_back', App\Services\Payment::class . ':notify');
    $app->get('/spay_back', App\Services\Payment::class . ':notify');
    $app->get('/', App\Controllers\HomeController::class . ':index');
    $app->get('/indexold', App\Controllers\HomeController::class . ':indexold');
    $app->get('/404', App\Controllers\HomeController::class . ':page404');
    $app->get('/405', App\Controllers\HomeController::class . ':page405');
    $app->get('/500', App\Controllers\HomeController::class . ':page500');
    $app->post('/notify', App\Controllers\HomeController::class . ':notify');
    $app->get('/tos', App\Controllers\HomeController::class . ':tos');
    $app->get('/staff', App\Controllers\HomeController::class . ':staff');
    $app->post('/telegram_callback', App\Controllers\HomeController::class . ':telegram');

    // User Center
    $app->group('/user', function (RouteCollectorProxy $group) {
        $group->get('', App\Controllers\UserController::class . ':index');
        $group->get('/', App\Controllers\UserController::class . ':index');
        $group->post('/checkin', App\Controllers\UserController::class . ':doCheckin');
        $group->get('/node', App\Controllers\UserController::class . ':node');
        $group->get('/tutorial', App\Controllers\UserController::class . ':tutorial');
        $group->get('/announcement', App\Controllers\UserController::class . ':announcement');
        $group->get('/donate', App\Controllers\UserController::class . ':donate');
        $group->get('/lookingglass', App\Controllers\UserController::class . ':lookingglass');
        $group->get('/node/{id}', App\Controllers\UserController::class . ':nodeInfo');
        $group->get('/node/{id}/ajax', App\Controllers\UserController::class . ':nodeAjax');
        $group->get('/profile', App\Controllers\UserController::class . ':profile');
        $group->get('/invite', App\Controllers\UserController::class . ':invite');

        $group->get('/detect', App\Controllers\UserController::class . ':detect_index');
        $group->get('/detect/log', App\Controllers\UserController::class . ':detect_log');

        $group->get('/disable', App\Controllers\UserController::class . ':disable');

        $group->get('/shop', App\Controllers\UserController::class . ':shop');
        $group->post('/coupon_check', App\Controllers\UserController::class . ':CouponCheck');
        $group->post('/buy', App\Controllers\UserController::class . ':buy');

        // Relay Mange
        $group->get('/relay', App\Controllers\RelayController::class . ':index');
        $group->get('/relay/create', App\Controllers\RelayController::class . ':create');
        $group->post('/relay', App\Controllers\RelayController::class . ':add');
        $group->get('/relay/{id}/edit', App\Controllers\RelayController::class . ':edit');
        $group->put('/relay/{id}', App\Controllers\RelayController::class . ':update');
        $group->delete('/relay', App\Controllers\RelayController::class . ':delete');

        $group->get('/ticket', App\Controllers\UserController::class . ':ticket');
        $group->get('/ticket/create', App\Controllers\UserController::class . ':ticket_create');
        $group->post('/ticket', App\Controllers\UserController::class . ':ticket_add');
        $group->get('/ticket/{id}/view', App\Controllers\UserController::class . ':ticket_view');
        $group->put('/ticket/{id}', App\Controllers\UserController::class . ':ticket_update');

        $group->post('/buy_invite', App\Controllers\UserController::class . ':buyInvite');
        $group->post('/custom_invite', App\Controllers\UserController::class . ':customInvite');
        $group->get('/edit', App\Controllers\UserController::class . ':edit');
        $group->post('/password', App\Controllers\UserController::class . ':updatePassword');
        $group->post('/wechat', App\Controllers\UserController::class . ':updateWechat');
        $group->post('/ssr', App\Controllers\UserController::class . ':updateSSR');
        $group->post('/theme', App\Controllers\UserController::class . ':updateTheme');
        $group->post('/mail', App\Controllers\UserController::class . ':updateMail');
        $group->post('/sspwd', App\Controllers\UserController::class . ':updateSsPwd');
        $group->post('/method', App\Controllers\UserController::class . ':updateMethod');
        $group->post('/hide', App\Controllers\UserController::class . ':updateHide');
        $group->get('/sys', App\Controllers\UserController::class . ':sys');
        $group->get('/trafficlog', App\Controllers\UserController::class . ':trafficLog');
        $group->get('/kill', App\Controllers\UserController::class . ':kill');
        $group->post('/kill', App\Controllers\UserController::class . ':handleKill');
        $group->get('/logout', App\Controllers\UserController::class . ':logout');
        $group->get('/backtoadmin', App\Controllers\UserController::class . ':backtoadmin');
        $group->get('/code', App\Controllers\UserController::class . ':code');
        $group->get('/alipay', App\Controllers\UserController::class . ':alipay');
        $group->post('/code/f2fpay', App\Services\Payment::class . ':purchase');
        $group->get('/code/codepay', App\Services\Payment::class . ':purchase');
        $group->get('/code_check', App\Controllers\UserController::class . ':code_check');
        $group->post('/code', App\Controllers\UserController::class . ':codepost');
        $group->post('/gacheck', App\Controllers\UserController::class . ':GaCheck');
        $group->post('/gaset', App\Controllers\UserController::class . ':GaSet');
        $group->get('/gareset', App\Controllers\UserController::class . ':GaReset');
        $group->get('/telegram_reset', App\Controllers\UserController::class . ':telegram_reset');
        $group->get('/discord_reset', App\Controllers\UserController::class . ':discord_reset');
        $group->post('/resetport', App\Controllers\UserController::class . ':ResetPort');
        $group->post('/specifyport', App\Controllers\UserController::class . ':SpecifyPort');
        $group->post('/pacset', App\Controllers\UserController::class . ':PacSet');
        $group->post('/unblock', App\Controllers\UserController::class . ':Unblock');
        $group->get('/bought', App\Controllers\UserController::class . ':bought');
        $group->delete('/bought', App\Controllers\UserController::class . ':deleteBoughtGet');

        $group->get('/url_reset', App\Controllers\UserController::class . ':resetURL');

        $group->get('/inviteurl_reset', App\Controllers\UserController::class . ':resetInviteURL');

        //Reconstructed Payment System
        $group->post('/payment/purchase', App\Services\Payment::class . ':purchase');
        $group->get('/payment/return', App\Services\Payment::class . ':returnHTML');

        // Crypto Payment - BTC, ETH, EOS, BCH, LTC etch
        $group->post('/payment/bitpay/purchase', App\Services\BitPayment::class . ':purchase');
        $group->get('/payment/bitpay/return', App\Services\BitPayment::class . ':returnHTML');
    })->add(new Auth());

    $app->group('/payment', function (RouteCollectorProxy $group) {
        $group->post('/notify', App\Services\Payment::class . ':notify');
        $group->post('/notify/{type}', App\Services\Payment::class . ':notify');
        $group->post('/status', App\Services\Payment::class . ':getStatus');

        $group->post('/bitpay/notify', App\Services\BitPayment::class . ':notify');
        $group->post('/bitpay/status', App\Services\BitPayment::class . ':getStatus');
    });

    // Auth
    $app->group('/auth', function (RouteCollectorProxy $group) {
        $group->get('/login', App\Controllers\AuthController::class . ':login');
        $group->post('/qrcode_check', App\Controllers\AuthController::class . ':qrcode_check');
        $group->post('/login', App\Controllers\AuthController::class . ':loginHandle');
        $group->post('/qrcode_login', App\Controllers\AuthController::class . ':qrcode_loginHandle');
        $group->get('/register', App\Controllers\AuthController::class . ':register');
        $group->post('/register', App\Controllers\AuthController::class . ':registerHandle');
        $group->post('/send', App\Controllers\AuthController::class . ':sendVerify');
        $group->get('/logout', App\Controllers\AuthController::class . ':logout');
        $group->get('/telegram_oauth', App\Controllers\AuthController::class . ':telegram_oauth');
        $group->get('/login_getCaptcha', App\Controllers\AuthController::class . ':getCaptcha');
    })->add(new Guest());

    // Password
    $app->group('/password', function (RouteCollectorProxy $group) {
        $group->get('/reset', App\Controllers\PasswordController::class . ':reset');
        $group->post('/reset', App\Controllers\PasswordController::class . ':handleReset');
        $group->get('/token/{token}', App\Controllers\PasswordController::class . ':token');
        $group->post('/token/{token}', App\Controllers\PasswordController::class . ':handleToken');
    })->add(new Guest());

    // Admin
    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->get('', App\Controllers\AdminController::class . ':index');
        $group->get('/', App\Controllers\AdminController::class . ':index');

        $group->get('/trafficlog', App\Controllers\AdminController::class . ':trafficLog');
        $group->post('/trafficlog/ajax', App\Controllers\AdminController::class . ':ajax_trafficLog');
        // Node Mange
        $group->get('/node', App\Controllers\Admin\NodeController::class . ':index');

        $group->get('/node/create', App\Controllers\Admin\NodeController::class . ':create');
        $group->post('/node', App\Controllers\Admin\NodeController::class . ':add');
        $group->get('/node/{id}/edit', App\Controllers\Admin\NodeController::class . ':edit');
        $group->put('/node/{id}', App\Controllers\Admin\NodeController::class . ':update');
        $group->delete('/node', App\Controllers\Admin\NodeController::class . ':delete');
        $group->post('/node/ajax', App\Controllers\Admin\NodeController::class . ':ajax');


        $group->get('/ticket', App\Controllers\Admin\TicketController::class . ':index');
        $group->get('/ticket/{id}/view', App\Controllers\Admin\TicketController::class . ':show');
        $group->put('/ticket/{id}', App\Controllers\Admin\TicketController::class . ':update');
        $group->post('/ticket/ajax', App\Controllers\Admin\TicketController::class . ':ajax');

        // Relay Mange
        $group->get('/relay', App\Controllers\Admin\RelayController::class . ':index');
        $group->get('/relay/create', App\Controllers\Admin\RelayController::class . ':create');
        $group->post('/relay', App\Controllers\Admin\RelayController::class . ':add');
        $group->get('/relay/{id}/edit', App\Controllers\Admin\RelayController::class . ':edit');
        $group->put('/relay/{id}', App\Controllers\Admin\RelayController::class . ':update');
        $group->delete('/relay', App\Controllers\Admin\RelayController::class . ':delete');
        $group->get('/relay/path_search/{id}', App\Controllers\Admin\RelayController::class . ':path_search');
        $group->post('/relay/ajax', App\Controllers\Admin\RelayController::class . ':ajax_relay');

        // Shop Mange
        $group->get('/shop', App\Controllers\Admin\ShopController::class . ':index');
        $group->post('/shop/ajax', App\Controllers\Admin\ShopController::class . ':ajax_shop');

        $group->get('/bought', App\Controllers\Admin\ShopController::class . ':bought');
        $group->delete('/bought', App\Controllers\Admin\ShopController::class . ':deleteBoughtGet');
        $group->post('/bought/ajax', App\Controllers\Admin\ShopController::class . ':ajax_bought');

        $group->get('/shop/create', App\Controllers\Admin\ShopController::class . ':create');
        $group->post('/shop', App\Controllers\Admin\ShopController::class . ':add');
        $group->get('/shop/{id}/edit', App\Controllers\Admin\ShopController::class . ':edit');
        $group->put('/shop/{id}', App\Controllers\Admin\ShopController::class . ':update');
        $group->delete('/shop', App\Controllers\Admin\ShopController::class . ':deleteGet');

        // Ann Mange
        $group->get('/announcement', App\Controllers\Admin\AnnController::class . ':index');
        $group->get('/announcement/create', App\Controllers\Admin\AnnController::class . ':create');
        $group->post('/announcement', App\Controllers\Admin\AnnController::class . ':add');
        $group->get('/announcement/{id}/edit', App\Controllers\Admin\AnnController::class . ':edit');
        $group->put('/announcement/{id}', App\Controllers\Admin\AnnController::class . ':update');
        $group->delete('/announcement', App\Controllers\Admin\AnnController::class . ':delete');
        $group->post('/announcement/ajax', App\Controllers\Admin\AnnController::class . ':ajax');

        // Detect Mange
        $group->get('/detect', App\Controllers\Admin\DetectController::class . ':index');
        $group->get('/detect/create', App\Controllers\Admin\DetectController::class . ':create');
        $group->post('/detect', App\Controllers\Admin\DetectController::class . ':add');
        $group->get('/detect/{id}/edit', App\Controllers\Admin\DetectController::class . ':edit');
        $group->put('/detect/{id}', App\Controllers\Admin\DetectController::class . ':update');
        $group->delete('/detect', App\Controllers\Admin\DetectController::class . ':delete');
        $group->get('/detect/log', App\Controllers\Admin\DetectController::class . ':log');
        $group->post('/detect/ajax', App\Controllers\Admin\DetectController::class . ':ajax_rule');
        $group->post('/detect/log/ajax', App\Controllers\Admin\DetectController::class . ':ajax_log');

        $group->get('/auto', App\Controllers\Admin\AutoController::class . ':index');
        $group->get('/auto/create', App\Controllers\Admin\AutoController::class . ':create');
        $group->post('/auto', App\Controllers\Admin\AutoController::class . ':add');
        $group->delete('/auto', App\Controllers\Admin\AutoController::class . ':delete');
        $group->post('/auto/ajax', App\Controllers\Admin\AutoController::class . ':ajax');

        // IP Mange
        $group->get('/block', App\Controllers\Admin\IpController::class . ':block');
        $group->get('/unblock', App\Controllers\Admin\IpController::class . ':unblock');
        $group->post('/unblock', App\Controllers\Admin\IpController::class . ':doUnblock');
        $group->get('/login', App\Controllers\Admin\IpController::class . ':index');
        $group->get('/alive', App\Controllers\Admin\IpController::class . ':alive');
        $group->post('/block/ajax', App\Controllers\Admin\IpController::class . ':ajax_block');
        $group->post('/unblock/ajax', App\Controllers\Admin\IpController::class . ':ajax_unblock');
        $group->post('/login/ajax', App\Controllers\Admin\IpController::class . ':ajax_login');
        $group->post('/alive/ajax', App\Controllers\Admin\IpController::class . ':ajax_alive');

        // Code Mange
        $group->get('/code', App\Controllers\Admin\CodeController::class . ':index');
        $group->get('/code/create', App\Controllers\Admin\CodeController::class . ':create');
        $group->post('/code', App\Controllers\Admin\CodeController::class . ':add');
        $group->get('/donate/create', App\Controllers\Admin\CodeController::class . ':donate_create');
        $group->post('/donate', App\Controllers\Admin\CodeController::class . ':donate_add');
        $group->post('/code/ajax', App\Controllers\Admin\CodeController::class . ':ajax_code');

        // User Mange
        $group->get('/user', App\Controllers\Admin\UserController::class . ':index');
        $group->get('/user/{id}/edit', App\Controllers\Admin\UserController::class . ':edit');
        $group->put('/user/{id}', App\Controllers\Admin\UserController::class . ':update');
        $group->delete('/user', App\Controllers\Admin\UserController::class . ':delete');
        $group->post('/user/changetouser', App\Controllers\Admin\UserController::class . ':changetouser');
        $group->post('/user/ajax', App\Controllers\Admin\UserController::class . ':ajax');
        $group->post('/user/create', App\Controllers\Admin\UserController::class . ':createNewUser');
        $group->post('/user/buy', App\Controllers\Admin\UserController::class . ':buy');


        $group->get('/coupon', App\Controllers\AdminController::class . ':coupon');
        $group->post('/coupon', App\Controllers\AdminController::class . ':addCoupon');
        $group->post('/coupon/ajax', App\Controllers\AdminController::class . ':ajax_coupon');

        $group->get('/profile', App\Controllers\AdminController::class . ':profile');
        $group->get('/invite', App\Controllers\AdminController::class . ':invite');
        $group->post('/invite', App\Controllers\AdminController::class . ':addInvite');
        $group->get('/sys', App\Controllers\AdminController::class . ':sys');
        $group->get('/logout', App\Controllers\AdminController::class . ':logout');
        $group->post('/payback/ajax', App\Controllers\AdminController::class . ':ajax_payback');
    })->add(new Admin());

    // API
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->get('/token/{token}', App\Controllers\ApiController::class . ':token');
        $group->post('/token', App\Controllers\ApiController::class . ':newToken');
        $group->get('/node', App\Controllers\ApiController::class . ':node')->add(new Api());
        $group->get('/user/{id}', App\Controllers\ApiController::class . ':userInfo')->add(new Api());
        $group->get('/sublink', App\Controllers\Client\ClientApiController::class . ':GetSubLink');
    });

    // mu
    $app->group('/mu', function (RouteCollectorProxy $group) {
        $group->get('/users', App\Controllers\Mu\UserController::class . ':index');
        $group->post('/users/{id}/traffic', App\Controllers\Mu\UserController::class . ':addTraffic');
        $group->post('/nodes/{id}/online_count', App\Controllers\Mu\NodeController::class . ':onlineUserLog');
        $group->post('/nodes/{id}/info', App\Controllers\Mu\NodeController::class . ':info');
    })->add(new Mu());

    // mu
    $app->group('/mod_mu', function (RouteCollectorProxy $group) {
        $group->get('/nodes/{id}/info', App\Controllers\Mod_Mu\NodeController::class . ':get_info');
        $group->get('/users', App\Controllers\Mod_Mu\UserController::class . ':index');
        $group->post('/users/traffic', App\Controllers\Mod_Mu\UserController::class . ':addTraffic');
        $group->post('/users/aliveip', App\Controllers\Mod_Mu\UserController::class . ':addAliveIp');
        $group->post('/users/detectlog', App\Controllers\Mod_Mu\UserController::class . ':addDetectLog');
        $group->post('/nodes/{id}/info', App\Controllers\Mod_Mu\NodeController::class . ':info');

        $group->get('/nodes', App\Controllers\Mod_Mu\NodeController::class . ':get_all_info');
        $group->post('/nodes/config', App\Controllers\Mod_Mu\NodeController::class . ':getConfig');

        $group->get('/func/detect_rules', App\Controllers\Mod_Mu\FuncController::class . ':get_detect_logs');
        $group->get('/func/relay_rules', App\Controllers\Mod_Mu\FuncController::class . ':get_relay_rules');
        $group->post('/func/block_ip', App\Controllers\Mod_Mu\FuncController::class . ':addBlockIp');
        $group->get('/func/block_ip', App\Controllers\Mod_Mu\FuncController::class . ':get_blockip');
        $group->get('/func/unblock_ip', App\Controllers\Mod_Mu\FuncController::class . ':get_unblockip');
        $group->post('/func/speedtest', App\Controllers\Mod_Mu\FuncController::class . ':addSpeedtest');
        $group->get('/func/autoexec', App\Controllers\Mod_Mu\FuncController::class . ':get_autoexec');
        $group->post('/func/autoexec', App\Controllers\Mod_Mu\FuncController::class . ':addAutoexec');

        $group->get('/func/ping', App\Controllers\Mod_Mu\FuncController::class . ':ping');
        //============================================
    })->add(new Mod_Mu());

    // res
    $app->group('/res', function (RouteCollectorProxy $group) {
        $group->get('/captcha/{id}', App\Controllers\ResController::class . ':captcha');
    });


    $app->group('/link', function (RouteCollectorProxy $group) {
        $group->get('/{token}', App\Controllers\LinkController::class . ':GetContent');
    });

    $app->group('/user', function (RouteCollectorProxy $group) {
        $group->post('/doiam', App\Services\Payment::class . ':purchase');
    })->add(new Auth());
    $app->group('/doiam', function (RouteCollectorProxy $group) {
        $group->post('/callback/{type}', App\Services\Payment::class . ':notify');
        $group->get('/return/alipay', App\Services\Payment::class . ':returnHTML');
        $group->post('/status', App\Services\Payment::class . ':getStatus');
    });

    // Vue

    $app->get('/logout', App\Controllers\VueController::class . ':vuelogout');
    $app->get('/globalconfig', App\Controllers\VueController::class . ':getGlobalConfig');
    $app->get('/getuserinfo', App\Controllers\VueController::class . ':getUserInfo');
    $app->post('/getuserinviteinfo', App\Controllers\VueController::class . ':getUserInviteInfo');
    $app->get('/getusershops', App\Controllers\VueController::class . ':getUserShops');
    $app->get('/getallresourse', App\Controllers\VueController::class . ':getAllResourse');
    $app->get('/getnewsubtoken', App\Controllers\VueController::class . ':getNewSubToken');
    $app->get('/getnewinvotecode', App\Controllers\VueController::class . ':getNewInviteCode');
    $app->get('/gettransfer', App\Controllers\VueController::class . ':getTransfer');
    $app->get('/getCaptcha', App\Controllers\VueController::class . ':getCaptcha');
    $app->post('/getChargeLog', App\Controllers\VueController::class . ':getChargeLog');
    $app->get('/getnodelist', App\Controllers\VueController::class . ':getNodeList');

    /**
     * chenPay
     */
    $app->group('/user', function (RouteCollectorProxy $group) {
        $group->get('/chenPay', App\Services\Payment::class . ':purchase');
        $group->get('/orderDelete', App\Controllers\UserController::class . ':orderDelete');
    })->add(new Auth());
    $app->group('/chenPay', function (RouteCollectorProxy $group) {
        $group->get('/status', App\Services\Payment::class . ':getStatus');
    });
    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->get('/editConfig', App\Controllers\AdminController::class . ':editConfig');
        $group->post('/saveConfig', App\Controllers\AdminController::class . ':saveConfig');
    })->add(new Admin());
    // chenPay end
};
