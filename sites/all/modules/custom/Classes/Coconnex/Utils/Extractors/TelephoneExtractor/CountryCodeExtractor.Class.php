<?php

namespace Coconnex\Utils\Extractors\TelephoneExtractor;

class CountryCodeExtractor
{
    protected $tel;
    protected $code_pattern;
    protected $size_pattern;

    public function __construct($tel = null, $code_pattern = null, $size_pattern = null)
    {
        if ($tel) $this->tel = $tel;
        $this->code_pattern = ($code_pattern) ? $code_pattern : file_get_contents(dirname(__FILE__) . "/country_code_pattern/country_code_pattern.txt");
        $this->size_pattern = ($size_pattern) ? $code_pattern : file_get_contents(dirname(__FILE__) . "/country_code_pattern/country_code_size_pattern.txt");
    }

    public function extract_country_code($format_matches = false, $clean_spaces = false)
    {
        if (is_array($this->tel)) {
            return $this->bulk_extract_country_code($this->tel, $format_matches, $clean_spaces);
        } else {
            return $this->single_extract_country_code($this->tel, $format_matches, $clean_spaces);
        }
    }

    protected function single_extract_country_code($tel, $format_matches = false, $clean_spaces = false)
    {
        $result['tel_no'] = $tel;
        if ($tel) {
            $ret = $this->get_code_match_results($tel, $format_matches, $clean_spaces);
            return \array_merge($result, $ret);
        }
        $result['msg'] = "Please provide telephone number for extration";
        $result['success'] = false;
        return  $result;
    }

    protected function bulk_extract_country_code($tel_no_list, $format_matches = false, $clean_spaces = false)
    {
        $bulk_extraction_results = array();

        if (sizeof($tel_no_list) > 0) {
            for ($i = 0; $i < sizeof($tel_no_list); $i++) {
                $bulk_extraction_results[$i] = $this->single_extract_country_code($tel_no_list[$i], $format_matches, $clean_spaces);
            }

            foreach ($bulk_extraction_results as $key => $row) {
                $col_tel_no[$key]  = $row['tel_no'];
                $col_extracted_code[$key] = $row['extracted_code'];
                $col_extracted_remaining[$key] = $row['extracted_remaining'];
                $col_message[$key] = $row['msg'];
                $col_success[$key] = $row['success'];
                $col_size_is_valid[$key] = $row['size_is_valid'];
            }

            array_multisort($col_success, SORT_ASC, $col_size_is_valid, SORT_ASC, SORT_NUMERIC, $bulk_extraction_results);

            return $bulk_extraction_results;
        }

        return null;
    }

    protected function get_code_match_results($tel, $format_matches = false, $clean_spaces = false)
    {
        $raw_tel = $tel;

        if ($clean_spaces) {
            $tel = preg_replace("/[\s]+/", "", $tel);
        }

        $pre_s = ($format_matches) ? "<pre>" : "<BR/>";
        $pre_e = ($format_matches) ? "</pre>" : "";
        $result['extracted_code'] = "";
        $result['extracted_remaining'] = "";
        $result['msg'] = "";
        $result['success'] = false;
        $result['size_is_valid'] = true;
        if ($this->code_pattern) {
            $matches = null;
            $match = preg_match($this->code_pattern, $tel, $matches);
            $print_matches_result = $pre_s . print_r($matches, true) . $pre_e;
            if ($match) {
                if (isset($matches[0])) {
                    $result['extracted_code'] = intval(preg_replace("/[^0-9]/", "", $matches[0]));
                    $result['extracted_remaining'] = trim(substr($tel, strlen($matches[0])));
                    $result['extracted_code'] = "+" . $result['extracted_code'];
                    $result['msg'] = "Extraction sucessfull :) " . $print_matches_result;
                    $result['success'] = true;
                    $size_valid = $this->is_size_valid($tel);
                    if ($size_valid === false) {
                        $result['msg'] = "Extraction sucessfull, but size validation failed. In most probability telephone did not have a country code existing." . $print_matches_result;
                        $result['size_is_valid'] = false;
                    }
                    if ($size_valid === null) {
                        $result['msg'] = "Extraction sucessfull, but size validation could not be done because code pattern was not supplied" . $print_matches_result;
                    }
                    return $result;
                }
                $result['msg'] = "Matches found! Extraction unsucessfull :( " . $print_matches_result;
                $result['success'] = false;
                return  $result;
            }
            $result['msg'] = "No matches found. Extraction unsucessfull :( " . $print_matches_result;
            $result['success'] = false;
        } else {
            $result['msg'] = "Code pattern not available. Please provide code pattern.";
            $result['success'] = false;
        }

        return  $result;
    }

    protected function is_size_valid($tel)
    {
        if ($this->size_pattern) {
            $tel = preg_replace("/[^0-9]/", "", $tel);
            $matches = null;
            $match = preg_match($this->size_pattern, $tel, $matches);
            return ($match === 1) ? true : false;
        }
        return null;
    }
}
