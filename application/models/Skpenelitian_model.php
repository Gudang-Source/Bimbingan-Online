<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Skpenelitian_model extends MY_Model
{
    protected $column_order = array(null, 'SKID', 'NomerSK', 'PenelitianID', 'Prodi', 'Jenis', 'TahunAkademikID', 'created_at', 'updated_at');
    protected $column_search = array('SKID', 'NomerSK', 'penelitian.NIM', 'penelitian.NPP', 'Prodi', 'sk_penelitian.Jenis', 'tahun_akademik.TahunAkademik');
    protected $order = array('SKID' => 'asc');

    public function __construct()
    {
        $this->table       = 'sk_penelitian';
        $this->primary_key = 'SKID';
        $this->fillable    = $this->column_order;
        $this->timestamps  = TRUE;

        $this->has_one['penelitian'] = array('Penelitian_model', 'PenelitianID', 'PenelitianID');
        $this->has_one['tahun_akademik'] = array('Tahunakademik_model', 'TahunAkademikID', 'TahunAkademikID');

        $this->load->model(array('Mahasiswa_model' => 'mahasiswa', 'Dosen_model' => 'dosen', 'Bimbingan_model' => 'bimbingan', 'Penelitian_model' => 'penelitian', 'Tahunakademik_model'=>'tahun'));

        parent::__construct();
    }

    private function _get_datatables_query()
    {

        $this->db->select($this->column_search);

        $this->db->from($this->table);
        $this->db->join('penelitian', 'penelitian.PenelitianID=sk_penelitian.PenelitianID');
        $this->db->join('tahun_akademik', 'tahun_akademik.TahunAkademikID=sk_penelitian.TahunAkademikID');
        if ($this->ion_auth->in_group('kaprodi_SI')) {
            $this->db->where('Prodi', 'Sistem Informasi');
        }
        if ($this->ion_auth->in_group('kaprodi_TI')) {
            $this->db->where('Prodi', 'Teknik Informatika');
        }
        // if ($this->ion_auth->in_group('mahasiswa')) {
        //     $mahasiswa = $this->mahasiswa->where('NIM', $this->session->userdata('username'))->get();
        //     $this->db->where('Prodi', $mahasiswa->Prodi);
        // }
        if ($this->input->post('Jenis')) {
            $this->db->where('sk_penelitian.Jenis', $this->input->post('Jenis'));
        }
        if ($this->input->post('tahun')) {
            $this->db->where('tahun_akademik.TahunAkademikID', $this->input->post('tahun'));
        }
        
        // $this->db->join('kelas', 'kelas.KriteriaID=dosen_kelas.KriteriaID');
        // $this->db->join('dosen', 'dosen.npp=dosen_kelas.npp');

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
