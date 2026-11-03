<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karisma_code_generator
{
    public function qrcode($value, $targetFile)
    {
        $this->ensureDirectory(dirname($targetFile));

        if (!class_exists('QRcode')) {
            $phpQr = APPPATH . 'libraries/phpqrcode/qrlib.php';
            if (is_file($phpQr)) {
                require_once $phpQr;
            }
        }

        if (class_exists('QRcode') && method_exists('QRcode', 'png')) {
            call_user_func(['QRcode', 'png'], $value, $targetFile, 'M', 6, 2);
            return is_file($targetFile);
        }

        if ($this->simpleQrCode($value, $targetFile)) {
            return true;
        }

        if ($this->commandExists('qrencode')) {
            $cmd = 'qrencode -o ' . escapeshellarg($targetFile) . ' -s 8 -m 2 ' . escapeshellarg($value);
            @exec($cmd, $output, $status);
            if ($status === 0 && is_file($targetFile)) {
                return true;
            }
        }

        return $this->remoteQrCode($value, $targetFile);
    }

    public function barcode($value, $targetFile)
    {
        $this->ensureDirectory(dirname($targetFile));

        if (!function_exists('imagecreatetruecolor')) {
            throw new Exception('Ekstensi GD PHP belum aktif untuk membuat barcode.');
        }

        $patterns = $this->code128Patterns();
        $codes = [104];
        $checksum = 104;

        $length = strlen($value);
        for ($i = 0; $i < $length; $i++) {
            $ord = ord($value[$i]);
            if ($ord < 32 || $ord > 126) {
                throw new Exception('Barcode Code128 hanya mendukung karakter ASCII printable.');
            }
            $code = $ord - 32;
            $codes[] = $code;
            $checksum += $code * (count($codes) - 1);
        }

        $codes[] = $checksum % 103;
        $codes[] = 106;

        $module = 2;
        $quiet = 16;
        $barHeight = 90;
        $textHeight = 22;
        $modules = 0;

        foreach ($codes as $code) {
            $pattern = $patterns[$code];
            for ($i = 0; $i < strlen($pattern); $i++) {
                $modules += (int)$pattern[$i];
            }
        }

        $width = ($modules * $module) + ($quiet * 2);
        $height = $barHeight + $textHeight + 14;
        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 17, 24, 39);

        imagefilledrectangle($image, 0, 0, $width, $height, $white);

        $x = $quiet;
        foreach ($codes as $code) {
            $pattern = $patterns[$code];
            for ($i = 0; $i < strlen($pattern); $i++) {
                $w = (int)$pattern[$i] * $module;
                if ($i % 2 === 0) {
                    imagefilledrectangle($image, $x, 8, $x + $w - 1, $barHeight, $black);
                }
                $x += $w;
            }
        }

        $label = $value;
        $font = 3;
        $textWidth = imagefontwidth($font) * strlen($label);
        imagestring($image, $font, max(0, (int)(($width - $textWidth) / 2)), $barHeight + 7, $label, $black);

        $ok = imagepng($image, $targetFile);
        imagedestroy($image);
        if ($ok && is_file($targetFile)) {
            @chmod($targetFile, 0644);
        }

        return $ok && is_file($targetFile);
    }

    private function ensureDirectory($directory)
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        @chmod($directory, 0777);

        if (!is_dir($directory) || !is_writable($directory)) {
            throw new Exception('Folder asset tidak bisa ditulis: ' . $directory);
        }
    }

    private function commandExists($command)
    {
        if (!function_exists('shell_exec') || !$this->functionEnabled('shell_exec')) {
            return false;
        }

        $lookup = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where ' : 'command -v ';
        $result = trim((string)@shell_exec($lookup . escapeshellarg($command)));
        return $result !== '';
    }

    private function functionEnabled($function)
    {
        $disabled = array_map('trim', explode(',', (string)ini_get('disable_functions')));
        return !in_array($function, $disabled, true);
    }

    private function simpleQrCode($value, $targetFile)
    {
        if (!function_exists('imagecreatetruecolor')) {
            return false;
        }

        if (!class_exists('QRCode')) {
            $simpleQr = APPPATH . 'libraries/simpleqrcode/qrcode.php';
            if (is_file($simpleQr)) {
                require_once $simpleQr;
            }
        }

        if (!class_exists('QRCode')) {
            return false;
        }

        $generator = new QRCode($value, [
            's' => 'qr-m',
            'sf' => 8,
            'p' => 2,
            'bc' => 'FFFFFF',
            'fc' => '111827',
        ]);
        $image = $generator->render_image();
        $ok = imagepng($image, $targetFile);
        imagedestroy($image);

        if ($ok && is_file($targetFile)) {
            @chmod($targetFile, 0644);
        }

        return $ok && is_file($targetFile);
    }

    private function remoteQrCode($value, $targetFile)
    {
        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&margin=10&format=png&data=' . rawurlencode($value);
        $content = false;

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            $content = curl_exec($ch);
            $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (($content === false || $httpCode !== 200) && stripos((string)$url, 'https://') === 0) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
                curl_setopt($ch, CURLOPT_TIMEOUT, 4);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $content = curl_exec($ch);
                $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
            }

            if ($httpCode !== 200) {
                $content = false;
            }
        } elseif (ini_get('allow_url_fopen')) {
            $content = @file_get_contents($url);
        }

        if ($content === false || substr($content, 0, 8) !== "\x89PNG\x0d\x0a\x1a\x0a") {
            throw new Exception('Library QRCode belum tersedia. Install phpqrcode di application/libraries/phpqrcode atau command qrencode.');
        }

        $ok = file_put_contents($targetFile, $content) !== false && is_file($targetFile);
        if ($ok) {
            @chmod($targetFile, 0644);
        }

        return $ok;
    }

    private function code128Patterns()
    {
        return [
            '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213',
            '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132',
            '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211',
            '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313',
            '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331',
            '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111',
            '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214',
            '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111',
            '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141',
            '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141',
            '114131', '311141', '411131', '211412', '211214', '211232', '2331112'
        ];
    }
}
