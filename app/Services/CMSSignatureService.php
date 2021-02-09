<?php

namespace App\Services;


class CMSSignatureService
{
    public static function getSignature($data) {
        $tmpFileData = tmpfile();
        $tmpFileSignature = tmpfile();
        $headers = array();

        // Init files
        $tmpFileDataPath = stream_get_meta_data($tmpFileData)['uri'];
        $tmpFileSignaturePath = stream_get_meta_data($tmpFileSignature)['uri'];

        // Init certs
        $certificatePath = "file://".config('app.cms_sign_x509_cert');
        $privateKeyPath = "file://".config('app.cms_sign_x509_key');
        $privateKeyPass = config('app.cms_sign_x509_pass');

        var_dump($privateKeyPath);
        // Put data into data file
        file_put_contents($tmpFileDataPath,$data);

        // Sign it
        openssl_cms_sign($tmpFileDataPath,$tmpFileSignaturePath,$certificatePath,
            array($privateKeyPath, $privateKeyPass), $headers, OPENSSL_CMS_DETACHED | OPENSSL_CMS_NOCERTS,OPENSSL_ENCODING_DER   );

        // Grab signature contents
        return base64_encode(file_get_contents($tmpFileSignaturePath));
    }
}
