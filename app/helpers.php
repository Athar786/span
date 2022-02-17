<?php

if(!function_exists('serverside_validate'))
{
    function serverside_validate($validator=[])
    {
        if(!empty($validator)){
            if($validator->fails()) {
                http_response_code(200);
                $response['status'] = 0;
                $response['message'] = $validator->errors()->first();
                header("Content-Type: application/json");
                echo json_encode($response);die;
            }
        }
    }
}

if(!function_exists('getUserImg'))
{
    function getUserImg($name='')
    {
        if(!empty($name) && file_exists(public_path('uploads/users/'.$name)))
        {
            return asset('uploads/users/'.$name);
        } else {
            return asset('uploads/default/default-user.png');
        }
    }
}

if(!function_exists('deleteFile'))
{
    function deleteFile($fileDir)
    {
        if($fileDir)
        {
            if(file_exists($fileDir))
            {
                @unlink($fileDir);
            }
        }
    }
}

if(!function_exists('getUserStatusHtml'))
{
    function getUserStatusHtml($status)
    {           
        $html = '';
                        
        if($status == '1'){
            $html .= '<label class="badge badge-success mr-1">Active</label>';
        } else if($status == '0'){
            $html .= '<label class="badge badge-warning mr-1">Inactive</label>';
        } else if($status == '3'){
            $html .= '<label class="badge badge-danger mr-1">Deleted</label>';
        } else if($status == '4'){
            $html .= '<label class="badge badge-dark mr-1">Blocked</label>';
        }
        
        return $html;
    }
}
?>