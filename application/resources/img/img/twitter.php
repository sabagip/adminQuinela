<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Connect extends CI_Controller {
	public function __construct(){
		parent::__construct();
	
	    // To use site_url and redirect on this controller.
        $this->load->helper('url');
        $this->_ci =& get_instance();
        $this->load->library('twitteroauth');
		// Loading twitter configuration.
		$this->config->load('twitter');
		
		ini_set('display_errors', TRUE);
		
		
		if($this->session->userdata('access_token') && $this->session->userdata('access_token_secret'))
		{
			
			// If user already logged in
			$this->connection = $this->twitteroauth->create($this->config->item('twitter_consumer_token'), $this->config->item('twitter_consumer_secret'), $this->session->userdata('access_token'),  $this->session->userdata('access_token_secret'));
		}
		elseif($this->session->userdata('request_token') && $this->session->userdata('request_token_secret'))
		{
			// If user in process of authentication
			$this->connection = $this->twitteroauth->create($this->config->item('twitter_consumer_token'), $this->config->item('twitter_consumer_secret'), $this->session->userdata('request_token'), $this->session->userdata('request_token_secret'));
		}
		else
		{
			// Unknown user
			
			$this->connection = $this->twitteroauth->create($this->config->item('twitter_consumer_token'), $this->config->item('twitter_consumer_secret'));
		}
	}

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
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
	}
	    
    public function loginTwitter(){
	    
		if($this->input->get('oauth_token') && $this->session->userdata('request_token') !== $this->input->get('oauth_token'))
		{
			$this->reset_session();
			
			redirect(base_url('/connect/loginTW'));
		}
		else
		{
			$access_token = $this->connection->getAccessToken($this->input->get('oauth_verifier'));
			
			if ($this->connection->http_code == 200)
			{
				$avatar_user = $this->connection->get('account/verify_credentials'); 
				echo "<pre>";print_r($avatar_user);echo "</pre>";die;
				$this->session->set_userdata('access_token', $access_token['oauth_token']);
				$this->session->set_userdata('access_token_secret', $access_token['oauth_token_secret']);
				$this->session->set_userdata('twitter_user_id', $access_token['user_id']);
				$this->session->set_userdata('twitter_screen_name', $access_token['screen_name']);
				$this->session->set_userdata('twitter_avatar', $avatar_user->profile_image_url);
				$this->session->unset_userdata('request_token');
				$this->session->unset_userdata('request_token_secret');
				
				redirect(base_url('/connect/endTwitter'));
			}
			else
			{
				
				// An error occured. Add your notification code here.
				redirect(base_url('/connect/error'));
			}
		}
    } 
    public function endTwitter(){
    	$array_TW = array(
    		"user_id" => $this->session->userdata("twitter_user_id"),
    		"screen_name" => $this->session->userdata("twitter_screen_name"),
    		"avatar" => $this->session->userdata("twitter_avatar"),
    		"type" => "Twitter",
    		"from_date" => time()
     	); 
     	echo "<pre>"; print_r($array_TW); echo "</pre>";
    	//redirect(base_url('/connect/perfil'));
		

    }
    public function loginTW()
	{

		if($this->session->userdata('access_token') && $this->session->userdata('access_token_secret'))
		{
			
			// User is already authenticated. Add your user notification code here.
			redirect(base_url('/connect/endTwitter'));
		}
		else
		{
			
			// Making a request for request_token
			$request_token = $this->connection->getRequestToken(base_url('/connect/loginTwitter'));
			
			if($this->connection->http_code == 200)
			{
				
				$this->session->set_userdata('request_token', $request_token['oauth_token']);
				$this->session->set_userdata('request_token_secret', $request_token['oauth_token_secret']);
				$url = $this->connection->getAuthorizeURL($request_token);
				redirect($url);
			}
			else
			{
				$this->reset_session();
				// An error occured. Make sure to put your error notification code here.
				redirect(base_url('/connect/loginTW'));
			}
		}
	}
	
	public function logoutTwitter()
	{
		$this->session->unset_userdata('access_token');
		$this->session->unset_userdata('access_token_secret');
		$this->session->unset_userdata('request_token');
		$this->session->unset_userdata('request_token_secret');
		$this->session->unset_userdata('twitter_user_id');
		$this->session->unset_userdata('twitter_screen_name');
		$this->session->unset_userdata('objectFB');
	    $this->session->unset_userdata('objectTW');
	    $this->session->unset_userdata('objectGL');
		redirect('');
	}  

/**
	 * Reset session data
	 * @access	private
	 * @return	void
	 */
	private function reset_session()
	{
		$this->session->unset_userdata('access_token');
		$this->session->unset_userdata('access_token_secret');
		$this->session->unset_userdata('request_token');
		$this->session->unset_userdata('request_token_secret');
		$this->session->unset_userdata('twitter_user_id');
		$this->session->unset_userdata('twitter_screen_name');
	}
	
	public function error(){
		redirect(base_url( '' ));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
