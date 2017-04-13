<?php

class IndexController extends Controller
{
    /**
     * Construct this class by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index
     *  - or -
     *      http://example.com/index/index
     *  - or -
     * Since this controller is set as the default controller in
     * Config/Saika.config.php, it's displayed at http://example.com/
     *
     * So any other public methods will
     * map to /index/<method_name>
     */
    public function index()
    {
        // We can show a view to the visitor here
        $this->view->assignMulti(['title' => 'Saika']);
        $this->view->render('_global/header');
        $this->view->render('home/index');
        $this->view->render('_global/footer');
    }

}
