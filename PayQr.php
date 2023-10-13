<?php

use Exception;
use QRcode;

/**
 * 
 * @author Tuncay Uyar 
 * @version 1.0.0
 * 
 * 
 * Requires QR-Library: http://phpqrcode.sourceforge.net/ 
 * 
 * 
 * REFERENCE 
 * https://www.europeanpaymentscouncil.eu/document-library/guidance-documents/quick-response-code-guidelines-enable-data-capture-initiation
 * 
 * 
 */

include(ROOT . 'vendor/phpqrcode/qrlib.php');

class PayQr
{

    private $_serviceTag;

    private $_charSet;

    private $_version;

    private $_identification;

    private $_iban;

    private $_bic;

    private $_recipient;

    private $_amount;

    private $_subject;

    private $_comment;

    private $_imageSize;

    private $_filePath;

    const CHARSET = [
        "UTF-8"         => 1,
        "ISO 8859-1"    => 2,
        "ISO 8859-2"    => 3,
        "ISO 8859-4"    => 4,
        "ISO 8859-5"    => 5,
        "ISO 8859-7"    => 6,
        "ISO 8859-10"   => 7,
        "ISO 8859-15"   => 8,
    ];



    public function __construct()
    {
        $this->setServiceTag();
        $this->setIdentification();
        $this->setVersion();
        $this->setCharSet();
        $this->setImageSize();
    }

    public function generate($path_to_store_qr)
    {
        $data = $this->_build();
        $tempDir = $path_to_store_qr;
        $filename = "SEPA_" . "_" . time() . ".png";
        $this->_filePath = $tempDir . '/' . $filename;
        QRcode::png(
            $data,
            $this->_filePath,
            "QR_ECLEVEL_M",
            $this->_imageSize,
            2
        );
    }


    // ================================================= 
    // ================= SETTERS START =================
    // ================================================= 

    /**
     * 
     * Currently only BCD is allowed
     * 
     * https://de.wikipedia.org/wiki/BCD-Code
     * 
     */
    public function setServiceTag($st = "BCD")
    {
        if ($st != "BCD") throw new Exception('Invalid service tag');
        $this->_serviceTag = $st;
    }

    /**
     * 
     * Version 1 a BIC is required. In version 2 a BIC is only required outside europe.
     * 
     */
    public function setVersion($version = 2)
    {
        if (!in_array($version, [1, 2])) throw new Exception('Invalid version');
        $this->_version = $version;
    }

    /**
     * 
     * SCT = Sepa Credit Transfer
     * 
     */
    public function setIdentification($identification = "SCT")
    {
        if ($identification != "SCT") throw new Exception('Invalid character set');
        $this->_identification = $identification;
    }


    public function setCharSet($charSet = "UTF-8")
    {
        if (!array_key_exists($charSet, self::CHARSET)) throw new
            Exception('Invalid character set');
        $this->_charSet = self::CHARSET[$charSet];
    }


    public function setRecipientName(string $name)
    {
        $this->_recipient = $name;
    }

    public function setIban(string $iban)
    {
        $this->_iban = $iban;
    }

    public function setBic(string $bic)
    {
        $this->_bic = $bic;
    }

    public function setAmount(string $amount)
    {
        $this->_amount = $amount;
    }

    public function setSubject(string $subject)
    {
        $this->_subject = $subject;
    }

    public function setComment(string $comment)
    {
        $this->_comment = $comment;
    }

    public function setImageSize(int $imageSize = 4)
    {
        $this->_imageSize =  $imageSize;
    }

    // ================================================= 
    // ================== SETTERS END ==================
    // ================================================= 


    // ================================================= 
    // ================= GETTERS START =================
    // ================================================= 


    public function getServiceTag()
    {
        return $this->_serviceTag;
    }

    public function getVersion()
    {
        return $this->_version;
    }
    public function getIdentification()
    {
        return $this->_identification;
    }

    public function getCharSet()
    {
        return $this->_charSet;
    }

    public function getRecipientName()
    {
        return $this->_recipient;
    }

    public function getIban()
    {
        return $this->_iban;
    }

    public function getBic()
    {
        return $this->_bic;
    }

    public function getAmount()
    {
        return $this->_amount;
    }

    public function getSubject()
    {
        return $this->_subject;
    }


    public function getComment()
    {
        return $this->_comment;
    }

    public function getImageSize()
    {
        return $this->_imageSize;
    }

    public function getFilePath()
    {
        return $this->_filePath;
    }

    // ================================================= 
    // ================== GETTERS END ==================
    // ================================================= 


    /**
     * 
     * Build the data string with payment details
     */
    private function _build(): string
    {

        return rtrim(implode("\n", array(
            $this->getServiceTag(),
            sprintf('%03d', $this->getVersion()),
            $this->getCharSet(),
            $this->getIdentification(),
            $this->getBic(),
            $this->getRecipientName(),
            $this->getIban(),
            $this->getAmount() > 0 ?
                $this->formatAmount($this->getAmount()) : '',
            "",
            $this->getSubject(),
            "",
            $this->getComment()
        )), "\n");
    }



    private function formatAmount($amount)
    {
        return sprintf('EUR%s', number_format($amount, 2, '.', ''));
    }
}
