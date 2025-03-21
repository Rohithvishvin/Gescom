<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageCompressionController extends Controller
{
    public function compress()
    {
        $uploadsPath = public_path('uploads'); // for local
        // $uploadsPath = base_path('../uploads'); // for server

        if (File::isDirectory($uploadsPath)) {
            $images = File::files($uploadsPath);

            foreach ($images as $image) {
                $imageSize = filesize($image);

                // Check if the image is larger than 1 MB (1048576 bytes)
                if ($imageSize > 1048576) {
                    // $img = Image::make($image);
                    $img = Image::make($image)->resize(800, 800);
                    $img->encode('jpg', 70); // You can adjust the format and quality here
                    $img->save($image);
                    echo 'Compressed: ' . $image->getFilename() . '<br>';
                } else {
                    echo 'Skipped (not larger than 1 MB): ' . $image->getFilename() . '<br>';
                }
            }

            return 'Images compressed successfully.';
        } else {
            return 'Uploads folder not found.';
        }
    }

    public function compress_all_images_backup()
    {
        //$uploadsPath = public_path('uploads'); // for local
		$uploadsPath = base_path('../uploads'); // for server

        //dd($uploadsPath);

        if (File::isDirectory($uploadsPath)) {
            $images = File::files($uploadsPath);
            //dd($images);

            foreach ($images as $image) {
                $imageSize = filesize($image);
                //echo $image->getFilename() ."<br>";
                $checkBackUpImage = self::searchInBackup($image->getFilename());
                //echo $checkBackUpImage ."<br>";

                $compressed_backup_file_path = $image->getPath() . '/compressed_images/' . $image->getFilename();
                //dd($compressed_file_path);

                // Check if the image is larger than 1 MB (1048576 bytes)
                if ($imageSize > 1048576 && $checkBackUpImage == "not found") {
                    $img = Image::make($image);
                    //dd($img);
                    $img->resize(($img->width() / 2), ($img->height() / 2));

                    $img->encode('jpg', 70); // You can adjust the format and quality here

                    $img->save($compressed_backup_file_path);

                    echo 'Compressed amd moved to back up folder : ' . $image->getFilename() . '<br>';
                    //dd('here');
                } else if($imageSize < 1048576 ){
                    $img = Image::make($image);
                    $img->save($compressed_backup_file_path);

                    echo 'Skipped file present in back up folder : ' . $image->getFilename() . '<br>';
                }
            }

            return 'Images compressed successfully.';
        } else {
            return 'Uploads folder not found.';
        }
    }

    public static function compress_image($image_file_name)
    {
        $img = Image::make($image_file_name)->resize(1200, 1000);
        $img->encode('jpg', 70); // You can adjust the format and quality here
        $img->save($image_file_name);
        //return $img;
    }

    public function searchInBackup($original_filename){
        //$uploadsBackPath = public_path('uploads\compressed_images'); // for server
		$uploadsBackPath = base_path('../uploads/compressed_images'); 
        if (File::isDirectory($uploadsBackPath)) {
            $backup_images = File::f($uploadsBackPath);
            //echo $original_filename ."<br>";
            foreach ($backup_images as $image) {
                //echo $image->getFilename() ."<br>";
                if ($image->getFilename() == $original_filename) {
                    return 'found';
                    break;
                }
                //dd($image);
            }
        }
        return 'not found';
    }
	
	public function moveToVishvinData($dateFrom, $dataTo)
    {
        $select_query_results = DB::table('successful_records')
            ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->join('admins', 'meter_mains.created_by', '=', 'admins.id')
            ->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereBetween("successful_records.created_at", [$dateFrom, $dataTo])
            ->get();
		//dd($select_query_results);
        
		//$images = File::files($uploadsPath);
		foreach ($select_query_results as $record) {
			//dd($record);
			$filePath = '/var/www/vhosts/vishvin.com/hdgu.vishvin.com/' . $record->image_1_old;
			$fileTargetPath = '/vish-data/dont-delete-images/hdgu_images/';
            $fileExist = File::isFile($filePath);
            if($fileExist){
				File::copy($filePath, $fileTargetPath);
                dd(File::size($filePath));
			}
			else{
				dd("Not Exist");
			}
			dd($uploadsPath, $record);
			//$imageSize = filesize($image);
			//dd($imageSize);
		}
    }
}
