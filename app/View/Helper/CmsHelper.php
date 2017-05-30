<?php
/**
 * Common Helper
 *
 * PHP version 5
 *
 * @category Helper
 */
class CmsHelper extends AppHelper {
/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
    public $helpers = array(
        'Html',
        'Form',
        'Session',
        'Js',
		'Ajax',
    );

    public function getCmsContent($sTitle) {

    }

    public function getCmsSlug($sTitle) {
        $sSiteUrl = $this->_View->viewVars['siteUrl'];

        $slug = Inflector::slug ($sTitle,'-');
        $slug = strtolower($slug);
        return $sSiteUrl . $slug;
    }
}
?>
