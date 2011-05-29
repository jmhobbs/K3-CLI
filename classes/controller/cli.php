<?php

	/**
		This is a CLI controller class that is oriented on writing directly
		to output streams, and not using the Request object or views.
	**/
	class Controller_CLI extends Controller {

		// Stream handles
		protected $_stdout = null;
		protected $_stderr = null;

		protected $_color_on = false;

		const BLACK   = 30;
		const RED     = 31;
		const GREEN   = 32;
		const YELLOW  = 33;
		const BLUE    = 34;
		const MAGENTA = 35;
		const CYAN    = 36;
		const WHITE   = 37;

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

			// Turn of color on STDOUT
			$this->no_color();

			// Close handles to streams (quietly)
			@fclose( $this->stdout );
			@fclose( $this->stderr );
		}


		// Just a pass-through to built in CLI stuff
		protected function option ( $options ) { return CLI::options( $options ); }

		// Write raw to STDOUT
		protected function stdout ( $message, $flush = false ) {
			fwrite( $this->_stdout, $message );
			if( $flush ) { fflush( $this->_stdout ); }
		}
		
		// Write a line to STDOUT
		protected function stdout_ln ( $message, $flush ) {
			$this->stdout( $message . "\r\n", $flush );
		}

		// Write raw to STDERR
		protected function stderr ( $message, $flush = false ) {
			fwrite( $this->_stderr, $message );
			if( $flush ) { fflush( $this->stderr ); }
		}

		// Write a line to STDERR
		protected function stderr_ln ( $message, $flush ) {
			$this->stderr( $message . "\r\n", $flush );
		}

		// Start a color on stdout
		protected function color ( $color ) {
			fwrite( $this->_stdout, chr(27) . '[0;' . $color . 'm' );
			$this0>_color_on = true;
		}
		
		protected function no_color () {
			fwrite( $this->_stdout, chr(27) . '[m' );
			$this->_color_on = false;
		}

	}
