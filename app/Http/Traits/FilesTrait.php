<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

trait FilesTrait
{

    private function storeFiles($request, $attributes, $path)
    {

        foreach ($request->file() as $key => $file) {

            if (in_array($key, $attributes)) {

                $file_name = $file->getClientOriginalName();

                $file->move(public_path($path), $file_name);
            };
        }
    }

    private function getFileNames($request)
    {
        $file_names = [];

        foreach ($request->file() as $key => $file) {

            $client_original_name       = $file->getClientOriginalName();
            $client_original_extension  = $file->getClientOriginalExtension();

            $file_name_removed_extension = Str::remove($client_original_extension, $client_original_name);

            $file_name =  public_path() . Str::slug($file_name_removed_extension, '-') . '.' . $client_original_extension;

            $file_names[$key] = $file_name;
        }

        return $file_names;
    }
}
