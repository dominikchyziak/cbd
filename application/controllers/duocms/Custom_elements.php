<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_elements extends Backend_Controller {
    public $languages = array();
    private $custom_element_model;
    private $custom_element_field_model;
    public function __construct()
	{
		parent::__construct();
                $langs = get_languages();
                foreach($langs as $l){
                    $this->languages[] = $l->short;
                }
		$this->load->vars(['activePage' => 'custom_elements']);
                $this->load->model('CustomElementModel');
                $this->load->model('CustomElementFieldModel');
                $this->custom_element_model = new CustomElementModel();
                $this->custom_element_field_model = new CustomElementFieldModel();
	}

	public function index()
	{
		$custom_elements = (new CustomElementModel())->findAll();
		$this->layout('duocms/Custom_elements/index', [
			'custom_elements' => $custom_elements
		]);
	}
        
        public function add_category(){
            if($this->input->post('name')){
                $this->custom_element_model->name = $this->input->post('name');
                $res = $this->custom_element_model->insert();
                setAlert('success','Kategoria została dodana.');
		redirect('duocms/custom_elements');
            }
            $this->layout('duocms/Custom_elements/add_category', []);
        }
        
        public function edit_category($category_id){
            if($this->input->post('name') && $this->input->post('category_id')){
                $this->custom_element_model->name = $this->input->post('name');
                $this->custom_element_model->id = $this->input->post('category_id');
                $res = $this->custom_element_model->update();
                if($res){
                    setAlert('success','Nazwa kategorii została zaktualizowana.');
                    redirect('duocms/custom_elements');
                }
            }
            
            $category = new CustomElementModel($category_id);
            $this->layout('duocms/Custom_elements/add_category', ['name' => $category->name, 'category_id' => $category->id]);
        }
        
        public function delete_category($category_id){
            $this->custom_element_model->id = $category_id;
            $res = $this->custom_element_model->delete();
            if($res){
                setAlert('warning','Kategoria została usunięta.');
                redirect('duocms/custom_elements');
            }
        }
        
        public function add_element($category_id){
            if($this->input->post('title')){
                $this->custom_element_field_model->custom_element_id = $category_id;
                $this->custom_element_field_model->title = $this->input->post('title');
                $this->custom_element_field_model->type = $this->input->post('type');
                $this->custom_element_field_model->value = $this->input->post('content');
                foreach ($this->languages as $lang) {
                    $this->custom_element_field_model->lang = $lang;
                    $res = $this->custom_element_field_model->insert();
                }              
                if($res){
                    setAlert('success','Element został dodany.');
                    redirect('duocms/custom_elements/edit/'. $category_id);
                }
            }
            
            $this->layout('duocms/Custom_elements/add_element', ['category_id' => $category_id]);
        }
        
        public function delete_element($element_id, $category_id){
            $this->custom_element_field_model->id = $element_id;
            $res = $this->custom_element_field_model->delete();
            if($res){
                setAlert('warning','Element został usunięty.');
                redirect('duocms/custom_elements/edit/'.$category_id);
            }
        }

	public function edit($id)
	{
		$element = new CustomElementModel($id);

		if (!$element->id) {
			show_404();
		}

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$res = $element->update();

			if ($res) {
                            setAlert('success','Element został zapisany.');
			} else {
                            setAlert('error','Wystąpił błąd.');
			}

			$fields = $this->input->post('fields');

			$this->load->model('CustomElementFieldModel');

			foreach ($fields as $fieldId => $fieldValue) {
				$field = new CustomElementFieldModel($fieldId);
				$field->value = $fieldValue;
				$field->update();
			}

			redirect('duocms/custom_elements/edit/'.$element->id);
		}

		$this->load->helper('form');

		$this->layout('duocms/Custom_elements/edit', [
			'element' => $element,
			'fields' => $element->getFields()
		]);
	}
}