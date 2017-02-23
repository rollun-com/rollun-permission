<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.01.17
 * Time: 15:52
 */

namespace rollun\permission\Api;

use Google_Service_Drive;
use Google_Service_Plus;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\installer\Command;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Stratigility\MiddlewareInterface;

class OAuth2RedirectAction implements MiddlewareInterface
{

    /**
     * Process an incoming request and/or response.
     *
     * Accepts a server-side request and a response instance, and does
     * something with them.
     *
     * If the response is not complete and/or further processing would not
     * interfere with the work done in the middleware, or if the middleware
     * wants to delegate to another process, it can use the `$out` callable
     * if present.
     *
     * If the middleware does not return a value, execution of the current
     * request is considered complete, and the response instance provided will
     * be considered the response to return.
     *
     * Alternately, the middleware may return a response instance.
     *
     * Often, middleware will `return $out();`, with the assumption that a
     * later middleware will return a response.
     *
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        session_start();
        $client = new \Google_Client();
        $clientCredentials = Command::getDataDir() . DIRECTORY_SEPARATOR .
            'Api' . DIRECTORY_SEPARATOR .
            'Google' . DIRECTORY_SEPARATOR .
            'OpenIDAuthClient.json';
        $client->setAuthConfig($clientCredentials);
        $client->setAccessType("offline");
        $client->setRedirectUri('http://' . constant("HOST") . '/oauth2r');
        $client->addScope(Google_Service_Plus::USERINFO_EMAIL);

        $query = $request->getQueryParams();

        if (!isset($query['code'])) {
            $authUrl = $client->createAuthUrl();
            $response = new RedirectResponse($authUrl, 302, ['Location' => filter_var($authUrl, FILTER_SANITIZE_URL)]);
        } else {
            $client->authenticate($query['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
            $homeRedirectUrl = 'http://' . constant("HOST") . '/';
            $response = new RedirectResponse(
                $homeRedirectUrl,
                302,
                ['Location' => filter_var($homeRedirectUrl, FILTER_SANITIZE_URL)]
            );
        }

        return $response;
    }
}