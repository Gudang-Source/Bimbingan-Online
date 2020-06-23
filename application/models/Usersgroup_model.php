<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usersgroup_model extends MY_Model
{
    protected $column_order = array(null, 'id', 'user_id', 'group_id');
    protected $column_search = array('id','user_id' , 'group_id');
    protected $order = array('id' => 'asc');

    public function __construct()
    {
        $this->table       = 'users_groups';
        $this->primary_key = 'id';
        $this->fillable    = $this->column_order;
        $this->timestamps  = false;

        $this->has_one['groups'] = array('Groups_model', 'group_id', 'id');
        // $this->has_many['alternatif'] = array('Alternatif_model', 'CripsID', 'CripsID');
        // $this->has_one['kelas'] = array('Kelas_model', 'KriteriaID', 'KriteriaID');
        // $this->has_one['dosen'] = array('Dosen_model', 'npp', 'npp');

        parent::__construct();
    }

    private function _get_datatables_query()
    {

        $this->db->select($this->column_search);

        $this->db->from($this->table);
        // $this->db->join('kelas', 'kelas.KriteriaID=dosen_kelas.KriteriaID');
        // $this->db->join('dosen', 'dosen.npp=dosen_kelas.npp');
        // $this->db->join('kriteria', 'kriteria.KriteriaID=crips.KriteriaID');

        $i = 0;

        if (!empty($_POST['search']['value'])) {
            foreach ($this->column_search as $item) {
                if ($_POST['search']['value']) {

                    if ($i === 0) {
                        $this->db->group_start();
                        $this->db->like($item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like($item, $_POST['search']['value']);
                    }

                    if (count($this->column_search) - 1 == $i)
                        $this->db->group_end();
                }
                $i++;
            }
        }

        $where = array();
        if (isset($_POST['columns'])) {
            $i = 0;
            foreach ($this->column_search as $item) {
                if (isset($_POST['columns'][$i]) && $_POST['columns'][$i]['search']['value'] != '') {
                    $where[$item] = $_POST['columns'][$i]['search']['value'];
                }
                $i++;
            }

            if (count($where) > 0) {
                $this->db->group_start();
                $this->db->like($where);
                $this->db->group_end();
            }
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();

        $length = isset($_POST['length']) ? $_POST['length'] : 0;
        if ($length != -1) {
            $start  = isset($_POST['start']) ? $_POST['start'] : 0;
            $this->db->limit($length, $start);
        }

        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
/* End of file '/Products_model.php' */
/* Location: ./application/models/Products_model.php */
