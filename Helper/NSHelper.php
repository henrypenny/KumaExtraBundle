<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 27/11/15
 * Time: 10:26 AM
 */

namespace Hmp\KumaExtraBundle\Helper;


class NSHelper
{
    public static function getNSs()
    {
        return array(
            'App\\Bundle\\Entity\\',
            'App\\Bundle\\Entity\Pages\\',
            'App\\Bundle\\Entity\PageParts\\'
        );
    }

    public static function resolve($className)
    {
        $originalClassName = $className;

        if($className[0] != '.' && class_exists($className)) {
            return $className;
        }

        if($className[0] == '.') {
            $className = substr($className, 1);
        }

        foreach(self::getNSs() as $ns) {
            $_className = $ns . $className;
            if(class_exists($_className)) {
                return $_className;
            }
        }

        throw new \Exception('Class name ' . $originalClassName . ' could not be resolved');
    }
}
