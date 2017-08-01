<?php
namespace Concrete\Package\OunziwNoindexBeforeLaunch\Controller\SinglePage\Dashboard\System\Seo;

use \Concrete\Core\Page\Controller\DashboardPageController;
use Package;

class NoindexBeforeLaunch extends DashboardPageController {

    public function view()
    {
        $pkg = Package::getByHandle('ounziw_noindex_before_launch');
        $noindex_before_launch = $pkg->getConfig()->get('concrete.noindex_before_launch');
        $this->set('noindex_before_launch', $noindex_before_launch);
    }

    public function updated()
    {
        $this->set('message', t("Settings saved."));
        $this->view();
    }
    
    public function save_settings()
    {
        if ($this->token->validate("save_settings")) {
            if ($this->isPost()) {
                $hour = $this->post('noindex_before_launch_h') ;
                $minute = $this->post('noindex_before_launch_m');
                if ($this->post('noindex_before_launch_a') == 'PM') {
                    $hour += 12;
                }
                $noindex_before_launch = $this->post('noindex_before_launch_dt') . ' ' . $hour . ':' . $minute;
                $pkg = Package::getByHandle('ounziw_noindex_before_launch');
                $pkg->getConfig()->save('concrete.noindex_before_launch', $noindex_before_launch);
                $this->redirect('/dashboard/system/seo/noindex_before_launch','updated');
            }
        } else {
            $this->set('error', array($this->token->getErrorMessage()));
        }
    }

}