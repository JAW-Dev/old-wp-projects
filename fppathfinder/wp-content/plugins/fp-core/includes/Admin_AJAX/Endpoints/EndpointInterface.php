<?php

namespace FP_Core\Admin_AJAX\Endpoints;

interface EndpointInterface {
	public function get_name(): string;
	public function get_handler(): callable;
	public function get_nopriv_handler(): callable;
}
