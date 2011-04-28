<?php
// ssh protocols
// note: once openShell method is used, cmdExec does not work
class SSH {

	private $host = null;
	private $user = null;
	private $port = '22';
	private $password = '';
	private $con = null;
	private $shell_type = 'xterm';
	private $shell = null;

	public function __construct($host,$port=null) {
		$this->host  = $host;
		if($port!=null) {
			$this->port = $port;
		}
		$this->con = @ssh2_connect($this->host, $this->port);
		if(!$this->con) {
			throw new Exception('Failed to connect to host');
		}
	}

	public function auth($user,$password=null) {
		$this->user = $user;
		if($password!=null) {
			$this->password = $password;
		}
		if(!ssh2_auth_password($this->con,$this->user,$this->password)) {
			throw new Exception('Failed to connect to host');
		}
	}

	public function openShell($shell_type=null) {
		if($shell_type!=null) {
			$this->shell_type = $shell_type;
		}
		$this->shell = @ssh2_shell($this->con,$this->shell_type);
		if(!$this->shell){
			throw new Exception('Shell connection failed');
		}
	}

	public function writeShell($command) {
		fwrite($this->shell,$command."\n");
	}

	public function cmdExec() {
		$argc = func_num_args();
		$argv = func_get_args();
		$cmd = '';
		for($i=0;$i<$argc;$i++) {
			if($i!=($argc-1)) {
				$cmd .= $argv[$i]." && ";
			} else {
				$cmd .= $argv[$i];
			}
		}
		echo $cmd;
		$stream = ssh2_exec($this->con,$cmd);
		stream_set_blocking($stream,true);
		return fread( $stream, 4096 );
	}
}