<?php

/**
* Saika - The PHP Framework for KIDS
*
* A Tiny Captcha generation and validation class for Saika.
*
* @version 1.0
* @since 1.0
*/

class Captcha
{
    /**
     * Session key to store captcha code
     *
     * @var string
     */
    public static $captcha_session_key = '__saika_captcha_code';

    /**
     * Render the captcha image. Setting other parameters will override config
     * values.
     *
     * @param string $length    The captcha code length
     * @param string $width     Width of the captcha
     * @param string $height    Height of the captcha
     * @param string $font_size Captcha font size
     * @return
     */
    public static function render($length = '', $width = '', $height = '', $font_size = '')
    {
        if (empty($length)) {
            $length = Config::get('CAPTCHA_LENGTH');
        }
        if (empty($width)) {
            $width = Config::get('CAPTCHA_WIDTH');
        }
        if (empty($height)) {
            $height = Config::get('CAPTCHA_HEIGHT');
        }
        if (empty($font_size)) {
            $font_size = Config::get('CAPTCHA_FONT_SIZE');
        }

        // Force typecast to be safe!
        $length = (int)$length;
        $width = (int)$width;
        $height = (int)$height;
        $font_size = (int)$font_size;
        $text = (string)get_random_string($length);
        $font = Config::get('CAPTCHA_FONT');

        // Start a session if isn't started already
        Session::start();
        // Store the text in session
        Session::set(self::$captcha_session_key, $text);

        $im = imagecreatetruecolor($width, $height);

        $bg = imagecolorallocate($im, 220, 220, 220);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        $bg = imagecolorallocate($im, 255, 231, 172);
        $maroon = imagecolorallocate($im, 37, 230, 216);
        $lime = imagecolorallocate($im, 50, 250, 67);

        // Auto Adjust text align to center
        $bbox = imagettfbbox($font_size, 0, $font, $text);
        $x = ($width / 2) - (($bbox[2] - $bbox[0]) / 2);
        $y = ($height - ($bbox[1] - $bbox[7])) / 2;
        $y -= $bbox[7];

        imagefilledrectangle($im, 0, 0, $width, $height, $white);

        // Draw some random lines
        for ($i = 0; $i < 50; $i++) {
            imagesetthickness($im, rand(1, 3));
            imagearc(
                $im,
            rand(1, 300), // x-coordinate of the center.
            rand(1, 300), // y-coordinate of the center.
            rand(1, 300), // The arc width.
            rand(1, 300), // The arc height.
            rand(1, 300), // The arc start angle, in degrees.
            rand(1, 300), // The arc end angle, in degrees.
            (rand(0, 1) ? $maroon : $lime) // A color identifier.
            );
        }
        imagettftext($im, $font_size, 0, $x, $y, $black, $font, $text);

        // Prevent caching
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-type: image/jpeg');
        // Output the image
        imagejpeg($im);
        imagedestroy($im);
    }

    /**
     * Validate captcha code against user input
     *
     * @param string $input          User input
     * @param boolean $case_sensitive Toggle case-sensitivity, by default config
     *                                value is used.
     * @return boolean
     */
    public static function validate($input, $case_sensitive = '')
    {
        if (!Session::get(self::$captcha_session_key)) {
            return false;
        }

        if ($case_sensitive === '')
            $case_sensitive = (bool)Config::get('CAPTCHA_CASE_SENSITIVE');

        $captcha = (string)Session::get(self::$captcha_session_key);
        // delete the string from session
        // to prevent re-use
        Session::remove(self::$captcha_session_key);

        if (!$case_sensitive) {
            $input = mb_strtolower($input);
            $captcha = mb_strtolower($captcha);
        }
        return $input === $captcha;
    }
}
