<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{   
    public function upload ()
    {
        if (!request()->hasFile('upload')) return view('upload', ['error_message' => 'Please upload a CSV file']); 
        if (request()->file('upload')->extension() != 'csv') return view('upload', ['error_message' => 'Please upload a CSV file only']); 

        $csv_to_array = array_map('str_getcsv', file(request()->file('upload')));

        $names_out = [];

        foreach ($csv_to_array as $row)
        {
            $entry = $row[0];

            if (strpos($entry, "and") !== false || strpos($entry, "&") !== false)
            {
                $two_names = $this->handleEntry($entry);
                $names_out[] = $two_names[0];
                $names_out[] = $two_names[1];
            }
            elseif (str_word_count($entry) > 1)     // avoid header row
            {
                $names_out[] = $this->handleEntry($entry);
            }
        }

        return view('upload', compact('names_out'));
    }

    public function handleEntry ($entry) 
    {
        if (strpos($entry, "and") !== false || strpos($entry, "&") !== false)
        {
            $names = preg_split("[and|\&]", $entry);
            
            $first_person = explode(" ", trim($names[0]));
            $second_person = explode(" ", trim($names[1]));

            if (count($first_person) == 1) $names[0] .= end($second_person); // For Mr & Mrs Smith
            if (count($first_person) == 1 && count($second_person) == 3) 
            {
                $names[0] = $first_person[0] . ' ' . $second_person[1] . ' ' . $second_person[2];
                $names[1] = $second_person[0] . ' ' . $second_person[2]; // for Dr & Mrs Joe Bloggs
            }

            return array($this->serialiseName($names[0]), $this->serialiseName($names[1]));
        } 
        else
        {    
            return $this->serialiseName($entry);
        }
    }

    private function serialiseName (string $name) 
    {
            $first_name = null;
            $initial = null;

            $name = preg_replace('/(\w+)\.(\w+)/', '$1 $2', $name); // For F.Fredrickson
            $name_parts = explode(" ", trim($name));
            $title = reset($name_parts);
            $last_name = end($name_parts);

            $title = str_replace(['Mister', 'Doctor', 'Professor'], ['Mr', 'Dr', 'Prof'], $title);

            if (count($name_parts) == 3) {
                if (strlen($name_parts[1]) != 1 && substr($name_parts[1], -1) != '.') $first_name = $name_parts[1];
                if (strlen($name_parts[1]) == 1 || substr($name_parts[1], -1) == '.') $initial =  rtrim($name_parts[1], "."); 
            }

            return [
                'title' => $title,
                'first_name' => $first_name,
                'initial' => $initial,
                'last_name' => $last_name
            ];
    }
}
