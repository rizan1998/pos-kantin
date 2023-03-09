<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 */

class Sistem_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}


	function _validate($username, $password)
	{
		$this->db->select('users.*');
		$this->db->from('users');
		$this->db->where('users.username', $username);
		$this->db->where('users.password', $password);
		$this->db->where('users.status', 1);
		// $this->db->where('users.status', 1);

		$query = $this->db->get();

		if ($query->num_rows() > 0) return $query->row_array();
		else return array();
	}

	function _input($table, $data)
	{
		return $this->db->insert($table, $data);
	}

	function _get($table, $order_by = "")
	{

		if (!empty($order_by)) {
			$order = explode("-", $order_by);
			$this->db->order_by($order['0'], $order[1]);
		}

		$query = $this->db->get($table);

		if ($query->num_rows() > 0) return $query->result_array();
		else return array();
	}

	function _get_spesipic($table, $spesipic = "", $order_by = "")
	{

		if (!empty($order_by)) {
			$order = explode("-", $order_by);
			$this->db->order_by($order['0'], $order[1]);
		}

		$this->db->select($spesipic);
		$query = $this->db->get($table);

		if ($query->num_rows() > 0) return $query->result_array();
		else return array();
	}

	function _get_spesipic_where($table, $spesipic = "", $where = "", $order_by = "")
	{

		if (!empty($order_by)) {
			$order = explode("-", $order_by);
			$this->db->order_by($order['0'], $order[1]);
		}

		$this->db->select($spesipic);
		$this->db->where($where);
		$query = $this->db->get($table);

		if ($query->num_rows() > 0) return $query->result_array();
		else return array();
	}

	function _get_where_id($table, $where)
	{
		$this->db->where($where);
		$query = $this->db->get($table);

		$row = $query->row_array();

		if ($query->num_rows() > 0) return $row;
		else return array();
	}

	function _get_wheres($table, $where, $order_by = "")
	{

		$this->db->where($where);

		if (!empty($order_by)) {
			$order = explode("-", $order_by);
			$this->db->order_by($order['0'], $order[1]);
		}

		$query = $this->db->get($table);

		if ($query->num_rows() > 0) return $query->result_array();
		else return array();
	}

	function _get_wheres_like($table, $where, $like, $order_by = "")
	{

		$this->db->where($where);
		$this->db->like($like);
		if (!empty($order_by)) {
			$order = explode("-", $order_by);
			$this->db->order_by($order['0'], $order[1]);
		}

		$query = $this->db->get($table);

		if ($query->num_rows() > 0) return $query->result_array();
		else return array();
	}


	function _get_wheres_limit($table, $where, $order_by = "", $limit, $offset)
	{

		$this->db->where($where);

		if (!empty($order_by)) {
			$order = explode("-", $order_by);
			$this->db->order_by($order['0'], $order[1]);
		}

		$this->db->limit($offset, $limit);
		$query = $this->db->get($table);

		if ($query->num_rows() > 0) return $query->result_array();
		else return array();
	}

	function _get_limits($table, $limit, $offset)
	{

		$this->db->limit($offset, $limit);
		$query = $this->db->get($table);

		if ($query->num_rows() > 0) return $query->result_array();
		else return array();
	}


	function _get_wheres_limit_id($table, $where, $order_by = "", $limit, $offset)
	{

		$this->db->where($where);

		if (!empty($order_by)) {
			$order = explode("-", $order_by);
			$this->db->order_by($order['0'], $order[1]);
		}

		$this->db->limit($offset, $limit);
		$query = $this->db->get($table);

		$row = $query->row_array();

		if ($query->num_rows() > 0) return $row;
		else return array();
	}


	function _get_limit($table, $order_by = "", $limit, $offset)
	{

		if (!empty($order_by)) {
			$order = explode("-", $order_by);
			$this->db->order_by($order['0'], $order[1]);
		}

		$this->db->limit($offset, $limit);
		$query = $this->db->get($table);

		if ($query->num_rows() > 0) return $query->result_array();
		else return array();
	}

	function _get_wheres_count($table, $where)
	{

		$this->db->where($where);

		if (!empty($order_by)) {
			$order = explode("-", $order_by);
			$this->db->order_by($order['0'], $order[1]);
		}

		$this->db->get($table);
		$query = $this->db->count_all_results();
		return $query;
	}

	function _delete($table, $where)
	{
		$this->db->delete($table, $where);
	}

	function _update($table, $data, $where)
	{
		return $this->db->update($table, $data, $where);
	}


	function _get_provinsi()
	{
		$this->db->select('provinsi');
		$this->db->group_by('provinsi');
		$this->db->order_by('provinsi', 'ASC');
		$query = $this->db->get('rs_kota');

		return $query->result_array();
	}

	function _get_like($table, $where, $like)
	{
		$this->db->where($where);
		$this->db->like($like);
		$query = $this->db->get($table);

		$row = $query->row_array();

		if ($query->num_rows() > 0) return $row;
		else return array();
	}

	public function _automatic_code($code = "", $table = "")
	{
		$this->db->select('Right(code,3) as kode', false);
		$this->db->order_by('code', 'DESC');
		// $this->db->limit(1);
		$query = $this->db->get($table);
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$kode = $query->num_rows() + 1;
		} else {
			$kode = 1;
		}

		$kodeMax = str_pad($kode, 3, "0", STR_PAD_LEFT);
		$newCode = $code . $kodeMax;
		return $newCode;
	}

	public function _automatic_nota($nota = "", $table = "")
	{
		$this->db->select('Right(nota,3) as nota', false);
		$this->db->where('date(created)', date('Y-m-d'));

		$this->db->order_by('nota', 'DESC');
		// $this->db->limit(1);
		$query = $this->db->get($table);
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$kode = $query->num_rows() + 1;
		} else {
			$kode = 1;
		}

		$kodeMax = str_pad($kode, 3, "0", STR_PAD_LEFT);
		$newCode = $nota . $kodeMax;
		return $newCode;
	}

	public function _automatic_code_trans($format = "", $table = "", $field = "")
	{
		$this->db->select("Right($field,3) as code", false);
		$this->db->where('date(created)', date('Y-m-d'));

		$this->db->order_by("$field", 'DESC');
		// $this->db->limit(1);
		$query = $this->db->get($table);
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$kode = $query->num_rows() + 1;
		} else {
			$kode = 1;
		}

		$kodeMax = str_pad($kode, 3, "0", STR_PAD_LEFT);
		$newCode = $format . $kodeMax;
		return $newCode;
	}
}
