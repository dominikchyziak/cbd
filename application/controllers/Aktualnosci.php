<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * News Controller.
 */
class Aktualnosci extends Frontend_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load models.
        $this->load->model('NewsModel');

        // Set defaults.
        $this->set_title((new CustomElementModel('10'))->getField('aktualnosci meta title'));
        $this->set_desc((new CustomElementModel('10'))->getField('aktualnosci meta desc'));
        
        $home = site_url('/');
        $this->breadcrumbs[] = "<a href=$home>" . (new CustomElementModel('10'))->getField('Strona glowna') . "</a>";
    }

//    public function index(){
//        $this->load->model('PageModel');
//        $pages = [
//            0 => [
//                'name' => (new CustomElementModel('20'))->getField('odbiorcy wycen tytul')->value,
//                'image' => (new CustomElementModel('20'))->getField('odbiorcy wycen obrazek')->value,
//                'link' => site_url('klienci/odbiorcy-wycen')
//            ],
//            1 => [
//                'name' => (new CustomElementModel('20'))->getField('banki tytul')->value,
//                'image' => (new CustomElementModel('20'))->getField('banki obrazek')->value,
//                'link' => site_url('klienci/banki')
//            ],
//            2 => [
//                'name' => (new CustomElementModel('20'))->getField('moi klienci tytuł')->value,
//                'image' => (new CustomElementModel('20'))->getField('moi klienci obrazek')->value,
//                'link' => site_url('klienci/moi-klienci')
//            ]
//        ];
//        $site = site_url('klienci');
//        $this->breadcrumbs[] = "<a href=$site>Klienci</a>";
//        $this->layout('Aktualnosci/index',[ 'pages' => $pages]);
//    }
    
//    public function klienci($category_id = 1){
//        $site = site_url('klienci');
//        $this->breadcrumbs[] = "<a href=$site>Klienci</a>";
//        switch ($category_id){
//            case 1: //odbiory wycen
//                $site = site_url('klienci/odbiorcy-wycen');
//                $title = (new CustomElementModel('20'))->getField('odbiorcy wycen tytul')->value;
//                $this->breadcrumbs[] = "<a href=$site>" . $title . "</a>";
//                $news = $this->NewsModel->getFrontendList(LANG, 3000, 0,$category_id);
//                $content = (new CustomElementModel('20'))->getField('odbiorcy wycen tresc')->value;
//                break;
//            case 2:
//                $site = site_url('klienci/banki');
//                $title = (new CustomElementModel('20'))->getField('banki tytul')->value;
//                $this->breadcrumbs[] = "<a href=$site>" . $title  . "</a>";
//                $news = $this->NewsModel->getFrontendList(LANG, 3000, 0,$category_id);
//                $content = (new CustomElementModel('20'))->getField('banki tresc')->value;
//                break;
//            case 3:
//                $site = site_url('klienci/moi-klienci');
//                $title = (new CustomElementModel('20'))->getField('moi klienci tytuł')->value;
//                $this->breadcrumbs[] = "<a href=$site>" . $title  . "</a>";
//                $news = $this->NewsModel->getFrontendList(LANG, 3000, 0,$category_id);
//                $content = (new CustomElementModel('20'))->getField('moi klienci tresc')->value;
//                break;
//            default :
//                break;
//        }
//        
//        
//        $this->layout('Aktualnosci/klienci',[
//            'content' => $content,
//            'news' => $news,
//            'title' => $title
//        ]);
//    }
//    /**
//     * Default method. Use strona() method.
//     */
//    public function index($category_id = null)
//    {
//        $this->strona(null,$category_id);
//    }
//      
    public function index($strona = 1)
    {
        $this->load->library('pagination');
        
        $config = [
            'base_url' => site_url('blog'),
            'total_rows' => $this->NewsModel->getFrontendTotalRows(LANG),
            'per_page' => 9,
            'use_page_numbers' => true,
            'attributes' => array('class' => 'page-link'),
            'cur_tag_open' => '<li class="page-item active"><a href="" class="page-link">',
            'cur_tag_close' => '</a></li>'
        ];
        
        $this->pagination->initialize($config);   
        
        $news = $this->NewsModel->getFrontendList(LANG, $config['per_page'], (--$strona)*9);
        
        //$site = site_url('blog');
        //$this->breadcrumbs[] = "<a href=$site>" . (new CustomElementModel('10'))->getField('Aktualnosci') . "</a>";
        $this->layout('Aktualnosci/strona', [
            'news' => $news,
        ]);
    }
//    /**
//     * Get news list.
//     *
//     * @param int $strona Page number (Default 'null').
//     */
//    public function strona($strona = null, $category_id = 1)
//    {        
//        $this->load->library('pagination');
//
//        $config = [
//            'base_url' => site_url('restauracje/strona'),
//            'total_rows' => $this->NewsModel->getFrontendTotalRows(LANG, $category_id),
//            'per_page' => 10,
//            'use_page_numbers' => true
//        ];
//
//        $this->pagination->initialize($config);
//
//        $news = $this->NewsModel->getFrontendList(LANG, $config['per_page'], --$strona*10,$category_id);
//        $site = site_url('restauracje');
//        $this->breadcrumbs[] = "<a href=$site>" . (new CustomElementModel('10'))->getField('Aktualnosci') . "</a>";
//        $this->layout('Aktualnosci/strona', [
//            'news' => $news
//        ]);
//    }
    
//    public function promocje($strona = null)
//    {
//        $this->set_desc((new CustomElementModel('10'))->getField('Promocje opis'));
//        $this->set_title((new CustomElementModel('10'))->getField('Promocje'));
//        $this->load->library('pagination');
//
//        $config = [
//            'base_url' => site_url('tutoriale/strona'),
//            'total_rows' => $this->NewsModel->getFrontendTotalRows(LANG,2),
//            'per_page' => 10,
//            'use_page_numbers' => true
//        ];
//
//        $this->pagination->initialize($config);
//
//        $news = $this->NewsModel->getFrontendList(LANG, $config['per_page'], --$strona*10,2);
//        $site = site_url('promocje');
//        $this->breadcrumbs[] = "<a href=$site>" . (new CustomElementModel('10'))->getField('Promocje') . "</a>";
//        $this->layout('Aktualnosci/promocje', [
//            'news' => $news
//        ]);
//    }
    
    public function pokaz($id) {
        
        $news = new NewsModel($id);

        if(empty($news->id)){
            $this->show404(); 
        }else {
        $translation = $news->getTranslation(LANG);
        if(!empty($translation->meta_title)){
          $this->set_whole_title($translation->meta_title);   
        }else {
        $this->set_title($translation->title);
        }
        if(!empty($translation->meta_description)){
            $this->set_desc($translation->meta_description);
        }
        $this->set_canon_link($news->getPermalink());
        $site = site_url('blog');
        $this->breadcrumbs[] = "<a href=$site>" . (new CustomElementModel('10'))->getField('aktualnosci naglowek') . "</a>";
        //$home = site_url('aktualnosci/'.getAlias($translation->news_id, $translation->title));
        //$this->breadcrumbs[] = "<a href=$home>" . $translation->title . "</a>";
        $this->layout('Aktualnosci/pokaz', [
            'news' => $news,
            'translation' => $translation
        ]);
        }
    }




    /**
     * Get single news.
     *
     * @param int $id News ID.
     */
//    public function pokaz($id)
//    {
//        $this->load->model("User_model");
//        $news = new NewsModel($id);
//
//        if (! $news->id) {
//            show_404();
//        }
//
//        $translation = $news->getTranslation(LANG);
//
//        // Set defaults.
//        $this->set_desc($translation->excerpt);
//        $this->set_title($translation->title);
//        if($news->category_id == 2) {
//            $site = site_url('promocje');
//            $this->breadcrumbs[] = "<a href=$site>" . (new CustomElementModel('10'))->getField('Promocje') . "</a>";
//            $home = site_url('promocje/'.getAlias($translation->news_id, $translation->title));
//        } else {
//            $site = site_url('aktualnosci');
//            $this->breadcrumbs[] = "<a href=$site>" . (new CustomElementModel('10'))->getField('Aktualnosci') . "</a>";
//            $home = site_url('aktualnosci/'.getAlias($translation->news_id, $translation->title));
//        }
//        
//        
//        $this->breadcrumbs[] = "<a href=$home>" . $translation->title . "</a>";
//        $this->layout('Aktualnosci/pokaz', [
//            'news' => $news,
//            'translation' => $translation
//        ]);
//    }
}
