<?php

class Logtivity_Post extends Logtivity_Abstract_Logger
{
	public function registerHooks()
	{
		add_action( 'transition_post_status', [$this, 'postStatusChanged'], 10, 3);
		add_action( 'wp_trash_post', [$this, 'postWasTrashed'], 10, 1 );
		add_filter('wp_handle_upload', [$this, 'mediaUploaded'], 10, 2);
		add_action('delete_post', [$this, 'postPermanentlyDeleted'], 10, 1);
		add_filter( 'wp_ajax_save-attachment', [$this, 'mediaMetaUpdated'], -1 );
	}

	public function postStatusChanged($new_status, $old_status, $post)
	{
		if ($this->shouldIgnore($post)) {
			return;
		}

		if ($old_status == 'trash') {
			return $this->postWasRestored($post);
		}

		if ($old_status != 'publish' && $new_status == 'publish') {
			return $this->postWasPublished($post);
		}

		return $this->postWasUpdated($post, $old_status);
	}

	public function postWasPublished($post)
	{
		return Logtivity_Logger::log()
			->setAction($this->getPostTypeLabel($post->ID) . ' Published')
			->setContext($post->post_title)
			->addMeta('Post ID', $post->ID)
			->addMeta('Post Title', $post->post_title)
			->addMeta('Post Type', $post->post_type)
			->addMeta('Post Status', $post->post_status)
			->send();
	}

	/**
	 * Post was updated or created. ignoring certain auto save system actions
	 * 
	 * @param  integer $post_id 
	 * @param  WP_Post $post    
	 * @param  bool $update
	 * @return void
	 */
	public function postWasUpdated($post, $old_status)
	{
		return Logtivity_Logger::log()
			->setAction($this->getPostTypeLabel($post->ID) . ' Updated')
			->setContext($post->post_title)
			->addMeta('Post ID', $post->ID)
			->addMeta('Post Title', $post->post_title)
			->addMeta('Post Type', $post->post_type)
			->addMeta('Post Status', $post->post_status)
			->addMeta('Old Status', $old_status)
			->send();
	}

	public function postWasTrashed($post_id)
	{
		if (get_post_type($post_id) == 'customize_changeset') {
			return;
		}
		
		return Logtivity_Logger::log()
			->setAction($this->getPostTypeLabel($post_id) . ' Trashed')
			->setContext(get_the_title($post_id))
			->addMeta('Post ID', $post_id)
			->addMeta('Post Type', get_post_type($post_id))
			->addMeta('Post Title', get_the_title($post_id))
			->send();
	}

	public function postWasRestored($post)
	{
		return Logtivity_Logger::log()
			->setAction(
				$this->getPostTypeLabel($post->ID) . ' Restored from Trash'
			)
			->setContext($post->post_title)
			->addMeta('Post ID', $post->ID)
			->addMeta('Post Type', $post->post_type)
			->addMeta('Post Title', $post->post_title)
			->send();
	}

	public function postPermanentlyDeleted($post_id)
	{
		if ($this->ignoringPostType(get_post_type($post_id))) {
			return;
		}

		if ($this->ignoringPostTitle(get_the_title($post_id))) {
			return;
		}

		return Logtivity_Logger::log()
			->setAction(
				$this->getPostTypeLabel($post_id) . ' Permanently Deleted'
			)
			->setContext(get_the_title($post_id))
			->addMeta('Post ID', $post_id)
			->addMeta('Post Type', get_post_type($post_id))
			->addMeta('Post Title', get_the_title($post_id))
			->send();
	}

	public function mediaUploaded($upload, $context)
	{
		Logtivity_Logger::log()
			->setAction('Attachment Uploaded')
			->setContext(basename($upload['file']))
			->addMeta('Url', $upload['url'])
			->addMeta('Type', $upload['type'])
			->addMeta('Context', $context)
			->send();

		return $upload;
	}

	public function mediaMetaUpdated() 
	{
		$post_id = absint($_POST['id']);

		if ($post_id) {
			Logtivity_Logger::log()
				->setAction('Attachment Meta Updated.')
				->addMeta("Media ID", $post_id)
				->addMeta("Changes", ( isset($_POST['changes']) ? $_POST['changes'] : null))
				->send();
		}

		return $post;
	}
}

$Logtivity_Post = new Logtivity_Post;