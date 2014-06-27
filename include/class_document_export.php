<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_export_receipt
 *
 * @author dany
 */
class Document_Export
{

    function __construct()
    {
        // Create 2 temporary folders   1. convert to PDF + stamp
        //                              2. store result
        $this->feedback = array();
        $this->store_convert = tempnam($_ENV['TMP'], 'convert_');
        $this->store_pdf = tempnam($_ENV['TMP'], 'pdf_');
        unlink($this->store_convert);
        unlink($this->store_pdf);
        umask(0);
        mkdir($this->store_convert);
        mkdir($this->store_pdf);
    }

    function concatenate_pdf()
    {
        $stmt = PDFTK . " " . $this->store_pdf . '/stamp_*pdf  output ' . $this->store_pdf . '/result.pdf';
        $status = 0;
        echo $stmt;
        passthru($stmt, $status);

        if ($status <> 0)
        {
            $cnt_feedback = count($this->feedback);
            $this->feedback[$cnt_feedback]['file'] = 'result.pdf';
            $this->feedback[$cnt_feedback]['message'] = ' cannot concatenate PDF';
            $this->feedback[$cnt_feedback]['error'] = $status;
        }
    }

    function move_file($p_source, $target)
    {
        copy($p_source, $this->store_pdf . '/' . $target);
    }

    function send_pdf()
    {
        header('Content-Type: application/x-download');
        header('Content-Disposition: attachment; filename="result.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        echo file_get_contents($this->store_pdf . '/result.pdf');
    }

    function clean_folder()
    {
        //unlink($this->store_convert . '/*.pdf');
    }

    /**
     * @brief export all the pieces in PDF
     * @param $p_array contents all the jr_id
     */
    function export_all($p_array)
    {
        ob_start();
        var_dump($p_array);
        global $cn;

        $cn->start();
        foreach ($p_array as $value)
        {
            // For each file save it into the temp folder,
            $file = $cn->get_array('select jr_pj,jr_pj_name,jr_pj_number,jr_pj_type from jrn '
                    . ' where jr_id=$1', array($value));
            if ($file[0]['jr_pj'] == '')
                continue;

            $cn->lo_export($file[0]['jr_pj'], $this->store_convert . '/' . $file[0]['jr_pj_name']);

            // Convert this file into PDF 
            if ($file[0]['jr_pj_type'] != 'application/pdf')
            {
                $status = 0;
                passthru(OFFICE . " " . $this->store_convert . '/' . $file[0]['jr_pj_name'], $status);
                if ($status <> 0)
                {
                    $this->feedback[$cnt_feedback]['file'] = $file[0]['jr_pj_name'];
                    $this->feedback[$cnt_feedback]['message'] = ' cannot convert to PDF';
                    $this->feedback[$cnt_feedback]['error'] = $status;
                    $cnt_feedback++;
                    continue;
                }
            }

            // Create a image with the stamp + formula
            $img = imagecreatefromgif(__DIR__ . '/template/template.gif');
            $font = imagecolorallocatealpha($img, 100, 100, 100, 110);
            imagettftext($img, 40, 25, 500, 1000, $font, __DIR__ . '/tfpdf/font/unifont/DejaVuSans.ttf', _("Copie certifiée conforme à l'original"));
            imagettftext($img, 40, 25, 550, 1100, $font, __DIR__ . '/tfpdf/font/unifont/DejaVuSans.ttf', $file[0]['jr_pj_number']);
            imagettftext($img, 40, 25, 600, 1200, $font, __DIR__ . '/tfpdf/font/unifont/DejaVuSans.ttf', $file[0]['jr_pj_name']);
            imagegif($img, $this->store_convert . '/' . 'stamp.gif');

            // transform gif file to pdf with convert tool
            $stmt = CONVERT_GIF_PDF . " " . escapeshellarg($this->store_convert . '/' . 'stamp.gif') . " " . escapeshellarg($this->store_convert . '/stamp.pdf');
            passthru($stmt, $status);

            if ($status <> 0)
            {
                $this->feedback[$cnt_feedback]['file'] = 'stamp.pdf';
                $this->feedback[$cnt_feedback]['message'] = ' cannot convert to PDF';
                $this->feedback[$cnt_feedback]['error'] = $status;
                $cnt_feedback++;
                continue;
            }

            // 
            // remove extension
            $ext = strrpos($file[0]['jr_pj_name'], ".");
            $file_pdf = substr($file[0]['jr_pj_name'], 0, $ext);
            $file_pdf .=".pdf";

            // output
            $output = $this->store_convert . '/stamp_' . $file_pdf;

            // Concatenate stamp + file
            $stmt = PDFTK . " " . escapeshellarg($this->store_convert . '/' . $file_pdf) . ' stamp ' . $this->store_convert .
                    '/stamp.pdf output ' . $output;

            passthru($stmt, $status);
            echo $stmt;
            if ($status <> 0)
            {

                $this->feedback[$cnt_feedback]['file'] = $file_pdf;
                $this->feedback[$cnt_feedback]['message'] = ' cannot convert to stamped PDF';
                $this->feedback[$cnt_feedback]['error'] = $status;
                $cnt_feedback++;
                continue;
            }
            // Move the PDF into another temp directory 
            $this->move_file($output, 'stamp_' . $file_pdf);
        }
        $this->concatenate_pdf();
        ob_clean();
        $this->send_pdf();

        // remove files from "conversion folder"
        $this->clean_folder();
        var_dump($this->feedback);
        // concatenate all pdf into one
    }

}
