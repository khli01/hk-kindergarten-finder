<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Kindergarten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ImportController extends Controller
{
    /**
     * Show the import page.
     */
    public function index()
    {
        return view('admin.import.index');
    }

    /**
     * Import kindergartens from CSV.
     */
    public function importKindergartens(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $imported = 0;
        $errors = [];

        if (($handle = fopen($path, 'r')) !== false) {
            // Skip header row
            $header = fgetcsv($handle);
            
            $row = 1;
            while (($data = fgetcsv($handle)) !== false) {
                $row++;
                
                try {
                    // Expected columns: name_zh_tw, name_zh_cn, name_en, district_name_en, address_zh_tw, address_zh_cn, address_en, website_url, has_pn, has_k1, has_k2, has_k3, primary_success_rate, ranking_score, phone, email, school_type
                    if (count($data) < 7) {
                        $errors[] = "Row {$row}: Insufficient columns";
                        continue;
                    }

                    // Find district by English name
                    $district = District::where('name_en', 'LIKE', '%' . trim($data[3]) . '%')->first();
                    
                    if (!$district) {
                        $errors[] = "Row {$row}: District not found: " . $data[3];
                        continue;
                    }

                    Kindergarten::updateOrCreate(
                        [
                            'name_en' => trim($data[2]),
                            'district_id' => $district->id,
                        ],
                        [
                            'name_zh_tw' => trim($data[0]),
                            'name_zh_cn' => trim($data[1]),
                            'address_zh_tw' => trim($data[4]),
                            'address_zh_cn' => trim($data[5]),
                            'address_en' => trim($data[6]),
                            'website_url' => !empty($data[7]) ? trim($data[7]) : null,
                            'has_pn_class' => isset($data[8]) && strtolower(trim($data[8])) === 'yes',
                            'has_k1' => !isset($data[9]) || strtolower(trim($data[9])) !== 'no',
                            'has_k2' => !isset($data[10]) || strtolower(trim($data[10])) !== 'no',
                            'has_k3' => !isset($data[11]) || strtolower(trim($data[11])) !== 'no',
                            'primary_success_rate' => isset($data[12]) && is_numeric($data[12]) ? floatval($data[12]) : null,
                            'ranking_score' => isset($data[13]) && is_numeric($data[13]) ? intval($data[13]) : 0,
                            'phone' => !empty($data[14]) ? trim($data[14]) : null,
                            'email' => !empty($data[15]) ? trim($data[15]) : null,
                            'school_type' => !empty($data[16]) ? trim($data[16]) : 'private',
                            'is_active' => true,
                        ]
                    );

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row {$row}: " . $e->getMessage();
                }
            }

            fclose($handle);
        }

        $message = "Successfully imported {$imported} kindergarten(s).";
        if (count($errors) > 0) {
            $message .= " Errors: " . count($errors);
        }

        return back()
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    /**
     * Export kindergartens to CSV.
     */
    public function exportKindergartens()
    {
        $kindergartens = Kindergarten::with('district')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="kindergartens_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($kindergartens) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'name_zh_tw', 'name_zh_cn', 'name_en', 'district_name_en',
                'address_zh_tw', 'address_zh_cn', 'address_en', 'website_url',
                'has_pn', 'has_k1', 'has_k2', 'has_k3',
                'primary_success_rate', 'ranking_score', 'phone', 'email', 'school_type'
            ]);

            foreach ($kindergartens as $k) {
                fputcsv($file, [
                    $k->name_zh_tw,
                    $k->name_zh_cn,
                    $k->name_en,
                    $k->district->name_en,
                    $k->address_zh_tw,
                    $k->address_zh_cn,
                    $k->address_en,
                    $k->website_url,
                    $k->has_pn_class ? 'Yes' : 'No',
                    $k->has_k1 ? 'Yes' : 'No',
                    $k->has_k2 ? 'Yes' : 'No',
                    $k->has_k3 ? 'Yes' : 'No',
                    $k->primary_success_rate,
                    $k->ranking_score,
                    $k->phone,
                    $k->email,
                    $k->school_type,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Download import template.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="kindergarten_import_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header with instructions
            fputcsv($file, [
                'name_zh_tw', 'name_zh_cn', 'name_en', 'district_name_en',
                'address_zh_tw', 'address_zh_cn', 'address_en', 'website_url',
                'has_pn', 'has_k1', 'has_k2', 'has_k3',
                'primary_success_rate', 'ranking_score', 'phone', 'email', 'school_type'
            ]);

            // Example row
            fputcsv($file, [
                '約克國際幼稚園',
                '约克国际幼儿园',
                'York International Kindergarten',
                'Kowloon City',
                '九龍城窩打老道75號',
                '九龙城窝打老道75号',
                '75 Waterloo Road, Kowloon City',
                'https://www.york.edu.hk',
                'Yes',
                'Yes',
                'Yes',
                'Yes',
                '85.5',
                '90',
                '2337 8000',
                'info@york.edu.hk',
                'private'
            ]);

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
