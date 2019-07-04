<?php

namespace App\Helpers;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * @author  13.09.2017
 */
class SmsHelper
{
    protected $_configs;
	protected $_connection;
    protected $_channel;
    
    public function __construct() {
        //  Init entity manager.
        $config = array(
            'SMS' => array(
		        'comLink'         =>   'http://sms.izifix.com/service/',       // Đường link webservice hỗ trợ gửi tin nhắn
	            'comCode'         =>   'co_c.525e4cfcd19b60.90850',           // Tên đăng nhập đến webservice
                'comPass'         =>   '123456',                              // Mật khẩu webservice
                'comPack'         =>   '31',                                  // Mã gói dịch vụ tin nhắn đã đăng ký với nhà cung cấp
                'brandName'       =>   'IZIFIX',                          // Brandname đã đăng ký với nhà cung cấp
                'failRepeat'      =>    1,                                    // Số lần lặp lại khi gửi ko đc
	        ),
		    'RabbitMQ' => array(
		        'ipAddress'       =>    '210.245.124.17',
	            'port'            =>    '5672',
                'username'        =>    'izifix',
                'password'        =>    'itvina;a@123',
                'virtualHost'     =>    'izifix',
                'exchange'        =>    'sms',
		    )
        );
        
        $this->_configs = json_decode(json_encode($config), false);
        try {
            	
            $this->_connection = $this->_getConnectionRabbit( $this->_configs->RabbitMQ );
        
            $this->_channel = $this->_getChannel( $this->_connection );
        
        } catch (Exception $e) {
            	
            echo ( Zend_Json::encode ( array (
                "error" => $e->getMessage()
            ) ) );
            return false;
        }
    }

    /**
     * @todo: Hàm khởi tạo kết nối
     * @author: Croco
     * @since: 1-9-2016
     * @param: Zend_Configs $configs
     * @return AMQPStreamConnection
     */
    protected function _getConnectionRabbit( $configs ) {
        
        $connection = new AMQPStreamConnection( $configs->ipAddress, $configs->port, $configs->username, $configs->password, $configs->virtualHost );
    
        return $connection;
    }

    /**
     * @todo: Hàm khởi tạo kênh
     * @author: Croco
     * @since: 1-9-2016
     * @param: AMQPStreamConnection $connection
     * @return: channel
     */
    protected function _getChannel( $connection ) {
        
        if( ! ($connection instanceof AMQPStreamConnection) ){
    
            throw new \Exception( $this->translate('Không thể kết nối') );
        }
    
        $channel = $connection->channel();
        $channel->queue_declare( $this->_configs->RabbitMQ->exchange , false, false, false, false);
    
        return $channel;
    }

    /**
     * @todo: Hàm Validate số điện thoại
     * @author: Croco
     * @since: 1-9-2016
     * @param: Number $phone
     * @return: Boolean
     */
     protected function _checkPhoneNumber( $phone ) {
        
        if( ! is_numeric( $phone ) )
            return false;
    
            $phone = (string)$phone;
    
            $length = strlen($phone);
    
            if( $length < 7 && $length > 20 )
                return false;
    
                return true;
    }

    /**
     * @todo: Action gửi tin nhắn đến 1 số
     * @author: Croco
     * @since: 1-9-2016
     * @param: smsPhone, content
     * @tutorial:
     * tạo 1 message gửi đến server RabbitMQ
     * Chưa kiểm tra bảo mật
     */
    public function sendSms($smsPhone, $message) {
        if( ! $this->_checkPhoneNumber($smsPhone) ) {
            // throw new Exception('Số điện thoại không hợp lệ: ' . $smsPhone);
            return false;
        }

        $this->_sendSMS($smsPhone, app('commonHelper')->convertViToEn($message));
        return true;
    }

    /**
     * @todo: Hàm tạo message cho rabbitmq
     * @author: Croco
     * @since: 1-9-2016
     * @param: Number $phone, String $message
     */
    protected function _sendSMS( $phone, $message ) {
        $param = array(
            'comCode'		=>		$this->_configs->SMS->comCode,
            'comPass'		=>		$this->_configs->SMS->comPass,
            'comPack'		=>		$this->_configs->SMS->comPack,
            'smsBrandname'	=>		$this->_configs->SMS->brandName,
            'data-type'		=>		'json',
            'smsPhone'		=>		$phone,
            'smsMessage'	=>		$message
        );
    
        $opts = array(
    
            'method'		=>		'POST',
            'header'		=>		'Content-type: application/x-www-form-urlencoded'
        );

        if( config('app.env') == "production" ) {

            $this->_publishMessage( array(
                'link'			=>		$this->_configs->SMS->comLink . 'sendsms',
                'options'		=>		$opts,
                'params'		=>		$param,
                'failRepeat'	=>		$this->_configs->SMS->failRepeat
            ) );
        }
    
    }

    /**
     * @todo: Hàm gửi message đến RabbitMQ
     * @author: Croco
     * @since: 1-9-2016
     * @param: String | Array $message
     */
    protected function _publishMessage( $message ) {
        
        $message = $this->_encode_string_array( $message );
    
        $message = new AMQPMessage( $message );
    
        $this->_channel->basic_publish($message, '', $this->_configs->RabbitMQ->exchange);
    }

    /**
     * @todo: Hàm serialize string or array
     * @author: Croco
     * @since: 1-9-2016
     * @param: String | Array $stringArray
     * @return: String
     */
    protected function _encode_string_array ( $stringArray ) {
        
        $s = strtr(base64_encode(addslashes(gzcompress(serialize( $stringArray), 2))), '+/=', '-_,');
        return $s;
    }
}