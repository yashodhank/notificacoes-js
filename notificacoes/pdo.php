<?php
    class Database
    {
        private static $instance = null;
        public static function conectar($cfhost, $cfuser, $cfsenha, $cfdb, $cftype = 'mysql', $cfps = false) 
        {
            if($cfps != FALSE){$cfps = TRUE;}
            if(!isset(self::$instance))
            {
                try
                {
                    self::$instance = new \PDO($cftype . ':host=' . $cfhost . ';dbname=' . $cfdb, $cfuser, $cfsenha, array(\PDO::ATTR_PERSISTENT => $cfps));
                }
                catch (\PDOException $ex) 
                {
                    exit("Erro ao conectar com o banco de dados: " . $ex->getMessage());
                }
            }
            self::$instance->query("SET NAMES 'utf8'; SET character_set_connection=utf8; SET character_set_client=utf8; SET character_set_results=utf8;");
            return self::$instance;
        }
        public static function desconectar() 
        {
            if (self::$instance != null){self::$instance = null;}
        }
    }
?>