<?php
namespace nano\core\widget;

/**
 * Base class for widgets.
 * 
 * @author Christopher Beck, Alessandro Barzanti & Alex Cipriani
 * @version SVN: $id
 * @package nanophp
 * @subpackage project.core.widget
 */
class Widget {

	private $pageInstance = null;
	private $routing = null;
	private $renderedWidget = null;
	private $data = array();
	private $isRendered = false;
	private $templateToRender = null;
	
	/**
	 * Constructor.
	 * Calls the widget's preLoad() function.
	 * 
	 * @param \nano\core\page\Page $pageInstance Page on which this widget is to be used.
	 */
	final public function __construct(\nano\core\page\Page $pageInstance = null) {
		$this->pageInstance = $pageInstance;
		$this->routing = \nano\core\routing\Routing::getInstance();
		$this->preLoad($this->routing,$this->pageInstance);
	}
	
	/**
	 * Destructor.
	 * If the request was made via Ajax, the widget will be rendered on a call to the destructor
	 * if it has not already been.
	 */
	final public function __destruct() {
		if($this->routing->isAjax()&&!$this->isRendered){
			$this->render();
			echo $this->renderedWidget;
			$this->postOutput($this->routing,$this->pageInstance);
		}
	}
	
	/**
	 * Function - __set
	 * @param mixed $name Name
	 * @param  $value Value
	 */
	final public function __set($name, $value) {
		$this->data[$name] = $value;
	}
	
	/**
	 * Function - &__get
	 * @param mixed $name Name
	 * @return mixed
	 */
	final public function &__get($name) {
		return $this->data[$name];
	}
	
	/**
	 * Function - Render
	 */
	final public function render() {
		if(!$this->isRendered){
			$this->isRendered = true;
			$this->preExecute($this->routing,$this->pageInstance);
			$this->templateToRender = $this->execute($this->routing,$this->pageInstance);
			$this->postExecute($this->routing,$this->pageInstance);
			$this->preRender($this->routing,$this->pageInstance);
			$this->renderedWidget = \nano\core\view\View::load($this->templateToRender,$this->data,true);
			$this->postRender($this->routing,$this->pageInstance);
		}
	}
	
	/**
	 * Render the widget (if it has not already been rendered, and return.
	 * @return string Rendered widget.
	 */
	final public function getRenderedWidget() {
		$this->render();
		return $this->renderedWidget;
	}
	
	/**
	 * Set the rendered output for the widget and mark it as having been rendered.
	 * @param string $data Rendered output.
	 */
	final public function setRenderedWidget($data) {
		$this->isRendered = true;
		$this->renderedWidget = $data;
	}
	
	/**
	 * Set the filename of the template to use to render this widget.
	 * @param string $name Filename of template.
	 */
	final public function setTemplateToRender($name){
		$this->templateToRender = (string)$name;
	}
	
	/**
	 * Get the filename of the template set to render this widget.
	 * @return string Filename of template.
	 */
	final public function getTemplateToRender(){
		return $this->templateToRender;
	}
	
	/**
	 * Abstract method called by the widget constructor.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 * @param \nano\core\page\Page $pageInstance Page on which this widget is to be used.
	 */
	protected function preLoad(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null){
		
	}
	
	/**
	 * Abstract method called immediately on the first call to render().
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 * @param \nano\core\page\Page $pageInstance Page on which this widget is to be used.
	 */
	protected function preExecute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null){
		
	}
	
	/**
	 * Abstract method called by render() following preExecute().
	 * When overridden, this should return the full path of the Twig template to render.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 * @param \nano\core\page\Page $pageInstance Page on which this widget is to be used.
	 */
	protected function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null){
		
	}
	
	/**
	 * Abstract method called by render() following execute().
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 * @param \nano\core\page\Page $pageInstance Page on which this widget is to be used.
	 */
	protected function postExecute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null){
		
	}
	
	/**
	 * Abstract method called by render() prior to rendering the Twig template.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 * @param \nano\core\page\Page $pageInstance Page on which this widget is to be used.
	 */
	protected function preRender(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null){
		
	}
	
	/**
	 * Abstract method called by render() after the Twig template has been rendered.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 * @param \nano\core\page\Page $pageInstance Page on which this widget is to be used.
	 */
	protected function postRender(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null){
		
	}
	
	/**
	 * Abstract method called after the widget has been output by the destructor.
	 * @param \nano\core\routing\Routing $routing Shared routing instance.
	 * @param \nano\core\page\Page $pageInstance Page on which this widget is to be used.
	 */
	protected function postOutput(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null){
		
	}
	
}