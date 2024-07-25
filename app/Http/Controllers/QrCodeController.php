<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QrCodeModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class QrCodeController extends Controller
{
    public function index()
    {
        $qrCodes = QrCodeModel::paginate(25);
        return view('qr.index', compact('qrCodes'));
    }

    public function create()
    {
        return view('qr.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'note' => 'required',
            'foreground_color' => 'required|string|in:black,white',
            'background_color' => 'required|string|in:white,transparent'
        ]);

        $quantity = $request->input('quantity');
        $foregroundColor = $request->input('foreground_color');
        $backgroundColor = $request->input('background_color');

        for ($i = 0; $i < $quantity; $i++) {
            $label = Str::random(10);
            $url = "https://qr.cardquest.be/qr/{$label}";

            // Stel de voorgrondkleur en achtergrondkleur in
            $foregroundColorRgb = ($foregroundColor === 'white') ? [255, 255, 255] : [0, 0, 0];
            $backgroundColorRgb = ($backgroundColor === 'white') ? [255, 255, 255] : [0, 0, 0, 0];

            // Genereer QR-code met aangepaste kleuren
            $qrCode = QrCode::size(300)
                ->format('png')
                ->color(...$foregroundColorRgb)
                ->backgroundColor(...$backgroundColorRgb)
                ->errorCorrection('H')
                ->generate($url);

            $qrCodeModel = new QrCodeModel();
            $qrCodeModel->label = $label;
            $qrCodeModel->note = $request->note;
            $qrCodeModel->foreground_color = $foregroundColor;
            $qrCodeModel->background_color = $backgroundColor;
            $qrCodeModel->save();

            Storage::put("public/qr_codes/{$qrCodeModel->label}.png", $qrCode);
        }

        return redirect()->route('qr.index');
    }

    public function edit($id)
    {
        $qrCode = QrCodeModel::findOrFail($id);
        return view('qr.edit', compact('qrCode' ));
    }

    public function update (Request $request, $id)
    {
        // validate request
        $request->validate([
            'forwarding_link' => 'required'
        ]);

        $qrCode = QrCodeModel::findOrFail($id);

        // update and save qrCode
        $qrCode->forwarding_link = $request->forwarding_link;
        $qrCode->save();

        session()->flash('success', 'QR-code successfully updated.');
        return redirect()->back();


    }

    public function updateStatus(Request $request)
    {
        $labels = $request->input('labels');
        QrCodeModel::whereIn('label', $labels)->update(['is_downloaded' => true]);
        return response()->json(['status' => 'success']);
    }

    public function download($id)
    {
        $qrCode = QrCodeModel::findOrFail($id);

        $filePath = storage_path("app/public/qr_codes/{$qrCode->label}.png");

        if (!File::exists($filePath)) {
            abort(404);
        }

        $qrCode->is_downloaded = true;
        $qrCode->save();

        $fileName = "{$qrCode->label}.png";
        return response()->download($filePath, $fileName, ['Content-Type' => 'image/png']);
    }


    public function forwarding ($label)
    {

        $qrCode = QrCodeModel::where('label', '=', $label)->get()->first();

        // check if qr code exists
        if ($qrCode == null)
        {
            return view('qr.not_found');
        }
        else {
            return redirect()->away($qrCode->forwarding_link);
        }

    }
}
