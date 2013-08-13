<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Show extends CI_Controller {

    protected $_activity = 1;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        $data = array(
            'home_active'=>'',
            'join_active'=>'',
            'show_active'=>'active'
        );
        $this->load->view('head',$data);
        $this->load->view('banner');
        $this->load->view('pic_container');
        $this->load->view('foot');
    }

    function more(){
        $per = 10;
        $page = intval($this->input->get('page'))?intval($this->input->get('page')):1;
        $last_id = intval($this->input->get('last_id'))?intval($this->input->get('last_id')):0;
        $limit = $per * ($page-1) ;
        $this->db->select("*");
        $this->db->from("images");
        $this->db->where(array(
                'state >'=>'0',
                'activity'=>$this->_activity,
                // 'id >'=>$last_id
            ));
        //$this->db->order_by('id','desc');
        $this->db->limit($per,$limit);
        $query = $this->db->get();
        $data = array();
        if($query->num_rows() > 0){
            foreach($query->result_array() as $row){
                if(empty($row['weibo_nickname']) && empty($row['weixin_nickname'])){
                    $nickname = empty($row['bbs_nickname'])?$row['bbs_nickname']:'匿名';
                }else{
                    $nickname = empty($row['weibo_nickname'])?$row['weixin_nickname']:$row['weibo_nickname'];
                }
                $images[] = array(
                        'thumb'=>base_url('uploads/thumb/'.$row['image']),
                        'image'=>base_url('uploads/'.$row['image']),
                        'id'=>$row['id'],
                        'weibo'=>!empty($row['weibo_url'])?$row['weibo_url']:'javascript:void(0)',
                        'avatar'=>$row['avatar'],
                        'time'=>$row['create_time'],
                        'nickname'=>$nickname,
                        'width'=>$row['width'],
                        'height'=>$row['height'],
                        'title'=>$row['title'],
                        'desc'=>!empty($row['description'])?$row['description']:'他(她)没有说什么哦,一切尽在不言中!'
                    );
            }
            $data['images'] = $images;
            $data['end'] = 0;
            $data['num'] = $query->num_rows();
            $data['query'] = $this->db->last_query();
            echo json_encode($data);
            exit;
        }else{
            $data = array(
                    'end'=>1
                );
            echo json_encode($data);
            exit;
        }
    }

    function demo(){
        //$this->load->helper('url');
        //$this->load->view('image_items');
        $this->load->helper('file');
        $x = read_file('D:\www\test\zhuanti\uploads\1376291523.txt');
        preg_match("/CONFIG\[\'oid\'\]\=\'(.*?)\'/i",$x,$match);
        var_dump($match);
    }

    function joinin(){
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $config = array(
               array(
                     'field'   => 'bbs_nickname', 
                     'label'   => '称呼', 
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'description', 
                     'label'   => '甜言蜜语', 
                     'rules'   => 'required'
                ),
               array(
                    'filed' => 'phone',
                    'label' => '手机号码',
                    'rules' => 'numeric|exact_length[11]'
                ),
            );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_message('required', '%s 空缺,你没有填写');
        $config['upload_path'] ='./uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '900';
        $config['max_width'] = '3600';
        $config['max_height'] = '1800';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        
        $data = array(
            'home_active'=>'',
            'join_active'=>'active',
            'show_active'=>''
        );
        if ($this->form_validation->run() == FALSE){
            $this->load->view('head',$data);
            $this->load->view('joinin');
            $this->load->view('foot');
        }else{
            if($this->input->post('weibo_url')){
                $weibo = $this->_get_weibo($this->input->post('weibo_url'));
                //var_dump($weibo);exit;

            }
            if(!$this->upload->do_upload('image')){
                $data['error'] = TRUE;
                $this->load->view('head',$data);
                $this->load->view('joinin');
                $this->load->view('foot');
            }else{
                $image = $this->upload->data();                
                $config_img['source_image'] = $image['full_path'];
                $config_img['new_image'] = $image['file_path'].'/thumb/'.$image['file_name'];
                $config_img['width'] = 210;
                $config_img['height'] = 1200;
                $config_img['maintain_ratio'] = TRUE;
                //$config_img['create_thumb'] = TRUE;
                $this->load->library('image_lib',$config_img);
                if(!$this->image_lib->resize()){
                    var_dump($this->image_lib->display_errors());
                }else{
                    $data = array(
                            'bbs_nickname' => $this->input->post('bbs_nickname',TRUE),
                            //'title' => $this->input->post('title',TRUE),
                            'description' => $this->input->post('description',TRUE),
                            'image' => $image['file_name'],
                            'width' => $image['image_width'],
                            'height' => $image['image_height'],
                            'weibo_url' => $this->input->post('weibo_url',TRUE),
                            'weixin_nickname' => $this->input->post('weixin_nickname',TRUE),
                            'create_time' => date('Y-m-d H:i:s'),
                            'avatar' => isset($weibo['avatar'])?prep_url($weibo['avatar']):base_url('uploads/avatar/shy.png'),
                            'weibo_nickname' =>isset($weibo['weibo_nickname'])?$weibo['weibo_nickname']:'',
                            'phone' => $this->input->post('phone',TRUE),
                        );
                    if(!$this->db->insert('images',$data)){
                        $this->load->view('head',$data);
                        $this->load->view('joinin',array('database'=>'error'));
                        $this->load->view('foot');
                    }else{
                        redirect(site_url('show'),'refresh');
                    }
                }
            }
        }
    }

    function _get_weibo($weibo_url){
        //require_once (APPPATH.'third_party/phpQuery/phpQuery.php');
        require_once (APPPATH.'libraries/signin.php');
        $mycurl=new SignIn();
        $htmlcontent=$mycurl->gethtml($weibo_url);
        //$filename = './uploads/'.time().'.txt';
        //$this->load->helper('file');
        //write_file($filename, $htmlcontent);
        // phpQuery::$defaultCharset='utf8';
        // phpquery::newDocumentFileHTML($filename);
        // $avatar = pq('#pf_head_pic img')->attr('src');
        // $nickname = pq('#pf_head_pic img')->attr('alt');
        // phpQuery::$documents = array();
        if(preg_match("/CONFIG\[\'oid\'\]\=\'(.*?)\'/i", $htmlcontent, $oid)){
            preg_match("/CONFIG\[\'onick\'\]\=\'(.*?)\'/i", $htmlcontent, $onick);
            $avatar = 'tp'.($oid[1] % 4).'.sinaimg.cn/'.$oid[1].'/50/'.time().'/0';
            $nickname = $onick[1];
        }else{
            require_once (APPPATH.'third_party/phpQuery/phpQuery.php');
            phpQuery::$defaultCharset='utf8';
            phpquery::newDocumentFileHTML($weibo_url);
            $avatar = pq('.avatar img')->attr('src');
            $nickname = pq('.avatar img')->attr('alt');
            phpQuery::$documents = array();
        }
        
        return array('avatar'=>$avatar,'weibo_nickname'=>$nickname);
    }

}

/* End of file  */
/* Location: ./application/controllers/ */