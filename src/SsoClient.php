<?php

namespace RabbitDigital\SsoClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\TransferStats;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RabbitDigital\SsoClient\Exceptions\ApplicationHttpException;
use RabbitDigital\SsoClient\Exceptions\CountryNotFoundException;
use RabbitDigital\SsoClient\Exceptions\LocationException;
use RabbitDigital\SsoClient\Exceptions\SessionInvalidException;
use RabbitDigital\SsoClient\Exceptions\SsoServerErrorException;
use RabbitDigital\SsoClient\Exceptions\SsoUnknownResponseException;
use RabbitDigital\SsoClient\Exceptions\UserNotFoundException;
use RabbitDigital\SsoClient\Exceptions\ValidatorException;

class SsoClient extends Client
{
    const SSO_AUTH_SERVICE                  = 'SSO_AUTH';
    const SSO_BACKOFFICE_SERVICE            = 'SSO_BACKOFFICE';
    const SSO_BIFROST_SERVICE               = 'SSO_BIFROST';
    const SSO_LOCATION_SERVICE              = 'SSO_LOCATION';
    const SSO_EXTENDED_SERVICE              = 'SSO_EXTENDED';
    const SSO_OAUTH_SERVICE                 = 'SSO_OAUTH';
    const SSO_URL_SHORTENER_SERVICE         = 'SSO_URL_SHORTENER';
    const SSO_USER_SERVICE                  = 'SSO_USER';

    protected string $baseUrl = '';
    protected int $successStatusCode;
    protected static array $ssoServices;

    public function __construct(array $config = [])
    {
        self::$ssoServices = [
            self::SSO_AUTH_SERVICE                     => config('sso_service_url.auth'),
            self::SSO_BACKOFFICE_SERVICE               => config('sso_service_url.backoffice'),
            self::SSO_BIFROST_SERVICE                  => config('sso_service_url.bifrost'),
            self::SSO_LOCATION_SERVICE                 => config('sso_service_url.location'),
            self::SSO_EXTENDED_SERVICE                 => config('sso_service_url.extended'),
            self::SSO_OAUTH_SERVICE                    => config('sso_service_url.oauth'),
            self::SSO_URL_SHORTENER_SERVICE            => config('sso_service_url.url_shortener'),
            self::SSO_USER_SERVICE                     => config('sso_service_url.user'),
        ];

        $config += [
            'connect_timeout' => (float) config('guzzle.connect_timeout_sso'),
            'timeout'         => (float) config('guzzle.timeout_sso'),
            'on_stats'        => function (TransferStats $stats) {
                openlog('guzzle_request_log', LOG_PID | LOG_PERROR, LOG_LOCAL0);

                $request = json_decode((string) $stats->getRequest()->getBody(), true) ??
                    (string) $stats->getRequest()->getBody();

                if (is_array($request) && isset($request['password'])) {
                    $log['request']['password'] = 'HIDDEN_PASSWORD';
                } elseif (!is_array($request) && preg_match('/(password=.*+&?)|(&?password=.*+)/', $request)) {
                    $request = preg_replace('/(password=.*+&?)|(&?password=.*+)/', '', $request)
                        . '&password=HIDDEN_PASSWORD';
                }

                $log = [
                    'uri'           => $stats->getEffectiveUri(),
                    'transfer_time' => $stats->getTransferTime(),
                    'handler_stats' => $stats->getHandlerStats(),
                    'request_data'  => $request,
                ];

                if ($stats->hasResponse()) {
                    $log['response'] = json_decode((string) $stats->getResponse()->getBody(), true);

                    if (is_array($log['response']) && isset($log['response']['data']['password'])) {
                        $log['response']['data']['password'] = 'HIDDEN_PASSWORD';
                    }
                } else {
                    $log['error'] = $stats->getHandlerErrorData();
                }

                syslog(LOG_INFO, 'Request log [' . date('Y-m-d H:i:s') . '] ' . json_encode($log));

                closelog();
            },
        ];

        parent::__construct($config);
    }

    /**
     * Set Service name
     *
     * @param string $serviceName
     *
     * @throws InvalidArgumentException
     */
    public function setService(string $serviceName)
    {
        if (!in_array($serviceName, array_keys(self::$ssoServices))) {
            throw new InvalidArgumentException($serviceName . ' is not in SSO service list');
        }

        $this->baseUrl = self::$ssoServices[$serviceName];
    }

    /**
     * Set success status code
     *
     * @param int $httpStatusCode
     *
     * @return self
     */
    public function setSuccessStatus(int $httpStatusCode)
    {
        $this->successStatusCode = $httpStatusCode;

        return $this;
    }

    /**
     * @param mixed $method
     * @param string $uri
     * @param array $options
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    protected function makeRequest(mixed $method, string $uri = '', array $options = []) : ResponseInterface
    {
        return parent::request($method, $uri, $options);
    }

    /**
     * @param $method
     * @param string $uri
     * @param array $options
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function request($method, $uri = '', array $options = []) : ResponseInterface
    {
        $uri = $this->baseUrl . $uri;

        try {
            $response = $this->makeRequest($method, $uri, $options);

            if (isset($this->successStatusCode) && $this->successStatusCode !== $response->getStatusCode()) {
                throw new SsoUnknownResponseException($response->getStatusCode());
            }

            return $response;
        } catch (ClientException $exception) {
            $this->handleClientException($exception);
        } catch (ServerException $exception) {
            $serviceName      = $this->getServiceNameFromUri($exception->getRequest());
            $serviceErrorCode = SsoServerErrorException::SSO_ERROR_CODE[$serviceName . '_SERVICE_SERVER_ERROR'];

            throw new SsoServerErrorException($serviceErrorCode, null, $exception);
        } catch (ConnectException $exception) {
            $serviceName      = $this->getServiceNameFromUri($exception->getRequest());
            $serviceErrorCode = SsoServerErrorException::SSO_ERROR_CODE[$serviceName . '_SERVICE_UNAVAILABLE'];

            throw new SsoServerErrorException($serviceErrorCode, null, $exception);
        }
    }

    /**
     * Handles exception for status 4xx
     *
     * @param ClientException $exception
     *
     * @return null
     *
     * @throws UserNotFoundException
     * @throws SsoUnknownResponseException
     */
    protected function handleClientException(ClientException $exception)
    {
        $response     = $exception->getResponse();
        $responseData = json_decode($response->getBody()->getContents() ?? null, true);
        $errorCode = $responseData['error_code'] ?? null;

        if ($errorCode === ApplicationHttpException::APP_ERROR_CODE['USER_NOT_FOUND']) {
            throw new UserNotFoundException(null, $exception);
        }

        if ($errorCode === ApplicationHttpException::APP_ERROR_CODE['VALIDATION_ERROR']) {
            throw new ValidatorException(json_encode($responseData['errors']), $exception);
        }

        if ($errorCode === LocationException::LOCATION_ERROR_CODE['COUNTRY_NOT_FOUND']) {
            throw new CountryNotFoundException(null, $exception);
        }

        if ($errorCode === SessionInvalidException::SESSION_ERROR_CODE['SESSION_INVALID']) {
            throw new SessionInvalidException;
        }

        throw new SsoUnknownResponseException($response->getStatusCode(), $errorCode, $exception);
    }

    /**
     * Get service name from uri
     *
     * @param RequestInterface $request
     *
     * @return string|null
     */
    protected function getServiceNameFromUri(RequestInterface $request)
    {
        $requestUri = $request->getUri();

        foreach (self::$ssoServices as $name => $uri) {
            if (str_contains($requestUri, $uri)) {
                return $name;
            }
        }
    }
}
