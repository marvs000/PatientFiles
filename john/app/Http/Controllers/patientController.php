<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Requests;
use Guzzle\Http\Client;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePatientRequest;

class patientController extends Controller
{
	protected $medix;

	public function __construct()
    {
        $this->middleware('nocache');
        $this->medix = new \App\Medix\Client();
    }

    /**
     * patientProfile
     */
    public function patientProfile($id)
    {
        if (! \Session::has('fname')) {
            return redirect('/#about')->with('message',['type'=> 'danger','text' => 'Access denied, Please Login to view a patient profile!']);
        }

        $recentCons = $this->medix->get('patient/' . $id .'/consultations/recent');
        $pastCons = $this->medix->get('patient/' . $id .'/consultations/past');
        $pastVitals = $this->medix->get('vitals/patient/' . $id . '/past');
        //dd($pastVitals);
        $profile = $this->medix->get('patient/'.$id);
        $address = current((array)$profile->data->user->user_addresses);
        //dd($profile);
        $vitals_date = current((array)$pastVitals->data);
        //dd($vitals_date);
        $vitals = $this->medix->get('patient/' . $id . '/vitals/' . $vitals_date->general->created_at);
        $height =$vitals->data->vitals->general_survey->height * 0.01;

        if ($height == 0) {
            $bmi = 'N/A';
        } else {
            $bmi = round(($vitals->data->vitals->general_survey->weight) / pow($height,2),2);
        }
         
        //dd($bmi);
        //dd($vitals);

        return view('pages.patientProfile')
            ->with('prof', $profile->data)
            ->with('recentCons', $recentCons->data)
            ->with('address', $address)
            ->with('consult', $pastCons->data->patient_appointments)
            ->with('vitals', $pastVitals->data)
            ->with('bmi', $bmi)
            ->with('recentVitals', $vitals->data->vitals->general_survey);
    }

    public function saveVitals($id, Request $request)
    {

        if (! \Session::has('token')) {
            return redirect('/#about')->with('message',['type'=> 'danger','text' => 'Access denied, Please Login!']);
        }

        $request->all();

        $height = $request->input('height');
        $weight = $request->input('weight');
        $pulse  = $request->input('pulse');
        $resp   = $request->input('respiratory');
        $temp   = $request->input('temp');
        $sys    = $request->input('sys');
        $dia    = $request->input('dia');
        $mens   = $request->input('mens');
        $notes  = $request->input('notes');

        $data = [
            'height'            => $height,
            'weight'            => $weight,
            'pulserate'         => $pulse,
            'respiratoryrate'   => $resp,
            'bodytemperature'   => $temp,
            'bloodpressure_sys' => $sys,
            'bloodpressure_dia' => $dia,
            'last_menstrual'    => $mens,
            'notes'             => $notes
        ];
        $addVitals = $this->medix->post('patient/'. $id .'/vitals/general_survey', $data);
        //dd($addVitals);

        return redirect('patientProfile/'. $id .'#vitals')->with('success',['type'=> 'success','text' => 'Vitals successfully added']);
    }

    public function addPatient(CreatePatientRequest $request)
    {   
        if (! \Session::has('token')) {
            return redirect('/#about')->with('message',['type'=> 'danger','text' => 'Access denied, Please Login!']);
        }

        $request->all();

        $fname        = $request->input('fname');
        $mname        = $request->input('mname');
        $lname        = $request->input('lname');
        $nickname     = $request->input('nickname');
        $bdate        = $request->input('bdate');
        $civil_status = $request->input('civil_status');
        $religion     = $request->input('religion');
        $gender       = $request->input('gender');
        $govt         = $request->input('govt');
        $govtnum      = $request->input('govtnum');
        $email        = $request->input('email');
        $mobile       = $request->input('mobile');
        $landline     = $request->input('landline');
        $efname       = $request->input('efname');
        $emname       = $request->input('emname');
        $elname       = $request->input('elname');
        $econtact     = $request->input('econtact');
        $erelation    = $request->input('erelation');
        $street       = $request->input('street');
        $brgy         = $request->input('brgy');
        $city         = $request->input('city');
        $province     = $request->input('province');
        $zip_code     = $request->input('zip_code');

        $data = [
            'firstname'                 => $fname,
            'middlename'                => $mname,
            'lastname'                  => $lname,
            'nickname'                  => $nickname,
            'birthdate'                 => $bdate,
            'user_religion'             => $religion,
            'civil_status'              => $civil_status,
            'gender'                    => $gender,
            "user_government_id"        => [
                                                'government_id_type_id' => $govt,
                                                'number'                => $govtnum,
                                           ],
            'user_emails'               => $email,
            'user_phone_numbers'        => $mobile,
            'user_emergency_contacts'   => $efname,
            'user_emergency_contacts'   => $emname,
            'user_emergency_contacts'   => $elname,
            'user_emergency_contacts'   => $econtact,
            'user_emergency_contacts'   => $erelation,
            'user_addresses'            => $street,
            'user_addresses'            => $brgy,
            'user_addresses'            => $city,
            'user_addresses'            => $province,
            'user_addresses'            => $zip_code,
        ];
        $addPatient = $this->medix->post('patient', $data);
        dd($addPatient);

        //return redirect()->to('/register')->withInput();
    }
}
