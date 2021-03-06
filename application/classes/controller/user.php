<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base {
    
    private $error;
    private $success = '';
    
    public function action_index() {        
        
        if($this->request->param('sort')){
            $sort = $this->request->param('sort');
        } else {
            $sort = 'id';
        }
        
        if($this->request->param('order')){
            $order = $this->request->param('order');
        } else {
            $order = 'DESC';
        }
        
        if($this->request->param('filter_batch')) {
            $filters = array(
                    'filter_batch' => $this->request->param('filter_batch'),
                    
            );
            
            $total = Model_User::users_total_batch($filters);
            
            $count = $total;
            
            $pagination = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 50,
            ));
            
            $filters = array_merge($filters, array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pagination->items_per_page,
                'offset' => $pagination->offset,            
            ));
            
            $users = Model_User::users_batch($filters);
        
        } else if($this->request->param('filter_course')) {
            $filters = array(
                    'filter_course' => $this->request->param('filter_course'),
                    
            );
            
            $total = Model_User::users_total_course($filters);
            
            $count = $total;
            
            $pagination = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 50,
            ));
            
            $filters = array_merge($filters, array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pagination->items_per_page,
                'offset' => $pagination->offset,            
            ));
            
            $users = Model_User::users_course($filters);
        } else if($this->request->param('filter_role')) {
            $filters = array(
                    'filter_role' => $this->request->param('filter_role'),
                    
            );
            
            $total = Model_User::users_total_role($filters);
            
            $count = $total;
            
            $pagination = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 50,
            ));
            
            $filters = array_merge($filters, array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pagination->items_per_page,
                'offset' => $pagination->offset,            
            ));
            
            $users = Model_User::users_role($filters);
        } else {
            $filters = array(
                    'filter_id' => $this->request->param('filter_id'),
                    'filter_name' => $this->request->param('filter_name'),
                    'filter_approved' => $this->request->param('filter_approved'),
            );
            
            $total = Model_User::users_total($filters);
            
            $count = $total;
            
            $pagination = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 50,
            ));
            
            $filters = array_merge($filters, array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pagination->items_per_page,
                'offset' => $pagination->offset,            
            ));
            
            $users = Model_User::users($filters);
            
        }
        
        $sorting = new Sort(array(
            'Roll No'           => 'id',
            'Name'              => array('sort' => 'firstname', 'attributes' => array('width' => 330)),
            'Batch'             => array('sort' => '', 'attributes' => array('width' => 140)),
            'Courses'           => array('sort' => '', 'attributes' => array('width' => 140)),
            'Approved'          => array('sort' => 'status', 'attributes' => array('width' => 140)),
            'Actions'           => ''
        ));
        
        $url = ('user/index');
        
        if($this->request->param('filter_name')){
            $url .= '/filter_name/'.$this->request->param('filter_name');
            $filter = $this->request->param('filter_name');
            $filter_select = 'filter_name';
        }
        
        if($this->request->param('filter_id')){
            $url .= '/filter_id/'.$this->request->param('filter_id');
            $filter = $this->request->param('filter_id');
            $filter_select = 'filter_id';
        }
        
        if($this->request->param('filter_batch')){
            $url .= '/filter_batch/'.$this->request->param('filter_batch');
            $filter = $this->request->param('filter_batch');
            $filter_select = 'filter_batch';
        }
        
        if($this->request->param('filter_course')){
            $url .= '/filter_course/'.$this->request->param('filter_course');
            $filter = $this->request->param('filter_course');
            $filter_select = 'filter_course';
        }
        
        if($this->request->param('filter_approved')){
            $url .= '/filter_approved/'.$this->request->param('filter_approved');
            $filter = $this->request->param('filter_approved');
            $filter_select = 'filter_approved';
        }
        
        if($this->request->param('filter_role')){
            $url .= '/filter_role/'.$this->request->param('filter_role');
            $filter = $this->request->param('filter_role');
            $filter_select = 'filter_role';
        }
        
        $sorting->set_link($url);
        
        $sorting->set_order($order);
        $sorting->set_sort($sort);
        $heading = $sorting->render();
        
        $pagination = $pagination->render();
        
        $links = array(
            'add'       => Html::anchor('/user/add/', 'Create a user', array('class' => 'createButton l')),
            'uploadcsv' => Html::anchor('/user/uploadcsv/', 'Upload CSV', array('class' => 'pageAction l')),
            'roles'     => Html::anchor('/role/', 'Manage Roles', array('class' => 'pageAction 1')),
            'delete'    => URL::site('/user/delete/')
        );
        
        $table['heading'] = $heading;
        $table['data'] = $users;
        
        $filter_url = URL::site('user/index');
        $cacheimage = CacheImage::instance();

        $success = Session::instance()->get('success');
        Session::instance()->delete('success');        
        
        $view = View::factory('user/list')
            ->bind('table', $table)
            ->bind('users', $users)
            ->bind('count', $count)
            ->bind('links', $links)
            ->bind('pagination', $pagination)
            ->bind('filter', $filter)
            ->bind('filter_select', $filter_select)
            ->bind('filter_url', $filter_url)
            ->bind('cacheimage', $cacheimage)
            ->bind('success', $success);
        
        Breadcrumbs::add(array(
            'User', Url::site('user')
        ));
        
        $this->content = $view;
    }
    
    public function action_add(){
        $submitted = false;
        
        if($this->request->method() === 'POST' && $this->request->post()){
            if (Arr::get($this->request->post(), 'save') !== null){
                $submitted = true;
                $user = ORM::factory('user');
                $validator = $user->validator_create($this->request->post());
                $validator->bind(':user', NULL);
                if($validator->check()) {
                    $user->firstname = $this->request->post('firstname');
                    $user->lastname = $this->request->post('lastname');
                    $user->email = $this->request->post('email');
                    $user->avatar = $this->request->post('avatar');
                    $password = rand(10000, 65000);
                    $user->password = Auth::instance()->hash($password);
                    $user->status = $this->request->post('status');
                    $role = ORM::factory('role', $this->request->post('role_id'));
                    $user->save();
                    $user->add('roles', $role);
                    $this->update_courses($user, Arr::get($this->request->post(), 'course_id', array()));
                    $this->update_batches($user, Arr::get($this->request->post(), 'batch_id', array()));
                    self::notify_by_email($user, $password);
                    Session::instance()->set('success', 'User added successfully.');
                    Request::current()->redirect('user');
                    exit;
                } else {
                    $this->_errors = $validator->errors('register');
                }
            }
        }
        
        $form = $this->form('user/add', $submitted);
        $upload_url = URL::site('user/uploadavatar');
        $remove_url = URL::site('user/removeimage');
        $image = CacheImage::instance();
        $avatar = $image->resize('', 100, 100);
        $filename = "";
        $view = View::factory('user/form')
            ->bind('form', $form)
            ->bind('upload_url', $upload_url)
            ->bind('remove_url', $remove_url)
            ->bind('avatar', $avatar)
            ->bind('filename', $filename)
            ->set('page_title', 'Create a new user');
        Breadcrumbs::add(array(
            'User', Url::site('user')
        ));
        Breadcrumbs::add(array(
            'Create', Url::site('user/add')
        ));    
        $this->content = $view;
    }
    
    private function form($action, $submitted = false, $saved_data = array()) {
        
        $roles = array();
        foreach(ORM::factory('role')->find_all() as $role){
            $roles[$role->id] = $role->name;
        }

        $batches = array();
        foreach(ORM::factory('batch')->find_all() as $batch){
            $batches[$batch->id] = $batch->name;
        }
        
        $courses = array();
        foreach(ORM::factory('course')->find_all() as $course) {
            $courses[$course->id] = $course->name;
        }
        
        $form = new Stickyform($action, array(), ($submitted ? $this->_errors : array()));
        $form->default_data = array(
            'firstname' => '',
            'lastname'  => '',
            'email'     => '',
            'role_id'   => '',
            'batch_id'  => '',
            'course_id' => '',
            'status'    => 0,
        );        
        $form->saved_data = $saved_data;
        // var_dump($saved_data); exit;
        $form->posted_data = $submitted ? $this->request->post() : array();
        $form->append('First Name', 'firstname', 'text');
        $form->append('Last Name', 'lastname', 'text');
        $form->append('Email', 'email', 'text');
        $form->append('Role', 'role_id', 'select', array('options' => $roles));
        $form->append('Select batch', 'batch_id', 'select', array('options' => $batches, 'attributes' => array('multiple' => 'multiple', 'name' => 'batch_id[]')));
        $form->append('Select Course', 'course_id', 'select', array('options' => $courses, 'attributes' => array('multiple' => 'multiple', 'name' => 'course_id[]')));
        $form->append('User Status', 'status', 'select', array(
            'options' => array(
                1 => 'Approved',
                0 => 'Unapproved/Banned',
            ),         
        ));
        $form->append('Save', 'save', 'submit', array('attributes' => array('class' => 'button')));
        $form->process();
        return $form;
    }
    
    public function action_edit() {
        $submitted = false;
        
        $id = $this->request->param('id');
        if (!$id) {
            Request::current()->redirect('user');
        }
        
        $user = ORM::factory('user', $id);

        if($this->request->method() === 'POST' && $this->request->post()){
            if (Arr::get($this->request->post(), 'save') !== null){
                $submitted = true;
                $validator = $user->validator_create($this->request->post());
                $validator->bind(':user', $user);
                if ($validator->check()) {
                    $user->firstname = $this->request->post('firstname');
                    $user->lastname = $this->request->post('lastname');
                    $user->email = $this->request->post('email');
                    $user->avatar = $this->request->post('avatar');                    
                    $user->status = $this->request->post('status');
                    $user->save();
                    //removing the previous role assigned
                    $user->remove('roles');
                    //creating a role object and assigning a new role
                    $role = ORM::factory('role', $this->request->post('role_id'));
                    $user->add('roles', $role);                    
                    $this->update_courses($user, Arr::get($this->request->post(), 'course_id', array()));
                    $this->update_batches($user, Arr::get($this->request->post(), 'batch_id', array()));
                    Session::instance()->set('success', 'User modified successfully.');
                    Request::current()->redirect('user');
                    exit;
                } else {
                    $this->_errors = $validator->errors('register');
                }
            }
        }
        
        $form = $this->form('user/edit/id/'.$id ,$submitted, array(
            'firstname' => $user->firstname, 
            'lastname' => $user->lastname, 
            'email' => $user->email, 
            'role_id' => $user->roles->find()->id, 
            'batch_id' => $user->batches->find_all()->as_array(NULL, 'id'), 
            'course_id' => $user->courses->find_all()->as_array(NULL, 'id'),
            'status' => (int) $user->status,
        ));        
        //$heading[] = "View/Edit ".$user->firstname."'s Profile";        
        $upload_url = URL::site('user/uploadavatar');
        $remove_url = URL::site('user/removeimage');
        $image = CacheImage::instance();
        $avatar = $image->resize($user->avatar, 100, 100);
        $filename = $user->avatar;
        $view = View::factory('user/form')
            ->bind('form', $form)
            ->bind('upload_url', $upload_url)
            ->bind('remove_url', $remove_url)
            ->bind('filename', $filename)
            ->bind('avatar', $avatar)
            ->set('page_title', sprintf('View/Edit %s\'s profile', $user->firstname));
        Breadcrumbs::add(array(
            'User', Url::site('user')
        ));
        Breadcrumbs::add(array(
            'Edit', Url::site('user/edit/id/'.$id)
        ));    
        $this->content = $view;
    }

    /**
     * Method to update the batches in which the user is added depending upon the list of selected batch ids
     * Will be used incase of both add and edit
     */
    protected function update_batches($user, $selected) {
        $current_batches = $user->batches->find_all()->as_array(null, 'id');        
        $added = array_values(array_diff($selected, $current_batches));
        $removed = array_values(array_diff($current_batches, $selected));
        // removing to be done before adding
        if ($removed) {
            //removing the previous batches assigned
            $user->remove('batches');   
            foreach ($removed as $batch_id) {
                $feed = new Feed_Batch();
                $feed->set_action('student_remove');
                $feed->set_course_id('0');
                $feed->set_respective_id($batch_id);
                $feed->set_actor_id(Auth::instance()->get_user()->id); 
                $feed->streams(array('user_id' => $user->id));
                $feed->save();                
            }
        }
        if ($added) {
            foreach ($added as $batch_id) {
                $batch = ORM::factory('batch', $batch_id);
                $user->add('batches', $batch);
                $feed = new Feed_Batch();
                $feed->set_action('student_add');
                $feed->set_course_id('0');
                $feed->set_respective_id($batch_id);
                $feed->set_actor_id(Auth::instance()->get_user()->id); 
                $feed->streams(array('user_id' => $user->id));
                $feed->save();                
            }
        }
    }

    /**
     * Method to update the courses in which the user is added depending upon the list of selected course ids
     * Will be used incase of both add and edit
     */
    protected function update_courses($user, $selected) {
        $current_courses = $user->courses->find_all()->as_array(null, 'id');        
        $added = array_values(array_diff($selected, $current_courses));
        $removed = array_values(array_diff($current_courses, $selected));
        // removing to be done before adding
        if ($removed) {
            //removing the previous courses assigned
            $user->remove('courses');   
            foreach ($removed as $course_id) {
                $feed = new Feed_Course();
                $feed->set_action('student_remove');
                $feed->set_course_id('0');
                $feed->set_respective_id($course_id);
                $feed->set_actor_id(Auth::instance()->get_user()->id); 
                $feed->streams(array('user_id' => $user->id));
                $feed->save();                
            }
        }
        if ($added) {
            foreach ($added as $course_id) {
                $course = ORM::factory('course', $course_id);
                $user->add('courses', $course);
                $feed = new Feed_Course();
                $feed->set_action('student_add');
                $feed->set_course_id('0');
                $feed->set_respective_id($course_id);
                $feed->set_actor_id(Auth::instance()->get_user()->id); 
                $feed->streams(array('user_id' => $user->id));
                $feed->save();                
            }
        }
    }

    public function action_delete(){
        if($this->request->method() === 'POST' && $this->request->post('selected')){
            foreach($this->request->post('selected') as $user_id){
                ORM::factory('user', $user_id)->delete();
            }
        }
        Session::instance()->set('success', 'User(s) deleted successfully.');
        Request::current()->redirect('user');
    }
    
    public function action_uploadcsv(){

        if($this->request->method() === 'POST' && $this->request->post()){
            
            if (Arr::get($this->request->post(), 'save') !== null){
                
                $filename = $_FILES['csv']['name'];
                $extension = explode(".",$filename);
                if(isset($extension[1]) && strtolower($extension[1]) === "csv"){ //Validation of file 
                    
                    $filename = $_FILES['csv']['tmp_name'];
                    $handle = fopen($filename, "r");
                    
                    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE){
                        $filedata[] = $data;
                    }

                    unset($filedata[0]);

                    $records_added = 0;
                    $error = 0;
                    
                    $user_id = array();
                    foreach($filedata as $key => $row){
                    	$data = array(
                            'firstname' => isset($row[0]) ? $row[0] : '',
                            'lastname'  => isset($row[1]) ? $row[1] : '',
                    	    'email'     => isset($row[2]) ? $row[2] : '',
                    	);
                        
                    	$user = ORM::factory('user');
                        $validator = $user->validator_create($data);
                        $validator->bind(':user', NULL);
                        if ($validator->check()) {
                            //add user
                            $user->firstname = $data['firstname'];
                            $user->lastname = $data['lastname'];
                            $user->email = $data['email'];
                            $password = rand(10000, 65000);
                            $user->password = Auth::instance()->hash($password);
                            $role = ORM::factory('role', $this->request->post('role_id'));
                            $user->save();
                            $user->add('roles', $role);
                            $user_id[] = $user->id;
                            
                            // assign to batch only if the role of the user is student
                            if ($user->is_role('student')) {
                                if(($this->request->post('batch_id'))){
                                    foreach($this->request->post('batch_id') as $batch_id){
                                        $batch = ORM::factory('batch', $batch_id);
                                        $user->add('batches', $batch);
                                    }
                                }
                            }                            

                            // send email
                            self::notify_by_email($user, $password);
                            $records_added += 1;	
                        } else {
                            $this->error['warning'] = "There was an error on line # " . $key . " Records Added " . $records_added;
                            $this->error['description'] = implode('<br/>',$validator->errors('register'));
                            $error = 1;
                            break;
                        }
                    }                    
                    if(!$error){
                        $this->success = "Users uploaded successfully. Records Added " . $records_added ;
                        if(($this->request->post('batch_id'))){
                            foreach($this->request->post('batch_id') as $batch_id){
                                $feed = new Feed_Batch();
                                $feed->set_action('student_add');
                                $feed->set_course_id('0');
                                $feed->set_respective_id($batch_id);
                                $feed->set_actor_id(Auth::instance()->get_user()->id); 
                                $feed->streams(array('user_id' => $user->id));
                                $feed->save();
                            }
                        }
                    }                    
                    fclose($handle);
                } else {
                    $this->error['warning'] = "The file you uploaded is not a valid csv file";
                    $this->error['description'] = ""; 
                }
            }
        }
        
    	$roles = array();
        foreach(ORM::factory('role')->find_all() as $role){
            $roles[$role->id] = $role->name;
        }

        $batches = array();
        foreach(ORM::factory('batch')->find_all() as $batch){
            $batches[$batch->id] = $batch->name;
        }
        
        $form = new Stickyform('user/uploadcsv', array('enctype' => 'multipart/form-data'), array());
        $form->default_data = array(
            'role_id'   => '',
            'batch_id'  => $this->request->param('batch_id', array())
        );
        
        $form->saved_data = array();
        $form->posted_data =  array();
        $form->append('Role', 'role_id', 'select', array('options' => $roles));
        $form->append('Select batch', 'batch_id', 'select', array('options' => $batches, 'attributes' => array('multiple' => 'multiple', 'name' => 'batch_id[]')));
        $form->append('Upload', 'save', 'submit', array('attributes' => array('class' => 'button')));
        $form->process();
        
        $links = array(
            'sample'    => Html::anchor('/users_sample.csv', 'or click here to download a sample CSV file')
        );
        
    	$view = View::factory('user/uploadcsv')
            ->bind('form', $form)
            ->bind('error', $this->error)
            ->bind('success', $this->success)
            ->bind('links', $links)
            ;
        Breadcrumbs::add(array(
            'User', Url::site('user')
        ));
        Breadcrumbs::add(array(
            'Upload csv', Url::site('user/uploadcsv')
        ));
    	$this->content = $view;
        
    }

    private static function notify_by_email($user, $password) {
        
        $file = "account_creation_email";
        $data =array(
                '{user_name}'  => $user->firstname ." ". $user->lastname,
                '{email}' => $user->email,
                '{password}' => $password,
            );
        
        Email::send_mail($user->email, $file, $data);
    }

    public function action_uploadavatar(){
        
        $filename = time() . '_' . $_FILES['image']['name'];
                
        $file_validation = new Validation($_FILES);
        $file_validation->rule('image','upload::valid');
        $file_validation->rule('image', 'upload::type', array(':value', array('jpg', 'png', 'gif', 'jpeg')));
        
        if ($file_validation->check()){
            
            if($path = Upload::save($_FILES['image'], $filename, DIR_IMAGE)){
                
                $image = CacheImage::instance();
                $src = $image->resize($filename, 100, 100);
                
                $json = array(
                   'success'   => 1,
                   'image'     => $src,
                   'filename' => $filename
                );
            } else {
                $json = array(
                   'success'  => 0,
                   'errors'   => array('image' => 'The file is not a valid Image'),
                   
                );
            }
        } else {
            $json = array(
                 'success'   => 0,
                 'errors'    => (array) $file_validation->errors('profile')
            );
        }
        
         
        echo json_encode($json);
        exit;
        
    }
    
    public function action_removeimage(){
        $image = CacheImage::instance();
        $src = $image->resize('', 100, 100);
        echo $src;
        exit;
    }
    
}
