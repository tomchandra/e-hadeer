<?php

/**
 * Controller untuk auth
 * @param  mixed
 * @return array
 */

namespace App\Controllers;

use App\Models\M_Employee;
use App\Models\M_User;

class Auth extends BaseController
{

	protected $session;
	protected $request;
	protected $M_User;
	protected $M_Employee;

	public function __construct()
	{
		$this->session  = \Config\Services::session();
		$this->request  = \Config\Services::request();
		$this->M_User   = new M_User();
		$this->M_Employee = new M_Employee();
		$this->session->start();
	}

	public function index()
	{
		return view('app/login');
	}


	/**
	 * Method untuk auth login
	 * @param string username
	 * @param string password
	 * @return session
	 */
	public function auth()
	{
		if ($this->request->getPost('input_username') && $this->request->getPost('input_password')) {
			$username = $this->request->getPost('input_username');
			$password = md5($this->request->getPost('input_password'));
			$data     = $this->M_User->where('user_name', $username)->first();
			if ($data) {
				if ($data['user_status'] == "active") {
					$verify_pass = password_verify($password, password_hash($data['password'], PASSWORD_DEFAULT));
					if ($verify_pass) {
						$employee  = $this->M_Employee->app_getEmployee($data['person_id']);
						$user_name = $employee ? $employee["person_name"] : "";
						$session_data = [
							'user_id'  		 => $data['user_id'],
							'user_name'      => $user_name,
							'person_id'		 => $data['person_id'],
							'user_access_id' => $data['user_id'],
							'is_auth'  		 => TRUE
						];

						$this->M_User->set("last_login_dttm", date("Y-m-d H:i:s"));
						$this->M_User->where("user_id", $data['user_id']);
						$this->M_User->update();

						$this->session->set($session_data);
						return redirect()->to('/app/presence');
					} else {
						$this->session->setFlashdata('msg', 'Wrong Password');
						return redirect()->to('/');
					}
				} else {
					$this->session->setFlashdata('msg', 'User ' . $data['user_status']);
					return redirect()->to('/');
				}
			} else {
				$this->session->setFlashdata('msg', 'Data Not Found');
				return redirect()->to('/');
			}
		} else {
			$this->session->setFlashdata('msg', 'Login Required');
			return redirect()->to('/');
		}
	}

	/**
	 * Method untuk logout
	 * @return page
	 */
	public function logout()
	{
		$this->session->destroy();
		return redirect()->to('/');
	}
}
