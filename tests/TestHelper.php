<?php
namespace tests;

use Yii;

class TestHelper
{
    protected static $db;

    /**
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        if (self::$db === null) {
            self::$db = Yii::$app->db;
            self::$db->open();
        }

        return self::$db;
    }

    /**
     * Recria o banco de dados de teste:
     * - Elimina o esquema public
     * - Roda as migrations
     * - Salva o esquema
     */
    public static function recreateDataBase()
    {
        self::clearSchema();

        self::runMigrations();

        self::loadFixtures();
        
        self::exportSchema();
    }

    /**
     * Roda as migrations
     */
    protected static function runMigrations()
    {
        $migrationsDir = __DIR__ . '/../migrations/';
        $migrationsFiles = [];

        foreach (scandir($migrationsDir) as $fileName) {

            if (substr($fileName, strlen($fileName) - 4) == '.php') {
                $migrationsFiles[$fileName] = $migrationsDir . $fileName;
            }
        }

        ksort($migrationsFiles);

        foreach ($migrationsFiles as $fileName => $filePath) {

            if (is_file($filePath) && preg_match('/^m/', $fileName)) {

                $className = substr($fileName, 0, strlen($fileName) - 4);
                
                if (!class_exists($className, false)) {
                    require $filePath;
                }

                $migration = new $className;

                echo $className, "...\n";

                $status = false;

                ob_start();

                $status = $migration->up();

                ob_get_clean();

                if ($status === false) {
                    throw new \Exception('Error while running migration: ' . $className);
                }
            }
        }
    }

    /**
     * Carrega as fixtures
     */
    protected static function loadFixtures()
    {
        $transaction = self::getDb()->beginTransaction();

        $fixturesDir = Yii::getAlias(Yii::$app->fixture->basePath);
        $fixtureFiles = [];

        foreach (scandir($fixturesDir) as $file) {
            if (substr($file, strlen($file) - 4) == '.php') {
                $fixtureFiles[] = substr($file, 0, strlen($file) - 4);
            }
        }

        Yii::$app->fixture->load($fixtureFiles);

        $transaction->commit();
    }

    /**
     * @return string o caminho do arquivo .SQL
     */
    public static function getSchemaFilePath()
    {
        return __DIR__ . '/_data/dump.sql';
    }

    /**
     * Limpa o banco
     */ 
    public static function clearSchema()
    {
        self::getDb()->pdo->query('drop schema public cascade');
        self::getDb()->pdo->query('create schema public');
    }
    
    /**
     * Limpa o banco e importa o esquema salvo
     */ 
    public static function recreateSchema()
    {
        self::clearSchema();
        self::importSchema();
    }
    
    /**
     * Importa o esquema salvo
     */ 
    public static function importSchema()
    {
        $params = self::getDbParams();
        $command = 'psql -h ' . $params['host'] . ' -U ' . $params['username']
                 . ' ' . $params['dbname'] . ' < ' . self::getSchemaFilePath()
        ;

        exec($command);
    }
    /**
     * Exporta o esquema do banco de dados em um arquivo .SQL
     */
    public static function exportSchema()
    {
        $fileName = self::getSchemaFilePath();
        
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        
        $params = self::getDbParams();
        $command = 'pg_dump -h ' . $params['host'] . ' -U ' . $params['username']
                 . ' ' . $params['dbname'] . ' > ' . $fileName
        ;

        echo exec($command);
    }

    /**
     * @return array parâmetros de conexão com o banco de testes
     */
    protected static function getDbParams()
    {
        //pgsql:host=localhost;dbname=vigilantus_test
        $dsn = explode(':', self::getDb()->dsn);

        if ($dsn[0] != 'pgsql') {
            throw new \Exception('Altere este script para funcionar com bancos diferentes do PostgreSQL');
        }

        $dsn = explode(';', $dsn[1]);

        $connection = [];

        foreach ($dsn as $part) {
            $part = explode('=', $part);
            $connection[$part[0]] = $part[1];
        }

        $connection['username'] = self::getDb()->username;

        if (false === strpos($connection['dbname'], 'test')) {
            throw new \Exception('Parece que você não está utilizando um banco de testes!');
        }

        return $connection;
    }
}