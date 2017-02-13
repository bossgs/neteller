<?php
namespace grandmasterx\neteller\api;

/**
 * Class CancelSubscription
 * @package grandmasterx\neteller\api
 */
class CancelSubscription extends NetellerApi
{
    /**
     * @var
     */
    public $subscriptionId;

    /**
     * @var
     */
    public $executionErrors;

    /**
     * @param $subscriptionId
     * @return $this
     */
    public function setSubscriptionId($subscriptionId) {
        $this->subscriptionId = $subscriptionId;
        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function doRequest() {
        $token = $this->getToken_ClientCredentials();

        if ($token == false) {
            return false;
        }

        $headers = [
            "Content-type"  => "application/json",
            "Authorization" => "Bearer " . $token
        ];

        $queryParams = [];

        $response = $this->post("v1/subscriptions/" . $this->subscriptionId . "/cancel", $queryParams, $headers, []);

        $responseInfo = $response['info'];
        $responseBody = json_decode($response['body']);

        if ($responseInfo['http_code'] == 200) {
            return $responseBody;
        } elseif ($responseInfo['http_code'] >= 400) {
            $this->executionErrors = [
                'http_status_code'  => $responseInfo['http_code'],
                'api_error_code'    => $responseBody->error->code,
                'api_error_message' => $responseBody->error->message,
                'api_resource_used' => 'POST /v1/subscriptions/{}/cancel'
            ];
            return false;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getExecutionErrors() {
        return $this->executionErrors;
    }
}
