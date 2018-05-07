<?php

class m180507_094125_create_plugins_table extends CDbMigration
{
    public function safeUp()
    {
        $this->execute("CREATE TABLE  `" . $this->Table('plugins') . "` (
			`plugin_id` int(11) NOT NULL AUTO_INCREMENT,
			`identify` varchar(45) NOT NULL,
			`path` varchar(255) NOT NULL,
			`hooks` text NOT NULL,
			`enable` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`plugin_id`),
			UNIQUE KEY `identify_UNIQUE` (`identify`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		CREATE TABLE `" . $this->Table('plugins_setting') . "` (
			`plugin` varchar(45) NOT NULL,
			`key` varchar(45) NOT NULL,
			`value` text,
			PRIMARY KEY (`plugin`,`key`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }
    public function safeDown()
    {
        $this->dropTable($this->table('plugins'));
        $this->dropTable($this->table('plugins_setting'));
    }
    public function Table($table)
    {
        return Yii::app()->getDb()->tablePrefix ? Yii::app()->getDb()->tablePrefix . $table : $table;
    }
}
