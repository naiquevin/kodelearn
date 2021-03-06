<?php defined('SYSPATH') or die('No direct access allowed.');

class Acl_Menu_Admin extends Acl_Menu {

    public function __construct() {
        parent::__construct();
        $topmenu = DynamicMenu::factory('topmenu');
        $topmenu->add_link('home', 'Home')
            ->add_link('account', 'Profile')
            ->add_link('inbox', 'Inbox')
            ->add_link('auth/logout', 'Logout');
        $sidemenu = DynamicMenu::factory('sidemenu');
        $sidemenu->add_link('user', 'Users', 0)
            ->add_link('batch', 'Batches', 1)
            ->add_link('system', 'System', 2)
            ->add_link('course', 'Courses', 3);
        $myaccount = DynamicMenu::factory('myaccount');
        $myaccount->add_link('system', 'Setting', 0)
            ->add_link('account', 'Account', 1)
            ->add_link('auth/logout', 'Logout', 2);
        $coursemenu = DynamicMenu::factory('coursemenu');
        
        $profilemenu = DynamicMenu::factory('profilemenu');
        $profilemenu->add_link('profile/view/id', 'Info', 0)
                ->add_link('profile', 'Wall', 1);
                    
        $this->set('topmenu', $topmenu)
            ->set('sidemenu', $sidemenu)
            ->set('coursemenu', $coursemenu)
            ->set('myaccount', $myaccount)
            ;
    }
}
