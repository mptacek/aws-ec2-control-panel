<?php

namespace App;

use Aws\Ec2\Ec2Client;

class Service
{
    /**
     * @var Ec2Client
     */
    protected $client;

    protected $dryRun = true;

    public function __construct(Ec2Client $client, $dryRun)
    {
        $this->client = $client;
        $this->dryRun = $dryRun;
    }

    /**
     * Returns array of available instances
     *
     * @return array
     */
    public function getInstances()
    {
        $instances = [];

        $result = $this->client->describeInstances();

        $reservations = $result['Reservations'];
        foreach ($reservations as $reservation) {
            foreach ($reservation['Instances'] as $instance) {
                $name = '';
                foreach ($instance['Tags'] as $tag) {
                    if ($tag['Key'] == 'Name') {
                        $name = $tag['Value'];
                        break;
                    }
                }

                $token = md5($name . $instance['InstanceId']);

                $instances[$token] = [
                    'name' => $name,
                    'id' => $instance['InstanceId'],
                    'state' => $instance['State']['Name'],
                    'token' => $token,
                ];
            }
        }

        return $instances;
    }

    /**
     * Start instance
     *
     * @param $instanceId
     * @return \Aws\Result
     */
    public function startInstance($instanceId)
    {
        $result = $this->client->startInstances([
            'InstanceIds' => [$instanceId],
            'DryRun' => $this->dryRun,
        ]);

        return $result;
    }

    /**
     * Stop instance
     *
     * @param $instanceId
     * @return \Aws\Result
     */
    public function stopInstance($instanceId)
    {
        $result = $this->client->stopInstances([
            'InstanceIds' => [$instanceId],
            'DryRun' => $this->dryRun,
        ]);

        return $result;
    }
}
