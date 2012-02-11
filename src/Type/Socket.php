<?php
/* 
 * @category IRCBot
 * @package IRCBot_Types
 * @subpackage Socket
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

namespace Ircbot\Type;

/**
 * The socket class
 */
class Socket
{
    /**
     * The socket resource
     * @var resource
     */
    private $_resource = null;
    public $socketId = 0;
    private $_endLineBuffer = '';
    /**
     * Sets the internal resource to a existing one
     * @param resource $&resource
     */
    public function setResource(&$resource)
    {
        $this->_resource = $resource;
    }
    /**
     * Creates and returns a socket resource, also referred to as an endpoint of
     * communication. A typical network connection is made up of 2 sockets, one
     * performing the role of the client, and another performing the role of the
     * server.
     * @param int $domain The domain parameter specifies the protocol family to
     * be used by the socket.
     * @param int $type The type parameter selects the type of communication to
     * be used by the socket.
     * @param int $protocol The protocol parameter sets the specific protocol
     * within the specified domain to be used when communicating on the
     * returned socket. The proper value can be retrieved by name by using
     * getprotobyname(). If the desired protocol is TCP, or UDP the
     * corresponding constants SOL_TCP, and SOL_UDP can also be used.
     */
    public function create($domain, $type, $protocol)
    {
        $this->_resource = socket_create($domain, $type, $protocol);
    }
    /**
     * This creates a new socket resource of type AF_INET listening on all local
     * interfaces on the given port waiting for new connections.
     * This method is meant to ease the task of creating a new socket which
     * only listens to accept new connections.
     * @param int $port The port on which to listen on all interfaces.
     * @param int $backlog The backlog parameter defines the maximum length the
     * queue of pending connections may grow to. SOMAXCONN may be passed as
     * backlog parameter
     * @link http://nl2.php.net/manual/en/function.socket-create-listen.php
     */
    public function createListen($port, $backlog = 128)
    {
        $this->_resource = socket_create($port, $backlog);
    }
    /**
     * This creates two connected and indistinguishable sockets, and stores
     * them in fd. This function is commonly used in
     * IPC (InterProcess Communication).
     * @param int $domain The domain parameter specifies the protocol family to
     * be used by the socket.
     * @param int $type The type parameter selects the type of communication to
     * be used by the socket.
     * @param int $protocol The protocol parameter sets the specific protocol
     * within the specified domain to be used when communicating on the
     * returned socket. The proper value can be retrieved by name by using
     * getprotobyname(). If the desired protocol is TCP, or UDP the
     * corresponding constants SOL_TCP, and SOL_UDP can also be used.
     * @param array $&fd Reference to an array in which the two socket resources
     * will be inserted.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function createPair($domain, $type, $protocol, array &$fd)
    {
        return socket_create_pair($domain, $type, $protocol, $fd);
    }
    /**
     * After the socket socket has been created using socke::create(),
     * bound to a name with socket_bind(), and told to listen for connections
     * with socket::listen(), this function will accept incoming connections on
     * that socket. Once a successful connection is made, a new socket resource
     * is returned, which may be used for communication. If there are multiple
     * connections queued on the socket, the first will be used. If there are
     * no pending connections, socket::accept() will block until a connection
     * becomes present. If socket has been made non-blocking using
     * socket::setBlocking() or socket::setNonBlock(), FALSE will be returned.
     * The socket resource returned by socket::accept() may not be used to
     * accept new connections. The original listening socket socket, however,
     * remains open and may be reused.
     * @return mixed Returns a new socket resource on
     * success, or FALSE on error. The actual error code can be retrieved by
     * calling socket::lastError(). This error code may be passed to
     * socket::strError() to get a textual explanation of the error.
     */
    public function accept()
    {
        $resource = socket_accept($this->_resource);
        if (is_resource($resource)) {
            $socket = new \Ircbot\Type\Socket();
            $socket->setResource($resource);
            return $socket;
        } else {
            return $resource;
        }
    }
    /**
     * The socket::setNonBlock() function sets the O_NONBLOCK flag on the
     * socket specified by the socket parameter.
     * When an operation (e.g. receive, send, connect, accept, ...) is
     * performed on a non-blocking socket, the script not pause its execution
     * until it receives a signal or it can perform the operation. Rather, if
     * the operation would result in a block, the called function will fail.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function setNonBlock()
    {
        return socket_set_nonblock($this->_resource);
    }
    /**
     * The socket_set_block() function removes the O_NONBLOCK flag on the 
     * socket specified by the socket parameter.
     * When an operation (e.g. receive, send, connect, accept, ...) is
     * performed on a blocking socket, the script will pause its execution
     * until it receives a signal or it can perform the operation.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function setBlock()
    {
        return socket_set_block($this->_resource);
    }
    /**
     * After the socket socket has been created using socket::create() and bound
     * to a name with socket::bind(), it may be told to listen for incoming
     * connections on socket.
     * socket::listen() is applicable only to sockets of type SOCK_STREAM or
     * SOCK_SEQPACKET.
     * @param int $backlog A maximum of backlog incoming connections will be 
     * queued for processing. If a connection request arrives with the queue 
     * full the client may receive an error with an indication of ECONNREFUSED, 
     * or, if the underlying protocol supports retransmission, the request may 
     * be ignored so that retries may succeed.
     * @return mixed Returns TRUE on success or FALSE on failure. The error 
     * code can be retrieved with socket::lastError(). This code may be passed 
     * to socket::strError() to get a textual explanation of the error.
     */
    public function listen($backlog = 0)
    {
        return socket_listen($this->_resource, $backlog);
    }
    /**
     * socket::close() closes the socket resource given by socket. This
     * function is specific to sockets and cannot be used on any other type of
     * resources.
     */
    public function close()
    {
        socket_close($this->_resource);
    }
    /**
     * The function socket::write() writes to the socket from the given buffer.
     * @param string $buffer
     * @param int $length
     * @return mixed Returns the number of bytes successfully written to the
     * socket or FALSE on failure. The error code can be retrieved with
     * socket::lastError(). This code may be passed to socket::strError() to get
     * a textual explanation of the error.
     */
    public function write($buffer, $length = 0)
    {
        if ($length == 0) {
            $length = strlen($buffer) + 1;
        }
        return socket_write($this->_resource, $buffer, $length);
    }
    /**
     * The function socket::read() reads from the socket resource socket
     * created by the socket::create() or socket:;accept() functions.
     * @param int $length The maximum number of bytes read is specified by the
     * length parameter. Otherwise you can use \r, \n, or \0 to end reading
     * (depending on the type parameter, see below).
     * @param int $type Optional type parameter is a named constant:
     * PHP_BINARY_READ (Default) - use the system recv() function. Safe for
     * reading binary data.
     * PHP_NORMAL_READ - reading stops at \n or \r.
     * @return mixed socket::read() returns the data as a string on success,
     * or FALSE on error (including if the remote host has closed the
     * connection). The error code can be retrieved with socket::lastError().
     * This code may be passed to socket::strError() to get a textual
     * representation of the error.
     */
    public function read($length, $type = PHP_BINARY_READ)
    {
        return socket_read($this->_resource, $length, $type);
    }
    /**
     * Note: socket::getSockName() should not be used with AF_UNIX sockets
     * created with socket::connect(). Only sockets created with
     * socket::accept() or a primary server socket following a call to
     * socket::bind() will return meaningful values.
     * @param string &$addr If the given socket is of type AF_INET or AF_INET6,
     * socket::getSockName() will return the local IP address in appropriate 
     * notation (e.g. 127.0.0.1 or fe80::1) in the address parameter and, 
     * if the optional port parameter is present, also the associated port.
     * If the given socket is of type AF_UNIX, socket::getSockName() will 
     * return the Unix filesystem path (e.g. /var/run/daemon.sock) in the 
     * address parameter.
     * @param int &$port If provided, this will hold the associated port.
     * @return bool Returns TRUE on success or FALSE on failure. 
     * socket::getSockName() may also return FALSE if the socket type is not 
     * any of AF_INET, AF_INET6, or AF_UNIX, in which case the last socket 
     * error code is not updated. 
     */
    public function getSockName(&$addr, &$port = null)
    {
        return socket_getsockname($this->_resource, $addr, $port);
    }
    /**
     * Queries the remote side of the given socket which may either result in
     * host/port or in a Unix filesystem path, dependent on its type.
     * @param string &$address If the given socket is of type AF_INET or
     * AF_INET6, socket::getPeerName() will return the peers (remote) IP
     * address in appropriate notation (e.g. 127.0.0.1 or fe80::1) in the
     * address parameter and, if the optional port parameter is present, also
     * the associated port.
     * If the given socket is of type AF_UNIX, socket::getPeerName() will
     * return the Unix filesystem path (e.g. /var/run/daemon.sock) in the
     * address parameter.
     * @param int &$port If given, this will hold the port associated to
     * address.
     * @return bool Returns TRUE on success or FALSE on failure.
     * socket::getPeerName() may also return FALSE if the socket type is not
     * any of AF_INET, AF_INET6, or AF_UNIX, in which case the last socket
     * error code is not updated.
     */
    public function getPeerName(&$address, &$port = null)
    {
        return socket_getpeername($this->_resource, $address, $port);
    }
    /**
     * Initiate a connection to address using the socket resource socket,
     * which must be a valid socket resource created with socket::create().
     * @param string $address The address parameter is either an IPv4 address
     * in dotted-quad notation (e.g. 127.0.0.1) if socket is AF_INET, a valid
     * IPv6 address (e.g. ::1) if IPv6 support is enabled and socket is AF_INET6
     * or the pathname of a Unix domain socket, if the socket family is AF_UNIX.
     * @param int $port The port parameter is only used and is mandatory when
     * connecting to an AF_INET or an AF_INET6 socket, and designates the port
     * on the remote host to which a connection should be made.
     * @return bool Returns TRUE on success or FALSE on failure. The error
     * code can be retrieved with socket::lastError(). This code may be passed
     * to socket::strError() to get a textual explanation of the error
     */
    public function connect($address, $port = 0)
    {
        return socket_connect($this->_resource, $address, $port);
    }
    /**
     * socket::strError() takes as its errno parameter a socket error code as 
     * returned by socket::lastError() and returns the corresponding 
     * explanatory text.
     * @param int $errno A valid socket error number, likely produced by 
     * socket::lastError().
     * @return string Returns the error message associated with the errno 
     * parameter. 
     */
    public function strError($errno)
    {
        return socket_strerror($errno);
    }
    /**
     * Binds the name given in address to the socket described by socket. 
     * This has to be done before a connection is be established using 
     * socket::connect() or socket::listen().
     * @param string $address If the socket is of the AF_INET family, the 
     * address is an IP in dotted-quad notation (e.g. 127.0.0.1).
     * If the socket is of the AF_UNIX family, the address is the path of a 
     * Unix-domain socket (e.g. /tmp/my.sock).
     * @param int $port The port parameter is only used when binding an 
     * AF_INET socket, and designates the port on which to listen for 
     * connections.
     * @return bool Returns TRUE on success or FALSE on failure.
     * The error code can be retrieved with socket::lastError(). This code may 
     * be passed to socket::strError() to get a textual explanation of the
     * error. 
     */
    public function bind($address, $port = 0)
    {
        return socket_bind($this->_resource, $address, $port);
    }
    public function recv(&$buf, $len, $flags)
    {
        return socket_recv($this->_resource, $buf, $len, $flags);
    }
    public function send($buf, $len, $flags)
    {
        return socket_send($this->_resource, $buf, $len, $flags);
    }
    public function recvFrom(&$buf, $len, $flags, &$name, &$port = null)
    {
        return socket_recvfrom(
            $this->_resource, $buf, $len, $flags, $name, $port
        );
    }
    public function sendTo($buf, $len, $flags, $addr, $port = 0)
    {
        return socket_sendto(
            $this->_resource, $buf, $len, $flags, $addr, $port
        );
    }
    public function getOption($level, $optname)
    {
        return socket_get_option($this->_resource, $level, $optname);
    }
    public function setOption($level, $optname, $optval)
    {
        return socket_set_option($this->_resource, $level, $optname, $optval);
    }
    public function shutdown($how = 2)
    {
        return socket_shutdown($this->_resource, $how);
    }
    public function lastError()
    {
        return socket_last_error($this->_resource);
    }
    public function clearError()
    {
        return socket_clear_error($this->_resource);
    }
    public function readLine()
    {
        $data = '';
        $this->_endLineBuffer .= socket_read($this->_resource, 512);
        $this->_endLineBuffer = explode("\n", $this->_endLineBuffer);
        if (count($this->_endLineBuffer) > 1) {
            $data = array_shift($this->_endLineBuffer);
        }
        $this->_endLineBuffer = implode("\n", $this->_endLineBuffer);
        return $data;
    }
}
