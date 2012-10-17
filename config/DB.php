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
            $paths = array("../application/models/entities");
            $appConfig = \Rexume\Config\Configuration::getInstance();

            $dbParams = array(
                'driver' => 'pdo_mysql',
                'user' => $appConfig->getDatabaseUser(),
                'password' => $appConfig->getDatabasePassword(),
                'dbname' => $appConfig->getDatabaseName()
            );
            //initialize database
            $config = Setup::createAnnotationMetadataConfiguration($paths, DEVELOPMENT_ENVIRONMENT);
            return self::$entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $config);
        }
    }
    
    public static function getOne($entityType, $where)
    {
        try{
            $repository = self::getInstance()->getRepository($entityType);
            if(!empty($repository))
            {
                return $repository->findOneBy($where);
            }
        }
        catch(Exception $e)
        {
            //swallow
            throw $e;
        }
        return null;
    }
    
    public static function remove($entities)
    {
        try
        {
            $entityManager = self::getInstance();
            if(is_array($entities)){
                foreach($entities as $entity){
                    $entityManager->remove($entity);
                }
            }
            else{
                $entityManager->remove($entities);
            }
            return $entityManager->flush();
        }
        catch(Exception $e){
            //swallow
            throw $e;
        }
        return -1;
    }
    
    /**
     * Persists any initialized or changed object to the database
     * @param Entity $entity any type of entity to save
     * @return integer success code
     */
    public static function save($entity)
    {
        $entityManager = self::getInstance();
        $entityManager->persist($entity);
        return $entityManager->flush();
    }
}
