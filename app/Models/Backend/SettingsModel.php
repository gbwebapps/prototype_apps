<?php

namespace App\Models\Backend;

class SettingsModel extends BackendModel
{
    protected $table = 'settings';
    protected $primaryKey = 'id';

    public $applicationRules = [
        'application_name' => [
            'label' => 'Application Name', 
            'rules' => 'required'
        ], 
        'admin_email' => [
            'label' => 'Admin Email', 
            'rules' => 'required|valid_email'
        ]
    ];

    public $displayingRules = [
        'upload_file_size' => [
            'label' => 'Max Size', 
            'rules' => 'required|integer'
        ], 
        'upload_file_x' => [
            'label' => 'Max Width', 
            'rules' => 'required|integer'
        ], 
        'upload_file_y' => [
            'label' => 'Max Height', 
            'rules' => 'required|integer'
        ], 
        'resize_medium_x' => [
            'label' => 'Max Medium Width', 
            'rules' => 'required|integer'
        ], 
        'resize_medium_y' => [
            'label' => 'Max Medium Height', 
            'rules' => 'required|integer'
        ], 
        'resize_small_x' => [
            'label' => 'Max Small Width', 
            'rules' => 'required|integer'
        ], 
        'resize_small_y' => [
            'label' => 'Max Small Height', 
            'rules' => 'required|integer'
        ], 
        'rename_images' => [
            'label' => 'Renaming Images', 
            'rules' => 'if_exist|in_list[on]'
        ], 
        'overwrite_images' => [
            'label' => 'Overwriting Images', 
            'rules' => 'if_exist|in_list[on]'
        ], 
        'rows_per_list' => [
            'label' => 'Rows Per List', 
            'rules' => 'required|integer'
        ],
    ];

    public $shieldRules = [
        'attempts_limit' => [
            'label' => 'Attempts Limit', 
            'rules' => 'required|integer'
        ], 
        'attempts_time' => [
            'label' => 'Attempts Time', 
            'rules' => 'required|integer'
        ], 
        'remember_me_time' => [
            'label' => 'Remember Me', 
            'rules' => 'required|integer'
        ], 
        'session_time' => [
            'label' => 'Session Time', 
            'rules' => 'required|integer'
        ], 
        'activation_time' => [
            'label' => 'Activation Time', 
            'rules' => 'required|integer'
        ]
    ];

    public $debugRules = [
        'log_file' => [
            'label' => 'Flow File', 
            'rules' => 'if_exist|in_list[on]'
        ], 
        'benchmark' => [
            'label' => 'Benchmark', 
            'rules' => 'if_exist|in_list[on]'
        ], 
        'service' => [
            'label' => 'Maintenance', 
            'rules' => 'if_exist|in_list[on]'
        ]
    ];

    public function getSettings(String $section): Bool|Array
    {
        $this->db->transBegin();

            $query = $this->builder->select('opt, val')->getWhere(['environment' => 'backend', 'section' => $section]);

            $settings = [];

            foreach($query->getResult() as $k => $v):
                $settings[$v->opt] = $v->val;
            endforeach;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return false;
        else:
            $this->db->transCommit();
            return $settings;
        endif;
    }

    public function saveSettings(Array $posts): Bool|Array
    {
        $this->db->transBegin();

            /* Recovering the section which is sent */
            $section = $posts['section']; unset($posts['section']);

            /* These are the checkboxes to evaluate */
            $application = [];
            $displaying = ['rename_images', 'overwrite_images'];
            $shield = [];
            $debug = ['log_file', 'benchmark', 'service'];

            /* Evaluating the checkboxes depending on which section was sent */
            if(count($$section)):
                foreach($$section as $v):
                    $posts[$v] = (isset($posts[$v])) ? $posts[$v] : 0;
                endforeach;
            endif;

            /* Updating the settings */
            foreach($posts as $k => $v):
                $this->builder->update(['val' => $v], ['opt' => $k]);
            endforeach;

            /* Selecting the settings depending on which section was sent */
            $query = $this->builder->select('opt, val')->getWhere(['environment' => 'backend', 'section' => $section]);

            $settings = [];

            foreach($query->getResult() as $k => $v):
                $settings[$v->opt] = $v->val;
            endforeach;

        if ($this->db->transStatus() === false):
            $this->db->transRollback();
            return false;
        else:
            $this->db->transCommit();
            $this->writeSettings($section);
            return $settings;
        endif;
    }

    private function writeSettings(String $section): Void
    {
        $query = $this->builder->select('opt, val')->getWhere(['environment' => 'backend', 'section' => $section]);

        $output = '';

        $file = fopen(APPPATH . '/Config/' . ucfirst($section) . ".php", "w");

        $output .= '<?php ' . "\n\n";
        $output .= 'namespace Config;' . "\n\n";
        $output .= 'use CodeIgniter\Config\BaseConfig;' . "\n\n";
        $output .= 'class ' . ucfirst($section) . ' extends BaseConfig {' . "\n\n";

        foreach($query->getResult() as $k => $v):
            $quote = ( ! is_numeric($v->val) ? '"' : '');
            $output .= "\t" . "public $$v->opt = $quote$v->val$quote;" . "\n\n";
        endforeach;

        $output .= '}';

        fwrite($file, $output);

        fclose($file);
    }
}
