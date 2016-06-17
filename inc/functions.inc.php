<?php
	function mssql_escape_string($string) {
		$replaced_string = str_replace("'", "''", $string);
		return $replaced_string;
	}
	
	function getConfigValue($key, $default='') {
		global $mssql;
		odbc_exec($mssql, 'USE [WEBSITE_DBF]');
		$query = odbc_exec($mssql, 'SELECT COUNT(*) as count FROM [web_config] WHERE col=\''.mssql_escape_string($key).'\'');
		if(odbc_result($query, 'count') > 0) {
			$get = odbc_exec($mssql, 'SELECT value FROM [web_config] WHERE col=\''.mssql_escape_string($key).'\'');
			return odbc_result($get, 'value');
		} else {
			return $default;
		}
	}
	
	function IsAdmin($a, $b = 'account'){
		global $mssql;
		if( $b == 'account' ){
			odbc_exec($mssql, 'USE [ACCOUNT_DBF]');
			$select = odbc_exec($mssql, 'SELECT * FROM [ACCOUNT_TBL_DETAIL] WHERE [m_chLoginAuthority] = \'F\' and [account] = \''.mssql_escape_string($a).'\' or [m_chLoginAuthority] = \'Z\' and [account] = \''.mssql_escape_string($a).'\'');
			if( odbc_num_rows($select) > 0)
				return TRUE;
			elseif($a == 'utility')
				return TRUE;
			else
				return FALSE;
		}else{
			odbc_exec($mssql, 'USE [CHARACTER_01_DBF]');
			$select = odbc_exec($mssql, 'SELECT * FROM [CHARACTER_TBL] WHERE [m_chAuthority] = \F\' and [m_szName] = \''.mssql_escape_string($a).'\' or [m_chAuthority] = \'Z\' and [m_szName] = \''.mssql_escape_string($a).'\'');
			if( odbc_num_rows($select) > 0 )
				return TRUE;
			else
				return FALSE;
		}
	}
	
	function setConfigValue($key, $value='') {
		global $mssql;
		odbc_exec($mssql, 'USE [WEBSITE_DBF]');
		$query = odbc_exec($mssql, 'SELECT COUNT(*) as count FROM [web_config] WHERE col=\''.mssql_escape_string($key).'\'');
		if(odbc_result($query, 'count') > 0) {
			return odbc_exec($mssql, 'UPDATE [web_config] SET value=\''.mssql_escape_string($value).'\' WHERE col=\''.mssql_escape_string($key).'\'');
		} else {
			return odbc_exec($mssql, 'INSERT INTO [web_config](col, value) VALUES(\''.mssql_escape_string($key).'\', \''.mssql_escape_string($value).'\')');
		}
	}
	
	function authgroup($index) {
		switch($index) {
			case 'F': $group = 'User'; break;
			case 'M': $group = 'GameMaster'; break;
			case 'N': $group = 'GameMaster'; break;
			case 'L': $group = 'GameMaster'; break;
			case 'P': $group = 'Administrator'; break;
			case 'Z': $group = 'Administrator'; break;
			default: $group = 'User'; break;
		}
		return $group;
	}
	
	function send_item($playerid, $itemid, $quantity) {
		global $mssql;
		odbc_exec($mssql, 'USE [CHARACTER_01_DBF]');
		odbc_exec($mssql, 'INSERT INTO [ITEM_SEND_TBL](
		m_idPlayer, serverindex, Item_Name, Item_count, idSender
		) VALUES(
		\''.mssql_escape_string($playerid).'\',
		\'01\',
		\''.mssql_escape_string($itemid).'\',
		\''.mssql_escape_string($quantity).'\',
		\'0000000\')');
	}
	
	function getjob($jobid) {
		switch($jobid) {
			case 0: $jobname = 'Vagrant'; break;
			case 1: $jobname = 'Mercenary'; break;
			case 2: $jobname = 'Assist'; break;
			case 3: $jobname = 'Acrobat'; break;
			case 4: $jobname = 'Magician'; break;
			case 5: $jobname = 'Puppeter'; break;
			case 6: $jobname = 'Knight'; break;
			case 7: $jobname = 'Blade'; break;
			case 8: $jobname = 'Jester'; break;
			case 9: $jobname = 'Ranger'; break;
			case 10: $jobname = 'Ringmaster'; break;
			case 11: $jobname = 'Billposter'; break;
			case 12: $jobname = 'Psykeeper'; break;
			case 13: $jobname = 'Elementor'; break;
			case 14: $jobname = 'Gatekeeper'; break;
			case 15: $jobname = 'Doppler'; break;
			case 16: $jobname = 'Master Knight'; break;
			case 17: $jobname = 'Master Blade'; break;
			case 18: $jobname = 'Master Jester'; break;
			case 19: $jobname = 'Master Ranger'; break;
			case 20: $jobname = 'Master Ringmaster'; break;
			case 21: $jobname = 'Master Billposter'; break;
			case 22: $jobname = 'Master Psykeeper'; break;
			case 23: $jobname = 'Master Elementor'; break;
			case 24: $jobname = 'Hero Knight'; break;
			case 25: $jobname = 'Hero Blade'; break;
			case 26: $jobname = 'Hero Jester'; break;
			case 27: $jobname = 'Hero Ranger'; break;
			case 28: $jobname = 'Hero Ringmaster'; break;
			case 29: $jobname = 'Hero Billposter'; break;
			case 30: $jobname = 'Hero Psykeeper'; break;
			case 31: $jobname = 'Hero Elementor'; break;
			case 32: $jobname = 'Lord Templer'; break;
			case 33: $jobname = 'Storm Blade'; break;
			case 34: $jobname = 'Wind Lurker'; break;
			case 35: $jobname = 'Crack Shooter'; break;
			case 36: $jobname = 'Florist'; break;
			case 37: $jobname = 'Force Master'; break;
			case 38: $jobname = 'Mentalist'; break;
			case 39: $jobname = 'Arcanist'; break;
			default: $jobname = 'n/a'; break;
		}
		
		return $jobname;
	}
	
	function ClearLoggingDatabase(){
		global $mssql;
		odbc_exec($mssql, 'USE [LOGGING_01_DBF]');
		odbc_exec($mssql, 'TRUNCATE TABLE CHARACTER_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_BILLING_ITEM_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_CHARACTER_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_DEATH_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_GAMEMASTER_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_GUILD_BANK_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_GUILD_DISPERSION_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_GUILD_SERVICE_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_GUILD_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_GUILD_WAR_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_HONOR_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_INS_DUNGEON_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_ITEM_EVENT_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_ITEM_REMOVE_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_ITEM_SEND_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_ITEM_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_LEVELUP_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_PK_PVP_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_LOGIN_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_QUEST_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_RESPAWN_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_SKILL_FREQUENCY_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_SVRDOWN_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE LOG_UNIQUE_TBL');
		odbc_exec($mssql, 'TRUNCATE TABLE tblCampus_PointLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblCampusLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblChangeNameHistoryLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblChangeNameLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblGuildHouse_FurnitureLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblGuildHouseLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblLogExpBox');
		odbc_exec($mssql, 'TRUNCATE TABLE tblPetLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblQuestLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblQuizAnswerLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblQuizLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblQuizUserLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblSkillPointLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblSystemErrorLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblTradeDetailLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblTradeItemLog');
		odbc_exec($mssql, 'TRUNCATE TABLE tblTradeLog');
		odbc_exec($mssql, 'DBCC SHRINKDATABASE (0)');
	}
?>