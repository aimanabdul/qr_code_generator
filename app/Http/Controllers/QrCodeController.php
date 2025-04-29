<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QrCodeModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class QrCodeController extends Controller
{
    public function index(Request $request)
    {

        if ($request->has('label')) {
            $qrCodes = QrCodeModel::where('label', 'like', '%' . $request->label . '%')
                ->paginate(25);
        } else {
            $qrCodes = QrCodeModel::paginate(25);
        }
        return view('qr.index', compact('qrCodes'));
    }

    public function create(): View
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
                ->eye('square')
                ->style('dot', .9)
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
        return view('qr.edit', compact('qrCode'));
    }

    public function update(Request $request, $id)
    {

        // validate request
        if (!$request->place_name || !$request->place_address || !$request->place_id) {
            session()->flash('danger', 'Something went wrong. Please try again later.');
            return redirect()->back();
        }

        $qrCode = QrCodeModel::findOrFail($id);

        // update and save qrCode
        $qrCode->business_name = $request->input('place_name');
        $qrCode->address = $request->input('place_address');
        $qrCode->business_id = $request->input('place_id');
        $qrCode->forwarding_link = "Https://search.google.com/local/writereview?placeid=" . $request->input('place_id');
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


    public function forwarding($label)
    {
        $qrCode = QrCodeModel::where('label', $label)->get()->first();

        if ($qrCode && $qrCode->forwarding_link != null) {
            return redirect()->away($qrCode->forwarding_link);
        } elseif ($qrCode && $qrCode->forwarding_link == null) {
            return view('qr.setup', compact('qrCode'));
        } else {
            return view('qr.not_found');
        }
    }

    public function activateByCustomer(Request $request, $id)
    {
        // validate request
        $qrCode = QrCodeModel::findOrFail($id);
        if($qrCode->forwarding_link) {
            session()->flash('danger', 'This Review QR code is already in use.');
            return redirect()->back();
        }
        elseif($request->place_name == null || $request->place_address == null || $request->place_id == null) {
            session()->flash('danger', 'Gegevens zijn niet correct ingevuld. Gelieve een profiel te selecteren bij stap 1.');
            return redirect()->back();
        }
        elseif($request->activation_code == null){
            session()->flash('danger', 'Activatie code niet geldig.');
            return redirect()->back();
        }
        // chack if the activation code is valid/
        elseif($request->activation_code != $qrCode->activation_code) {
            session()->flash('danger', 'Activatie code niet geldig.');
            return redirect()->back();
        }

        // not expired
        elseif($request->activation_code == $qrCode->activation_code && !$qrCode->activation_code_is_active) {
            session()->flash('danger', 'Activatie code is niet meer geldig, Gelieve ons te contacteren indien u de QR code wenst te activeren.');
            return redirect()->back();
        }

        elseif($request->activation_code == $qrCode->activation_code && $qrCode->activation_code_is_active && $qrCode->activation_code_expired_at > now()) {
            // update and save qrCode
            $qrCode->business_name = $request->input('place_name');
            $qrCode->address = $request->input('place_address');
            $qrCode->business_id = $request->input('place_id');
            $qrCode->forwarding_link = "Https://search.google.com/local/writereview?placeid=" . $request->input('place_id');
            $qrCode->is_used = true;
            $qrCode->activation_code_is_active = false;
            $qrCode->activation_code_used_at = now();
            $qrCode->save();

            session()->flash('success', 'QR-code successfully updated.');
            return view("qr.activate_success");
        }
        else {
            session()->flash('danger', 'Something went wrong. Please try again later.');
            return redirect()->back();
        }
    }

    public function setup(Request $request, $label)
    {
        $qrCode = QrCodeModel::where('label', '=', $label)->get()->first();
        // if qr code does not exist
        if ($qrCode == null) {
            return ("qr code does not exist");
        }

        // if forwarding link does exist
        if ($qrCode->forwarding_link) {
            return ("Qr Code in use!");
        } else {
            return view('qr.setup', compact('qrCode'));
        }

    }


    public function activate(Request $request, $id)
    {
        // validate request
        if (!$request->place_name || !$request->place_address || !$request->place_id) {
            session()->flash('danger', 'Something went wrong. Please try again later.');
            return redirect()->back();
        }

        $qrCode = QrCodeModel::findOrFail($id);

        // update and save qrCode
        $qrCode->business_name = $request->input('place_name');
        $qrCode->address = $request->input('place_address');
        $qrCode->business_id = $request->input('place_id');
        $qrCode->forwarding_link = "Https://search.google.com/local/writereview?placeid=" . $request->input('place_id');
        $qrCode->save();

        session()->flash('success', 'QR-code successfully updated.');
        return view("qr.activate_success");
    }

    public function getActivationCode($id)
    {
        $qrCode = QrCodeModel::findOrFail($id);
        if ($qrCode->activation_code) {
            session()->flash('danger', 'Activation is already set.');
            return redirect()->back();
        }

        $activationCode = Str::random(10);
        $qrCode->activation_code = $activationCode;
        $qrCode->activation_code_is_active = true;
        $qrCode->activation_code_created_at = now();
        $qrCode->activation_code_expired_at = now()->addDays(10);
        $qrCode->save();

        session()->flash('success', 'Activation code generated successfully.');
        return redirect()->back();
    }
}
