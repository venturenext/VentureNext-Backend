<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\PartnerInquiryRequest;
use App\Http\Requests\PerkClaimRequest;
use App\Models\Lead;
use App\Models\Inbox;

class LeadController extends Controller
{
    /**
     * Submit perk claim form
     */
    public function perkClaim(PerkClaimRequest $request)
    {
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

        // Increment claim count for the perk
        if ($lead->perk && $lead->perk->statistics) {
            $lead->perk->statistics->increment('claim_count');
            $lead->perk->statistics->update(['last_claimed_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Perk claim submitted successfully. We will contact you soon.',
            'data' => [
                'lead_id' => $lead->id
            ]
        ], 201);
    }

    /**
     * Submit partner inquiry form
     */
    public function partnerInquiry(PartnerInquiryRequest $request)
    {
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
                'company_size' => $request->input('company_size'),
                'industry' => $request->input('industry'),
                'utm_source' => $request->input('utm_source'),
                'utm_medium' => $request->input('utm_medium'),
                'utm_campaign' => $request->input('utm_campaign'),
                'referrer' => $request->headers->get('referer'),
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Partner inquiry submitted successfully. Our team will reach out to you shortly.',
            'data' => [
                'lead_id' => $lead->id
            ]
        ], 201);
    }

    /**
     * Submit contact form
     */
    public function contact(ContactFormRequest $request)
    {
        $inbox = Inbox::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->input('subject'),
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact form submitted successfully. We will get back to you soon.',
            'data' => [
                'inbox_id' => $inbox->id
            ]
        ], 201);
    }
}
