<?php declare(strict_types = 1);

namespace App\Models\Backend;

use App\Libraries\Token;
use App\Libraries\Email;

class AuthModel extends BackendModel 
{
	protected $table = 'sellers';
	protected $primaryKey = 'id';

	protected $allowedLoginFields = ['email', 'password', 'remember_me'];
	protected $allowedRecoveryFields = ['email'];
	protected $allowedSetPasswordFields = ['password', 'confirmation_password', 'code'];

	protected $controller = 'auth';
	protected $entity = 'sellers';

	private $response;

	public $loginRules = [
		'email' => [
			'label'  => 'Email',
			'rules'  => 'required|valid_email|is_not_unique[sellers.email.{email}] ',
			'errors' => [
				'is_not_unique' => 'Email inesistente!',
			]
		], 
		'password' => [
			'label'  => 'Password',
			'rules'  => 'required|alpha_numeric'
		]
	];

	public $recoveryRules = [
		'email' => [
			'label'  => 'Email',
			'rules'  => 'required|valid_email|is_not_unique[sellers.email.{email}] ',
			'errors' => [
				'is_not_unique' => 'Email inesistente!',
			]
		]
	];

	public $setPasswordRules = [
		'code' => [
			'label'  => 'Activation Code',
			'rules'  => 'required|alpha_numeric|checkActivationCode'
		], 
		'password' => [
			'label'  => 'New Password',
			'rules'  => 'required|alpha_numeric'
		],
		'confirm_password' => [
			'label'  => 'Confirmation Password',
			'rules'  => 'required|matches[password]'
		]
	];

	public function __construct()
	{
		parent::__construct();
		$this->response = service('response');
	}

	/**
	 * The login method
	 */
	public function loginAction(Array $posts): Array
	{
		$posts = $this->allowedFields($posts, 'allowedLoginFields');

		/* Checking if the seller has selected the remember me option... */
		$remember_me = (isset($posts['remember_me']) ? (bool)$posts['remember_me'] : false);

		/* Setting the where clause, checking the passed email, checking the seller has to be active and checking there is no value on the suspended_at field... */
		$where = ['email' => $posts['email'], 'status' => '1', 'suspended_at' => null];

		/* Collecting the attempts time... */
		$attempts_time = time() - config('Shield')->attempts_time;

		/* The main query collecting the password to check, the seller id to write on the seller's tokens table or the sellers attempts table if needed, and we count the attempts if there are some... */
		$query = $this->builder->select('sellers.id, sellers.password_hash, (count(sellers_attempts.id)) as times')
							   ->limit(1)
							   ->join('sellers_attempts', 'sellers_attempts.seller_id = sellers.id and timestamp > "' . $attempts_time . '"', 'left')
							   ->groupBy('sellers.id')
							   ->getWhere($where);

		/* If we get the password... */
		if($query->getRow('password_hash')):

			/* If we didn't overcome the attempts limit... */
			if($query->getRow('times') < config('Shield')->attempts_limit):

				/* Creating the sellers_tokens table connection... */
				$builder = $this->db->table('sellers_attempts');

				/* If the given password matches... */
				if(password_verify($posts['password'], $query->getRow('password_hash'))):

					/* We remove every attempt previously written in the table because we are within the limit and the password matches... */
					$builder->delete(['seller_id' => $query->getRow('id')]);

					/* We prepare all things... */
					$this->setToken($query->getRow('id'), $remember_me);

					/* The password matched, the attempts limit was not overcome, so... what are we waiting for? Let's go work!!! */
					return ['result' => true];

				/* The password didn't match so we write an attempt in the sellers_attempts table... */
				else:

					$data = ['seller_id' => $query->getRow('id'), 'ip' => $_SERVER['REMOTE_ADDR'], 'timestamp' => time()];
					$builder->insert($data);

				endif;

			else:

				/* We were attempting too much times... */
				return ['result' => false, 'message' => 'Troppi tentativi'];

			endif;

		endif;

		/* We didn't get the password or the password didn't match... */
		return ['result' => false, 'message' => 'Login Failed!'];
	}

	/**
	 * The recovery method
	 */
	public function recoveryAction(Array $posts): Array
	{
		$posts = $this->allowedFields($posts, 'allowedRecoveryFields');

		/* If we found the seller by the passed email... */
		if($seller = $this->builder->select('id, firstname, lastname')->limit(1)->getWhere(['email' => $posts['email']])):

			$token = new Token();
			$token_hash = $token->getHash();

			$builder = $this->db->table('sellers_tokens');

			/* If there are some old activation code referring to this seller, we delete them... */
			$builder->delete(['seller_id' => $seller->getRow('id'), 'token_type' => 'activation']);

			/* Writing of the activation code in the sellers_tokens table... */
			$builder->insert(['seller_id' => $seller->getRow('id'), 'token_hash' => $token_hash, 'token_create' => date('Y-m-d H:i:s'), 'token_expire' => date('Y-m-d H:i:s', time() + 43200), 'token_type' => 'activation', 'seller_agent' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $_SERVER['REMOTE_ADDR']]); // 12 ore

			/* Setting of email params to send the activation code email... */
		    $params = [
		    	'to' => esc($posts['email']), 
		    	'subject' => 'Recovery password ' . esc($seller->getRow('firstname')) . ' ' . esc($seller->getRow('lastname')), 
		    	'firstname' => esc($seller->getRow('firstname')), 
		    	'lastname' => esc($seller->getRow('lastname')), 
		    	'email' => esc($posts['email']), 
		    	'token' => $token->getValue(), 
		    	'action' => 'recovery', 
		    	'view' => 'setPasswordEmailView'
			];

			/* Sending of the email... */
			if( ! Email::sendEmail($params)):
				$message = sprintf('<b>%s %s</b>, il processo di recovery password ha avuto inizio, ma ci sono stati problemi con l\'invio della email, contatta l\'amministratore!', esc($seller->getRow('firstname')), esc($seller->getRow('lastname')));
			else:
				$message = sprintf('<b>%s %s</b>, il processo di recovery password ha avuto inizio! Controlla la tua email!', esc($seller->getRow('firstname')), esc($seller->getRow('lastname')));
			endif;

			/* We are all set! We return true and a proper message depending on the email sending result... */
			return ['result' => true, 'message' => $message];

		endif;

		/* We didn't found any seller by the passed email... */
		return ['result' => false, 'message' => 'Recupero password failed!'];
	}

	/**
	 * The set password method...
	 */
	public function setPasswordAction(Array $posts): Array
	{
		$this->db->transBegin();

			$posts = $this->allowedFields($posts, 'allowedSetPasswordFields');

			$token = new Token($posts['code']);
			$token_hash = $token->getHash();

			/* Updating the seller table with the new password and the resetted_at field on null */
			$sql = "update sellers 
					join sellers_tokens 
					on sellers.id = sellers_tokens.seller_id 
					set sellers.password_hash = '" . $this->db->escapeString(password_hash($posts['password'], PASSWORD_DEFAULT)) . "', sellers.resetted_at = null 
					where sellers_tokens.token_hash = '" . $this->db->escapeString($token_hash) . "' 
					and sellers_tokens.token_type = 'activation'";

			$query = $this->db->query($sql);

			/* Retrieving of the firstname and lastname in order to set the message... */
			$identity = $this->builder->select('firstname, lastname')->join('sellers_tokens', 'sellers_tokens.seller_id = sellers.id')->limit(1)->getWhere(['token_hash' => $token_hash, 'token_type' => 'activation']);

			/* Creating of the connection with the sellers_tokens table... */
			$builder = $this->db->table('sellers_tokens');

			/* Deleting of the activation code used for this action from the sellers_tokens table... */
			$builder->delete(['token_hash' => $token_hash, 'token_type' => 'activation']);

		if ($this->db->transStatus() === false):
			$this->db->transRollback();
			return ['result' => false, 'message' => sprintf('There was an error in creating the new password!', esc($identity->getRow('firstname')), esc($identity->getRow('lastname')))];
		else:
			$this->db->transCommit();
			return ['result' => true, 'message' => sprintf('<b>%s %s</b>, your password was created successfully!', esc($identity->getRow('firstname')), esc($identity->getRow('lastname')))];
		endif;
	}

	/**
	 * This method is responsible of the checking the token code passed by url...
	 */
	public function checkAuthCode(String $token): Bool
	{
		$token = new Token($token);
		$token_hash = $token->getHash();

		/* Selecting of the expire date, the seller id and the seller password... */
		$query = $this->builder->select('sellers_tokens.token_expire, sellers_tokens.seller_id, sellers.password_hash')
						 	   ->join('sellers_tokens', 'sellers_tokens.seller_id = sellers.id')
						       ->limit(1)
						 	   ->getWhere(['token_hash' => $token_hash, 'token_type' => 'activation']);

		/* If we found the data and the expire date is within the limit... */
		if(($query->getNumRows() > 0) && (date('Y-m-d H:i:s') < $query->getRow('token_expire'))):

			/* If password_hash is not null, put it null... */
			if( ! is_null($query->getRow('password_hash'))):
				$this->builder->update(['password_hash' => null], ['id' => $query->getRow('seller_id')]);
			endif;

			return true;

		endif;

		/* We didn't found the data or the expire date is expired... */
		return false;
	}

	/**
	 * This method is responsible to set the session and write it in the sellers_tokens table 
	 * depending on if the seller has chosen the remember me option... 
	 */
	private function setToken(String $id, Bool $remember_me): Void
	{
		/* Setting of the right time and the token type depending on if the seller has chosen the remember me option... */
		 if($remember_me === true): 
		 	$time = 864000; // 10gg
		 	$token_type = 'cookie';
		 else: 
		 	$time = 86400; // 24h
		 	$token_type = 'session';
		 endif;

		$token = new Token();
		$token_hash = $token->getHash();

		$token_expire = date('Y-m-d H:i:s', time() + $time);

		/* Creating of the connection with the sellers_tokens table... */
		$builder = $this->db->table('sellers_tokens');

		/* Writing of seller session data... */
		$builder->insert(['seller_id' => $id, 'token_hash' => $token_hash, 'token_create' => date('Y-m-d H:i:s'), 'token_expire' => $token_expire, 'token_type' => $token_type, 'seller_agent' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $_SERVER['REMOTE_ADDR']]);

		/* If the remember me option was checked, we prepare data to have a session with php cookies... */
		if($remember_me):
			$this->response->setCookie('remember_me', $token->getValue(), $token_expire);

		/* The remember me option was not checked so we prepare the data to have a session based on php sessions... */
		else:
			$session = session();
			$session->regenerate();
			$session->set(['backend_session' => $token->getValue()]);
		endif;
	}

	/**
	 * Method responsible of the logout, 
	 * if the current seller was logged in by session...
	 */
	public function logoutBySession(): Void
	{
		$token = new Token(session('backend_session'));
		$token_hash = $token->getHash();

		$builder = $this->db->table('sellers_tokens');
		$builder->where(['token_hash' => $token_hash, 'token_type' => 'session'])->delete();

		session()->destroy();
	}

	/**
	 * Method responsible of the logout, 
	 * if the current seller was logged in by cookie...
	 */
	public function logoutByCookie(String $cookie): Void
	{
		$token = new Token($cookie);
		$token_hash = $token->getHash();

		$builder = $this->db->table('sellers_tokens');
		$builder->where(['token_hash' => $token_hash, 'token_type' => 'cookie'])->delete();
	
		$this->response->deleteCookie('remember_me');
	}
}