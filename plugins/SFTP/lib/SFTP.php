<?php

/**
 * SFTP Extension.
 * Provides basic functionality for uploading and downloading files via the SFTP protocol.
 */
class SFTP {
	private $connection;
	private $sftp;
	
	/**
	 * Constructor.
	 * Throws an Exception if the connection fails.
	 * @param string $host Host to connect to.
	 * @param int $port Port to connect to.
	 */
	public function __construct($host, $port=22) {
		$this->connection = @ssh2_connect($host, $port);
		if(!$this->connection) {
			throw new Exception("Could not connect to $host on port $port.");
		}
	}
	
	/**
	 * Log in to the SFTP server.
	 * Throws an Exception if authentication fails, or if the SFTP subsystem could not be initialised.
	 * 
	 * @param string $username Username for server.
	 * @param string $password Password for server.
	 */
	public function login($username, $password) {
		if(!@ssh2_auth_password($this->connection, $username, $password)){
			throw new Exception("Could not authenticate with username $username " . "and password $password.");
		}
		$this->sftp = @ssh2_sftp($this->connection);
		if(!$this->sftp){
			throw new Exception("Could not initialize SFTP subsystem.");
		}
	}
	
	/**
	 * Upload data to the specified file.
	 * If the file specified already exists on the server, it will be overwritten; if it does
	 * not exist, it will be created.
	 * An Exception will be thrown on any error.
	 * 
	 * @param string $data_to_send Data.
	 * @param string $remote_file Full path to the remote file to write to.
	 */
	public function uploadData($data_to_send, $remote_file) {
		$sftp = $this->sftp;
		$stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');
		if(!$stream){
			throw new Exception("Could not open file: $remote_file");
		}
		if(@fwrite($stream, $data_to_send) === false){
			throw new Exception("Could not send data from file: $local_file.");
		}
		@fclose($stream);
	}
	
	/**
	 * Upload a file to the remote server.
	 * If the file specified already exists on the server, it will be overwritten; if it does
	 * not exist, it will be created.
	 * An Exception will be thrown on any error.
	 * 
	 * @param string $local_file Path to the local file to send.
	 * @param string $remote_file Full path to the remote file to write to.
	 */
	public function uploadFile($local_file, $remote_file) {
		$sftp = $this->sftp;
		$stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');
		if(!$stream){
			throw new Exception("Could not open file: $remote_file");
		}
		$data_to_send = @file_get_contents($local_file);
		if($data_to_send === false){
			throw new Exception("Could not open local file: $local_file.");
		}
		if(@fwrite($stream, $data_to_send) === false){
			throw new Exception("Could not send data from file: $local_file.");
		}
		@fclose($stream);
	}
	
	/**
	 * Get a file from the remote server.
	 * If the local file exists, it will be overwritten; if not, it will be created.
	 * 
	 * @param string $remote_file Path to the remote file to be downloaded.
	 * @param string $local_file Path to the local file to write to.
	 */
	public function receiveFile($remote_file, $local_file) {
		$sftp = $this->sftp;
		$stream = @fopen("ssh2.sftp://$sftp$remote_file", 'r');
		if(!$stream){
			throw new Exception("Could not open file: $remote_file");	
		}
		$contents = fread($stream, filesize("ssh2.sftp://$sftp$remote_file"));
		file_put_contents($local_file, $contents);
		@fclose($stream);
	}
	
	/**
	 * Delete a file from the remote server.
	 * @param string $remote_file Full path to the remote file to be removed.
	 */
	public function deleteFile($remote_file) {
		$sftp = $this->sftp;
		unlink("ssh2.sftp://$sftp$remote_file");
	}
}