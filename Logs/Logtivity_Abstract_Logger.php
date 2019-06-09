<?php

abstract class Logtivity_Abstract_Logger
{
	protected $logger;

	protected $ignoredPostTypes = ['revision', 'customize_changeset'];
	
	protected $ignoredPostTitles = ['Auto Draft'];
	
	protected $ignoredPostStatuses = ['trash'];

	public function __construct()
	{
		$this->registerHooks();
	}

	/**
	 * Check against certain rules on whether we should ignore the logging of a certain post
	 * 
	 * @param  WP_Post $post
	 * @return bool
	 */
	protected function shouldIgnore($post)
	{
		if ($this->ignoringPostType($post->post_type)) {
			return true;
		}

		if ($this->ignoringPostTitle($post->post_title)) {
			return true;
		}

		if ($this->ignoringPostStatus($post->post_status)) {
			return true;
		}

		return false;
	}

	/**
	 * Ignoring certain post statuses. Example: trash. 
	 * We already have a postWasTrashed hook so 
	 * don't need to log twice.
	 * 
	 * @param  string $post_status
	 * @return bool
	 */
	protected function ignoringPostStatus($post_status)
	{
		return in_array($post_status, $this->ignoredPostStatuses);
	}

	/**	
	 * Ignoring certain post types. Particularly system generated
	 * that are not directly triggered by the user.
	 * 
	 * @param  string $post_type
	 * @return bool
	 */
	protected function ignoringPostType($post_type)
	{
		return in_array($post_type, $this->ignoredPostTypes);
	}

	/**	
	 * Ignore certain system generated post titles
	 * 
	 * @param  string $title
	 * @return bool
	 */
	protected function ignoringPostTitle($title)
	{
		return in_array($title, $this->ignoredPostTitles);
	}

	/**	
	 * Generate a label version of the given post ids post type
	 * 
	 * @param  integer $post_id
	 * @return string
	 */
	protected function getPostTypeLabel($post_id)
	{
		return ucwords( str_replace(['_', '-'], ' ', get_post_type($post_id)) );
	}
}