<?php
namespace PhpAmqpLib\Connection;

use PhpAmqpLib\Wire\IO\StreamIO;

class AMQPStreamConnection extends AbstractConnection
{
    /**
     * @param AbstractConnection $host
     * @param string $port
     * @param string $user
     * @param bool $password
     * @param string $vhost
     * @param bool $insist
     * @param string $login_method
     * @param null $login_response
     * @param string $locale
     * @param int $connection_timeout
     * @param int $read_write_timeout
     * @param null $context
     * @param bool $keepalive
     * @param int $heartbeat
     * @param float $channel_rpc_timeout
     */
    public function __construct(
        $host,
        $port,
        $user,
        $password,
        $vhost = '/',
        $insist = false,
        $login_method = 'AMQPLAIN',
        $login_response = null,
        $locale = 'en_US',
        $connection_timeout = 2,
        $read_write_timeout = 3,
        $context = null,
        $keepalive = false,
        $heartbeat = 0,
        $channel_rpc_timeout = 0.0
    ) {
        if ($channel_rpc_timeout > $read_write_timeout)
        {
            throw new \InvalidArgumentException('channel RPC timeout must not be greater than I/O read-write timeout');
        }

        $io = new StreamIO($host, $port, $connection_timeout, $read_write_timeout, $context, $keepalive, $heartbeat);

        parent::__construct($user, $password, $vhost, $insist, $login_method, $login_response, $locale, $io, $heartbeat, $connection_timeout, $channel_rpc_timeout);

        // save the params for the use of __clone, this will overwrite the parent
        $this->construct_params = func_get_args();
    }
}
