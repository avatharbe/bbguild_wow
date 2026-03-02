<?php
/**
 * WoW Installer
 *
 * Installs World of Warcraft factions, classes, races, and roles.
 * Extends the abstract_game_install from bbGuild core.
 *
 * @package   bbguild_wow v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\game;

use avathar\bbguild\model\games\abstract_game_install;

/**
 * Class wow_installer
 *
 * @package avathar\bbguild_wow\game
 */
class wow_installer extends abstract_game_install
{
	/**
	 * @inheritdoc
	 */
	protected function has_api_support(): bool
	{
		return true;
	}

	/**
	 * Installs WoW factions (Alliance, Horde)
	 */
	protected function install_factions()
	{
		global $db;

		$db->sql_query('DELETE FROM ' . $this->table('bb_factions_table') . " WHERE game_id = '" . $db->sql_escape($this->game_id) . "'");
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id, 'faction_id' => 1, 'faction_name' => 'Alliance');
		$sql_ary[] = array('game_id' => $this->game_id, 'faction_id' => 2, 'faction_name' => 'Horde');
		$db->sql_multi_insert($this->table('bb_factions_table'), $sql_ary);
	}

	/**
	 * Installs WoW classes with translations (en, fr, de, it)
	 */
	protected function install_classes()
	{
		global $db;

		// classes (note class 10 does not exist)
		$db->sql_query('DELETE FROM ' . $this->table('bb_classes_table') . " WHERE game_id = '" . $db->sql_escape($this->game_id) . "'");
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 0,  'class_armor_type' => 'PLATE',   'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#999',    'imagename' => 'wow_unknown');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 1,  'class_armor_type' => 'PLATE',   'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#c69b6d', 'imagename' => 'wow_warrior');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 4,  'class_armor_type' => 'LEATHER', 'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#fff468', 'imagename' => 'wow_rogue');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 3,  'class_armor_type' => 'MAIL',    'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#aad372', 'imagename' => 'wow_hunter');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 2,  'class_armor_type' => 'PLATE',   'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#f48cba', 'imagename' => 'wow_paladin');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 7,  'class_armor_type' => 'MAIL',    'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#2359ff', 'imagename' => 'wow_shaman');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 11, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#ff7c0a', 'imagename' => 'wow_druid');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 9,  'class_armor_type' => 'CLOTH',   'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#9382c9', 'imagename' => 'wow_warlock');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 8,  'class_armor_type' => 'CLOTH',   'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#68ccef', 'imagename' => 'wow_mage');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 5,  'class_armor_type' => 'CLOTH',   'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#f0ebe0', 'imagename' => 'wow_priest');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 6,  'class_armor_type' => 'PLATE',   'class_min_level' => 55, 'class_max_level' => 110, 'colorcode' => '#c41e3b', 'imagename' => 'wow_death_knight');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 10, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1,  'class_max_level' => 110, 'colorcode' => '#00ffba', 'imagename' => 'wow_monk');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 12, 'class_armor_type' => 'LEATHER', 'class_min_level' => 98, 'class_max_level' => 110, 'colorcode' => '#A330C3', 'imagename' => 'wow_demon_hunter');
		$db->sql_multi_insert($this->table('bb_classes_table'), $sql_ary);
		unset($sql_ary);

		// class names in multiple languages
		$db->sql_query('DELETE FROM ' . $this->table('bb_language_table') . " WHERE game_id = '" . $db->sql_escape($this->game_id) . "' AND attribute='class' ");

		$sql_ary = array();
		// en
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'en', 'attribute' => 'class', 'name' => 'Unknown',      'name_short' => 'Unknown');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'en', 'attribute' => 'class', 'name' => 'Warrior',      'name_short' => 'Warrior');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'en', 'attribute' => 'class', 'name' => 'Rogue',        'name_short' => 'Rogue');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'en', 'attribute' => 'class', 'name' => 'Hunter',       'name_short' => 'Hunter');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'en', 'attribute' => 'class', 'name' => 'Paladin',      'name_short' => 'Paladin');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'en', 'attribute' => 'class', 'name' => 'Shaman',       'name_short' => 'Shaman');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'en', 'attribute' => 'class', 'name' => 'Druid',        'name_short' => 'Druid');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'en', 'attribute' => 'class', 'name' => 'Warlock',      'name_short' => 'Warlock');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'en', 'attribute' => 'class', 'name' => 'Mage',         'name_short' => 'Mage');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'en', 'attribute' => 'class', 'name' => 'Priest',       'name_short' => 'Priest');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'en', 'attribute' => 'class', 'name' => 'Death Knight', 'name_short' => 'Death Knight');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'en', 'attribute' => 'class', 'name' => 'Monk',         'name_short' => 'Monk');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' => 'en', 'attribute' => 'class', 'name' => 'Demon Hunter', 'name_short' => 'Demon Hunter');

		// fr
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Unknown',               'name_short' => 'Unknown');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Warrior',               'name_short' => 'Warrior');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Voleur',                'name_short' => 'Voleur');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Chasseur',              'name_short' => 'Chasseur');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Paladin',               'name_short' => 'Paladin');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Chaman',                'name_short' => 'Chaman');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Druide',                'name_short' => 'Druide');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Démoniste',             'name_short' => 'Démoniste');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Mage',                  'name_short' => 'Mage');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Prêtre',                'name_short' => 'Prêtre');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'fr', 'attribute' => 'class', 'name' => 'Chevalier de la Mort',  'name_short' => 'Chevalier de la Mort');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Moine',                 'name_short' => 'Moine');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Chasseur de démons',    'name_short' => 'Chasseur de démons');

		// de
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'de', 'attribute' => 'class', 'name' => 'Unbekannt',      'name_short' => 'Unbekannt');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'de', 'attribute' => 'class', 'name' => 'Krieger',        'name_short' => 'Krieger');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'de', 'attribute' => 'class', 'name' => 'Schurke',        'name_short' => 'Schurke');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'de', 'attribute' => 'class', 'name' => 'Jäger',          'name_short' => 'Jäger');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'de', 'attribute' => 'class', 'name' => 'Paladin',        'name_short' => 'Paladin');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'de', 'attribute' => 'class', 'name' => 'Schamane',       'name_short' => 'Schamane');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'de', 'attribute' => 'class', 'name' => 'Druide',         'name_short' => 'Druide');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'de', 'attribute' => 'class', 'name' => 'Hexenmeister',   'name_short' => 'Hexenmeister');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'de', 'attribute' => 'class', 'name' => 'Magier',         'name_short' => 'Magier');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'de', 'attribute' => 'class', 'name' => 'Priester',       'name_short' => 'Priester');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'de', 'attribute' => 'class', 'name' => 'Todesritter',    'name_short' => 'Todesritter');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'de', 'attribute' => 'class', 'name' => 'Mönch',          'name_short' => 'Mönch');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' => 'de', 'attribute' => 'class', 'name' => 'Dämonenjäger',   'name_short' => 'Dämonenjäger');

		// it
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'it', 'attribute' => 'class', 'name' => 'Sconosciuto',            'name_short' => 'Sconosciuto');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'it', 'attribute' => 'class', 'name' => 'Guerriero',              'name_short' => 'Guerriero');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'it', 'attribute' => 'class', 'name' => 'Ladro',                  'name_short' => 'Ladro');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'it', 'attribute' => 'class', 'name' => 'Cacciatore',             'name_short' => 'Cacciatore');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'it', 'attribute' => 'class', 'name' => 'Paladino',               'name_short' => 'Paladino');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'it', 'attribute' => 'class', 'name' => 'Sciamano',               'name_short' => 'Sciamano');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'it', 'attribute' => 'class', 'name' => 'Druido',                 'name_short' => 'Druido');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'it', 'attribute' => 'class', 'name' => 'Stregone',               'name_short' => 'Stregone');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'it', 'attribute' => 'class', 'name' => 'Mago',                   'name_short' => 'Mago');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'it', 'attribute' => 'class', 'name' => 'Sacerdote',              'name_short' => 'Sacerdote');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'it', 'attribute' => 'class', 'name' => 'Cavaliere della Morte',  'name_short' => 'Cavaliere della Morte');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'it', 'attribute' => 'class', 'name' => 'Monaco',                 'name_short' => 'Monaco');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' => 'it', 'attribute' => 'class', 'name' => 'Cacciatore di Demoni',   'name_short' => 'Cacciatore di Demoni');

		$db->sql_multi_insert($this->table('bb_language_table'), $sql_ary);
	}

	/**
	 * Installs WoW races with translations (en, fr, de, it)
	 */
	protected function install_races()
	{
		global $db;

		$db->sql_query('DELETE FROM ' . $this->table('bb_races_table') . " WHERE game_id = '" . $db->sql_escape($this->game_id) . "' ");
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 0,  'race_faction_id' => 0, 'image_female' => ' ',                  'image_male' => ' ');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 1,  'race_faction_id' => 1, 'image_female' => 'wow_human_female',    'image_male' => 'wow_human_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 2,  'race_faction_id' => 2, 'image_female' => 'wow_orc_female',      'image_male' => 'wow_orc_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 3,  'race_faction_id' => 1, 'image_female' => 'wow_dwarf_female',    'image_male' => 'wow_dwarf_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 4,  'race_faction_id' => 1, 'image_female' => 'wow_nightelf_female', 'image_male' => 'wow_nightelf_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 5,  'race_faction_id' => 2, 'image_female' => 'wow_scourge_female',  'image_male' => 'wow_scourge_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 6,  'race_faction_id' => 2, 'image_female' => 'wow_tauren_female',   'image_male' => 'wow_tauren_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 7,  'race_faction_id' => 1, 'image_female' => 'wow_gnome_female',    'image_male' => 'wow_gnome_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 8,  'race_faction_id' => 2, 'image_female' => 'wow_troll_female',    'image_male' => 'wow_troll_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 9,  'race_faction_id' => 2, 'image_female' => 'wow_goblin_female',   'image_male' => 'wow_goblin_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 10, 'race_faction_id' => 2, 'image_female' => 'wow_bloodelf_female', 'image_male' => 'wow_bloodelf_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 11, 'race_faction_id' => 1, 'image_female' => 'wow_draenei_female',  'image_male' => 'wow_draenei_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 22, 'race_faction_id' => 1, 'image_female' => 'wow_worgen_female',   'image_male' => 'wow_worgen_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 25, 'race_faction_id' => 1, 'image_female' => 'wow_pandaren_female', 'image_male' => 'wow_pandaren_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 26, 'race_faction_id' => 2, 'image_female' => 'wow_pandaren_female', 'image_male' => 'wow_pandaren_male');
		$db->sql_multi_insert($this->table('bb_races_table'), $sql_ary);
		unset($sql_ary);

		// race names
		$db->sql_query('DELETE FROM ' . $this->table('bb_language_table') . " WHERE game_id = '" . $db->sql_escape($this->game_id) . "' AND attribute = 'race' ");

		$sql_ary = array();
		// en
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'en', 'attribute' => 'race', 'name' => 'Unknown',           'name_short' => 'Unknown');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'en', 'attribute' => 'race', 'name' => 'Human',              'name_short' => 'Human');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'en', 'attribute' => 'race', 'name' => 'Orc',                'name_short' => 'Orc');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'en', 'attribute' => 'race', 'name' => 'Dwarf',              'name_short' => 'Dwarf');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'en', 'attribute' => 'race', 'name' => 'Night Elf',          'name_short' => 'Night Elf');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'en', 'attribute' => 'race', 'name' => 'Undead',             'name_short' => 'Undead');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'en', 'attribute' => 'race', 'name' => 'Tauren',             'name_short' => 'Tauren');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'en', 'attribute' => 'race', 'name' => 'Gnome',              'name_short' => 'Gnome');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'en', 'attribute' => 'race', 'name' => 'Troll',              'name_short' => 'Troll');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'en', 'attribute' => 'race', 'name' => 'Blood Elf',          'name_short' => 'Blood Elf');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'en', 'attribute' => 'race', 'name' => 'Draenei',            'name_short' => 'Draenei');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'en', 'attribute' => 'race', 'name' => 'Goblin',             'name_short' => 'Goblin');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 22, 'language' => 'en', 'attribute' => 'race', 'name' => 'Worgen',             'name_short' => 'Worgen');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 25, 'language' => 'en', 'attribute' => 'race', 'name' => 'Pandaren Alliance',  'name_short' => 'Pandaren Alliance');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 26, 'language' => 'en', 'attribute' => 'race', 'name' => 'Pandaren Horde',     'name_short' => 'Pandaren Horde');

		// fr
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Inconnu',            'name_short' => 'Inconnu');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Humain',             'name_short' => 'Humain');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Orc',                'name_short' => 'Orc');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Nain',               'name_short' => 'Nain');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Elfe de la Nuit',    'name_short' => 'Elfe de la Nuit');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Mort-Vivant',        'name_short' => 'Mort-Vivant');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Tauren',             'name_short' => 'Tauren');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Gnome',              'name_short' => 'Gnome');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Troll',              'name_short' => 'Troll');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'fr', 'attribute' => 'race', 'name' => 'Elfe de Sang',       'name_short' => 'Elfe de Sang');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'fr', 'attribute' => 'race', 'name' => 'Draeneï',            'name_short' => 'Draeneï');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Goblin',             'name_short' => 'Goblin');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 22, 'language' => 'fr', 'attribute' => 'race', 'name' => 'Worgen',             'name_short' => 'Worgen');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 25, 'language' => 'fr', 'attribute' => 'race', 'name' => 'Pandaren Alliance',  'name_short' => 'Pandaren Alliance');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 26, 'language' => 'fr', 'attribute' => 'race', 'name' => 'Pandaren Horde',     'name_short' => 'Pandaren Horde');

		// de
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'de', 'attribute' => 'race', 'name' => 'Unbekannt',          'name_short' => 'Unbekannt');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'de', 'attribute' => 'race', 'name' => 'Mensch',             'name_short' => 'Mensch');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'de', 'attribute' => 'race', 'name' => 'Orc',                'name_short' => 'Orc');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'de', 'attribute' => 'race', 'name' => 'Zwerg',              'name_short' => 'Zwerg');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'de', 'attribute' => 'race', 'name' => 'Nachtelf',           'name_short' => 'Nachtelf');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'de', 'attribute' => 'race', 'name' => 'Untoter',            'name_short' => 'Untoter');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'de', 'attribute' => 'race', 'name' => 'Tauren',             'name_short' => 'Tauren');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'de', 'attribute' => 'race', 'name' => 'Gnome',              'name_short' => 'Gnome');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'de', 'attribute' => 'race', 'name' => 'Troll',              'name_short' => 'Troll');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'de', 'attribute' => 'race', 'name' => 'Blutelf',            'name_short' => 'Blutelf');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'de', 'attribute' => 'race', 'name' => 'Draenei',            'name_short' => 'Draenei');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'de', 'attribute' => 'race', 'name' => 'Goblin',             'name_short' => 'Goblin');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 22, 'language' => 'de', 'attribute' => 'race', 'name' => 'Worgen',             'name_short' => 'Worgen');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 25, 'language' => 'de', 'attribute' => 'race', 'name' => 'Pandaren Alliance',  'name_short' => 'Pandaren Alliance');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 26, 'language' => 'de', 'attribute' => 'race', 'name' => 'Pandaren Horde',     'name_short' => 'Pandaren Horde');

		// it
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'it', 'attribute' => 'race', 'name' => 'Sconosciuto',        'name_short' => 'Sconosciuto');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'it', 'attribute' => 'race', 'name' => 'Umani',              'name_short' => 'Umani');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'it', 'attribute' => 'race', 'name' => 'Orchi',              'name_short' => 'Orchi');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'it', 'attribute' => 'race', 'name' => 'Nani',               'name_short' => 'Nani');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'it', 'attribute' => 'race', 'name' => 'Elfi del Sangue',    'name_short' => 'Elfi del Sangue');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'it', 'attribute' => 'race', 'name' => 'Non Morti',          'name_short' => 'Non Morti');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'it', 'attribute' => 'race', 'name' => 'Tauren',             'name_short' => 'Tauren');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'it', 'attribute' => 'race', 'name' => 'Gnomi',              'name_short' => 'Gnomi');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'it', 'attribute' => 'race', 'name' => 'Troll',              'name_short' => 'Troll');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'it', 'attribute' => 'race', 'name' => 'Elfi del Sangue',    'name_short' => 'Elfi del Sangue');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'it', 'attribute' => 'race', 'name' => 'Draenei',            'name_short' => 'Draenei');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'it', 'attribute' => 'race', 'name' => 'Goblin',             'name_short' => 'Goblin');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 22, 'language' => 'it', 'attribute' => 'race', 'name' => 'Worgen',             'name_short' => 'Worgen');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 25, 'language' => 'it', 'attribute' => 'race', 'name' => 'Pandaren Alleanza',  'name_short' => 'Pandaren Alleanza');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 26, 'language' => 'it', 'attribute' => 'race', 'name' => 'Pandaren Orda',      'name_short' => 'Pandaren Orda');

		$db->sql_multi_insert($this->table('bb_language_table'), $sql_ary);
	}
}
