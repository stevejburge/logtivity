<?php

class Logtivity_FrmEntryFormatter extends FrmEntryFormatter
{
	public function __construct( $atts )
	{
		parent::__construct($atts);
	}

	public function logtivityGetEntryValues()
	{
		return $this->entry_values->get_field_values();
	}
}