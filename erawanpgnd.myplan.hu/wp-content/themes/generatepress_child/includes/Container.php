<?php

namespace Erawan;

class Container {
	private $instances = array();

	public function set( string $key, $instance ) {
		$this->instances[ $key ] = $instance;
	}

	public function get( string $key ) {
		return $this->instances[ $key ] ?? null;
	}
}
