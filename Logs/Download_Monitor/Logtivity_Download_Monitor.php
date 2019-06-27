<?php

class Logtivity_Download_Monitor extends Logtivity_Abstract_Logger
{
	public function __construct()
	{
		add_action('dlm_downloading',  [$this, 'itemDownloaded']);
	}

	public function itemDownloaded($download)
	{
		return Logtivity_Logger::log()
					->setAction('DLM Item Downloaded. [' . $download->get_title() . ']')
					->addMeta('Download Title', $download->get_title())
					->addMeta('Download Slug', $download->get_slug())
					->addMeta('Download ID', $download->get_id())
					->addMeta('Download Count', $download->get_download_count())
					->addMeta('Is Featured', $download->is_featured())
					->addMeta('Is Members Only', $download->is_members_only())
					->send();
	}
}

$Logtivity_Download_Monitor = new Logtivity_Download_Monitor;