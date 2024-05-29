<?php
class NewsModel extends MY_Model
{
	public $id;
	public $started_at;
	public $created_at;
	public $updated_at;
        public $facebook;
        public $category_id;

	private $_table = 'news';

	public function __construct($id = null)
	{
		parent::__construct();

		if (!is_null($id)) {
			$this->getById($id);
		}
	}

	public function getById($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->_table);

		foreach ($query->result() as $row) {
			$this->id = $row->id;
			$this->started_at = $row->started_at;
			$this->created_at = $row->created_at;
			$this->updated_at = $row->updated_at;
                        $this->category_id = $row->category_id;
                        $this->facebook = $row->facebook;
		}
	}

	public function findAll()
	{
		$this->db->order_by('started_at', 'desc');
		$query = $this->db->get($this->_table);
		return $query->result('NewsModel');
	}

	public function add()
	{
		$res = $this->db->insert($this->_table, [
			'started_at' => $this->started_at,
                        'category_id' => $this->category_id,
			'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
                        'facebook' => $this->facebook
		]);

		if ($res) {
			$this->id = $this->db->insert_id();
		}

		return $res;
	}

	public function edit()
	{
		$this->db->where('id', $this->id);
		$res = $this->db->update($this->_table, [
                    'facebook' => $this->facebook,
			'started_at' => $this->started_at,
                        'category_id' => $this->category_id,
			'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
		]);

		return $res;
	}

	public function delete()
	{
		$this->db->where('news_id', $this->id);
		$this->db->delete('duo_news_translations');
		
		$this->db->where('id', $this->id);
		$res = $this->db->delete($this->_table);

		return $res;
	}

	public function getTranslation($lang)
	{
		$this->load->model('NewsTranslationModel');

		return $this->NewsTranslationModel->findByNewsAndLang($this, $lang);
	}

	public function getFrontendList($lang, $limit = null, $offset = null, $category_id = null)
	{
		$this->db->select(implode(', ', [
			'news.id',
			'news.started_at',
                        'news.category_id',
			'news_translations.title',
			'news_translations.excerpt',
			'news_translations.image'
		]));
		$this->db->from('news');
		$this->db->join('news_translations', 'news.id = news_translations.news_id');
		$this->db->where('news_translations.lang', $lang);
		$this->db->where('news.started_at < NOW()');
//                $this->db->where('news.category_id', $category_id);
		$this->db->order_by('news.started_at', 'desc');
		$this->db->limit($limit, $offset);

		return $this->db->get()->result(get_called_class());
	}

	public function getFrontendTotalRows($lang, $category_id = 1)
	{
		$this->db->select(implode(', ', [
			'news.id',
			'news.started_at',
                        'news.category_id',
			'news_translations.title',
			'news_translations.excerpt'
		]));
		$this->db->from('news');
		$this->db->join('news_translations', 'news.id = news_translations.news_id');
		$this->db->where('news_translations.lang', $lang);
		$this->db->where('news.started_at < NOW()');
//                $this->db->where('news.category_id', $category_id);
		$this->db->order_by('news.started_at', 'desc');

		return $this->db->count_all_results();
	}

	/**
	 * Get news link.
	 *
	 * @return string
	 */
	public function getPermalink()
	{
            $tnews = $this->getTranslation(LANG);
            if(empty($tnews->custom_url)){
		return site_url('blog/' . getAlias($this->id, $tnews->title));
            } else {
                return site_url('blog/'.$tnews->custom_url);
            }
	}

	/**
	 * Get started at date by format.
	 * Default format d.m.Y (e.g. 20.02.2017).
	 *
	 * @param string $format Date format.
	 * @return string
	 */
	public function getStartedAt($format = 'd.m.Y')
	{
            setlocale(LC_ALL, 'pl_PL.UTF-8');
            
		//return date($format, strtotime($this->started_at));
            
            return strftime('%e %B %Y', strtotime($this->started_at));
	}
        
        public function checkFbId($id){
            $this->db->where('facebook', $id);
            $q = $this->db->get($this->_table);
            
            if($q->num_rows() > 0){
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        public function getMonthName($monthNumber, $lang="pl")
        {
			if ($lang==="pl"){
				$months = array( 'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca',
                  'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia'); 
            } else {
				$months = array( 'January', 'February', 'March', 'April', 'May', 'June',
                  'July', 'August', 'September', 'October', 'November', 'December'); 
			}
            return $months[$monthNumber-1];
        }
}
