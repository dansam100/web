<?php
namespace Rexume\Application\Controllers;
use Rexume\Lib\Authentication\Authentication as Authentication;
use Rexume\Lib\Readers\OAuthReader as OAuthReader;

class VerificationController extends Controller
{
    private $protocol = 'Basic';
    /**
     * 
     * @param VerificationModel $model
     * @param VerificationController $view
     * @param string $action
     */
    public function __construct($model, $view, $action) {
        parent::__construct($model, $view, $action);
        $this->model = new $model(Authentication::currentUser());
        if(isset($_GET['protocol'])){ $this->protocol($_GET['protocol']); }
    }
    
    public function display()
    {
       switch($this->protocol())
       {
           case "LinkedIn":
               $this->getOAuthData();
               break;
           default:
               break;               
       }
    }
    
    public function getOAuthData()
    {
        //get data request protocol for linkedin
        $protocol   = \Rexume\Config\Configuration::getInstance()->getDataProtocol($this->protocol());
        $url        = $protocol->scope();
        $query      = $protocol->query();
        $reader     = new OAuthReader($this->protocol());
        $page       = $reader->read($url, $query, $this->model->oauthToken(), $this->model->oauthTokenSecret());
        $dataArray  = $protocol->parseOne($page);
        
        //var_dump($dataArray->experiences[1]->activities);
        //var_dump(Authentication::currentUser()->experiences[0]);
        var_dump($dataArray);
        //print_r($dataArray);
        
        foreach($dataArray as $entity)
        {
            
        }
    }
    
    public function protocol($protocol = null)
    {
        if(!empty($protocol))
        {
            $this->protocol = $protocol;
        }
        return $this->protocol;
    }
}