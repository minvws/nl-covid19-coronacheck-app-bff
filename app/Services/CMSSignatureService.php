<?php

namespace App\Services;

use \Exception;

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

    public function checkThirdPartyTestSignature($payload,$signature) : bool {
        try {
            $test = json_decode(base64_decode($payload));
            if(!ctype_alnum($test->providerIdentifier)) {
                throw new Exception('Test provider identifier is not correct');
            }

            $tmpFilePayload = tmpfile();
            $tmpFileSignature = tmpfile();

            // Locate CMS public key
            $cmsPublicKeyPath =config('app.cms_sign_ctp_dir') . strtolower($test->providerIdentifier) .'_cms_sign_public.pem';

            // Init files
            $tmpFilePayloadPath = stream_get_meta_data($tmpFilePayload)['uri'];
            $tmpFileSignaturePath = stream_get_meta_data($tmpFileSignature)['uri'];

            // Place data in files
            file_put_contents($tmpFilePayloadPath,base64_decode($payload));
            file_put_contents($tmpFileSignaturePath,base64_decode($signature));

            // This only checks the leaf certificate.
            // TODO:  !!! Also check CA is PKI-O !!!
            $verifyCert = openssl_cms_verify(
                $tmpFilePayloadPath,
                OPENSSL_CMS_NOVERIFY | OPENSSL_CMS_DETACHED | OPENSSL_CMS_NOINTERN,
                null,
                [],
                $cmsPublicKeyPath,
                null,
                null,
                $tmpFileSignaturePath,
                OPENSSL_ENCODING_DER
            );

            return $verifyCert;
        }  catch (Exception $exception) {

        }

        return false;
    }

    public function signData($payload) : String {
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
        file_put_contents($tmpFileDataPath,$payload);

        // Sign it
        openssl_cms_sign(
            $tmpFileDataPath,
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
