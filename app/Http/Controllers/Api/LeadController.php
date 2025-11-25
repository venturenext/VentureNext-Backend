<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\PartnerInquiryRequest;
use App\Http\Requests\PerkClaimRequest;
use App\Mail\PerkClaimConfirmation;
use App\Models\Lead;
use App\Models\Inbox;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{

    public function perkClaim(PerkClaimRequest $request)
    {
        try {
            $lead = Lead::create([
                'perk_id' => $request->perk_id,
                'lead_type' => 'perk_claim',
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'utm_source' => $request->input('utm_source'),
                    'utm_medium' => $request->input('utm_medium'),
                    'utm_campaign' => $request->input('utm_campaign'),
                    'referrer' => $request->headers->get('referer'),
                ],
            ]);


            if ($lead->perk && $lead->perk->statistics) {
                $lead->perk->statistics->increment('claim_count');
                $lead->perk->statistics->update(['last_claimed_at' => now()]);
            }


            $this->notifyLeadEmail('Perk Claim Received', [
                "Lead Type: Perk Claim",
                "Perk ID: {$lead->perk_id}",
                "Name: {$lead->name}",
                "Email: {$lead->email}",
                "Company: {$lead->company}",
                "Message: {$lead->message}",
            ]);


            try {
                Mail::to($lead->email)->queue(new PerkClaimConfirmation($lead));
            } catch (\Throwable $e) {
                Log::error('Failed to send perk claim confirmation email to user', [
                    'email' => $lead->email,
                    'lead_id' => $lead->id,
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Perk claim submitted successfully. We will contact you soon.',
                'data' => [
                    'lead_id' => $lead->id
                ]
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Perk claim submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit perk claim. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    public function partnerInquiry(PartnerInquiryRequest $request)
    {
        try {
            $lead = Lead::create([
                'lead_type' => 'partner_inquiry',
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'contact' => $request->input('contact'),
                    'company_size' => $request->input('company_size'),
                    'industry' => $request->input('industry'),
                    'utm_source' => $request->input('utm_source'),
                    'utm_medium' => $request->input('utm_medium'),
                    'utm_campaign' => $request->input('utm_campaign'),
                    'referrer' => $request->headers->get('referer'),
                ],
            ]);

            $contact = '';
            if (is_array($lead->metadata ?? null) && array_key_exists('contact', $lead->metadata)) {
                $contact = $lead->metadata['contact'] ?? '';
            }

            $this->notifyLeadEmail('Partner Inquiry Received', [
                "Lead Type: Partner Inquiry",
                "Name: {$lead->name}",
                "Email: {$lead->email}",
                "Company: {$lead->company}",
                "Message: {$lead->message}",
                "Contact: {$contact}",
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Partner inquiry submitted successfully. Our team will reach out to you shortly.',
                'data' => [
                    'lead_id' => $lead->id
                ]
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Partner inquiry submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit partner inquiry. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    public function contact(ContactFormRequest $request)
    {
        try {
            $inbox = Inbox::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->input('contact'),
                'subject' => $request->input('subject'),
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->headers->get('referer'),
            ]);

            $this->notifyLeadEmail('Contact Form Submission', [
                "Type: Contact Form",
                "Name: {$inbox->name}",
                "Email: {$inbox->email}",
                "Contact: {$inbox->contact}",
                "Subject: {$inbox->subject}",
                "Message: {$inbox->message}",
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contact form submitted successfully. We will get back to you soon.',
                'data' => [
                    'inbox_id' => $inbox->id
                ]
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit contact form. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function notifyLeadEmail(string $subject, array $lines): void
    {
        $to = Setting::get('lead_notification_email', Setting::get('contact_email'));
        if (!$to) {
            Log::warning('Lead notification skipped: no recipient configured.');
            return;
        }

        $rows = '';
        foreach ($lines as $line) {
            [$label, $value] = array_pad(explode(':', $line, 2), 2, '');
            $rows .= '<tr>'
                . '<td style="padding:8px 6px;width:35%;font-weight:600;background:#f9fafb;border:1px solid #e5e7eb;">' . e(trim($label)) . '</td>'
                . '<td style="padding:8px 6px;border:1px solid #e5e7eb;">' . e(trim($value)) . '</td>'
                . '</tr>';
        }

        $html = <<<HTML
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title>{$subject}</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f7f7f7; padding: 16px;">
  <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
    <tr>
      <td style="padding: 16px 20px; background: #0c4a34; color: #ffffff;">
        <h2 style="margin: 0; font-size: 18px;">{$subject}</h2>
      </td>
    </tr>
    <tr>
      <td style="padding: 20px;">
        <p style="margin: 0 0 12px 0; color: #111827; font-size: 15px;">Hi team,</p>
        <p style="margin: 0 0 12px 0; color: #111827; font-size: 15px;">A new submission just came in:</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-top: 8px; color: #111827; font-size: 14px;">
          <tbody>
            {$rows}
          </tbody>
        </table>
        <p style="margin: 16px 0 0 0; color: #6b7280; font-size: 13px;">Generated automatically by PerkPal.</p>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;

        try {
            // Dispatch email to background queue to avoid blocking the HTTP response
            // This runs after the response is sent, so SMTP issues won't cause timeouts
            dispatch(function () use ($html, $to, $subject) {
                try {
                    Mail::html($html, function ($message) use ($to, $subject) {
                        $message->to($to)->subject($subject);
                    });
                } catch (\Throwable $e) {
                    Log::error('Failed to send lead notification email', ['error' => $e->getMessage()]);
                }
            })->afterResponse();
        } catch (\Throwable $e) {
            Log::error('Failed to queue lead notification email', ['error' => $e->getMessage()]);
        }
    }
}
