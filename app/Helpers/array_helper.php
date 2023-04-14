<?php 

/* Codeigniter 4 array validation leaves the key of the errors arrays with '.*'
In order to not have problems with jQuery validation, we use this function to remove '.*' */
function array_replace_key(Array $errors, Array $search = []): Array 
{
    $updatedArray = [];

    if($search):

        foreach($search as $field):

            foreach ($errors as $key => $value):

                if(strpos($key, '.')):
                    $key = str_replace($field, '', $key);
                endif;

                $updatedArray = array_merge($updatedArray, [$key => $value]);

            endforeach;

        endforeach;

    else:

        foreach ($errors as $key => $value):

            if(strpos($key, '.')):
                $key = substr($key, 0, strpos($key, '.'));
            endif;

            $updatedArray = array_merge($updatedArray, [$key => $value]);

        endforeach;

    endif;

    return $updatedArray;
}