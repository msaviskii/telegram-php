<?php
// doc https://gist.github.com/zqsd/c273411c02d11bae364e
// doc https://core.telegram.org/methods

class Telegram_Bot {
	public function getMe() {
		return $this->_get('getMe');
	}
	public function _get($method_name, array $aDatas = array()) {
		$postdata = http_build_query($aDatas);

		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);

		$context  = stream_context_create($opts);
		$result = file_get_contents('https://api.telegram.org/bot'.TELEGRAM_BOT_TOKEN.'/'.$method_name, false, $context);

		// Result analyse :
		if ( ! $result ) {
			throw new \Exception('return empty');
		}
		$result = json_decode($result);
		if ( ! $result->ok ) {
			throw new \Exception('return false');
		}
		$result = $result->result;
		return $result;
	}
	protected $_channel = 0;
	public function setChannel($id_channel) {
		$this->_channel = $id_channel;
	}
	public function talk($sMessage) {
		return $this->sendMessage($this->_channel, $sMessage);
	}
	public function sendMessage($iChannel, $sMessage) {
		return $this->_get('sendMessage', array(
			'chat_id'	=> $iChannel,
			'text'		=> $sMessage,
		));
	}
	public function getUpdates() {
		return $this->_get('getUpdates');
	}
	public function test($id_channel) {
		$this->setChannel($id_channel);
		var_export($this->talk(date('c')));
		echo "\n";
	}
	/**
	 * TODO :
	**/
	public function readHistory($iIdChat, $iMessageId) {
		return  $this->_get('readHistory', array(
                        'peer'		=> $iIdChat,
                        'max_id'	=> $iMessageId,
                ));
	}
}

