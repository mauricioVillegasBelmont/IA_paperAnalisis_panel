<?php
/**
 * Pymengine IA Services PlugIn v1.0
 * 
 * @package IAServices
 * @author  Pymeweb
 * @license MIT
 * @link
 */

require_once __dir__ . "/settings.php";

class PythonHelpers{
    /**
     * @param string $filename
     * @return string
     */
    public function get_pdf_contents($file){
        try{
            $shell = __DIR__ . "/py_services/extractPDF.py " . $file;
            return self::run($shell);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function get_pdf_file_contents($filename){
        try{
            $file = STATIC_DIR . "public/" . $filename;
            if (!file_exists($file)) {
                throw new Exception("File not found: " .  $filename);
            }
            $shell = __DIR__ . "/py_services/extractPDF.py " . $file;
            return self::run($shell);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * @param string $shell
     * @return string
     */
    private function run($shell){
        try{
            $output = shell_exec(PYTHON3 . " ". $shell . " 2>&1");
            return $output;
        }catch(Exception $e){
            return 'Caught exception: ' .  $e->getMessage();
        }
    }
}

function PythonHelpers(){
    return new PythonHelpers();
}