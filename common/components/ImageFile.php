<?php

namespace common\components;

class ImageFile
{
    public static function upload(&$file, &$obj, $campoBD)
    {
        if ($obj->validate()) {
            $rutaFichero = $file->tempName;

            if ($rutaFichero) {
                $contenido = file_get_contents($rutaFichero);

                if ($contenido) {
                    $obj->{$campoBD} = base64_encode($contenido);
                }
            } else {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }
}
