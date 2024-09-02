<?php

/**
 * @copyright  https://github.com/UsabilityDynamics/zoom-api-php-client/blob/master/LICENSE
 */
namespace Zoom\Endpoint;

use Zoom\Interfaces\Request;

/**
 * Class Assistants
 * @package Zoom\Endpoint
 */
class Assistants extends Request {

    /**
     * Assistants constructor.
     * @param $apiKey
     * @param $apiSecret
     */
    public function __construct($apiKey, $apiSecret) {
        parent::__construct($apiKey, $apiSecret);
    }

    /**
     * List
     *
     * @param $userId
     * @param array $query
     * @return array|mixed
     */
    public function list(string $userId, array $query = []) {
        return $this->get("users/{$userId}/assistants", $query);
    }

    /**
     * Create
     *
     * @param $userId
     * @param array $data
     * @return array|mixed
     */
    public function create(string $userId, array $data  = null) {
        return $this->post("users/{$userId}/assistants", $data);
    }

    /**
     * Assistant
     *
     * @param $assistantId
     * @return array|mixed
     */
    public function assistant(string $assistantId) {
        return $this->get("assistants/{$assistantId}");
    }

    /**
     * Remove
     *
     * @param $assistantId
     * @return array|mixed
     */
    public function remove(string $assistantId) {
        return $this->delete("assistants/{$assistantId}");
    }

    /**
     * Update
     *
     * @param $assistantId
     * @param array $data
     * @return array|mixed
     */
    public function update(string $assistantId, array $data = []) {
        return $this->patch("assistants/{$assistantId}", $data);
    }

    /**
     * Status
     *
     * @param $assistantId
     * @param array $data
     * @return mixed
     */
    public function status(string $assistantId, array $data = []) {
        return $this->put("assistants/{$assistantId}/status", $data);
    }

    /**
     * List Registrants
     *
     * @param $assistantId
     * @param array $query
     * @return array|mixed
     */
    public function listRegistrants(string $assistantId, array $query = []) {
        return $this->get("assistants/{$assistantId}/registrants", $query);
    }

    /**
     * Add Registrant
     *
     * @param $assistantId
     * @param array $data
     * @return array|mixed
     */
    public function addRegistrant(string $assistantId, $data = []) {
        return $this->post("assistants/{$assistantId}/registrants", $data);
    }

    /**
     * Update Registrant Status
     *
     * @param $assistantId
     * @param array $data
     * @return array|mixed
     */
    public function updateRegistrantStatus(string $assistantId, array $data = []) {
        return $this->put("assistants/{$assistantId}/registrants/status", $data);
    }

    /**
     * Past Assistant
     *
     * @param $assistantUUID
     * @return array|mixed
     */
    public function pastAssistant(string $assistantUUID) {
        return $this->get("past_assistants/{$assistantUUID}");
    }

    /**
     * Past Assistant Participants
     *
     * @param $assistantUUID
     * @param array $query
     * @return array|mixed
     */
    public function pastAssistantParticipants(string $assistantUUID, array $query = []) {
        return $this->get("past_assistants/{$assistantUUID}/participants", $query);
    }

}