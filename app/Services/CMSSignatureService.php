<?php

namespace App\Services;


class CMSSignatureService
{
    private String $certificatePath;
    private String $privateKeyPath;
    private String $privateKeyPass;
    private String $certificateChainPath;

    public function __construct($certificatePath, $privateKeyPath, $privateKeyPass, $certificateChainPath) {
        $this->certificatePath = $certificatePath;
        $this->privateKeyPath = $privateKeyPath;
        $this->privateKeyPass = $privateKeyPass;
        $this->certificateChainPath = $certificateChainPath;
    }

    public function signData($data) {
        $tmpFileData = tmpfile();
        $tmpFileSignature = tmpfile();
        $headers = array();

        // Init files
        $tmpFileDataPath = stream_get_meta_data($tmpFileData)['uri'];
        $tmpFileSignaturePath = stream_get_meta_data($tmpFileSignature)['uri'];

        // Init certs
        $certificatePath = "file://".$this->certificatePath;
        $privateKeyPath = "file://".$this->privateKeyPath;
        $certificateChainPath = $this->certificateChainPath;
        $privateKeyPass = $this->privateKeyPass;

        // Put data into data file
        file_put_contents($tmpFileDataPath,$data);

        // Sign it
        openssl_cms_sign($tmpFileDataPath,
            $tmpFileSignaturePath,
            $certificatePath,
            array($privateKeyPath, $privateKeyPass),
            $headers,
            OPENSSL_CMS_DETACHED,
            OPENSSL_ENCODING_DER,
            $certificateChainPath
        );

        // Grab signature contents
        return base64_encode(file_get_contents($tmpFileSignaturePath));
    }


}
