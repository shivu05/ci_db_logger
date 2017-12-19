<?php

/*
 * Author: Shivaraj Badiger
 * function to log all the database queries in log file
 */
if (!function_exists('db_logger')) {

    function db_logger($sql) {
        $CI = & get_instance();
        if ($CI->config->item('enable_db_query_logs')) {
            $links = $CI->config->item('db_log_links');
            if (empty($links)) {
                log_queries($sql);
            } else {
                $ctrl = $CI->router->fetch_class();
                if (in_array($ctrl, $links)) {
                    log_queries($sql);
                }
            }
        }
    }

}

if (!function_exists('log_queries')) {

    function log_queries($sql) {
        $CI = & get_instance();
        $user_details['id'] = 1; //logged in user id get from session variable
        if (isset($user_details['id'])) {
            $id = $user_details['id'];
        }
        $qtype = $CI->config->item('db_query_logs_for');
        if ($qtype == NULL || $qtype == '') {
            return FALSE;
        }
        $id = '';
        $str = strtoupper($qtype);
        if ($str == 'ALL') {
            write_logs($sql, $id);
        } else {
            $catch_sql = explode(',', $str);
            if (!empty($catch_sql)) {
                foreach ($catch_sql as $q) {
                    if ((preg_match('/' . $q . '/', $sql))) {
                        write_logs($sql, $id);
                    }
                }
            } else {
                write_logs('Invalid configuration', $id);
            }
        }
    }

}

if (!function_exists('write_logs')) {

    function write_logs($sql, $id) {
        date_default_timezone_set('Asia/Kolkata');
        $filepath = APPPATH . 'logs/Query-log-' . date('Y-m-d') . '.txt';
        $handle = fopen($filepath, "a+");
        fwrite($handle, $sql . " \r\n User: " . $id . " \r\r Execution Time: " . date("Y-m-d H:i:s") . "\r\n");
        fwrite($handle, "----------------------------------------------------------------------------------------- \r\n");
        fclose($handle);
    }

}