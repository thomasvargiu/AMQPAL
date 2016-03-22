<?php

namespace AMQPAL\Adapter;

/**
 * Interface ConnectionInterface
 *
 * @package AMQPAL\Adapter
 */
interface ConnectionInterface
{
    /**
     * Establish a connection with the AMQP broker.
     *
     * @return $this
     */
    public function connect();

    /**
     * Close any open connections and initiate a new one with the AMQP broker.
     *
     * @return $this
     */
    public function reconnect();

    /**
     * Closes the connection with the AMQP broker.
     *
     * @return $this
     */
    public function disconnect();

    /**
     * Check whether the connection to the AMQP broker is still valid.
     *
     * @return bool
     */
    public function isConnected();
}
