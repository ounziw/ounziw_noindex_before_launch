<?php    
namespace Concrete\Package\OunziwNoindexBeforeLaunch;

use SinglePage;
use Core;
use URL;
use Page;
use Package;
use View;
use Events;
use Loader;
use Concrete\Core\Application\Service\UserInterface\Menu;


class Controller extends \Concrete\Core\Package\Package {

    protected $pkgHandle = 'ounziw_noindex_before_launch';
    protected $appVersionRequired = '5.7.4';
    protected $pkgVersion = '1.2';
    
    public function getPackageDescription()
    {
        return t("Add noindex into HEAD tag");
    }
    
    public function getPackageName()
    {
        return t("Noindex Before Launch");
    }
    
    public function install()
    {
        $pkg = parent::install();
        // set the default date = 1 month after
        // the noindex,nofollow disappears when 1 month has passed
        $noindex_before_launch = date('Y-m-d H:i', strtotime('+1 month'));
        $pkg->getConfig()->save('concrete.noindex_before_launch', $noindex_before_launch);
        // create new single page
        $sp = SinglePage::add('/dashboard/system/seo/noindex_before_launch', $pkg);
        if (is_object($sp)) {
            $sp->update(array('cName'=>t('Noindex Before Launch'), 'cDescription'=>t('Noindex this site before the specified date.')));
        }
    }
    
    public function uninstall()
    {
        $pkg = Package::getByHandle('ounziw_noindex_before_launch');
        // remove config from database
        $pkg->getConfig()->clear('concrete.noindex_before_launch');
        parent::uninstall();
    }

    
    public function on_start()
    {
        Events::addListener('on_before_render', array($this,'check'));
    }

    public function check()
    {
        $icon = 'check';
        $status = t('Indexed');

        // Check if noindex datetime is ok or not.
        $pkg = Package::getByHandle('ounziw_noindex_before_launch');
        $noindex_before_launch = $pkg->getConfig()->get('concrete.noindex_before_launch');
        $now = date('Y-m-d H:i');

        if (strtotime($now) < strtotime($noindex_before_launch)) {
            $status = t('No index');
            $icon = 'remove';

            // Check if the current page is noindex
            // If already noindex, do not show twice.
            $page = Page::getCurrentPage();
            if (!is_object($page) || !$page->getCollectionAttributeValue('exclude_search_index') ) {
                $v = View::getInstance();
                $v->addHeaderItem('<meta name="robots" content="noindex">');
            }
        }
        $ihm = Core::make('helper/concrete/ui/menu');
        $ihm->addPageHeaderMenuItem('ounziw_noindex_before_launch', 'ounziw_noindex_before_launch',
            array(
                'label' => $status,
                'icon' => $icon,
                'position' => 'left',
                'href' => URL::to('/dashboard/system/seo/noindex_before_launch'),

            )
        );
    }
}