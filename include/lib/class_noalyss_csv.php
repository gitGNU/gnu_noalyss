<?php

/*
 * Copyright (C) 2015 Dany De Bontridder <dany@alchimerys.be>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


/***
 * @file 
 * @brief Manage the CSV : manage files and write CSV record
 *
 */

class Noalyss_Csv
{

    private $filename;

    function __construct($p_filename)
    {
        $this->filename=$p_filename;
        $this->correct_name();
    }

    /***
     * @brief
     * Correct the name of the file , remove forbidden character and
     * add extension
     */
    function correct_name()
    {
        if (trim(strlen($this->filename))==0) {
            error_log('CSV->correct_name filename is empty');
            throw new Exception('CSV-CORRECT_NAME');
        }

        // add extension if needed
        if (strpos($this->filename, ".csv")==0)
        {
            $this->filename.".csv";
        }
        
        $this->filename=str_replace(";", "", $this->filename);
        $this->filename=str_replace("/", "", $this->filename);
        $this->filename=str_replace(" ", "_", $this->filename);
        $this->filename=strtolower($this->filename);
    }

    /***
     * Send an header for CSV , the filename is corrected 
     */
    function send_header()
    {
        $this->correct_name();
        header('Pragma: public');
        header('Content-type: application/csv');
        header("Content-Disposition: attachment;filename=\"{$this->filename}\"",
                FALSE);
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Expires: Sun, 1 Jan 2000 12:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
    }

    /***
     * write record , the numeric are detected are formatted properly with the
     * function nb
     * @param array $p_array Array of 1 dimension with the contains of a row
     * @see nb
     * 
     */
    function write($p_array)
    {
        $size_array=count($p_array);
        $sep="";
        for ($i=0; $i<$size_array; $i++)
        {
            if (isNumber($p_array[$i])==1)
            {
                printf($sep.'%s', nb($p_array[$i]));
            }
            else
            {
                printf($sep.'"%s"', $p_array[$i]);
            }
            $sep=";";
        }
        printf("\r\n");
    }


}
