<?php declare(strict_types = 1); 

namespace App\Validation;

use App\Libraries\Token;

class CustomValidationRules
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function checkActivationCode(String $token, String &$error = null): Bool
    {
        $token = new Token($token);
        $token_hash = $token->getHash();

        $builder = $this->db->table('sellers_tokens');
        $query = $builder->limit(1)->getWhere(['token_hash' => $token_hash, 'token_type' => 'activation']);

        if(($query->getNumRows() > 0) && (date('Y-m-d H:i:s') < $query->getRow('token_expire'))):
            return true;
        endif;

        $error = 'Il codice di attivazione non va bene o è scaduto!';

        return false;
    }
}