<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\InviteCode;
use App\Utils\AliPay;
use App\Utils\TelegramSessionManager;
use App\Utils\TelegramProcess;
use App\Utils\Geetest;
use Slim\Http\{ServerRequest, Response};
use Psr\Http\Message\ResponseInterface;

/**
 *  HomeController
 */
class HomeController extends BaseController
{
    public function index(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $GtSdk = null;
        $recaptcha_sitekey = null;
        if ($_ENV['captcha_provider'] != '') {
            switch ($_ENV['captcha_provider']) {
                case 'recaptcha':
                    $recaptcha_sitekey = $_ENV['recaptcha_sitekey'];
                    break;
                case 'geetest':
                    $uid = time() . random_int(1, 10000);
                    $GtSdk = Geetest::get($uid);
                    break;
            }
        }

        if ($_ENV['enable_telegram'] == 'true') {
            $login_text = TelegramSessionManager::add_login_session();
            $login = explode('|', $login_text);
            $login_token = $login[0];
            $login_number = $login[1];
        } else {
            $login_token = '';
            $login_number = '';
        }

        $response->getBody()->write($this->view()
            ->assign('geetest_html', $GtSdk)
            ->assign('login_token', $login_token)
            ->assign('login_number', $login_number)
            ->assign('telegram_bot', $_ENV['telegram_bot'])
            ->assign('enable_logincaptcha', $_ENV['enable_login_captcha'])
            ->assign('enable_regcaptcha', $_ENV['enable_reg_captcha'])
            ->assign('base_url', $_ENV['baseUrl'])
            ->assign('recaptcha_sitekey', $recaptcha_sitekey)
            ->fetch('index.tpl'));
        return $response;
    }

    public function indexold(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $response->getBody()->write($this->view()->fetch('indexold.tpl'));
        return $response;
    }

    public function code(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $codes = InviteCode::where('user_id', '=', '0')->take(10)->get();
        $response->getBody()->write($this->view()->assign('codes', $codes)->fetch('code.tpl'));
        return $response;
    }

    public function tos(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $response->getBody()->write($this->view()->fetch('tos.tpl'));
        return $response;
    }

    public function staff(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $response->getBody()->write($this->view()->fetch('staff.tpl'));
        return $response;
    }

    public function telegram(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $token = $request->getQueryParam('token');

        if ($token == $_ENV['telegram_request_token']) {
            TelegramProcess::process();
        }
        return $response;
    }

    public function page404(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $response->getBody()->write($this->view()->fetch('404.tpl'));
        return $response;
    }

    public function page405(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $response->getBody()->write($this->view()->fetch('405.tpl'));
        return $response;
    }

    public function page500(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $response->getBody()->write($this->view()->fetch('500.tpl'));
        return $response;
    }

    public function getOrderList(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $key = $request->getParam('key');
        if ($key == null || $key != $_ENV['key']) {
            $res['ret'] = 0;
            $res['msg'] = '错误';
            $response->getBody()->write(json_encode($res));
        } else {
            $response->getBody()->write(json_encode(['data' => AliPay::getList()]));
        }
        return $response;
    }

    public function setOrder(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $key = $request->getParam('key');
        $sn = $request->getParam('sn');
        $url = $request->getParam('url');
        if ($key == null || $key != $_ENV['key']) {
            $res['ret'] = 0;
            $res['msg'] = '错误';
            $response->getBody()->write(json_encode($res));
        } else {
            $response->getBody()->write(json_encode(['res' => AliPay::setOrder($sn, $url)]));
        }
        return $response;
    }
}
