<?php

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    public function __construct() {
        parent::__construct();
        $this->load->model('store_model');
    }
	public function index()
	{
        $from_date = '2017-10-16 00:00:00'; // thời gian bắt đầu
        $to_date = '2017-10-19 00:00:00'; // thời gian kết thúc
        $id_todoi = 149; // id của tổ đội
        $id_loai = 1; // id loại kính

        // hinh 1
        $result = $this->hinh1($from_date,$to_date);

        // hinh 2
        //$result = $this->hinh2($id_todoi,$from_date,$to_date);

        // hinh 3
        //$result = $this->hinh3($id_todoi,$id_loai,$from_date,$to_date);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
	}
	function hinh1($from_date,$to_date){

        $data = $this->store_model->call_list_data_to_doi($from_date,$to_date);
        $data = $this->array_group_by($data, function($i){  return $i->name; });
        $ts1 = strtotime($from_date);
        $ts2 = strtotime($to_date);

        $date_diff = $ts2 - $ts1;
        $num_date = floor($date_diff / (60 * 60 * 24));
        $i = 0;
        $result = array();
        do {
            $z = 0;
            foreach($data as $k => $d):
                $arr = array();
                $arr['name'] = $k;
                $date_check = date('Y-m-d', strtotime('+'.$i.' day', strtotime($from_date)));
                $key = array_search($date_check , array_column($d, 'fd'));
                if($key !== NULL):
                    echo "no";
                else:
                    echo "co";
                endif;
                echo $key;
                die;
                if($i == 0):
                    $fd = $d[$i]->fd;
                    if($fd == ""){
                        $fd = $from_date;
                    }
                    $arr['id'] = $d[$i]->id;
                    $arr['sotam'] = $d[$i]->sotam;
                    $arr['metvuong'] = $d[$i]->metvuong;
                    $arr['fd'] = date('Y-m-d',strtotime($fd));
                else:
                    if(isset($d[$i])):
                        $fd = $d[$i]->fd;
                        if($fd == ""){
                            $fd = date('Y-m-d', strtotime('+'.$i.' day', strtotime($from_date)));
                        }
                        $arr['id'] = $d[$i]->id;
                        $arr['sotam'] = $d[$i]->sotam;
                        $arr['metvuong'] = $d[$i]->metvuong;
                        $arr['fd'] = date('Y-m-d',strtotime($fd));
                    else:
                        $fd = $d[0]->fd;
                        if($fd == ""){
                            $fd = date('Y-m-d', strtotime('+'.$i.' day', strtotime($from_date)));
                        }
                        $arr['id'] = $d[0]->id;
                        $arr['sotam'] = 0;
                        $arr['metvuong'] = 0;
                        $arr['fd'] = date('Y-m-d',strtotime($fd));
                    endif;
                endif;
                $result[] = $arr;
                $z++;
            endforeach;
            $i++;
        } while ($i < $num_date);
        $result = $this->array_group_by($result, function($i){  return $i['fd']; });
        return $result;
    }
    public function hinh2($id_todoi,$from_date,$to_date){
        $data = $this->store_model->call_list_data_by_id_to_doi($id_todoi,$from_date,$to_date);
        return $data;
    }
    public function hinh3($id_todoi,$id_loai,$from_date,$to_date){
        $data = $this->store_model->call_list_data_by_id_to_doi_id_loai_kinh($id_todoi,$id_loai,$from_date,$to_date);
        return $data;
    }
    function array_group_by(array $arr, callable $key_selector) {
        $result = array();
        foreach ($arr as $i) {
            $key = call_user_func($key_selector, $i);
            $result[$key][] = $i;
        }
        return $result;
    }
}
