<?php

class Logtivity_Comment
{
	public function __construct()
	{
		add_action('comment_post', [$this, 'commentCreated'], 10, 3);
		add_action( 'wp_set_comment_status', [$this, 'statusChanged'], 10, 2);
		add_action( 'unspam_comment', [$this, 'commentMarkedAsNotspam'], 10, 2);
	}

	public function commentCreated($comment_ID, $comment_approved, $commentdata)
	{
		if ($comment_approved === 'spam') {
			return;
		}
		
		return Logtivity_Logger::log()
			->setAction('Comment Created')
			->setContext(substr(strip_tags($commentdata['comment_content']), 0, 30).'...') 
			->addMeta('Author', $commentdata['comment_author'] ?? null)
			->addMeta('Approved', $comment_approved)
			->addMeta('Post URL', get_permalink($commentdata['comment_post_ID']))
			->send();
	}

	public function statusChanged($comment_id, $comment_status)
	{
		if (in_array($comment_status, ['0', '1'])) {
			return;
		}

		return Logtivity_Logger::log()
			->setAction('Comment Status Changed')
			->setContext($comment_status) 
			->addMeta('Comment ID', $comment_id)
			->send();
	}

	public function commentMarkedAsNotspam($comment_ID, $comment)
	{
		return $this->statusChanged($comment->comment_ID, 'Not Spam');
	}
}

$Logtivity_Comment = new Logtivity_Comment;