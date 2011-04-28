<?php
namespace project\apps\back_end\widgets\delete;

/**
 * Delete class 
 * 
 * Delete
 * 
 * @author Christopher Beck <cwbeck@gmail.com> & Alessandro Barzanti <a.barzanti@gmail.com>
 * @version SVN: $id
 * @package widgets
 * @subpackage delete
 */
class Delete extends \project\apps\back_end\templates\WidgetTemplate {

	/**
	 * Function - Execute
	 * @param \nano\core\routing\Routing $routing Routing
	 * @param \nano\core\page\Page $pageInstance Page Instance
	 * @return mixed
	 */
	public function execute(\nano\core\routing\Routing $routing,\nano\core\page\Page $pageInstance = null) {
		
		$this->deleteButton = array(
			'buttonValue' => 'delete',
			'buttonLabel' => i18n('admin.delete.delete-button-value','Delete Record')
		);
		
		return 'project/apps/back_end/widgets/delete/views/delete.twig';
	}
	
}