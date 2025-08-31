<?php
/**
 * Compile PO files to MO files for WordPress translation
 */

class POToMOCompiler {
    
    public function compilePOFile($poFile, $moFile) {
        if (!file_exists($poFile)) {
            echo "PO file not found: $poFile\n";
            return false;
        }
        
        $po = file_get_contents($poFile);
        $lines = explode("\n", $po);
        
        $messages = [];
        $current_msgid = '';
        $current_msgstr = '';
        $in_msgid = false;
        $in_msgstr = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line) || $line[0] === '#') {
                // Save previous message if complete
                if (!empty($current_msgid) && !empty($current_msgstr)) {
                    $messages[stripcslashes($current_msgid)] = stripcslashes($current_msgstr);
                }
                $current_msgid = '';
                $current_msgstr = '';
                $in_msgid = false;
                $in_msgstr = false;
                continue;
            }
            
            if (preg_match('/^msgid\s+"(.*)"\s*$/', $line, $matches)) {
                $current_msgid = $matches[1];
                $in_msgid = true;
                $in_msgstr = false;
            } elseif (preg_match('/^msgstr\s+"(.*)"\s*$/', $line, $matches)) {
                $current_msgstr = $matches[1];
                $in_msgid = false;
                $in_msgstr = true;
            } elseif (preg_match('/^"(.*)"\s*$/', $line, $matches)) {
                if ($in_msgid) {
                    $current_msgid .= $matches[1];
                } elseif ($in_msgstr) {
                    $current_msgstr .= $matches[1];
                }
            }
        }
        
        // Save the last message
        if (!empty($current_msgid) && !empty($current_msgstr)) {
            $messages[stripcslashes($current_msgid)] = stripcslashes($current_msgstr);
        }
        
        // Create MO file
        $this->createMOFile($messages, $moFile);
        echo "Compiled: $poFile -> $moFile (" . count($messages) . " translations)\n";
        return true;
    }
    
    private function createMOFile($messages, $filename) {
        // MO file format header
        $magic = 0x950412de;
        $revision = 0;
        $count = count($messages);
        $origTableOffset = 28;
        $translTableOffset = $origTableOffset + 8 * $count;
        
        $origTable = '';
        $translTable = '';
        $origData = '';
        $translData = '';
        
        $origDataOffset = $translTableOffset + 8 * $count;
        $translDataOffset = $origDataOffset;
        
        foreach ($messages as $orig => $transl) {
            $origLen = strlen($orig);
            $translLen = strlen($transl);
            
            $origTable .= pack('VV', $origLen, $translDataOffset);
            $origData .= $orig . "\0";
            $translDataOffset += $origLen + 1;
            
            $translTable .= pack('VV', $translLen, $translDataOffset);
            $translData .= $transl . "\0";
            $translDataOffset += $translLen + 1;
        }
        
        $header = pack('VVVVVV', $magic, $revision, $count, $origTableOffset, $translTableOffset, 0);
        
        file_put_contents($filename, $header . $origTable . $translTable . $origData . $translData);
    }
}

// Compile Vietnamese translations
$compiler = new POToMOCompiler();
$languagesDir = dirname(__DIR__) . '/languages/';

echo "Compiling translation files...\n";

// Compile Vietnamese
$compiler->compilePOFile(
    $languagesDir . 'improve-image-delivery-pagespeed-vi_VN.po',
    $languagesDir . 'improve-image-delivery-pagespeed-vi_VN.mo'
);

// Compile English
$compiler->compilePOFile(
    $languagesDir . 'improve-image-delivery-pagespeed-en_US.po',
    $languagesDir . 'improve-image-delivery-pagespeed-en_US.mo'
);

echo "Translation compilation completed!\n";
?>