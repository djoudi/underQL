<?php 

class umodule_template extends UQLModule implements IUQLModule {

    private $left_delimiter;
    private $right_delimiter;
    private $template_source;
    private $template_result;

    public function init() {

        $this->left_delimiter  = '#';
        $this->right_delimiter = '#';
        $this->template_source = '';
        $this->template_result = '';

        $this->useInput(false);
    }

    public function setDelimiters($left,$right)
    {
        $this->left_delimiter = $left;
        $this->right_delimiter = $right;
    }

    public function setTemplateFromString($str)
    {
        $this->template_source = $str;
    }

    public function setTemplateFromFile($path)
    {
        $this->setTemplateFromString(file_get_contents($path));
    }
    
    public function getResult()
    {
        return $this->template_result;
    }

    public function in(&$values,$is_insert = true) {
        // No implementation
    }

    public function out(&$path) {

        $temp_result = '';
        if($path->_('count') != 0)
        {
           $fields = $path->_('fields');
           $this->template_result = $this->template_source;
           while($path->_('fetch'))
           {
             for($i = 0; $i < @count($fields); $i++)
             {  
                $target_value = $this->left_delimiter.$fields[$i].$this->right_delimiter;
                $this->template_result = str_replace($target_value,$path->$fields[$i],$this->template_result);
             }

             $temp_result .= $this->template_result;
             $this->template_result = $this->template_source;
           }

            $this->template_result = $temp_result;
        }
    }

    public function shutdown() {
        
        $this->left_delimiter  = null;
        $this->right_delimiter = null;
        $this->template_source = null;
        $this->template_result = null;

    }
}

?>