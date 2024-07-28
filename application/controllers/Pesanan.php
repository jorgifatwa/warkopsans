<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';
class Pesanan extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('pesanan_model');
		$this->load->model('produk_model');
		$this->load->model('pelanggan_model');
		$this->load->model('transaksi_model');
		$this->load->model('pesanan_model');
        $this->load->library('pagination');
	}

	public function index() 
	{
		$this->load->helper('url');
		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/pesanan/list_v';
		} else {
			$this->data['content'] = 'errors/html/restrict';
		}

		// Hitung total data
		$total_data = $this->produk_model->record_count();

		// Tentukan jumlah data per halaman
		$per_page = 9;

		// Hitung jumlah halaman jika total data tidak nol
		if ($total_data > 0) {
			$total_pages = ceil($total_data / $per_page);
		} else {
			$total_pages = 1; // Atur jumlah halaman menjadi 1 jika tidak ada data
		}

		// Ambil halaman saat ini
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		// Hitung offset (mulai) untuk data yang akan ditampilkan pada halaman ini
		$offset = ($page - 1) * $per_page;

		// Ambil data untuk halaman ini dari model
		$this->data['data_produks'] = $this->produk_model->fetch_produk($per_page, $offset);

		// Inisialisasi links
		$this->data['links'] = '<ul class="pagination">';

		// Tampilkan nomor halaman
		for ($i = 1; $i <= $total_pages; $i++) {
			// Tentukan kelas untuk tautan aktif atau non-aktif
			$class = ($i == $page) ? 'active' : '';

			// Tambahkan tautan ke links
			$this->data['links'] .= '<li class="page-item ' . $class . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
		}

		// Tambahkan penutup ul
		$this->data['links'] .= '</ul>';
		$this->load->view('admin/layouts/page', $this->data);

	}

	public function pagination(){
		// Hitung total data
		$total_data = $this->produk_model->record_count();

		// Tentukan jumlah data per halaman
		$per_page = 9;

		// Hitung jumlah halaman jika total data tidak nol
		if ($total_data > 0) {
			$total_pages = ceil($total_data / $per_page);
		} else {
			$total_pages = 1; // Atur jumlah halaman menjadi 1 jika tidak ada data
		}

		// Ambil halaman saat ini
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		// Hitung offset (mulai) untuk data yang akan ditampilkan pada halaman ini
		$offset = ($page - 1) * $per_page;

		// Ambil data untuk halaman ini dari model
		$this->data['data_produks'] = $this->produk_model->fetch_produk($per_page, $offset);

		// Inisialisasi links
		$this->data['links'] = '<ul class="pagination">';

		// Tampilkan nomor halaman
		for ($i = 1; $i <= $total_pages; $i++) {
			// Tentukan kelas untuk tautan aktif atau non-aktif
			$class = ($i == $page) ? 'active' : '';

			// Tambahkan tautan ke links
			$this->data['links'] .= '<li class="page-item ' . $class . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
		}

		// Tambahkan penutup ul
		$this->data['links'] .= '</ul>';

		echo json_encode($this->data);
	}
	

    public function search() {
        $keyword = $this->input->post('keyword');
        // Hitung total data
		$total_data = $this->produk_model->record_count_search($keyword);

		// Tentukan jumlah data per halaman
		$per_page = 9;

		// Hitung jumlah halaman jika total data tidak nol
		if ($total_data > 0) {
			$total_pages = ceil($total_data / $per_page);
		} else {
			$total_pages = 1; // Atur jumlah halaman menjadi 1 jika tidak ada data
		}

		// Ambil halaman saat ini
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		// Hitung offset (mulai) untuk data yang akan ditampilkan pada halaman ini
		$offset = ($page - 1) * $per_page;

		// Ambil data untuk halaman ini dari model
		$this->data['data_produks'] = $this->produk_model->fetch_produk_search($per_page, $offset, $keyword);

		// Inisialisasi links
		$this->data['links'] = '<ul class="pagination">';

		// Tampilkan nomor halaman
		for ($i = 1; $i <= $total_pages; $i++) {
			// Tentukan kelas untuk tautan aktif atau non-aktif
			$class = ($i == $page) ? 'active' : '';

			// Tambahkan tautan ke links
			$this->data['links'] .= '<li class="page-item ' . $class . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
		}

		// Tambahkan penutup ul
		$this->data['links'] .= '</ul>';

		echo json_encode($this->data);
    }

	public function checkout() 
	{
		$this->data['id_produk'] = $_POST['id_produk'];
		$this->data['quantity'] = $_POST['quantity'];
		$this->data['total'] = 0;
		for ($i = 0; $i < count($_POST['quantity']); $i++) {
			$produk[$i] = $this->produk_model->getAllById(array("produk.id" => $_POST['id_produk'][$i]));
			foreach ($produk[$i] as $key => $value) {
				$this->data['sub_total'][$i] = $value->harga_jual * intval($_POST['quantity'][$i]);
				$this->data['total'] += $this->data['sub_total'][$i];
			}
		}
		$this->data['nama'] = $_POST['nama'];
		$this->data['pelanggans'] = $this->pelanggan_model->getAllById();
		$this->data['content'] = 'admin/pesanan/checkout_v';
		$this->load->view('admin/layouts/page', $this->data);
	}

	public function create_pesanan() 
	{
		$nama_pelanggan = $this->input->post('nama_pelanggan');
		if (!is_numeric($nama_pelanggan)) {
			$data = array(
				'nama' => $this->input->post('nama_pelanggan'),
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->data['users']->id
			);

			$insert = $this->pelanggan_model->insert($data);
		}

		if(isset($insert)){
			$data_transaksi = array(
				'id_pelanggan' => $insert,
				'status' => 0,
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->data['users']->id
			);
		}else{
			$data_transaksi = array(
				'id_pelanggan' => $this->input->post('nama_pelanggan'),
				'status' => 0,
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->data['users']->id
			);
		}

		$insert_transaksi = $this->transaksi_model->insert($data_transaksi);

		$id_produk = $this->input->post('id_produk');
		$quantity = $this->input->post('quantity');
		for ($i=0; $i < count($id_produk); $i++) { 
			$data_pesanan = array(
				'tanggal_pesanan' => date('Y-m-d H:i:s'),
				'id_transaksi' => $insert_transaksi,
				'id_produk' => $id_produk[$i],
				'jumlah' => $quantity[$i],
				'keterangan' => '',
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->data['users']->id
			);
			$insert_pesanan = $this->pesanan_model->insert($data_pesanan);
		}

		redirect('Pesanan/');
	}

	public function dataList() 
	{
		$columns = array(
			0 => 'nama',
			1 => 'description',
			2 => '',
		);

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$search = array();
		$limit = 0;
		$start = 0;
		$totalData = $this->pesanan_model->getCountAllBy($limit, $start, $search, $order, $dir);

		if (!empty($this->input->post('search')['value'])) {
			$search_value = $this->input->post('search')['value'];
			$search = array(
				"pesanan.nama" => $search_value,
				"pesanan.description" => $search_value,
			);
			$totalFiltered = $this->pesanan_model->getCountAllBy($limit, $start, $search, $order, $dir);
		} else {
			$totalFiltered = $totalData;
		}

		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$datas = $this->pesanan_model->getAllBy($limit, $start, $search, $order, $dir);

		$new_data = array();
		if (!empty($datas)) {

			foreach ($datas as $key => $data) {

				$edit_url = "";
				$delete_url = "";

				if ($this->data['is_can_edit'] && $data->is_deleted == 0) {
					$edit_url = "<a href='" . base_url() . "pesanan/edit/" . $data->id . "' class='btn btn-sm btn-info white'> Ubah</a>";
				}
				if ($this->data['is_can_delete']) {
					$delete_url = "<a href='#'
						url='" . base_url() . "pesanan/destroy/" . $data->id . "/" . $data->is_deleted . "'
						class='btn btn-sm btn-danger white delete'>Hapus
						</a>";
				}

				$nestedData['id'] = $start + $key + 1;
				$nestedData['nama'] = $data->nama;
				$nestedData['description'] = substr(strip_tags($data->description), 0, 50);
				$nestedData['action'] = $edit_url . " " . $delete_url;
				$new_data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $new_data,
		);

		echo json_encode($json_data);
	}

	public function destroy() 
	{
		$response_data = array();
		$response_data['status'] = false;
		$response_data['msg'] = "";
		$response_data['data'] = array();

		$id = $this->uri->segment(3);
		$is_deleted = $this->uri->segment(4);
		if (!empty($id)) {
			$this->load->model("pesanan_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1,
			);
			$update = $this->pesanan_model->update($data, array("id" => $id));

			$response_data['data'] = $data;
			$response_data['msg'] = "Kategori Produk Berhasil di Hapus";
			$response_data['status'] = true;
		} else {
			$response_data['msg'] = "ID Harus Diisi";
		}

		echo json_encode($response_data);
	}

	
}
