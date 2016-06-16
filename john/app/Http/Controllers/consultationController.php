<?php

namespace App\Http\Controllers;

use App;
use PDF;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Models\Diagnosis;
use App\Http\Models\Labrequest;
use App\Http\Models\Vaccination;
use App\Http\Models\Prescription;


class consultationController extends Controller
{
	protected $medix;

	public function __construct()
    {
        $this->middleware('nocache');
        $this->medix = new \App\Medix\Client();
    }

	/*---------------------------------------------------------------------------------------------------------------------------------------------
	| CREATE AN APPOINTMENT FOR NEW PATIENT
	|----------------------------------------------------------------------------------------------------------------------------------------------
	|
	*/
	public function appointmentForNewPatient(Request $req)
	{
		if (! \Session::has('fname')) {
            return redirect('/#about')->with('success',['type'=> 'danger','text' => 'Access denied, Please Login to view a patient profile!']);
        }
        if (\Session::has('consult')) {
            return redirect('/patientRecords')->with('message',['type'=> 'danger','text' => 'There is an ongoing visit! Please end the ongoing visit before proceeding. ']);
        }

        $req->only('chief_complaints', 'patient_id');
        $id 				= $req->input('patient_id');
        $chief_complaints 	= $req->input('chief_complaints');

        $data =
        [
            'practitioner_id'   => \Session::get('user_id'),
        	'patient_id' 		=> $id,
        	'appointment_date'	=> date('Y-m-d', strtotime(Carbon::now()->setTimezone('GMT+8'))),
        	'appointment_time'	=> date('H:i:s', strtotime(Carbon::now()->setTimezone('GMT+8'))),
        	'purpose_id'		=> 1,
        	'chief_complaints'	=> $chief_complaints,
        ];
        //dd($data);
        $consult = \Session::put('consult', $id);
		$appointment = $this->medix->post('appointment', $data);
        $profile = $this->medix->get('patient/'.$id);

		return redirect('consultation/'.$id)
                ->with('prof', $profile->data)
                ->with('appoint', $appointment->data)
                ->with('type', 'New Patient Consultation');

	}


    /*---------------------------------------------------------------------------------------------------------------------------------------------
    | CREATE AN APPOINTMENT FOR OLD PATIENT
    |----------------------------------------------------------------------------------------------------------------------------------------------
    |
    */
    public function appointmentForOldPatient(Request $req)
    {
        if (! \Session::has('fname')) {
            return redirect('/#about')->with('message',['type'=> 'danger','text' => 'Access denied, Please Login to view a patient profile!']);
        }
        if (\Session::has('consult')) {
            return redirect('/patientRecords')->with('message',['type'=> 'danger','text' => 'There is an ongoing visit! Please end the ongoing visit before proceeding. ']);
        }

        $req->only('chief_complaints', 'patient_id','purpose_id');
        $id                 = $req->input('patient_id');
        $purpose_id         = $req->input('purpose_id');
        $chief_complaints   = $req->input('chief_complaints');

        $data =
        [
            'practitioner_id'   => \Session::get('user_id'),
            'patient_id'        => $id,
            'appointment_date'  => date('Y-m-d', strtotime(Carbon::now()->setTimezone('GMT+8'))),
            'appointment_time'  => date('H:i:s', strtotime(Carbon::now()->setTimezone('GMT+8'))),
            'purpose_id'        => $purpose_id,
            'chief_complaints'  => $chief_complaints,
        ];

        $consult = \Session::put('consult', $id);
        $appointment = $this->medix->post('appointment', $data);
        $profile = $this->medix->get('patient/'.$id);
        $key = end($profile->data->patient_appointments);
        $appoint = \Session::put('appoint', $key->id);
        //dd(\Session::get('appoint'));

        if ($purpose_id = 1) {
            $type = 'Old Patient Consultation';
        } else {
            $type = 'Follow-up Consultation';
        }

        return redirect('consultation/'.$id)
                ->with('prof', $profile->data)
                ->with('type', $type);

    }


	/*---------------------------------------------------------------------------------------------------------------------------------------------
	| CREATE A PRESCRIPTION IN PDF
	|----------------------------------------------------------------------------------------------------------------------------------------------
	|
	*/
   	public function createPrescription()
   	{
        if (! \Session::has('fname')) {
            return redirect('/#about')->with('message',['type'=> 'danger','text' => 'Access denied, Please Login!']);
        }

   		//$pdf = \PDF::loadView('forms.patientRegister');
		//return $pdf->stream();
		$pdf = App::make('dompdf.wrapper');
		$pdf->loadView('pdf.prescription');
		$pdf->setPaper('DEFAULT_PDF_PAPER_SIZE', 'portrait');
  		return $pdf->stream();

	}


    /*---------------------------------------------------------------------------------------------------------------------------------------------
    | END A VISIT SESSION
    |----------------------------------------------------------------------------------------------------------------------------------------------
    |
    */
    public function endVisit()
    {
       \Session::forget('consult');

       return redirect('/home')->with('visit',['type'=> 'success','text' => 'Visit successfully ended!']);

    }

    /*---------------------------------------------------------------------------------------------------------------------------------------------
    | END A VISIT SESSION
    |----------------------------------------------------------------------------------------------------------------------------------------------
    |
    */
    public function vaccination(Request $req, $id)
    {
       
        if (! \Session::has('consult')) {
           return redirect('/home')->with('message',['type'=> 'danger','text' => 'Consultation not yet Started! Please select a patient to be consulted!']);
        }

        if ($req->ajax()) {

            $req->only('select_vaccine','vac_date', 'patient_id', 'appointment_id');

            $select_vaccine  = $req->input('select_vaccine');
            $vac_date        = $req->input('vac_date');
            $patient_id      = $req->input('patient_id');
            $appointment_id  = $req->input('appointment_id');
            $practitioner_id = \Session::get('user_id');

            $data = [
                'vaccine_id'        => $select_vaccine,
                'date'              => $vac_date,
                'patient_id'        => $patient_id,
                'appointment_id'    => $appointment_id,
                'practitioner_id'   => $practitioner_id,
            ]

            $vaccine = Vaccination::create($data);

           $response = array(
                'status' => 'success',
                'msg'    => 'Vaccination added successfully',
                'data'   => $vaccine,
            );
            return \Response::json($response);
        }
        return false;
    }
}