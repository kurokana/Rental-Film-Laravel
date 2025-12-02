<?php

namespace App\Helpers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QRISHelper
{
    /**
     * Generate QRIS code for payment
     * 
     * @param string $merchantName
     * @param float $amount
     * @param string $referenceId
     * @return string Base64 encoded QR code image
     */
    public static function generate($merchantName, $amount, $referenceId)
    {
        // QRIS Format (simplified - dalam produksi gunakan format QRIS resmi)
        // Format: MERCHANTNAME|AMOUNT|REF
        $qrisData = sprintf(
            "00020101021126%s0303UMI51040014ID.CO.QRIS.WWW0215%s520454995303360540%s5802ID5913%s6011JAKARTA BARA610561106287630478E9",
            str_pad(strlen($merchantName), 2, '0', STR_PAD_LEFT),
            $referenceId,
            number_format($amount, 2, '.', ''),
            strtoupper(substr($merchantName, 0, 13))
        );

        // Generate QR Code
        $options = new QROptions([
            'version'      => 5,
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => QRCode::ECC_L,
            'scale'        => 10,
            'imageBase64'  => true,
        ]);

        $qrcode = new QRCode($options);
        
        return $qrcode->render($qrisData);
    }

    /**
     * Generate simple payment QR data
     */
    public static function generateSimple($paymentCode, $amount)
    {
        $merchantName = config('app.name', 'Rental Film');
        $data = "RENTAL_FILM|{$paymentCode}|" . number_format($amount, 0, '', '');
        
        $options = new QROptions([
            'version'      => 5,
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => QRCode::ECC_L,
            'scale'        => 8,
            'imageBase64'  => true,
        ]);

        $qrcode = new QRCode($options);
        
        return $qrcode->render($data);
    }
}
