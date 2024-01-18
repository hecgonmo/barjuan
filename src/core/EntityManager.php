<?php

namespace App\Core;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager as ORMEntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

/**
 * Esta clase es una instancia del EntityManager que utilizamos para conectarnos
 * entre Doctrine y la BB.DD. de MySQL
 */
class EntityManager
{
    private ORMEntityManager $entityManager;
    //private $dbConfig;

    public function __construct()
    {
        /**
         * Al igual que haciamos en el modelo creamos un array de string del json con
         * la configuración de la conexión de la BB.DD.
         */
        //Estas dos líneas sustituyen a la anterior porque hemos usado una libreria que lee el archivo de configuración .env 
        //Donde indicamos la ruta del archivo, que hemos colocado en la carpeta config, aúnque en muchos proyectos esta en la raiz
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../config/');
        $dotenv->load();
        
        //Guardamos la ruta donde estan ubicados todas las entidades.
        $paths = array(__DIR__.'/../entity');
        //Indicamos que estamos en modo desarrollo. Cogemos el valor de la configuración
        $isDevMode = boolval($_ENV["DEVELOP_MODE"]);
        //Cargamos en un array los datos de la conexión desde el archivo .env
        $dbParams = array(
            'host' => $_ENV["db_server"],
            'driver' => $_ENV["db_driver"],
            'user' => $_ENV["db_user"],
            'password' => $_ENV["db_password"],
            'dbname' => $_ENV["db_name"],
        );

        //Creamos la configuración de donde y como
        $config = ORMSetup::createAttributeMetadataConfiguration($paths,$isDevMode,null,null);
        //Creamos el objeto EntityManager con la configuración que hemos definido
        // para poder instanciarlo en esta clase.
        $connection= DriverManager::getConnection($dbParams,$config);
        $this->entityManager = new ORMEntityManager($connection, $config);
    }

    /**
     * Get the value of entityManager
     */ 
    public function getEntityManager(): ORMEntityManager
    {
        return $this->entityManager;
    }
}
