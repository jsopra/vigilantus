<?php
namespace tests;

use Yii;
use yii\test\DbFixtureManager as YiiDbFixtureManager;

class DbFixtureManager extends YiiDbFixtureManager
{
    /**
     * @inheritdoc
     */
    public function loadFixture($tableName)
    {
        $table = $this->db->getSchema()->getTableSchema($tableName);
        if ($table === null) {
            throw new InvalidConfigException("Table does not exist: $tableName");
        }

        $fileName = $this->basePath . '/' . $tableName . '.php';
        if (!is_file($fileName)) {
            return false;
        }

        $rows = [];
        foreach (require($fileName) as $alias => $row) {
            $this->db->createCommand()->insert($tableName, $row)->execute();
            if ($table->sequenceName !== null) {
                foreach ($table->primaryKey as $pk) {
                    if (!isset($row[$pk])) {
                        $row[$pk] = $this->db->getLastInsertID($table->sequenceName);
                        break;
                    }
                }
            }
            $rows[$alias] = $row;
        }

        // Atualiza a sequence
        if ($table->sequenceName!==null) {
            self::atualizaSequence($tableName, $table->sequenceName);
        }

        return $rows;
    }

    public function atualizaSequence($tableName, $sequenceName)
    {
        return (bool) $this->db->createCommand(
            "SELECT SETVAL('" . $sequenceName . "', (SELECT MAX(id)+1 FROM " . $tableName . "))"
        )->execute();
    }
}