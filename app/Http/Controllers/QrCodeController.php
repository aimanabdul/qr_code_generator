<?php

namespace App\Http\Controllers;

use App\Models\QrCodeModel;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class QrCodeController extends Controller
{
    public function create()
    {
        return view('qr.create');
    }
/*
 <a href="{{ route('qr.download', ['id' => $qrCode->id, 'format' => 'png']) }}">PNG</a> |
<a href="{{ route('qr.download', ['id' => $qrCode->id, 'format' => 'svg']) }}">SVG</a> |
<a href="{{ route('qr.download', ['id' => $qrCode->id, 'format' => 'pdf']) }}">PDF</a>
 */
    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer'
        ]);

        $quantity = $request->input('quantity');

        for ($i = 0; $i < $quantity; $i++) {
            $label = Str::random(10);
            $qrCode = QrCode::generate($label);

            $qrCodeModel = new QrCodeModel();
            $qrCodeModel->label = $label;
            $qrCodeModel->status = false;
            $qrCodeModel->save();

            Storage::put("public/qrcodes/{$qrCodeModel->id}.svg", $qrCode);
        }

        return redirect()->route('qr.index');
    }

    public function index()
    {
        $qrCodes = QrCodeModel::all();
        return view('qr.index', compact('qrCodes'));
    }

    public function download($id, $format)
    {
        $qrCode = QrCodeModel::findOrFail($id);

        $filePath = storage_path("app/public/qrcodes/{$id}.svg");

        if (!File::exists($filePath)) {
            abort(404);
        }

        $qrCode->status = true;
        $qrCode->save();

        $fileName = "{$qrCode->label}.{$format}";

        switch ($format) {
            case 'png':
                $image = \Intervention\Image\Facades\Image::make($filePath)->encode('png');
                return response($image, 200)->header('Content-Type', 'image/png')->header('Content-Disposition', "attachment; filename={$fileName}");
            case 'pdf':
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('qr.pdf', ['qrCode' => $qrCode]);
                return $pdf->download($fileName);
            case 'svg':
                return response()->download($filePath, $fileName, ['Content-Type' => 'image/svg+xml']);
            default:
                abort(400);
        }
    }
}
