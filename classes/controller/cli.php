<?php

	/**
		This is a CLI controller class that is oriented on writing directly
		to output streams, and not using the Request object or views.
	**/
	class Controller_CLI extends Controller {

		// Stream handles
		protected $_stdout = null;
		protected $_stderr = null;

		public function before () {
			parent::before();
			// Throw a 404 if we are not running from CLI
			if( PHP_SAPI != 'cli' ) { throw new HTTP_Exception_404();

			// Open handles to streams
			$this->_stdout = fopen("php://stdout", "w");
			$this->_stderr = fopen("php://stderr", "w");
		}

		public function after () {
			parent::after();

			// Close handles to streams (quietly)
			@fclose( $this->stdout );
			@fclose( $this->stderr );
		}


		// Just a pass-through to built in CLI stuff
		protected option ( $options ) { return CLI::options( $options ); }

		// Write raw to STDOUT
		protected stdout ( $message, $flush = false ) {
			fwrite( $this->_stdout, $message );
			if( $flush ) { fflush( $this->_stdout ); }
		}
		
		// Write a line to STDOUT
		protected stdout_ln ( $message, $flush ) {
			$this->stdout( $message . "\r\n", $flush );
		}

		// Write raw to STDERR
		protected stderr ( $message, $flush = false ) {
			fwrite( $this->_stderr, $message );
			if( $flush ) { fflush( $this->stderr ); }
		}

		// Write a line to STDERR
		protected stderr_ln ( $message, $flush ) {
			$this->stderr( $message . "\r\n", $flush );
		}

	}
