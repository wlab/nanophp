<?php
namespace nano\core\page;

/**
 * Base class for pages.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.page
 */
class Page {
	
	private $data = array();
	private $routing = null;
	protected $templateToRender = null;
	private $isRendered = false;
	
	/**
	 * Constructor.
	 * Calls the page's preLoad() function.
	 */
	final public function __construct() {
		$this->routing = \nano\core\routing\Routing::getInstance();
		$this->preLoad($this->routing);
	}
	
	/**
	 * Function - __set
	 * @param mixed $name Name
	 * @param mixed $value Value
	 */
	final public function __set($name, $value) {
		$this->data[$name] = $value;
	}
	
	//Must return a reference to the object saved in order for arrays and objects to exhibit expected behaviour, e.g. $foo[] = 'kung'.
	/**
	 * Function - &__get
	 * @param mixed $name Name
	 * @return mixed Value
	 */
	final public function &__get($name) {
		return $this->data[$name];
	}
	
	/**
	 * Destructor.
	 * Calls render() on the page.
	 */
	final public function __destruct() {
		$this->render();
	}
	
	/**
	 * Render the page.
	 * Normally called by the page's destructor. 
	 * Calls preExecute(), execute(), postExecute(), and preRender(), before passing the
	 * template name and page data to \nano\core\view\View::load(), and calling postRender()
	 * and postOutput().
	 */
	final public function render() {
		if(!$this->isRendered){
			$this->isRendered = true;
			$this->preExecute($this->routing);
			$this->templateToRender = $this->execute($this->routing);
			$this->postExecute($this->routing);
			$this->data['header'] = isset($this->data['header'])? $this->data['header'] : '';
			$this->preRender($this->routing);
			\nano\core\view\View::load($this->templateToRender,$this->data);
			$this->postRender($this->routing);
			$this->postOutput($this->routing);
		}
	}
	
	/**
	 * Abstract method called by the page constructor.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 */
	protected function preLoad(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Abstract method called immediately on the first call to render().
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 */
	protected function preExecute(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Abstract method called by render() following preExecute().
	 * When overridden, this should return the full path of the Twig template to render.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 */
	protected function execute(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Abstract method called by render() following execute().
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 */
	protected function postExecute(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Abstract method called by render() prior to rendering the Twig template.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 */
	protected function preRender(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Abstract method called by render() after the Twig template has been rendered.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 */
	protected function postRender(\nano\core\routing\Routing $routing){
		
	}
	
	/**
	 * Abstract method called by render() prior to that function returning.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 */
	protected function postOutput(\nano\core\routing\Routing $routing){
		
	}
	
}