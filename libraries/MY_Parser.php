<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* CodeIgniter Twig Parser Class
*
* @package CodeIgniter
* @subpackage Libraries
* @category Parser
* @author Zander Janse van Rensburg
*/

// Get the Twig autoloader in here
include APPPATH . 'third_party/Twig/Autoloader.php';

class MY_Parser extends CI_Parser
{
	private $_ci;
	private $_twig;
	private $_template_dir = '';
	private $_cache_dir    = '';

	public function __construct()
	{
		// Get the CI instance.
		$this->_ci = &get_instance();

		// Load the config.
		$this->_ci->config->load('twig');

		// Get the twig instance.
		$this->_twig = self::spawn();

		// Init all functions as Twig functions.
		if ($this->_ci->config->item('register_autoload'))
			foreach(get_defined_functions() as $functions)
			{
	            foreach($functions as $function)
	            {
	                 $this->_twig->addFunction($function, new Twig_Function_Function($function));
	            }
	        }
	}

	/**
	 * Spawns a new instance of Twig.
	 *
	 * @return object
	 **/
	private function spawn()
	{
		// Register the Twig autoloader.
		Twig_Autoloader::register();
		
		// Set the cache dir.
		$this->_cache_dir = $this->_ci->config->item('cache_dir');

		// Init the Twig loader.
		$loader = new Twig_Loader_String();

		// Check if the cache dir is set.
		if ($this->_cache_dir)
			$twig   = new Twig_Environment($loader, array(
				'autoescape' => false,
			    'cache' => $this->_cache_dir,
			));
		else
			$twig = new Twig_Environment($loader, array('autoescape' => false));

		// Finally, return the object.
		return $twig;
	}

	/**
	 * Makes all functions available to Twig.
	 *
	 * @return void
	 * @author Zander Janse van Rensburg
	 **/
	public function checkFunctions()
	{
		// Init all functions as Twig functions.
		foreach(get_defined_functions() as $functions)
		{
            foreach($functions as $function)
            {
                 $this->_twig->addFunction($function, new Twig_Function_Function($function));
            }
        }
	}

	/**
	 * Registers a function with Twig and makes it available in the view.
	 *
	 * @return void
	 * @author Zander Janse van Rensburg
	 **/
	public function addFunction($functionName, $alias = '')
	{
		if ($alias === '')
			$this->_twig->addFunction($functionName, new Twig_Function_Function($functionName));
		else
			$this->_twig->addFunction($alias, new Twig_Function_Function($functionName));
	}

	/**
	 * Parse a view file.
	 *
	 * @param string
	 * @param array
	 * @param bool
	 * @return string
	 **/
	public function parse($template, $data = array(), $return = FALSE)
	{
		// First get the view.
		$string = $this->_ci->load->view($template, $data, TRUE);

		// Now run the Twig parser.
		return $this->_parse($string, $data, $return);
	}

	/**
	 * Parse
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @param string
	 * @param array
	 * @param bool
	 * @return string
	 */
	public function _parse($string, $data, $return = FALSE)
	{
		// Start benchmark
		$this->_ci->benchmark->mark('twig_parse_start');

		// Convert from object to array
		if (!is_array($data))
		{
			$data = (array)$data;
		}

		// Ensure that we get all the data.
		$data = array_merge($data, $this->_ci->load->_ci_cached_vars);

		// Parse the template.
		try
		{
			$parsed_string = $this->_twig->render($string, $data);
		}
		catch (Exception $e)
		{
			show_error($e);
		}

		// Finish benchmark
		$this->_ci->benchmark->mark('twig_parse_end');

		// Return results or not ?
		if (!$return)
		{
			$this->_ci->output->append_output($parsed_string);
			return;
		}

		return $parsed_string;
	}
}