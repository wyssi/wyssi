<?php
namespace Controllers;

class BaseController {
    
    public $templateDir;
    
    public $xhr;

    function __construct() {
        $this->xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        
        $this->smarty = \SmartyInstance::getInstance()->smarty;

        $this->templateDir = ($_SESSION['admin'] == 'admin')? 'admin' : ($_SESSION['template']? $_SESSION['template'] : $_SESSION['template'] = 'base');
        
        $this->smarty->assign('siteDir', MAIN_DIR);
        $this->smarty->assign('mainDir', MAIN_DIR . 'themes/' . $this->templateDir . '/');
        $this->smarty->assign('themeDir', MAIN_DIR . 'themes/' . $this->templateDir . '/');
    }
    
    public function display($template = false, $master = 'index.tpl'){
        $trace=debug_backtrace();
        $this->caller=$trace[1];
        
        $this->smarty->assign('controller', $this->caller['class']);
        $this->smarty->assign('method', $this->caller['function']);
        
        $master = $this->templateDir . '/' . (($this->xhr)? 'ajax.tpl' : $master);
        $template = $this->templateDir . '/' . (($template)? $template : explode('\\', get_class($this))[1] . '/index.tpl');
        $this->smarty->assign('template', $template);
        
        $this->smarty->display($master);
    }

}