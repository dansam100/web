<?php
namespace Rexume\Application\Controllers;
use Rexume\Lib\Authentication\Authentication as Authentication;
/**
 * Description of DataController
 *
 * @author sam.jr
 */
class DataController extends Controller {
    const INTERFACE_DELIMITER = ' ';
    const PARAMETER_SEPARATOR = '/';
    /**
     * Read configuration interface for accessing interface declarations and types
     * @var Rexume\Config\ReadConfiguration
     */
    protected $configuration;
    
    /**
     * Ctor
     * 
     * @param DataModel $model
     * @param DataView $view
     * @param string $action
     */
    public function __construct($model, $view, $action) {
        $this->configuration = \Rexume\Config\Configuration::getInstance()->getInterfaceConfiguration();
        parent::__construct($model, $view, $action);
        $this->model = new $model(Authentication::currentUser(), $this->configuration);
        //TEST: $this->model = new $model(\DB::getOne('User', array('memberId' => 'tDz75GX1SG')), $this->configuration);
    }
    
    /**
     * Function that exposes reads to the data controller. Processes all read requests
     * 
     * @param type $scope this represents the scope of the data request
     * @param type $arguments the arguments passed to the read
     */
    public function __call($scope, $arguments){
        //TODO: Remember to add security here
        $where = null;
        //having more than one argument denotes a select on an oid
        if(count($arguments) > 1){
            $where = $arguments[1];
        }
        //support for multiple interface query (eg: data/resumes/profile+detail+educations)
        $arguments = \trim($arguments[0], self::INTERFACE_DELIMITER);
        //remove redundant queries
        $interfaceNames = \array_unique(\explode(self::INTERFACE_DELIMITER, $arguments));
        //get configuration for interfaces
        foreach($interfaceNames as $interfaceName){
            //get the interface definition for the names in the request to deduce return types
            $interface = $this->configuration->getInterface($scope, $interfaceName);
            //ensure client is asking for a defined interface
            if(isset($interface)){
                //all interfaces must return defined types
                $type = $this->configuration->getType($interface->getType());
                //type must be exposed through the interface definition
                if(isset($type)){
                    //discriminate between interfaces that expect to return collections and those that don't.
                    //the model will take care of the rest of the work
                    $this->model->setQuery($type, $where, $interface->getIsCollection());
                }
            }
        }
    }    
}
