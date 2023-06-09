<?php
/**
 *
 * MOD BBCode extension for the phpBB Forum Software package
 *
 * @copyright (c) 2021, Kailey Snay, https://www.snayhomelab.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kaileymsnay\modbbcode\migrations\v10x;

use kaileymsnay\modbbcode\core\bbcodes_installer;

class m1_initial_data extends \phpbb\db\migration\container_aware_migration
{
	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v330\v330'];
	}

	/**
	 * Add, update or delete data stored in the database
	 */
	public function update_data()
	{
		return [
			// Call a custom callable function to perform more complex operations
			['custom', [[$this, 'install_bbcodes']]],
		];
	}

	/**
	 * A custom function for making more complex database changes
	 * during extension installation. Must be declared as public.
	 */
	public function install_bbcodes()
	{
		$install = new bbcodes_installer($this->db, $this->phpbb_root_path, $this->php_ext);

		$install->install_bbcodes([
			'mod'	=> [
				'bbcode_match'		=> '[mod]{TEXT}[/mod]',
				'bbcode_tpl'		=> '<div class="mod-bbcode"><em><strong>{L_MOD_MESSAGE}{L_COLON}</strong><br />{TEXT}</div>',
				'bbcode_helpline'	=> '',
			],
		]);
	}
}
