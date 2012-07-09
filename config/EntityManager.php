<?php
use Doctrine\ORM\Tools\Setup;
/**
 * Description of EntityManager
 *
 * @author sam.jr
 */
class DB {
    private static $entityManager;
    
    /**
     * Database manager
     * @return Doctrine\ORM\EntityManager\EntityManager
     */
    public static function getInstance()
    {
        if(isset(self::$entityManager)){
            return self::$entityManager;
        }
        else{
            $paths = array("entities");
            $appConfig = \Rexume\Configuration\Configuration::getInstance();

            $dbParams = array(
                'driver' => 'pdo_mysql',
                'user' => $appConfig->getDatabaseUser(),
                'password' => $appConfig->getDatabasePassword(),
                'dbname' => $appConfig->getDatabaseName()
            );
            //initialize database
            $config = Setup::createAnnotationMetadataConfiguration($paths, DEVELOPMENT_ENVIRONMENT);
            return self::$entityManager = Doctrine\ORM\EntityManager::create($dbParams, $config);
        }
    }
    
    public static function getOne($entityType, $where)
    {
        try{
            $repository = self::getInstance()->getRepository($entityType);
            if($repository)
            {
                return $repository->findByOne($where);
            }
        }
        catch(Exception $e)
        {
            log($e);
        }
        return null;
    }
    
    public function save($entity)
    {
        $entityManager = self::getInstance();
        $entityManager->persist($entity);
        $entity->flush();
    }
}
