<?php

class Logtivity_Formidable
{
	protected $ignoreFieldTypes = [
		'password',
	];

	public function __construct()
	{
		add_action('frm_after_create_entry', [$this, 'entryCreated'], 10, 3);
	}

	public function entryCreated($entry_id, $form_id, $is_child)
	{
		try {
			$entry = FrmEntry::getOne( $entry_id, true );

			$FrmEntryFormatter = new Logtivity_FrmEntryFormatter([
				'entry' => $entry,
				'format' => 'array',
			]);

			$log = Logtivity_Logger::log()
				->setAction('Form Submitted')
				->setContext($entry->form_name)
				->addMeta('Form ID', $form_id)
				->addMeta('Entry ID', $entry_id);

			foreach ($FrmEntryFormatter->logtivityGetEntryValues() as $key => $field_value) {
				$field_value->prepare_displayed_value();
				$log->addMeta($field_value->get_field_label(), $this->maybeLogFormValue($field_value));
			}

			$log->send();
		} catch (\Exception $e) {
			error_log($e);
		}
	}

	private function maybeLogFormValue($field_value)
	{
		if (in_array($field_value->get_field_type(), $this->ignoreFieldTypes)) {
			return '******';
		}

		return $field_value->get_displayed_value();
	}
}

$Logtivity_Formidable = new Logtivity_Formidable;