<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Studio\V2\Flow;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
class FlowTestUserContext extends InstanceContext {
    /**
     * Initialize the FlowTestUserContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sid The sid
     */
    public function __construct(Version $version, $sid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = ['sid' => $sid, ];

        $this->uri = '/Flows/' . \rawurlencode($sid) . '/TestUsers';
    }

    /**
     * Fetch the FlowTestUserInstance
     *
     * @return FlowTestUserInstance Fetched FlowTestUserInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): FlowTestUserInstance {
        $payload = $this->version->fetch('GET', $this->uri);

        return new FlowTestUserInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Update the FlowTestUserInstance
     *
     * @param string[] $testUsers The test_users
     * @return FlowTestUserInstance Updated FlowTestUserInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $testUsers): FlowTestUserInstance {
        $data = Values::of(['TestUsers' => Serialize::map($testUsers, function($e) { return $e; }), ]);

        $payload = $this->version->update('POST', $this->uri, [], $data);

        return new FlowTestUserInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Studio.V2.FlowTestUserContext ' . \implode(' ', $context) . ']';
    }
}