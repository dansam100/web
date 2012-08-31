<?php
namespace Rexume\Controllers;

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
        $this->model = new $model(\Rexume\Models\Auth\Authentication::currentUser());
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
        $protocol   = \Rexume\Configuration\Configuration::getInstance()->getDataProtocol($this->protocol());
        $url        = $protocol->getScope();
        $query      = $protocol->getQuery();
        $reader     = new \Rexume\Readers\OAuthReader($this->protocol());
        $page       = $reader->read($url, $query, $this->model->getOAuthToken(), $this->model->getOAuthTokenSecret());
        $dataArray  = $protocol->parse($page);
        
        foreach($dataArray as $entity)
        {
            
        }
        var_dump($dataArray);
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