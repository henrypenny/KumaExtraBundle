<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 14/04/17
 * Time: 2:23 PM
 */

namespace Hmp\KumaExtraBundle\Twig;


class UtilTwigExtension extends BaseTwigExtension
{
    public function getName()
    {
        return 'hmp_kuma_extra_util_twig_extension';
    }

    public function getFilters()
    {
        return [
            'mailTo' => new \Twig_SimpleFilter('mailTo', [$this, 'mailTo'], ['is_safe' => array('all')]),
            'obfuscateEmail' => new \Twig_SimpleFilter('obfuscateEmail', [$this, 'obfuscateEmail'], ['is_safe' => array('html')]),
            'googleMapLink' => new \Twig_SimpleFilter('googleMapLink', [$this, 'googleMapLink'], ['is_safe' => array('html')]),
            'telLink' => new \Twig_SimpleFilter('telLink', [$this, 'telLink'], ['is_safe' => array('html')]),
        ];
    }

    public function getFunctions()
    {
        return [
            'mailTo' => new \Twig_Function_Method($this, 'mailTo', ['is_safe' => array('all')]),
            'obfuscateEmail' => new \Twig_Function_Method($this, 'obfuscateEmail', ['is_safe' => array('html')]),
            'googleMapLink' => new \Twig_Function_Method($this, 'googleMapLink', ['is_safe' => array('html')]),
            'telLink' => new \Twig_Function_Method($this, 'telLink', ['is_safe' => array('html')]),
        ];
    }

    public function mailTo($email)
    {
        return sprintf('mailto:%s', $this->obfuscateEmail($email));
    }

    public function googleMapLink($address)
    {
        $address = str_replace('<br>', ' ', $address);
        $result = sprintf('http://maps.google.com/?q=%s', urlencode(strip_tags($address)));
        return $result;
    }

    public function telLink($phone)
    {
        $result = preg_replace('/[^0-9^\+]/i', '', $phone);
        $result = sprintf('tel:%s', $result);
        return $result;
    }

    public function obfuscateEmail($email)
    {
        $alwaysEncode = array('.', ':', '@');

        $result = '';

        // Encode string using oct and hex character codes
        for ($i = 0; $i < strlen($email); $i++)
        {
            // Encode 25% of characters including several that always should be encoded
            if (in_array($email[$i], $alwaysEncode) || mt_rand(1, 100) < 25)
            {
                if (mt_rand(0, 1))
                {
                    $result .= '&#' . ord($email[$i]) . ';';
                }
                else
                {
                    $result .= '&#x' . dechex(ord($email[$i])) . ';';
                }
            }
            else
            {
                $result .= $email[$i];
            }
        }

        return $result;
    }
}