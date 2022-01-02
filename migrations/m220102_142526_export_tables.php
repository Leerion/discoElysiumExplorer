<?php

use yii\db\Migration;

/**
 * Class m220102_142526_export_tables
 */
class m220102_142526_export_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        ini_set('memory_limit', '-1');

        $files = [
            'disco_actors.sql', 
            'disco_conversations.sql', 
            'disco_links.sql',
            'disco_dialogues.sql'
        ];

        foreach ($files as $f) {
            $sql = file_get_contents(__DIR__ . '/'.$f);
            $command = Yii::$app->db->createCommand($sql);
            $command->execute();
        }

        while ($command->pdoStatement->nextRowSet()) {}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $command = Yii::$app->db->createCommand('drop table actors;');
        $command->execute();

        $command = Yii::$app->db->createCommand('drop table dialogues;');
        $command->execute();

        $command = Yii::$app->db->createCommand('drop table links;');
        $command->execute();

        $command = Yii::$app->db->createCommand('drop table conversations;');
        $command->execute();

        while ($command->pdoStatement->nextRowSet()) {}
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220102_142526_export_tables cannot be reverted.\n";

        return false;
    }
    */
}
