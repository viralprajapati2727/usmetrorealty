<?php
class Docx2Text {
	function getText($file) 
	{
		$content = '';
        $zip = zip_open($file);
        if (!$zip || is_numeric($zip)) return false;
        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            zip_entry_close($zip_entry);
        }// end while
 
        zip_close($zip);
        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $outtext = strip_tags($content);
		return $outtext ;
	}
}