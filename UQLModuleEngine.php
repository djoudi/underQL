<?php

class UQLModuleEngine extends UQLBase {


    public static function underql_module_run_input(&$values,$is_insert = true) {
        /* run modules */
        if(!$values || !is_array($values) || @count($values) == 0)
            return;

        if(isset($GLOBALS['uql_global_loaded_modules']) &&
                @count($GLOBALS['uql_global_loaded_modules']) != 0) {
            foreach($GLOBALS['uql_global_loaded_modules'] as $key => $module_name) {
                if(isset($GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)])
                        && $GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)]->isActive()
                        && $GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)]->isInput())
                    $GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)]->in($values,$is_insert);
                //$this->um_values_map->underql_set_map($current_vals);
            }
        }
    }

    public static function underql_module_run_output(&$path) {
        /* run modules */
        if(!$path || ($path instanceof UQLQueryPath && $path->_('count') == 0))
            return;

        if(isset($GLOBALS['uql_global_loaded_modules']) &&
                @count($GLOBALS['uql_global_loaded_modules']) != 0) {
            foreach($GLOBALS['uql_global_loaded_modules'] as $key => $module_name) {
                if(isset($GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)])
                        && $GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)]->isActive()
                        && $GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)]->isOutput()) {
                    $GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)]->out($path);
                    $path->_('reset');
                }
            }
        }
    }

    public static function underql_module_shutdown() {
        if(isset($GLOBALS['uql_global_loaded_modules']) &&
                @count($GLOBALS['uql_global_loaded_modules']) != 0) {
            foreach($GLOBALS['uql_global_loaded_modules'] as $key => $module_name) {
                if(isset($GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)]))
                    $GLOBALS[sprintf(UQL_MODULE_OBJECT_SYNTAX,$module_name)]->shutdown();
            }
        }
    }
}

?>