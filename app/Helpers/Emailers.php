<?php

namespace App\Helpers;

use App\Mail\ForgetPasswordMail;
use App\Mail\LoginOTPMail;
use App\Mail\PasswordAdd;
use App\Mail\Test;
use App\Mail\FeedBackForm;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_Message;
use Swift_TransportException;

class Emailers
{

    public static function forgetPassword($data)
    {
		try {
			$toEmail = $data['email'];
			$status = Mail::to($toEmail)
				->send(new ForgetPasswordMail($data));
			if ($data) {
				//Put Email Log
			}
			return $status;
		} catch (Swift_TransportException $th) {
			throw $th;
		}
    }

    public static function loginOTP($data)
    {
		try {
			$toEmail = $data['email'];
			$status = Mail::to($toEmail)
				->send(new LoginOTPMail($data));
			if ($data) {
				//Put Email Log
			}
			return $status;
		} catch (Swift_TransportException $th) {
			throw $th;
		}
	}

	public static function test($data) {
		try {
			$toEmail = $data['email'];
			$status = Mail::to($toEmail)
				->send(new Test($data));
			if ($data) {
				//Put Email Log
			}
			return $status;
		} catch (Swift_TransportException $th) {
			throw $th;
		}
	}

	public static function feedback_link($data) {
		try {
			$toEmail = $data['email'];
			$status = Mail::to($toEmail)->send(new FeedBackForm($data));
			return $status;
		} catch (Swift_TransportException $th) {
			throw $th;
		}
	}
	
	public static function passwordAdd($data)
    {
		try {
			$toEmail = $data['email'];
			$status = Mail::to($toEmail)
				->send(new PasswordAdd($data));
			if ($data) {
				//Put Email Log
			}
			return $status;
		} catch (Swift_TransportException $th) {
			throw $th;
		}
    }
}
