<?php
/**
 *
 * MOD BBCode extension for the phpBB Forum Software package
 *
 * @copyright (c) 2021, Kailey Snay, https://www.snayhomelab.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kaileymsnay\modbbcode\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * MOD BBCode event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth        $auth
	 * @param \phpbb\request\request  $request
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\request\request $request)
	{
		$this->auth = $auth;
		$this->request = $request;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.posting_modify_submit_post_before'	=> 'posting_modify_submit_post_before',
			'core.posting_modify_template_vars'			=> 'posting_modify_template_vars',

			'core.text_formatter_s9e_parse_before'	=> 'onParse',

			'core.user_setup'	=> 'user_setup',
		];
	}

	public function posting_modify_submit_post_before($event)
	{
		if (in_array($event['mode'], ['post', 'edit']) && !$event['post_data']['post_edit_locked'] && strpos($event['data']['message'], '[mod]') !== false)
		{
			$event->update_subarray('data', 'post_edit_locked', true);
		}
	}

	public function posting_modify_template_vars($event)
	{
		$event->update_subarray('page_data', 'S_MOD_BBCODE', $this->auth->acl_get('m_', $this->request->variable('f', 0)) ? true : false);
	}

	public function onParse($event)
	{
		if (!$this->auth->acl_get('m_', $this->request->variable('f', 0)))
		{
			$event['parser']->disable_bbcode('mod');
		}
	}

	public function user_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'kaileymsnay/modbbcode',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}
}
